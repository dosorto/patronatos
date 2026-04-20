<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProyectoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Información General
            'nombre_proyecto'           => 'required|string|max:255',
            'tipo_proyecto'             => 'required|string|max:255',
            'descripcion'               => 'required|string',
            'justificacion'             => 'required|string',
            'numero_acta'               => 'nullable|string|max:100',
            'fecha_aprobacion_asamblea' => 'nullable|date',
            'fecha_inicio'              => 'nullable|date',
            'fecha_fin'                 => 'nullable|date|after_or_equal:fecha_inicio',

            // Beneficiarios
            'descripcion_beneficiarios' => 'required|string',
            'benef_hombres'             => 'required|integer|min:0',
            'benef_mujeres'             => 'required|integer|min:0',
            'benef_ninos'               => 'required|integer|min:0',
            'benef_familias'            => 'required|integer|min:0',

            // Ubicación y Responsable
            'departamento_id'           => 'nullable|exists:departamentos,id',
            'municipio_id'              => 'nullable|exists:municipios,id',
            'miembro_responsable_id'    => 'nullable|exists:miembros,id',
            
            // Detalles de Presupuesto (Arreglo dinámico unificado)
            'detalles'                         => 'nullable|array',
            'detalles.*.id'                    => 'nullable|exists:detalle_presupuestos,id',
            'detalles.*.nombre'                => 'required_with:detalles|string|max:255',
            'detalles.*.cantidad'              => 'nullable|numeric|min:0',
            'detalles.*.unidad_medida'         => 'nullable|string|max:100',
            'detalles.*.precio_unitario'       => 'nullable|numeric|min:0',
            'detalles.*.total'                 => 'nullable|numeric|min:0',
            'detalles.*.observaciones'         => 'nullable|string',
            'detalles.*.es_donacion'           => 'nullable|boolean',
            'detalles.*.id_cooperante'         => 'nullable|required_if:detalles.*.es_donacion,1,true|exists:cooperantes,id_cooperante',

            // Step 4 - Configuración Aportaciones (opcional)
            'config_tipo_distribucion'         => 'nullable|in:equitativa,manual',
            'config_monto_total'               => 'nullable|numeric|min:0',
            'config_fecha_limite'              => 'nullable|date',
            'config_observaciones'             => 'nullable|string|max:500',
            'montos_manuales'                  => 'nullable|array',
            'montos_manuales.*.miembro_id'     => 'nullable|exists:miembros,id',
            'montos_manuales.*.monto'          => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_proyecto.required' => 'El nombre del proyecto es obligatorio.',
            'nombre_proyecto.max'      => 'El nombre no puede tener más de 255 caracteres.',
            'tipo_proyecto.required'   => 'El tipo de proyecto es obligatorio.',
            'descripcion.required'     => 'La descripción del proyecto es obligatoria.',
            'justificacion.required'   => 'La justificación del proyecto es obligatoria.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',

            'descripcion_beneficiarios.required' => 'La descripción de los beneficiarios es obligatoria.',
            'benef_hombres.required'   => 'El número de hombres beneficiarios es obligatorio.',
            'benef_hombres.integer'    => 'El número de hombres beneficiarios debe ser un entero.',
            'benef_mujeres.required'   => 'El número de mujeres beneficiarias es obligatorio.',
            'benef_mujeres.integer'    => 'El número de mujeres beneficiarias debe ser un entero.',
            'benef_ninos.required'     => 'El número de niños beneficiarios es obligatorio.',
            'benef_ninos.integer'      => 'El número de niños beneficiarios debe ser un entero.',
            'benef_familias.required'  => 'El número de familias beneficiarias es obligatorio.',
            'benef_familias.integer'   => 'El número de familias beneficiarias debe ser un entero.',
            'departamento_id.exists'   => 'El departamento seleccionado no existe.',
            'municipio_id.exists'      => 'El municipio seleccionado no existe.',
            'miembro_responsable_id.exists' => 'El miembro responsable seleccionado no existe.',
            
            'detalles.*.id_cooperante.required_if' => 'El cooperante es obligatorio si el concepto es una donación.',
            'detalles.*.id_cooperante.exists'      => 'El cooperante seleccionado no es válido.',
            'detalles.*.nombre.required_with'      => 'El rubro o descripción es obligatorio para cada detalle del presupuesto.',
        ];
    }
}