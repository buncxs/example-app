<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;


class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Obtenemos el usuario de la ruta para el "unique ignore"
        $user = $this->route('user');

        return [
            'name' => ['required', 'string', 'min:5', 'max:50'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)->letters()->numbers(),
            ],
        ];
    }

    /**
     * Sobrescribimos el método validated para procesar el password.
     */
    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key, $default);

        if (empty($data['password'])) {
            // Si está vacío, quitamos las llaves para que Eloquent no las toque
            unset($data['password']);
            unset($data['password_confirmation']);
        }

        return $data;
    }
}
