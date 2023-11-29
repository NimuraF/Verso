<?php

namespace App\Http\Requests\UserActionsRequests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class UpdateUserProfileRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['string', 'min:3', 'max:50', 'unique:App\Models\User,name'],
            'password' => ['string', 'min:8', 'max:60'],
            'avatar' => [
                File::image()
                    ->min('1kb')
                    ->max('5mb')
                    ->dimensions(Rule::dimensions()->maxWidth(1024)->maxHeight(1024))
            ]
        ];
    }
}
