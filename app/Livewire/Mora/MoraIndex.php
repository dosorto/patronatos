<?php

namespace App\Livewire\Mora;
 
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Mora;
use App\Models\Miembros;
 
class MoraIndex extends Component
{
    use WithPagination;
 
    public $search = '';
    public $perPage = 10;
 
    // Modal properties
    public $showCreateModal = false;
    public $showDeleteModal = false;
 
    // Create/Edit properties
    public $miembro_id;
    public $periodo;
    public $monto_original;
    public $estado = 'Pendiente';
 
    // Delete properties
    public $moraIdBeingDeleted = null;
 
    protected $rules = [
        'miembro_id' => 'required|exists:miembros,id',
        'periodo' => 'required|string|max:255',
        'monto_original' => 'required|numeric|min:0',
        'estado' => 'required|string|in:Pendiente,Abonado,Cancelado',
    ];
 
    public function updatingSearch()
    {
        $this->resetPage();
    }
 
    public function openCreateModal()
    {
        $this->reset(['miembro_id', 'periodo', 'monto_original', 'estado']);
        $this->showCreateModal = true;
    }
 
    public function save()
    {
        $this->validate();
 
        Mora::create([
            'organization_id' => session('tenant_organization_id'),
            'miembro_id' => $this->miembro_id,
            'periodo' => $this->periodo,
            'monto_original' => $this->monto_original,
            'monto_pendiente' => $this->monto_original, // Initially same as original
            'estado' => $this->estado,
        ]);
 
        $this->showCreateModal = false;
        session()->flash('success', 'Mora registrada exitosamente.');
    }
 
    public function confirmMoraDeletion($id)
    {
        $this->moraIdBeingDeleted = $id;
        $this->showDeleteModal = true;
    }
 
    public function delete()
    {
        $mora = Mora::findOrFail($this->moraIdBeingDeleted);
        $mora->delete();
 
        $this->showDeleteModal = false;
        session()->flash('success', 'Mora eliminada exitosamente.');
    }
 
    public function render()
    {
        $moras = Mora::with(['miembro.persona'])
            ->whereHas('miembro.persona', function($q) {
                $q->where('nombre', 'like', '%' . $this->search . '%')
                  ->orWhere('apellido', 'like', '%' . $this->search . '%');
            })
            ->orWhere('periodo', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate($this->perPage);
 
        $miembros = Miembros::with('persona')->get();
 
        return view('livewire.mora.mora-index', [
            'moras' => $moras,
            'miembros' => $miembros
        ]);
    }
}
