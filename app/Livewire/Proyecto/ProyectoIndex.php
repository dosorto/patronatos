<?php

namespace App\Livewire\Proyecto;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Proyecto;
use Livewire\Attributes\Url;
use Maatwebsite\Excel\Facades\Excel;

class ProyectoIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $search = '';

    public $perPage = 10;

    public $showDeleteModal = false;
    public $proyectoIdBeingDeleted = null;
    public $proyectoNameBeingDeleted = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmProyectoDeletion($id, $nombre)
    {
        $this->proyectoIdBeingDeleted = $id;
        $this->proyectoNameBeingDeleted = $nombre;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        Proyecto::findOrFail($this->proyectoIdBeingDeleted)->delete();

        $this->showDeleteModal = false;
        session()->flash('success', 'Proyecto eliminado exitosamente.');
    }

    public function export()
    {
        $orgNombre = auth()->user()->organization->name ?? 'organizacion';
        $nombre = \Illuminate\Support\Str::slug($orgNombre) . '_proyectos_' . now()->format('Y_m_d_His') . '.xlsx';

        return Excel::download(
            new \App\Exports\ProyectosExport,
            $nombre
        );
    }

    public function render()
    {
        $proyectos = Proyecto::where(function ($query) {
                $query->where('nombre_proyecto', 'like', '%' . $this->search . '%')
                      ->orWhere('tipo_proyecto', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.proyecto.proyecto-index', [
            'proyectos' => $proyectos,
        ]);
    }
}