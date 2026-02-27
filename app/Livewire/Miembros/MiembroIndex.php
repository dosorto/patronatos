<?php

namespace App\Livewire\Miembros;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Miembros;
use Livewire\Attributes\Url;
use Maatwebsite\Excel\Facades\Excel;

class MiembroIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $search = '';

    public $perPage = 10;

    public $showDeleteModal = false;
    public $miembroIdBeingDeleted = null;
    public $miembroNameBeingDeleted = '';

    // Resetear página al actualizar la búsqueda
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Confirmación de eliminación
    public function confirmMiembroDeletion($id, $nombre)
    {
        $this->miembroIdBeingDeleted = $id;
        $this->miembroNameBeingDeleted = $nombre;
        $this->showDeleteModal = true;
    }

    // Eliminar miembro
    public function delete()
    {
        $miembro = Miembros::findOrFail($this->miembroIdBeingDeleted);
        $miembro->delete();

        $this->showDeleteModal = false;
        session()->flash('success', 'Miembro eliminado exitosamente.');
    }

    // Exportar a Excel
    public function export()
    {
        // Nota: Asegúrate de que el permiso 'miembro.export' exista si habilitas esto
        // abort_if(!auth()->user()->can('miembro.export'), 403);

        return Excel::download(
            new \App\Exports\MiembrosExport, 
            'miembros_' . now()->format('Y_m_d_His') . '.xlsx'
        );
    }

    public function render()
    {
        $miembros = Miembros::with(['persona', 'organizacion', 'municipio'])
            ->where(function($query) {
                $query->whereHas('persona', function($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('apellido', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('organizacion', function($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('municipio', function($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.miembros.miembro-index', [
            'miembros' => $miembros
        ]);
    }
}