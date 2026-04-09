<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMiembroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->crear_persona == '1') {
            return [
                'nueva_nombre'           => 'required|string|max:255',
                'nueva_apellido'         => 'required|string|max:255',
                'nueva_dni'              => 'required|string|max:14|unique:personas,dni',
                'nueva_fecha_nacimiento' => 'nullable|date',
                'nueva_sexo'             => 'nullable|in:M,F',
                'nueva_telefono'         => 'nullable|string|max:20',
                'nueva_email'            => 'nullable|email|max:255|unique:personas,email',
                'direccion'              => 'nullable|string|max:255',
                'suscripciones'          => 'nullable|array',
                'suscripciones.*.servicio_id' => 'nullable|exists:servicios,id',
                'suscripciones.*.medidor_id'  => 'nullable',
                'suscripciones.*.identificador' => 'nullable|string|max:255',
                'suscripciones.*.nuevo_medidor_numero' => 'nullable|string|max:255',
            ];
        }

        return [
            'persona_id'    => 'required|exists:personas,id|unique:miembros,persona_id',
            'direccion'     => 'nullable|string|max:255',
            'suscripciones' => 'nullable|array',
            'suscripciones.*.servicio_id' => 'nullable|exists:servicios,id',
            'suscripciones.*.medidor_id'  => 'nullable',
            'suscripciones.*.identificador' => 'nullable|string|max:255',
            'suscripciones.*.nuevo_medidor_numero' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'persona_id.required'  => 'La persona es obligatoria.',
            'persona_id.exists'    => 'La persona seleccionada no existe.',
            'persona_id.unique'    => 'Esta persona ya está registrada como miembro.',
            'nueva_nombre.required'  => 'El nombre es obligatorio.',
            'nueva_apellido.required' => 'El apellido es obligatorio.',
            'nueva_dni.required'   => 'El DNI es obligatorio.',
            'nueva_dni.unique'     => 'Este DNI ya está registrado.',
            'nueva_email.email'    => 'El email debe tener un formato válido.',
            'nueva_email.unique'   => 'Este email ya está registrado.',
        ];
    }
}