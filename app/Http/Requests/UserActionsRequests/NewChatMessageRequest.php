<?php

namespace App\Http\Requests\UserActionsRequests;

use Illuminate\Foundation\Http\FormRequest;

class NewChatMessageRequest extends FormRequest
{

    public function authorize() : bool
    {
        if($this->user->cannot('createMessage', $this->chat)) {
            return false;
        }
        
        return true;
    }

    public function rules() : array
    {
        return [
            'message_body' => ['required', 'string', 'min:1', 'max:1000']
        ];
    }
    
}
