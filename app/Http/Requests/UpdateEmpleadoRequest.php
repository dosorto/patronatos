<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmpleadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cargo'          => 'required|string|max:255',
            'sueldo_mensual' => 'required|numeric|min:0',
            'frecuencia_pago' => 'required|string|in:Mensual,Quincenal,Semanal',
        ];
    }

    public function messages(): array
    {
        return [
            'cargo.required'          => 'El cargo es obligatorio.',
            'cargo.string'            => 'El cargo debe ser texto.',
            'cargo.max'               => 'El cargo no puede tener más de 255 caracteres.',
            'sueldo_mensual.required' => 'El monto del sueldo es obligatorio.',
            'sueldo_mensual.numeric'  => 'El sueldo debe ser un número.',
            'sueldo_mensual.min'      => 'El sueldo no puede ser negativo.',
            'frecuencia_pago.required' => 'La frecuencia de pago es obligatoria.',
            'frecuencia_pago.in'       => 'La frecuencia seleccionada no es válida.',
        ];
    }
}