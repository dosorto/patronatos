<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAportacionRequest extends FormRequest {
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
        return (new StoreAportacionRequest)->messages();
    }
}