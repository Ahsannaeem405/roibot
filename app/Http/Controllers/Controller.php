<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {

//        $compain=\Http::post('https://graph.facebook.com/v13.0/act_457009236222857/campaigns',[
//'name'=>'my  new',
//'objective'=>'LINK_CLICKS',
//'status'=>'PAUSED',
//'special_ad_categories'=>[],
//'access_token'=>env("ACCESS_TOKEN"),
//        ]);
//        dd(json_decode($compain->body()));

        $compain_id = 23849845241800045;

//        $addSet = \Http::post('https://graph.facebook.com/v13.0/act_457009236222857/adsets', [
//            'campaign_id' => $compain_id,
//
//            'name' => 'My First AdSet',
//            'lifetime_budget' => 1500*100,
//            'start_time' => '2022-04-18T22:48:53-0700',
//            'end_time' => '2022-04-25T22:48:53-0700',
//            'bid_amount' => '20',
//            'billing_event' => 'IMPRESSIONS',
//            'optimization_goal' => 'POST_ENGAGEMENT',
//            'targeting' => ['age_min' => 20, 'age_max' => 24,
//                //'behaviors' => ['id' => 6002714895372, 'name' => 'All travelers'],
//                'genders'=>[1],
//                'geo_locations'=>['countries'=>['US']]
//                ],
//            'status'=>'PAUSED',
//            'access_token' => env("ACCESS_TOKEN"),
//        ]);
//        dd(json_decode($addSet->body()));

        $addSet_id = 23849845246050045;




//        $adCreative = \Http::post('https://graph.facebook.com/v13.0/act_457009236222857/adcreatives', [
//            'name'=>'Sample Creative',
//            'object_story_spec' => [
//                'link_data' => [
//                    'image_hash' => md5_file(public_path('images/ad.png')),
//                    'link' => 'https://www.facebook.com/xyz',
//                    'message' => 'try it out',
//
//                ],
//                'page_id' => 111542568191241
//            ],
//
//            'access_token' => env("ACCESS_TOKEN"),
//        ]);
//        dd(json_decode($adCreative->body()));
$addCreative_id=23849845249010045;




        $add = \Http::post('https://graph.facebook.com/v13.0/act_457009236222857/ads', [
            'name'=>'my add',
            'adset_id' => $addSet_id,
            'creative' =>[
               'creative_id'=>$addCreative_id,
            ] ,
            'status'=>'PAUSED',

            'access_token' => env("ACCESS_TOKEN"),
        ]);
        dd(json_decode($add->body()));

    }
}
