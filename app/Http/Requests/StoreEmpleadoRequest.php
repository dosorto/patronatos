<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmpleadoRequest extends FormRequest
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
                'cargo'                  => 'required|string|max:255',
                'sueldo_mensual'         => 'required|numeric|min:0',
            ];
        }

        return [
            'persona_id' => [
                'required',
                'exists:personas,id',
                Rule::unique('empleados', 'persona_id')->whereNull('deleted_at'),
            ],
            'cargo'          => 'required|string|max:255',
            'sueldo_mensual' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'persona_id.required'    => 'La persona es obligatoria.',
            'persona_id.exists'      => 'La persona seleccionada no existe.',
            'persona_id.unique'      => 'Esta persona ya está registrada como empleado.',
            'nueva_nombre.required'  => 'El nombre es obligatorio.',
            'nueva_apellido.required'=> 'El apellido es obligatorio.',
            'nueva_dni.required'     => 'El DNI es obligatorio.',
            'nueva_dni.unique'       => 'Este DNI ya está registrado.',
            'nueva_email.email'      => 'El email debe tener un formato válido.',
            'nueva_email.unique'     => 'Este email ya está registrado.',
            'cargo.required'         => 'El cargo es obligatorio.',
            'cargo.string'           => 'El cargo debe ser texto.',
            'cargo.max'              => 'El cargo no puede tener más de 255 caracteres.',
            'sueldo_mensual.required'=> 'El sueldo mensual es obligatorio.',
            'sueldo_mensual.numeric' => 'El sueldo mensual debe ser un número.',
            'sueldo_mensual.min'     => 'El sueldo mensual no puede ser negativo.',
        ];
    }
}