<?php

namespace App\Livewire\Mora;
 
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Mora;
use App\Models\Miembros;
use App\Services\MoraService;
 
class MoraIndex extends Component
{
    use WithPagination;
 
    public $search = '';
    public $perPage = 10;
 
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function sincronizarMoras()
    {
        $moraService = new MoraService();
        $moraService->syncAllMembers();
        
        session()->flash('success', 'Las moras se han sincronizado correctamente basándose en suscripciones y aportaciones pendientes.');
    }
 
    public function render()
    {
        // Solo mostramos las moras pendientes
        $moras = Mora::with(['miembro.persona'])
            ->where('organization_id', session('tenant_organization_id'))
            ->whereIn('estado', ['Pendiente', 'Abonado'])
            ->whereHas('miembro.persona', function($q) {
                $q->where('nombre', 'like', '%' . $this->search . '%')
                  ->orWhere('apellido', 'like', '%' . $this->search . '%');
            })
            ->orWhere(function($query) {
                $query->where('organization_id', session('tenant_organization_id'))
                      ->whereIn('estado', ['Pendiente', 'Abonado'])
                      ->where('periodo', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);
 
        return view('livewire.mora.mora-index', [
            'moras' => $moras
        ]);
    }
}
