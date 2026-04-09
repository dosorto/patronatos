<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJornadaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fecha'              => 'required|date',
            'hora_inicio'        => 'nullable|date_format:H:i',
            'descripcion'        => 'nullable|string|max:500',
            'tipo_convocatoria'  => 'required|in:todos,manual',
            'miembros'           => 'required_if:tipo_convocatoria,manual|array',
            'miembros.*'         => 'exists:miembros,id',
        ];
    }

    public function messages(): array
    {
        return [
            'fecha.required'              => 'La fecha de la jornada es obligatoria.',
            'tipo_convocatoria.required'  => 'Debe seleccionar el tipo de convocatoria.',
            'miembros.required_if'        => 'Debe seleccionar al menos un miembro para convocatoria manual.',
        ];
    }
}
