<?php

namespace App\Http\Requests\ChatActionsRequests;

use Illuminate\Foundation\Http\FormRequest;

class ChatInfoRequest extends FormRequest
{

    public function authorize(): bool
    {
        if ($this->user()->can('getChatInfo', $this->chat)) {
            return true;
        }

        return false;
    }

    public function rules(): array
    {
        return [];
    }
    
}
