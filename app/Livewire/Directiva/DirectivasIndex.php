<?php

namespace App\Livewire\Directiva;

use App\Models\Directiva;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class DirectivasIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $search = '';
    
    public $perPage = 10;
    
    // Propiedades para el modal de eliminación
    public $showDeleteModal = false;
    public $directivaIdBeingDeleted = null;
    public $directivaNameBeingDeleted = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDirectivaDeletion($id, $name)
    {
        $this->directivaIdBeingDeleted = $id;
        $this->directivaNameBeingDeleted = $name;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $directiva = Directiva::findOrFail($this->directivaIdBeingDeleted);
        $directiva->delete();

        $this->showDeleteModal = false;
        
        session()->flash('success', 'Miembro de directiva eliminado exitosamente.');
    }

    public function export()
    {
        abort_if(!auth()->user()->can('directiva.export'), 403);
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\DirectivasExport, 'directivas_' . now()->format('Y_m_d_His') . '.xlsx');
    }

    public function render()
    {
        $directivas = Directiva::query()
            ->with(['miembro.persona', 'organization'])
            ->whereHas('miembro.persona', function($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('apellido', 'like', '%' . $this->search . '%');
            })
            ->orWhere('cargo', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.directiva.directivas-index', [
            'directivas' => $directivas
        ]);
    }
}
