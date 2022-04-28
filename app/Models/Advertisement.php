<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;

    public function activeAdd()
    {
        return $this->hasMany(AdvertisementAds::class,'advertisements_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function getAgeAttribute($val)
    {
        return explode('to',$val);
    }

    public function getCitiesAttribute($val)
    {
        return json_decode($val);
    }

    public function getCountriesAttribute($val)
    {
        return json_decode($val);
    }
    public function getInterestAttribute($val)
    {
        return json_decode($val);
    }
    public function getDemoAttribute($val)
    {
        return json_decode($val);
    }

    public function getBehaviourAttribute($val)
    {
        return json_decode($val);
    }




//    public function setCitiesAttribute($val)
//    {
//        return json_encode($val);
//    }
//
//    public function setCountriesAttribute($val)
//    {
//        return json_encode($val);
//    }
//    public function setInterestAttribute($val)
//    {
//        return json_encode($val);
//    }
//    public function setDemoAttribute($val)
//    {
//        return json_encode($val);
//    }
//
//    public function setBehaviourAttribute($val)
//    {
//        return json_encode($val);
//    }
}
