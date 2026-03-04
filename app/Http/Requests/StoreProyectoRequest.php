<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProyectoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Step 1 - Información General
            'nombre_proyecto'           => 'required|string|max:255',
            'tipo_proyecto'             => 'nullable|string|max:255',
            'descripcion'               => 'nullable|string',
            'justificacion'             => 'nullable|string',
            'numero_acta'               => 'nullable|string|max:100',
            'fecha_aprobacion_asamblea' => 'nullable|date',
            'fecha_inicio'              => 'nullable|date',
            'fecha_fin'                 => 'nullable|date|after_or_equal:fecha_inicio',

            // Step 2 - Beneficiarios
            'descripcion_beneficiarios' => 'nullable|string',
            'benef_hombres'             => 'nullable|integer|min:0',
            'benef_mujeres'             => 'nullable|integer|min:0',
            'benef_ninos'               => 'nullable|integer|min:0',
            'benef_familias'            => 'nullable|integer|min:0',

            // Step 3 - Ubicación y Responsable
            'departamento_id'        => 'nullable|exists:departamentos,id',
            'municipio_id'           => 'nullable|exists:municipios,id',
            'miembro_responsable_id' => 'nullable|exists:miembros,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_proyecto.required' => 'El nombre del proyecto es obligatorio.',
            'nombre_proyecto.max'      => 'El nombre no puede tener más de 255 caracteres.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
            'benef_hombres.integer'    => 'El número de hombres beneficiarios debe ser un entero.',
            'benef_mujeres.integer'    => 'El número de mujeres beneficiarias debe ser un entero.',
            'benef_ninos.integer'      => 'El número de niños beneficiarios debe ser un entero.',
            'benef_familias.integer'   => 'El número de familias beneficiarias debe ser un entero.',
            'departamento_id.exists'   => 'El departamento seleccionado no existe.',
            'municipio_id.exists'      => 'El municipio seleccionado no existe.',
            'miembro_responsable_id.exists' => 'El miembro responsable seleccionado no existe.',
        ];
    }
}