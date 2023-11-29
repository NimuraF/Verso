<?php

namespace App\Http\Requests\UserActionsRequests\Chat;

use Illuminate\Foundation\Http\FormRequest;

class RemoveChatMessageRequest extends FormRequest
{

    public function authorize(): bool
    {
        if($this->user()->can('deleteMessage', $this->chat, $this->message)) 
        {
            return true;
        }

        return false;
    }

    public function rules(): array
    {
        return [];
    }
}
