<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       return [
            'name' => [
                'required', 
                'string', 
                'min:2', 
                'max:50', // Recomendado para evitar ataques de strings largos
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email', // Validación de unicidad en tabla users
            ],
            'password' => [
                'required',
                'confirmed', // Esto busca automáticamente el campo 'password_confirmation'
                Password::min(8)
                    ->letters()   // regex /[a-zA-Z]/
                    ->numbers()   // regex /[0-9]/
            ],
            'role_ids' => [
                'required',
                'array',
                'min:1',
            ],
            'role_ids.*' => [
                Rule::exists('roles', 'id'),
            ],
        ];
    
    }
}
