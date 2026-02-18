<?php

namespace App\Livewire\Municipio;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Municipio;
use App\Models\Departamento;
use Livewire\Attributes\Url;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MunicipiosExport;

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

    // Resetear página al actualizar la búsqueda
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Confirmación de eliminación
    public function confirmMunicipioDeletion($id, $nombre)
    {
        $this->municipioIdBeingDeleted = $id;
        $this->municipioNameBeingDeleted = $nombre;
        $this->showDeleteModal = true;
    }

    // Eliminar municipio
    public function delete()
    {
        $municipio = Municipio::findOrFail($this->municipioIdBeingDeleted);
        $municipio->delete();

        $this->showDeleteModal = false;
        session()->flash('success', 'Municipio eliminado exitosamente.');
    }

    // Exportar a Excel
    public function export()
    {
        return Excel::download(
            new MunicipiosExport, 
            'municipios_' . now()->format('Y_m_d_His') . '.xlsx'
        );
    }

    public function render()
    {
        $municipios = Municipio::with('departamento')
            ->where('nombre', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.municipio.municipio-index', [
            'municipios' => $municipios
        ]);
    }
}
