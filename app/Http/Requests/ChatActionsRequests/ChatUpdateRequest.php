<?php

namespace App\Http\Requests\ChatActionsRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class ChatUpdateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return false;
    }

    public function rules(): array
    {
        return [
            'modified_id' => ['string', 'min:4', 'max:30', 'unique:App\Models\Chat,modified_id'],
            'name' => ['string', 'min:3', 'max:255'],
            'about' => ['string', 'max:1000'],
            'close' => ['in:0,1'],
            'avatar' => [
                File::image()
                    ->min('1kb')
                    ->max('5mb')
                    ->dimensions(Rule::dimensions()->maxWidth(1024)->maxHeight(1024))
            ]
        ];
    }
}
