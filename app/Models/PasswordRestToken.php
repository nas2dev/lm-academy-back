<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordRestToken extends Model
{
    protected $fillable = [
        'email', 'token'
    ];
}
