<?php

namespace App\Livewire\Aportacion;

use App\Models\Aportacion;
use App\Models\Miembros;
use App\Models\Proyecto;
use Livewire\Component;
use Livewire\WithPagination;

class AportacionIndex extends Component {
    use WithPagination;

    public string $search = '';
    public string $filtroEstado = '';
    public string $filtroProyecto = '';

    public bool $showModal = false;
    public bool $isEditing = false;

    public ?int $editId = null;
    public int $id_miembro = 0;
    public int $id_proyecto = 0;
    public string $monto = '';
    public string $fecha_aportacion = '';
    public bool $estado = true;

    protected function rules(): array {
        return [
            'id_miembro'       => 'required|integer|exists:miembros,id_miembro',
            'id_proyecto'      => 'required|integer|exists:proyectos,id_proyecto',
            'monto'            => 'required|numeric|min:0.01',
            'fecha_aportacion' => 'required|date',
            'estado'           => 'boolean',
        ];
    }

    protected $messages = [
        'id_miembro.required'       => 'Selecciona un miembro.',
        'id_proyecto.required'      => 'Selecciona un proyecto.',
        'monto.required'            => 'El monto es obligatorio.',
        'monto.min'                 => 'El monto debe ser mayor a cero.',
        'fecha_aportacion.required' => 'La fecha es obligatoria.',
    ];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFiltroEstado() { $this->resetPage(); }
    public function updatingFiltroProyecto() { $this->resetPage(); }

    public bool $showDeleteModal = false;
    public ?int $aportacionIdBeingDeleted = null;
    public string $aportacionNameBeingDeleted = '';

    public function openCreate() {
        $this->reset(['editId','id_miembro','id_proyecto','monto','fecha_aportacion']);
        $this->estado = true;
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEdit(int $id) {
        $a = Aportacion::findOrFail($id);

        $this->editId = $a->id_aportacion;
        $this->id_miembro = $a->id_miembro;
        $this->id_proyecto = $a->id_proyecto;
        $this->monto = (string) $a->monto;
        $this->fecha_aportacion = \Carbon\Carbon::parse($a->fecha_aportacion)->format('Y-m-d');
        $this->estado = $a->estado;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save() {
        $data = $this->validate();

        if ($this->isEditing) {
            Aportacion::findOrFail($this->editId)->update($data);
            session()->flash('success', 'Aportación actualizada.');
        } else {
            Aportacion::create($data);
            session()->flash('success', 'Aportación registrada.');
        }

        $this->showModal = false;
        $this->resetPage();
    }

    public function delete(int $id) {
        Aportacion::findOrFail($id)->delete();
        session()->flash('success', 'Aportación eliminada.');
    }

    public function toggleEstado(int $id) {
        $a = Aportacion::findOrFail($id);
        $a->update(['estado' => !$a->estado]);
    }

    public function render() {

        $miembros = Miembros::with('persona')->get();
        $proyectos = Proyecto::orderBy('nombre_proyecto')->get();

        $aportaciones = Aportacion::with(['miembro.persona', 'proyecto'])
            ->when($this->search, fn($q) =>
                $q->whereHas('miembro.persona', fn($m) =>
                    $m->where('nombre', 'like', "%{$this->search}%")
                      ->orWhere('apellido', 'like', "%{$this->search}%")
                )
                ->orWhereHas('proyecto', fn($p) =>
                    $p->where('nombre_proyecto', 'like', "%{$this->search}%")
                )
            )
            ->when($this->filtroEstado !== '', fn($q) =>
                $q->where('estado', (bool) $this->filtroEstado)
            )
            ->when($this->filtroProyecto, fn($q) =>
                $q->where('id_proyecto', $this->filtroProyecto)
            )
            ->latest()
            ->paginate(10);

        return view('livewire.aportacion.aportacion-index',
            compact('aportaciones', 'miembros', 'proyectos'));
    }
}