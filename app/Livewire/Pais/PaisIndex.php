<?php

namespace App\Livewire\Pais;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pais;
use Livewire\Attributes\Url;
use Maatwebsite\Excel\Facades\Excel;

class PaisIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $search = '';

    public $perPage = 10;

    public $showDeleteModal = false;
    public $paisIdBeingDeleted = null;
    public $paisNameBeingDeleted = '';

    // Resetear página al actualizar la búsqueda
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Confirmación de eliminación
    public function confirmPaisDeletion($id, $name)
    {
        $this->paisIdBeingDeleted = $id;
        $this->paisNameBeingDeleted = $name;
        $this->showDeleteModal = true;
    }

    // Eliminar país
    public function delete()
    {
        $pais = Pais::findOrFail($this->paisIdBeingDeleted);
        $pais->delete();

        $this->showDeleteModal = false;
        
        session()->flash('success', 'País eliminado exitosamente.');
    }

    // Exportar a Excel
    public function export()
    {
        abort_if(!auth()->user()->can('pais.export'), 403);
        
        $orgId = session('tenant_organization_id');
        $org = \App\Models\Organization::find($orgId);
        $orgNombre = $org ? \Illuminate\Support\Str::slug($org->name) : 'organization';
        $fecha = now()->format('Y_m_d_His');

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\PaisesExport(),
            $orgNombre . '_paises_' . $fecha . '.xlsx'
        );
    }

    public function render()
    {
        $paises = Pais::query()
            ->where('nombre', 'like', '%' . $this->search . '%')
            ->orWhere('iso', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate($this->perPage);


        return view('livewire.pais.pais-index', [
            'paises' => $paises
        ]);
    }
}
