<?php

namespace App\Livewire\TipoActivo;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TipoActivo;
use Livewire\Attributes\Url;
use Maatwebsite\Excel\Facades\Excel;

class TipoActivoIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $search = '';

    public $perPage = 10;

    public $showDeleteModal = false;
    public $paisIdBeingDeleted = null;
    public $paisNameBeingDeleted = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function export()
    {
        return Excel::download(
            new TipoActivoExport(),
            'tipo_activos_' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function render()
    {
        $tipoactivos = TipoActivo::where('nombre', 'like', '%' . $this->search . '%')
            ->orWhere('descripcion', 'like', '%' . $this->search . '%')
            ->paginate($this->perPage);

        return view('livewire.tipoactivo.tipo-activo-index', [
            'tipoactivos' => $tipoactivos,
        ]);
    }
}