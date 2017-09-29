<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    public function user()
    {
        return $this->hasMany(User::class);
    }
}
