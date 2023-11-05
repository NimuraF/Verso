<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RefreshTokens extends Model
{
    
    protected $table = "users_refresh_tokens";

    protected $primaryKey = "id";

    protected $fillable = ['token', 'user_id'];

}
