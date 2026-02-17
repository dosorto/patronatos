<?php

namespace App\Livewire\Municipio;

use App\Models\Municipio;
use App\Exports\MunicipiosExport;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Maatwebsite\Excel\Facades\Excel;

class MunicipioIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $search = '';
    
    public $perPage = 10;
    
    // Propiedades para el modal de eliminación
    public $showDeleteModal = false;
    public $municipioIdBeingDeleted = null;
    public $municipioNameBeingDeleted = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmMunicipioDeletion($id, $name)
    {
        $this->municipioIdBeingDeleted = $id;
        $this->municipioNameBeingDeleted = $name;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $municipio = Municipio::findOrFail($this->municipioIdBeingDeleted);
        $municipio->delete();

        $this->showDeleteModal = false;
        
        session()->flash('success', 'Municipio eliminado exitosamente.');
    }

    public function export()
    {
        abort_if(!auth()->user()->can('municipios.export'), 403);
        return Excel::download(new MunicipiosExport, 'municipios_' . now()->format('Y_m_d_His') . '.xlsx');
    }

    public function render()
    {
        $municipios = Municipio::query()
            ->where(function($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('codigo', 'like', '%' . $this->search . '%')
                    ->orWhere('descripcion', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.municipio.municipio', [
            'municipios' => $municipios
        ]);
    }
}
