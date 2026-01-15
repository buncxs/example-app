<?php

namespace App\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Obtenemos el usuario de la ruta para el "unique ignore"
        $permission = $this->route('permission');

        return [
            'name' => [
                'required',
                'string',
                'min:4',
                'max:40', // Recomendado para evitar ataques de strings largos,
                // REGEX: Solo minúsculas, números, puntos y guiones. Sin espacios.
                'regex:/^[a-z0-9\.\-]+$/',
                Rule::unique('permissions', 'name')->ignore($permission->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del permiso es obligatorio.',
            'name.min' => 'El nombre del permiso es demasiado corto (mínimo 4 caracteres).',
            'name.regex' => 'Formato inválido. Usa solo minúsculas, puntos y guiones (ej: usuarios.crear).',
            'name.unique' => 'Ya existe un permiso con este nombre, prueba con uno diferente.',
        ];
    }
}
