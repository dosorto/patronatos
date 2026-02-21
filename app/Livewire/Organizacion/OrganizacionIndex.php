<?php

namespace App\Livewire\Organizacion;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Organizacion;
use Livewire\Attributes\Url;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrganizacionesExport;


class OrganizacionIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $search = '';

    public $perPage = 10;

    public $showDeleteModal = false;
    public $organizacionIdBeingDeleted = null;
    public $organizacionNameBeingDeleted = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmOrganizacionDeletion($id, $nombre)
    {
        $this->organizacionIdBeingDeleted = $id;
        $this->organizacionNameBeingDeleted = $nombre;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $organizacion = Organizacion::findOrFail($this->organizacionIdBeingDeleted);
        $organizacion->delete();

        $this->showDeleteModal = false;
        $this->reset(['organizacionIdBeingDeleted', 'organizacionNameBeingDeleted']);
        session()->flash('success', 'Organización eliminada exitosamente.');
    }

    public function render()
    {
        $organizaciones = Organizacion::with(['tipoOrganizacion', 'municipio', 'departamento'])
            ->where('nombre', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.organizacion.organizacion-index', [
            'organizaciones' => $organizaciones
        ]);
    }

    public function export()
    {
        abort_if(!auth()->user()->can('organizacion.export'), 403);

        return Excel::download(
            new OrganizacionesExport,
            'organizaciones_' . now()->format('Y_m_d_His') . '.xlsx'
        );
    }
}