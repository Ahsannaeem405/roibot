<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Edujugon\GoogleAds\GoogleAds;
use Google\AdsApi\AdWords\v201708\cm\CampaignService;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {

        $compain = \Http::post('https://graph.facebook.com/v13.0/act_1060535174543124/campaigns', [
            'name' => 'my new',
            'objective' => 'LINK_CLICKS',
            'status' => 'PAUSED',
            'special_ad_categories' => [],
            'access_token' => env("ACCESS_TOKEN"),
        ]);
        $compain = json_decode($compain->body());
       // dd($compain);

        $compain_id = $compain->id;
//dd($compain_id);
        $addSet = \Http::post('https://graph.facebook.com/v13.0/act_1060535174543124/adsets', [
            'campaign_id' => $compain_id,

            'name' => 'My First AdSet',
            'lifetime_budget' => 1500 * 100,
            'start_time' => '2022-04-18T22:48:53-0700',
            'end_time' => '2022-04-25T22:48:53-0700',
            'bid_amount' => '20',
            'billing_event' => 'IMPRESSIONS',
            'optimization_goal' => 'POST_ENGAGEMENT',
            'targeting' => ['age_min' => 20, 'age_max' => 24,
                //'behaviors' => ['id' => 6002714895372, 'name' => 'All travelers'],
                'genders' => [1],
                'geo_locations' => ['countries' => ['US']]
            ],
            'status' => 'PAUSED',
            'access_token' => env("ACCESS_TOKEN"),
        ]);
        $addSet = json_decode($addSet->body());

        $addSet_id = $addSet->id;


        $adCreative = \Http::post('https://graph.facebook.com/v13.0/act_1060535174543124/adcreatives', [
            'name' => 'Sample Creative',
            'object_story_spec' => [
                'link_data' => [
                    'image_hash' => md5_file(public_path('images/ad.png')),
                    'link' => 'https://www.facebook.com/xyz',
                    'message' => 'try it out',

                ],
                'page_id' => 106526345371756
            ],

            'access_token' => env("ACCESS_TOKEN"),
        ]);
        $adCreative = json_decode($adCreative->body());
//dd($adCreative);
        $addCreative_id = $adCreative->id;


        $add = \Http::post('https://graph.facebook.com/v13.0/act_1060535174543124/ads', [
            'name' => 'my add',
            'adset_id' => $addSet_id,
            'creative' => [
                'creative_id' => $addCreative_id,
            ],
            'status' => 'PAUSED',

            'access_token' => env("ACCESS_TOKEN"),
        ]);
        dd(json_decode($add->body()));

    }

    public function index2()
    {


//        https://accounts.google.com/o/oauth2/v2/auth?
//        scope=https%3A//www.googleapis.com/auth/drive.metadata.readonly&
// access_type=offline&
// include_granted_scopes=true&
// response_type=code&
// state=state_parameter_passthrough_value&
// redirect_uri=http://127.0.0.1:8001/refresh/token&
// client_id=368856619669-2442dc6p657s23vdg8efnorgter8nv6o.apps.googleusercontent.com


      //http://127.0.0.1:8001/refresh/token?
        //state=state_parameter_passthrough_value
        //&code=4%2F0AX4XfWhU8hYEqQ65ZPaYoCF5mCUAU_ZIsgf4StWwq0XjtbliK_1Q1_lmZ_F1ytuEOdQyYA
        //&scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fdrive.metadata.readonly+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fdrive+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fdrive.metadata+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fdrive.photos.readonly+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fdrive.appdata+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fdrive.scripts+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fdrive.file

        $api=\Http::post('https://oauth2.googleapis.com/token',

            [
            'client_id'=>'368856619669-2442dc6p657s23vdg8efnorgter8nv6o.apps.googleusercontent.com',
            'clientSecret'=>'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',
            'refresh_token'=>'4%2F0AX4XfWhU8hYEqQ65ZPaYoCF5mCUAU_ZIsgf4StWwq0XjtbliK_1Q1_lmZ_F1ytuEOdQyYA',
            'grant_type'=>'refresh_token',
            ]);
        dd($api->body());


        $ads = new GoogleAds();
        $ads->getUserCredentials();


    }
}
