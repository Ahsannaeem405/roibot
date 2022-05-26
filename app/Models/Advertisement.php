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
    public function getLifeEventsAttribute($val)
    {
        return json_decode($val);
    }

    public function getFamilyStatusesAttribute($val)
    {
        return json_decode($val);
    }

    public function getIndustriesAttribute($val)
    {
        return json_decode($val);
    }

    public function getIncomeAttribute($val)
    {
        return json_decode($val);
    }

    public function getBehaviourAttribute($val)
    {
        return json_decode($val);
    }

}
