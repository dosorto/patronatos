<?php

namespace App\Livewire\TipoActivo;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TipoActivo;
use Livewire\Attributes\Url;

class TipoActivoIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $search = '';

    public int $perPage = 10;

    // 👇 Propiedades declaradas correctamente
    public bool $showDeleteModal = false;
    public ?int $tipoActivoIdToDelete = null;
    public string $tipoActivoNombre = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id, string $nombre): void
    {
        $this->tipoActivoIdToDelete = $id;
        $this->tipoActivoNombre = $nombre;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        TipoActivo::findOrFail($this->tipoActivoIdToDelete)->delete();
        $this->showDeleteModal = false;
        $this->tipoActivoIdToDelete = null;
        $this->tipoActivoNombre = '';
        session()->flash('success', 'Tipo de Activo eliminado correctamente.');
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->tipoActivoIdToDelete = null;
        $this->tipoActivoNombre = '';
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