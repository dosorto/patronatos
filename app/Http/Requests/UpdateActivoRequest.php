<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActivoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // permitir que pase la validación
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'tipo_activo_id' => 'required|exists:tipo_activos,id',
            'ubicacion' => 'nullable|string|max:255',      // <-- agregado
            'valor_estimado' => 'nullable|numeric',        // <-- agregado
            'estado' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del activo es obligatorio.',
            'tipo_activo_id.required' => 'Debe seleccionar un tipo de activo.',
            'tipo_activo_id.exists' => 'El tipo seleccionado no es válido.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.boolean' => 'El estado debe ser activo o inactivo.',
        ];
    }
}