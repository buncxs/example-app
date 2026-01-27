<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Obtenemos el objeto rol de la ruta para ignorar su propio ID en el unique
        $role = $this->route('role');

        return [
            'name' => [
                'required', 
                'string', 
                'min:4', 
                'max:40',
                'regex:/^[a-zA-ZÀ-ÿ\s\-]+$/u',
                Rule::unique('roles')->ignore($role->id),
            ],
            'description' => [
                'required',
                'string',
                'min:5',
                'max:255',
            ],
            'icon' => [
                'required',
                'string',
                'min:3',
            ],
            'permission_ids' => [
                'required',
                'array',
                'min:1',
            ],
            'permission_ids.*' => [
                'required',
                'integer',
                'exists:permissions,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [

            'name.required' => 'El nombre del rol es obligatorio.',
            'name.string'   => 'El nombre del rol debe ser un texto válido.',
            'name.min'      => 'El nombre del rol es demasiado corto (mínimo :min caracteres).',
            'name.max'      => 'El nombre del rol no puede exceder los :max caracteres.',
            'name.regex'    => 'El nombre del rol solo puede contener letras y espacios.',
            'name.unique'   => 'Este nombre ya está asignado a otro rol.',

            'description.required' => 'La descripción es necesaria para informar sobre las capacidades de este rol.',
            'description.string'   => 'La descripción debe ser una cadena de texto.',
            'description.min'      => 'La descripción debe tener al menos :min caracteres.',
            'description.max'      => 'La descripción no puede superar los :max caracteres.',

            'icon.required' => 'Debes asignar un icono para representar el rol.',
            'icon.string'   => 'El formato del icono es inválido.',
            'icon.min'      => 'El nombre del icono es demasiado corto.',

            'permission_ids.required' => 'Es obligatorio asignar al menos un permiso.',
            'permission_ids.array'    => 'Los permisos deben enviarse en formato de lista.',
            'permission_ids.min'      => 'Debes seleccionar al menos :min permiso para actualizar el rol.',
            'permission_ids.*.integer' => 'El identificador del permiso debe ser un número entero.',
            'permission_ids.*.exists'  => 'Uno de los permisos seleccionados ya no existe en la base de datos.',
        ];
    }
}