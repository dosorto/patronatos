<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfigurarAportacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo_distribucion'      => 'required|in:equitativa,manual',
            'monto_total_requerido'  => 'required|numeric|min:0.01',
            'fecha_limite'           => 'nullable|date',
            'observaciones'          => 'nullable|string|max:500',
            'montos_manuales'        => 'required_if:tipo_distribucion,manual|array',
            'montos_manuales.*.miembro_id' => 'required_with:montos_manuales|exists:miembros,id',
            'montos_manuales.*.monto'      => 'required_with:montos_manuales|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'tipo_distribucion.required'     => 'Debe seleccionar un tipo de distribución.',
            'monto_total_requerido.required'  => 'El monto total es obligatorio.',
            'monto_total_requerido.min'       => 'El monto total debe ser mayor a cero.',
            'montos_manuales.required_if'     => 'Debe asignar los montos por miembro en distribución manual.',
        ];
    }
}
