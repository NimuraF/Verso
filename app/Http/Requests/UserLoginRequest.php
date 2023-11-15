<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'min:3', 'max:255', 'exists:App\Models\User,email'],
            'password' => ['required', 'string', 'min:8', 'max:60']
        ];
    }
}
