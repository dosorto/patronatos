<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarListaAsistenciaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'asistencias'                       => 'required|array',
            'asistencias.*.id'                  => 'required|exists:asistencias_jornada,id',
            'asistencias.*.asistio'             => 'boolean',
            'asistencias.*.mando_sustituto'     => 'boolean',
            'asistencias.*.nombre_sustituto'    => 'nullable|string|max:255',
            'asistencias.*.observaciones'       => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'asistencias.required' => 'Debe enviar la lista de asistencia.',
        ];
    }
}
