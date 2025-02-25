<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Scoreboard extends Model
{
    protected $fillable = [
        'user_id', 'score'
    ];

    public function user() {
        $this->belongsTo(User::class, 'user_id');
    }

    public function userData() {
        $this->belongsTo(User::class, 'user_id')->selectSomeUserData();
    }
}
