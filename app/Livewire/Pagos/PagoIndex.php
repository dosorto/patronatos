<?php

namespace App\Livewire\Pagos;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\Pago;
use App\Models\Organization;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PagoExport;

class PagoIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $search = '';

    public $perPage = 10;

    public $showDeleteModal = false;
    public $pagoIdBeingDeleted = null;
    public $pagoNameBeingDeleted = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmPagoDeletion($id, $nombre)
    {
        $this->pagoIdBeingDeleted = $id;
        $this->pagoNameBeingDeleted = $nombre;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $pago = Pago::findOrFail($this->pagoIdBeingDeleted);
        $pago->delete();

        $this->showDeleteModal = false;

        session()->flash('success', 'Pago eliminado exitosamente.');
    }

    public function export()
    {
        $orgId = session('tenant_organization_id');
        $org = Organization::find($orgId);
        $orgNombre = $org ? \Illuminate\Support\Str::slug($org->name) : 'organization';
        $fecha = now()->format('Y_m_d_His');

        return Excel::download(
            new PagoExport(),
            $orgNombre . '_pagos_' . $fecha . '.xlsx'
        );
    }

    public function render()
    {
        $pagos = Pago::with(['persona', 'empleado.persona', 'detalles', 'recibo'])
            ->where(function ($query) {
                $query->where('tipo_pago', 'like', '%' . $this->search . '%')
                    ->orWhere('nombre_persona', 'like', '%' . $this->search . '%')
                    ->orWhere('descripcion', 'like', '%' . $this->search . '%')
                    ->orWhere('total', 'like', '%' . $this->search . '%')
                    ->orWhereHas('empleado.persona', function ($q) {
                        $q->where('nombre', 'like', '%' . $this->search . '%')
                            ->orWhere('apellido', 'like', '%' . $this->search . '%');
                    });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.pagos.pago-index', [
            'pagos' => $pagos,
        ]);
    }
}