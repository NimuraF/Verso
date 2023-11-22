<?php

namespace App\Http\Requests\ChatActionsRequests;

use Illuminate\Foundation\Http\FormRequest;

class ChatSearchRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'chat_modified_id' => ['required', 'string', 'min:2', 'max:255']
        ];
    }
    
}
