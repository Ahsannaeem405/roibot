<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demographics extends Model
{
    use HasFactory;

    public function child()
    {
        return $this->hasMany(Demographics::class,'parent','id');
    }
}
