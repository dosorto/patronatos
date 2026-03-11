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
            'tipo_proyecto'             => 'nullable|string|max:255',
            'descripcion'               => 'nullable|string',
            'justificacion'             => 'nullable|string',
            'numero_acta'               => 'nullable|string|max:100',
            'fecha_aprobacion_asamblea' => 'nullable|date',
            'fecha_inicio'              => 'nullable|date',
            'fecha_fin'                 => 'nullable|date|after_or_equal:fecha_inicio',
            'estado'                    => 'required|boolean',

            // Beneficiarios
            'descripcion_beneficiarios' => 'nullable|string',
            'benef_hombres'             => 'nullable|integer|min:0',
            'benef_mujeres'             => 'nullable|integer|min:0',
            'benef_ninos'               => 'nullable|integer|min:0',
            'benef_familias'            => 'nullable|integer|min:0',

            // Ubicación y Responsable
            'departamento_id'           => 'nullable|exists:departamentos,id',
            'municipio_id'              => 'nullable|exists:municipios,id',
            'miembro_responsable_id'    => 'nullable|exists:miembros,id',
            
            // Presupuestos (Arreglo dinámico)
            'presupuestos'                                 => 'nullable|array',
            'presupuestos.*.id'                            => 'nullable|exists:presupuestos,id',
            'presupuestos.*.anio_presupuesto'              => 'nullable|integer',
            'presupuestos.*.presupuesto_total'             => 'nullable|numeric|min:0',
            'presupuestos.*.monto_financiador'             => 'nullable|numeric|min:0',
            'presupuestos.*.monto_comunidad'               => 'nullable|numeric|min:0',
            'presupuestos.*.porcentaje_financiador'        => 'nullable|numeric|min:0|max:100',
            'presupuestos.*.porcentaje_comunidad'          => 'nullable|numeric|min:0|max:100',
            'presupuestos.*.estado'                        => 'nullable|string',
            'presupuestos.*.fecha_aprobacion'              => 'nullable|date',
            'presupuestos.*.es_donacion'                   => 'nullable|boolean',
            'presupuestos.*.id_cooperante'                 => 'nullable|required_if:presupuestos.*.es_donacion,1,true|exists:cooperantes,id_cooperante',
            
            // Detalles de Presupuesto
            'presupuestos.*.detalles'                      => 'nullable|array',
            'presupuestos.*.detalles.*.id'                 => 'nullable|exists:detalle_presupuestos,id',
            'presupuestos.*.detalles.*.nombre'             => 'required_with:presupuestos.*.detalles|string|max:255',
            'presupuestos.*.detalles.*.cantidad'           => 'nullable|numeric|min:0',
            'presupuestos.*.detalles.*.unidad_medida'      => 'nullable|string|max:100',
            'presupuestos.*.detalles.*.precio_unitario'    => 'nullable|numeric|min:0',
            'presupuestos.*.detalles.*.total'              => 'nullable|numeric|min:0',
            'presupuestos.*.detalles.*.observaciones'      => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_proyecto.required' => 'El nombre del proyecto es obligatorio.',
            'nombre_proyecto.max'      => 'El nombre no puede tener más de 255 caracteres.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
            'estado.required'          => 'El estado es obligatorio.',
            'estado.boolean'           => 'El estado debe ser activo o inactivo.',
            'benef_hombres.integer'    => 'El número de hombres beneficiarios debe ser un entero.',
            'benef_mujeres.integer'    => 'El número de mujeres beneficiarias debe ser un entero.',
            'benef_ninos.integer'      => 'El número de niños beneficiarios debe ser un entero.',
            'benef_familias.integer'   => 'El número de familias beneficiarias debe ser un entero.',
            'departamento_id.exists'   => 'El departamento seleccionado no existe.',
            'municipio_id.exists'      => 'El municipio seleccionado no existe.',
            'miembro_responsable_id.exists' => 'El miembro responsable seleccionado no existe.',
            
            'presupuestos.*.id_cooperante.required_if' => 'El cooperante es obligatorio si el presupuesto es una donación.',
            'presupuestos.*.id_cooperante.exists'      => 'El cooperante seleccionado no es válido.',
            'presupuestos.*.detalles.*.nombre.required_with' => 'El rubro o descripción es obligatorio para cada detalle del presupuesto.',
        ];
    }
}