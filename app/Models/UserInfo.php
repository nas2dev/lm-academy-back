<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserInfo extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'address', 
        'tel',
        'about'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
