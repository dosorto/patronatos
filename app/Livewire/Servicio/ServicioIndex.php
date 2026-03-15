<?php

namespace App\Livewire\Servicio;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Servicio;
use Livewire\Attributes\Url;
use Maatwebsite\Excel\Facades\Excel;


class ServicioIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $search = '';

    public $perPage = 10;

    public $showDeleteModal = false;
    public $servicioIdBeingDeleted = null;
    public $servicioNameBeingDeleted = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmServicioDeletion($id, $nombre)
    {
        $this->servicioIdBeingDeleted = $id;
        $this->servicioNameBeingDeleted = $nombre;
        $this->showDeleteModal = true;
    }
    public bool $isWizard = false;

    public function mount(): void
    {
        $this->isWizard = request()->boolean('wizard');
    }

    public function delete()
    {
        $servicio = Servicio::findOrFail($this->servicioIdBeingDeleted);
        $servicio->delete();

        $this->showDeleteModal = false;
        session()->flash('success', 'Servicio eliminado exitosamente.');
    }

    public function render()
    {
        $servicios = Servicio::query()
            ->with('proyecto')
            ->where(function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('descripcion', 'like', '%' . $this->search . '%')
                    ->orWhere('estado', 'like', '%' . $this->search . '%')
                    ->orWhereHas('proyecto', function ($q) {
                        $q->where('nombre', 'like', '%' . $this->search . '%');
                    });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.servicio.servicio-index', [
            'servicios' => $servicios,
        ]);
    }


    public function export()
    {
        return Excel::download(
            new \App\Exports\ServiciosExport,
            'servicios_' . now()->format('Y_m_d_His') . '.xlsx'
        );
    }
}