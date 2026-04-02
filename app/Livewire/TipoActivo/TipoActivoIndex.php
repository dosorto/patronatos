<?php

namespace App\Livewire\TipoActivo;

use App\Models\TipoActivo;
use App\Exports\TipoActivoExport;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Maatwebsite\Excel\Facades\Excel;

class TipoActivoIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $search = '';

    public $perPage = 10;

    // Propiedades para el modal de eliminación
    public $showDeleteModal = false;
    public $tipoActivoIdBeingDeleted = null;
    public $tipoActivoNameBeingDeleted = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmTipoActivoDeletion($id, $name)
    {
        $this->tipoActivoIdBeingDeleted = $id;
        $this->tipoActivoNameBeingDeleted = $name;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $tipoActivo = TipoActivo::findOrFail($this->tipoActivoIdBeingDeleted);
        $tipoActivo->delete();

        $this->showDeleteModal = false;

        session()->flash('success', 'Tipo de Activo eliminado correctamente.');
    }

    public function export()
    {
        abort_if(!auth()->user()->can('tipoactivo.export'), 403);
        return Excel::download(new TipoActivoExport, 'tipo_activos_' . now()->format('Y_m_d_His') . '.xlsx');
    }

    public function render()
    {
    $tipoactivos = TipoActivo::query()
        ->where('nombre', 'like', '%' . $this->search . '%')
        ->orWhere('descripcion', 'like', '%' . $this->search . '%')
        ->latest()
        ->paginate($this->perPage);

    return view('livewire.tipoactivo.tipo-activo-index', [
        'tipoactivos' => $tipoactivos,
    ]);
    }
}