<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewChatMessageRequest extends FormRequest
{

    public function authorize() : bool
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'message_body' => ['required', 'string', 'min:1', 'max:1000']
        ];
    }
}
