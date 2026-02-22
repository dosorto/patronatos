<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMiembroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Obtiene el miembro que viene por la ruta: /miembro/{miembro}/edit
        $miembroId = $this->route('miembro');

        return [
            'persona_id' => [
                'required',
                'exists:personas,id',
                Rule::unique('miembros', 'persona_id')->ignore($miembroId),
            ],
            'organizacion_id' => 'required|exists:organizacion,id_organizacion',
            'municipio_id' => 'required|exists:municipios,id',
            'direccion' => 'nullable|string|max:255',
            'estado' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'persona_id.required' => 'La persona es obligatoria.',
            'persona_id.exists'   => 'La persona seleccionada no existe.',
            'persona_id.unique'   => 'Esta persona ya está registrada como miembro.',
            'organizacion_id.required' => 'La organización es obligatoria.',
            'organizacion_id.exists'   => 'La organización seleccionada no existe.',
            'municipio_id.required'    => 'El municipio es obligatorio.',
            'municipio_id.exists'      => 'El municipio seleccionado no existe.',
            'direccion.string' => 'La dirección debe ser texto.',
            'direccion.max'    => 'La dirección no puede tener más de 255 caracteres.',
            'estado.required'  => 'El estado es obligatorio.',
            'estado.boolean'   => 'El estado debe ser verdadero o falso.',
        ];
    }
}