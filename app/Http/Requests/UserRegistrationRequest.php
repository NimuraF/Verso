<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegistrationRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:50', 'unique:App\Models\User,name'],
            'email' => ['required', 'string', 'email', 'min:3', 'max:255', 'unique:App\Models\User,email'],
            'password' => ['required', 'string', 'min:8', 'max:60']
        ];
    }
}
