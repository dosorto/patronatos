<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganizacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_tipo_organizacion' => 'required|exists:tipo_organizacion,id_tipo_organizacion',
            'id_municipio'         => 'required|exists:municipios,id',
            'id_departamento'      => 'required|exists:departamentos,id',
            'direccion'            => 'required|string|max:255',
            'nombre'               => 'required|string|max:255|unique:organizacion,nombre',
            'rtn'                  => 'required|string|max:255|unique:organizacion,rtn',
            'telefono'             => 'required|string|max:255',
            'fecha_creacion'       => 'required|date',
            'estado'               => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'id_tipo_organizacion.required' => 'El tipo de organización es obligatorio.',
            'id_tipo_organizacion.exists'   => 'El tipo de organización seleccionado no existe.',
            'id_municipio.required'         => 'El municipio es obligatorio.',
            'id_municipio.exists'           => 'El municipio seleccionado no existe.',
            'id_departamento.required'      => 'El departamento es obligatorio.',
            'id_departamento.exists'        => 'El departamento seleccionado no existe.',
            'direccion.required'            => 'La dirección es obligatoria.',
            'nombre.required'               => 'El nombre es obligatorio.',
            'nombre.unique'                 => 'Ya existe una organización con ese nombre.',
            'rtn.required'                  => 'El RTN es obligatorio.',
            'rtn.unique'                    => 'Ya existe una organización con ese RTN.',
            'telefono.required'             => 'El teléfono es obligatorio.',
            'fecha_creacion.required'       => 'La fecha de creación es obligatoria.',
            'fecha_creacion.date'           => 'La fecha de creación debe ser una fecha válida.',
            'estado.required'               => 'El estado es obligatorio.',
        ];
    }
}