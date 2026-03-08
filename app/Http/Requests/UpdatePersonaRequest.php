<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePersonaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('dni')) {
            $this->merge([
                'dni' => preg_replace('/[^0-9]/', '', $this->dni),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $persona = $this->route('persona');
        $personaId = $persona?->id ?? $persona;

        return [
            'dni' => [
                'required', 
                'string', 
                'max:20', 
                Rule::unique('personas', 'dni')->ignore($personaId)->whereNull('deleted_at')
            ],
            'nombre' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'],
            'apellido' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'],
            'fecha_nacimiento' => ['required', 'date', 'before_or_equal:today'],
            'sexo' => ['required', 'in:M,F'],
            'telefono' => ['nullable', 'numeric', 'digits_between:8,20'],
            'email' => [
                'nullable',
                'email',
                'max:255', 
                Rule::unique('personas', 'email')->ignore($personaId)->whereNull('deleted_at')
            ],
            'estado' => ['required', 'in:Activo,Inactivo'],
            'fecha_ingreso' => ['required', 'date'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'apellido.regex' => 'El apellido solo puede contener letras y espacios.',
            'telefono.numeric' => 'El teléfono solo puede contener números.',
            'fecha_nacimiento.before_or_equal' => 'La fecha de nacimiento no puede ser mayor a la fecha actual.',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'dni' => 'DNI',
            'nombre' => 'nombre',
            'apellido' => 'apellido',
            'fecha_nacimiento' => 'fecha de nacimiento',
            'sexo' => 'sexo',
            'telefono' => 'teléfono',
            'email' => 'correo electrónico',
            'estado' => 'estado',
            'fecha_ingreso' => 'fecha de ingreso',
        ];
    }
}
