<?php

namespace App\Livewire\Departamento;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Departamento;
use Livewire\Attributes\Url;
use Maatwebsite\Excel\Facades\Excel;

class DepartamentoIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $search = '';

    public $perPage = 10;

    public $showDeleteModal = false;
    public $departamentoIdBeingDeleted = null;
    public $departamentoNameBeingDeleted = '';

    // Resetear página al actualizar la búsqueda
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Confirmación de eliminación
    public function confirmDepartamentoDeletion($id, $nombre)
    {
        $this->departamentoIdBeingDeleted = $id;
        $this->departamentoNameBeingDeleted = $nombre;
        $this->showDeleteModal = true;
    }

    // Eliminar departamento
    public function delete()
    {
        $departamento = Departamento::findOrFail($this->departamentoIdBeingDeleted);
        $departamento->delete();

        $this->showDeleteModal = false;
        session()->flash('success', 'Departamento eliminado exitosamente.');
    }

    // Exportar a Excel
    public function export()
    {
        // Nota: Asegúrate de que el permiso 'departamento.export' exista si habilitas esto
        // abort_if(!auth()->user()->can('departamento.export'), 403);
        
        return Excel::download(
            new \App\Exports\DepartamentosExport, 
            'departamentos_' . now()->format('Y_m_d_His') . '.xlsx'
        );
    }

    public function render()
    {
        $departamentos = Departamento::with('pais')
            ->where(function($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%');
                
                // Si quisiéramos buscar por país también:
                $query->orWhereHas('pais', function($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.departamento.departamento-index', [
            'departamentos' => $departamentos
        ]);
    }
}
