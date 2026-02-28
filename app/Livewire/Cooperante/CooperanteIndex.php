<?php

namespace App\Livewire\Cooperante;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cooperante;
use Livewire\Attributes\Url;
use Maatwebsite\Excel\Facades\Excel;

class CooperanteIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $search = '';

    public $perPage = 10;

    public $showDeleteModal = false;
    public $cooperanteIdBeingDeleted = null;
    public $cooperanteNameBeingDeleted = '';

    // Resetear página al actualizar la búsqueda para evitar resultados vacíos
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Confirmación de eliminación
    public function confirmCooperanteDeletion($id, $nombre)
    {
        $this->cooperanteIdBeingDeleted = $id;
        $this->cooperanteNameBeingDeleted = $nombre;
        $this->showDeleteModal = true;
    }

    // Eliminar cooperante (usa SoftDeletes por heredar de BaseModel)
    public function delete()
    {
        $cooperante = Cooperante::findOrFail($this->cooperanteIdBeingDeleted);
        $cooperante->delete();

        $this->showDeleteModal = false;
        session()->flash('success', 'Cooperante eliminado exitosamente.');
    }

    // Exportar a Excel
    public function export()
    {
        return Excel::download(
            new \App\Exports\CooperanteExport, 
            'cooperantes_' . now()->format('Y_m_d_His') . '.xlsx'
        );
    }

    public function render()
    {
        $cooperantes = Cooperante::query()
            ->where(function($query) {
                // Busca por el nombre del cooperante
                $query->where('nombre', 'like', '%' . $this->search . '%')
                // O busca por el nombre de la organización relacionada
                ->orWhereHas('organizacion', function($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.cooperante.cooperante-index', [
            'cooperantes' => $cooperantes
        ]);
    }
}