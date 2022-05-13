<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intrests extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'data_id',
        'parent',

    ];

    public function child()
    {
        return $this->hasMany(Intrests::class,'parent','id');
    }
}
