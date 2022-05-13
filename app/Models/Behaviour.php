<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Behaviour extends Model
{
    use HasFactory;

    public function child()
    {
        return $this->hasMany(Behaviour::class,'parent','id');
    }
}
