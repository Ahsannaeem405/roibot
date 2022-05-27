<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisementAds extends Model
{
    use HasFactory;

    public function sum()
    {

        return $this->clicks + $this->impressions + $this->cpc  + $this->conversation  ;
    }

    public function compain()
    {
        return $this->belongsTo(Advertisement::class,'advertisements_id');
    }

    public function insightDetail()
    {
        return $this->hasMany(insightDetail::class,'add_id');
    }
}
