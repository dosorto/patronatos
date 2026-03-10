<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServicioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'                        => 'required|string|max:255',
            'descripcion'                   => 'nullable|string',
            'precio'                        => 'required|numeric|min:0',
            'estado'                        => 'required|string|in:activo,inactivo',
            'tiene_medidor'                 => 'boolean',
            'unidad_medida'                 => 'nullable|string|max:100',
            'precio_por_unidad_de_medida'   => 'nullable|numeric|min:0',
            'es_aportacion'                 => 'boolean',
            'proyecto_id' => 'nullable|exists:proyectos,id',
        ];
    }

    public function messages(): array
    {
        return [
        'nombre.required'       => 'El nombre es obligatorio.',
        'precio.required'       => 'El precio es obligatorio.',
        'precio.numeric'        => 'El precio debe ser un número.',
        'estado.in'             => 'El estado debe ser activo o inactivo.',
        'proyecto_id.exists'    => 'El proyecto seleccionado no existe.', // ✅
    ];
    }
}