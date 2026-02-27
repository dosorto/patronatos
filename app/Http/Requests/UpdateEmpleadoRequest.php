<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmpleadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $empleadoId = $this->route('empleado');

        return [
            'persona_id' => [
                'required',
                Rule::unique('empleados', 'persona_id')->whereNull('deleted_at')->ignore($this->route('empleado')),
            ],
            'organizacion_id' => 'required|exists:organizacion,id_organizacion',
            'cargo'           => 'required|string|max:255',
            'sueldo_mensual'  => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'persona_id.required'      => 'La persona es obligatoria.',
            'persona_id.exists'        => 'La persona seleccionada no existe.',
            'organizacion_id.required' => 'La organización es obligatoria.',
            'organizacion_id.exists'   => 'La organización seleccionada no existe.',
            'cargo.required'           => 'El cargo es obligatorio.',
            'cargo.string'             => 'El cargo debe ser texto.',
            'cargo.max'                => 'El cargo no puede tener más de 255 caracteres.',
            'sueldo_mensual.required'  => 'El sueldo mensual es obligatorio.',
            'sueldo_mensual.numeric'   => 'El sueldo mensual debe ser un número.',
            'sueldo_mensual.min'       => 'El sueldo mensual no puede ser negativo.',
        ];
    }

    // En StoreEmpleadoRequest y UpdateEmpleadoRequest
    protected function passedValidation()
    {
        $this->merge([
            'sueldo_mensual' => $this->sueldo_mensual * 100,
        ]);
    }
}
