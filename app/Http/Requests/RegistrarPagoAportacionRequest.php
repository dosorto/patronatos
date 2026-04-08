<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrarPagoAportacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'monto'      => 'required|numeric|min:0.01',
            'tipo_cobro' => 'required|string|max:50',
            'fecha'      => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'monto.required'      => 'El monto del pago es obligatorio.',
            'monto.min'           => 'El monto debe ser mayor a cero.',
            'tipo_cobro.required' => 'El tipo de cobro es obligatorio.',
            'fecha.required'      => 'La fecha del pago es obligatoria.',
        ];
    }
}
