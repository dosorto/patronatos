<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAportacionRequest extends FormRequest {
    public function authorize(): bool { return true; }

    public function rules(): array {
        return [
            'id_miembro'       => 'required|integer|exists:miembros,id_miembro',
            'id_proyecto'      => 'required|integer|exists:proyectos,id_proyecto',
            'monto'            => 'required|numeric|min:0.01',
            'fecha_aportacion' => 'required|date',
            'estado'           => 'boolean',
        ];
    }

    public function messages(): array {
        return [
            'id_miembro.required'       => 'El miembro es obligatorio.',
            'id_miembro.exists'         => 'El miembro seleccionado no existe.',
            'id_proyecto.required'      => 'El proyecto es obligatorio.',
            'id_proyecto.exists'        => 'El proyecto seleccionado no existe.',
            'monto.required'            => 'El monto es obligatorio.',
            'monto.min'                 => 'El monto debe ser mayor a cero.',
            'fecha_aportacion.required' => 'La fecha de aportación es obligatoria.',
            'fecha_aportacion.date'     => 'La fecha no tiene un formato válido.',
        ];
    }
}
