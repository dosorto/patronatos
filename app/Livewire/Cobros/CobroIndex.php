<?php

namespace App\Livewire\Cobros;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cobro;
use Livewire\Attributes\Url;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CobroExport;
use App\Models\Organization;

class CobroIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $search = '';

    public $perPage = 10;

    public $showDeleteModal = false;
    public $cobroIdBeingDeleted = null;
    public $cobroNameBeingDeleted = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmCobroDeletion($id, $nombre)
    {
        $this->cobroIdBeingDeleted = $id;
        $this->cobroNameBeingDeleted = $nombre;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $cobro = Cobro::findOrFail($this->cobroIdBeingDeleted);
        $cobro->delete();

        $this->showDeleteModal = false;

        session()->flash('success', 'Cobro eliminado exitosamente.');
    }

    public function export()
    {
        $orgId = session('tenant_organization_id');
        $org = Organization::find($orgId);
        $orgNombre = $org ? \Illuminate\Support\Str::slug($org->name) : 'organization';
        $fecha = now()->format('Y_m_d_His');

        return Excel::download(
            new CobroExport(),
            $orgNombre . '_cobros_' . $fecha . '.xlsx'
        );
    }

    public function render()
    {
        $cobros = Cobro::with(['organizacion', 'miembro.persona'])
            ->where(function ($query) {

                $query->where('tipo_cobro', 'like', '%' . $this->search . '%')
                      ->orWhere('tipo_pago', 'like', '%' . $this->search . '%')
                      ->orWhere('total', 'like', '%' . $this->search . '%')

                      ->orWhereHas('miembro.persona', function ($q) {
                          $q->where('nombre', 'like', '%' . $this->search . '%')
                            ->orWhere('apellido', 'like', '%' . $this->search . '%');
                      });

            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.cobros.cobro-index', [
            'cobros' => $cobros
        ]);
    }
}