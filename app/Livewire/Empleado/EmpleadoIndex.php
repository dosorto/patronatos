<?php

namespace App\Livewire\Empleado;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Empleado;
use Livewire\Attributes\Url;
use Maatwebsite\Excel\Facades\Excel;

class EmpleadoIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $search = '';

    public $perPage = 10;

    public $showDeleteModal = false;
    public $empleadoIdBeingDeleted = null;
    public $empleadoNameBeingDeleted = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmEmpleadoDeletion($id, $nombre)
    {
        $this->empleadoIdBeingDeleted = $id;
        $this->empleadoNameBeingDeleted = $nombre;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $empleado = Empleado::findOrFail($this->empleadoIdBeingDeleted);
        $empleado->delete();

        $this->showDeleteModal = false;
        session()->flash('success', 'Empleado eliminado exitosamente.');
    }

    public function export()
    {
        return Excel::download(
            new \App\Exports\EmpleadosExport,
            'empleados_' . now()->format('Y_m_d_His') . '.xlsx'
        );
    }

    public function render()
    {
        $empleados = Empleado::with(['persona', 'organization'])
            ->where(function ($query) {
                $query->where('cargo', 'like', '%' . $this->search . '%')
                    ->orWhereHas('persona', function ($q) {
                        $q->where('nombre', 'like', '%' . $this->search . '%')
                          ->orWhere('apellido', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('organization', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.empleado.empleado-index', [
            'empleados' => $empleados,
        ]);
    }
}