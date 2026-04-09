// app/Livewire/Configuracion/EditOrganization.php

namespace App\Livewire\Configuracion;

use App\Models\Organization;
use App\Models\TipoOrganizacion;
use App\Models\Pais;
use App\Models\Departamento;
use App\Models\Municipio;
use Livewire\Component;

class EditOrganization extends Component
{
    public $org;

    // Campos
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $rtn = '';
    public string $direccion = '';
    public string $fecha_creacion = '';
    public string $estado = 'Activo';
    public ?int $id_tipo_organizacion = null;
    public ?int $pais_id = null;
    public ?int $id_departamento = null;
    public ?int $id_municipio = null;

    public function mount()
    {
        $this->org = Organization::find(session('tenant_organization_id'));

        $this->name               = $this->org->name ?? '';
        $this->email              = $this->org->email ?? '';
        $this->phone              = $this->org->phone ?? '';
        $this->rtn                = $this->org->rtn ?? '';
        $this->direccion          = $this->org->direccion ?? '';
        $this->fecha_creacion     = $this->org->fecha_creacion ?? '';
        $this->estado             = $this->org->estado ?? 'Activo';
        $this->id_tipo_organizacion = $this->org->id_tipo_organizacion;
        $this->id_departamento    = $this->org->id_departamento;
        $this->id_municipio       = $this->org->id_municipio;

        // Cargar el pais_id desde el departamento
        $this->pais_id = optional($this->org->departamento)->pais_id;
    }

    // Reactivo: al cambiar país, limpiar depto y municipio
    public function updatedPaisId(): void
    {
        $this->id_departamento = null;
        $this->id_municipio    = null;
    }

    // Reactivo: al cambiar departamento, limpiar municipio
    public function updatedIdDepartamento(): void
    {
        $this->id_municipio = null;
    }

    // Computed: departamentos filtrados por país
    public function getDepartamentosProperty()
    {
        if (!$this->pais_id) return collect();
        return Departamento::where('pais_id', $this->pais_id)->get();
    }

    // Computed: municipios filtrados por departamento
    public function getMunicipiosProperty()
    {
        if (!$this->id_departamento) return collect();
        return Municipio::where('departamento_id', $this->id_departamento)->get();
    }

    public function save()
    {
        $this->validate([
            'name'              => ['required', 'string', 'min:3', 'max:255'],
            'email'             => ['nullable', 'email', 'max:255'],
            'phone'             => ['nullable', 'string', 'max:30'],
            'rtn'               => ['nullable', 'string', 'max:20'],
            'direccion'         => ['nullable', 'string', 'max:255'],
            'fecha_creacion'    => ['nullable', 'date'],
            'id_tipo_organizacion' => ['nullable', 'exists:tipo_organizacion,id_tipo_organizacion'],
            'id_departamento'   => ['nullable', 'exists:departamentos,id'],
            'id_municipio'      => ['nullable', 'exists:municipios,id'],
        ]);

        $this->org->update([
            'name'                 => $this->name,
            'email'                => $this->email ?: null,
            'phone'                => $this->phone ?: null,
            'rtn'                  => $this->rtn ?: null,
            'direccion'            => $this->direccion ?: null,
            'fecha_creacion'       => $this->fecha_creacion ?: null,
            'estado'               => $this->estado,
            'id_tipo_organizacion' => $this->id_tipo_organizacion ?: null,
            'id_departamento'      => $this->id_departamento ?: null,
            'id_municipio'         => $this->id_municipio ?: null,
        ]);

        session()->flash('success', 'Organización actualizada correctamente.');
        $this->redirect(route('settings.index'));
    }

    public function render()
    {
        return view('livewire.configuracion.edit-organization', [
            'tipos'   => TipoOrganizacion::all(),
            'paises'  => Pais::all(),
        ]);
    }
}