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

            // Step 3 - Presupuestos (array acumulado)
            'presupuestos'                          => 'nullable|array',
            'presupuestos.*.anio_presupuesto'       => 'nullable|integer|min:2000|max:2100',
            'presupuestos.*.presupuesto_total'      => 'nullable|numeric|min:0',
            'presupuestos.*.monto_financiador'      => 'nullable|numeric|min:0',
            'presupuestos.*.monto_comunidad'        => 'nullable|numeric|min:0',
            'presupuestos.*.porcentaje_financiador' => 'nullable|numeric|min:0|max:100',
            'presupuestos.*.porcentaje_comunidad'   => 'nullable|numeric|min:0|max:100',
            'presupuestos.*.estado'                 => 'nullable|string|max:100',
            'presupuestos.*.fecha_aprobacion'       => 'nullable|date',
            'presupuestos.*.es_donacion'            => 'nullable|boolean',
            'presupuestos.*.id_cooperante'          => 'nullable|integer|exists:cooperantes,id_cooperante',

            // Step 4 - Detalle de Presupuesto (nested)
            'presupuestos.*.detalles'                   => 'nullable|array',
            'presupuestos.*.detalles.*.nombre'          => 'nullable|string|max:255',
            'presupuestos.*.detalles.*.cantidad'        => 'nullable|numeric|min:0',
            'presupuestos.*.detalles.*.unidad_medida'   => 'nullable|string|max:100',
            'presupuestos.*.detalles.*.precio_unitario' => 'nullable|numeric|min:0',
            'presupuestos.*.detalles.*.total'           => 'nullable|numeric|min:0',
            'presupuestos.*.detalles.*.observaciones'   => 'nullable|string',
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

            'presupuestos.*.presupuesto_total.numeric'      => 'El presupuesto total debe ser un número.',
            'presupuestos.*.monto_financiador.numeric'      => 'El monto del financiador debe ser un número.',
            'presupuestos.*.monto_comunidad.numeric'        => 'El monto de la comunidad debe ser un número.',
            'presupuestos.*.id_cooperante.exists'           => 'El cooperante seleccionado no existe.',
            'presupuestos.*.detalles.*.cantidad.numeric'        => 'La cantidad debe ser un número.',
            'presupuestos.*.detalles.*.precio_unitario.numeric' => 'El precio unitario debe ser un número.',
        ];
    }
}