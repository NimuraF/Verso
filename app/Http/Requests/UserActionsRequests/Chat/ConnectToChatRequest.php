<?php

namespace App\Http\Requests\UserActionsRequests\Chat;

use App\Services\Authorization\ValidateUserPermission;
use Illuminate\Foundation\Http\FormRequest;

class ConnectToChatRequest extends FormRequest
{

    public function authorize(): bool
    {
        if ($this->user()->can('connectToChat', $this->chat)) {
            return true;
        }

        return false;
    }


    public function rules(): array
    {
        return [];
    }
}
