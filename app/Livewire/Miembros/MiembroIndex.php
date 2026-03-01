<?php

namespace App\Livewire\Miembros;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Miembros;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MiembrosExport;

class MiembroIndex extends Component
{
    use WithPagination;

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
        $orgId = session('tenant_organization_id');
        $org = \App\Models\Organization::find($orgId);
        $orgNombre = $org ? \Illuminate\Support\Str::slug($org->name) : 'organizacion';
        $fecha = now()->format('Y_m_d_His');

        return Excel::download(
            new \App\Exports\MiembrosExport(),
            $orgNombre . '_miembros_' . $fecha . '.xlsx'
        );
    }

    public function render()
    {
        // Filtramos solo por nombre/apellido de la persona
        $miembros = Miembros::with(['persona'])
            ->whereHas('persona', function($q) {
                $q->where('nombre', 'like', '%' . $this->search . '%')
                ->orWhere('apellido', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.miembros.miembro-index', [
            'miembros' => $miembros
        ]);
    }
}