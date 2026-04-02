<?php

namespace App\Livewire\Activos;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Activo;
use Livewire\Attributes\Url;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ActivosExport;
use App\Models\Organization;

class ActivoIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $search = '';

    public $perPage = 10;

    public $showDeleteModal = false;
    public $activoIdBeingDeleted = null;
    public $activoNameBeingDeleted = '';

    // Resetear página al actualizar la búsqueda
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public bool $isWizard = false;

    public function mount(): void
    {
        $this->isWizard = request()->boolean('wizard');
    }

    // Confirmación de eliminación
    public function confirmActivoDeletion($id, $nombre)
    {
        $this->activoIdBeingDeleted = $id;
        $this->activoNameBeingDeleted = $nombre;
        $this->showDeleteModal = true;
    }

    // Eliminar activo
    public function delete()
    {
        $activo = Activo::findOrFail($this->activoIdBeingDeleted);
        $activo->delete();

        $this->showDeleteModal = false;
        session()->flash('success', 'Activo eliminado exitosamente.');
    }

   // Exportar a Excel
    public function export()
    {
        $orgId = session('tenant_organization_id');
        $org = \App\Models\Organization::find($orgId);
        $orgNombre = $org ? \Illuminate\Support\Str::slug($org->name) : 'organization';
        $fecha = now()->format('Y_m_d_His');

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\ActivosExport(),
            $orgNombre . '_activos_' . $fecha . '.xlsx'
        );
    }

    public function render()
    {
        $activos = Activo::with(['organization', 'tipoActivo'])
            ->where(function($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('descripcion', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.activos.activo-index', [
            'activos' => $activos
        ]);
    }
}