<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check(); 
        // o puedes poner true si el middleware ya protege la ruta
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'ubicacion' => 'nullable|string|max:255',
            'fecha_adquisicion' => 'nullable|date',
            'valor_estimado' => 'nullable|numeric|min:0',
            'tipo_activo_id' => 'required|exists:tipo_activos,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del activo es obligatorio.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',

            'ubicacion.max' => 'La ubicación no puede tener más de 255 caracteres.',

            'fecha_adquisicion.date' => 'La fecha de adquisición no es válida.',

            'valor_estimado.numeric' => 'El valor estimado debe ser numérico.',
            'valor_estimado.min' => 'El valor estimado no puede ser negativo.',

            'tipo_activo_id.required' => 'Debe seleccionar un tipo de activo.',
            'tipo_activo_id.exists' => 'El tipo de activo seleccionado no existe.',
        ];
    }
}