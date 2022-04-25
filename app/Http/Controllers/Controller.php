<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\AdvertisementAds;
use App\Models\AdvertisementDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Edujugon\GoogleAds\GoogleAds;
use Google\AdsApi\AdWords\v201708\cm\CampaignService;
use Illuminate\Support\Facades\DB;

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

         dd($compain);

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
dd($adCreative,1);
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

        $api = \Http::post('https://oauth2.googleapis.com/token',

            [
                'client_id' => '368856619669-2442dc6p657s23vdg8efnorgter8nv6o.apps.googleusercontent.com',
                'clientSecret' => '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',
                'refresh_token' => '4%2F0AX4XfWhU8hYEqQ65ZPaYoCF5mCUAU_ZIsgf4StWwq0XjtbliK_1Q1_lmZ_F1ytuEOdQyYA',
                'grant_type' => 'refresh_token',
            ]);
        dd($api->body());


        $ads = new GoogleAds();
        $ads->getUserCredentials();


    }




    public function insightFB(){
        $ads=AdvertisementAds::where('add_id','!=',null)->get();
        foreach ($ads as $ad)
        {
            $insight = \Http::get('https://graph.facebook.com/v13.0/'.$ad->add_id.'/insights', [
                "date_preset"=>"maximum",
                "fields"=>'impressions,clicks,cpc,reach',
                'access_token' => $ad->compain->user->fb_token,

            ]);
            $insight=json_decode($insight->body());
        if (count($insight->data)>=1){

            $ad->clicks=intval($insight->data[0]->clicks);
            $ad->impressions=intval($insight->data[0]->impressions);
            $ad->cpc=intval($insight->data[0]->cpc);
            $ad->conversation=intval($insight->data[0]->reach);
            $ad->update();

        }
        }
    }

    public function step1()
    {
        $adsStep1 = Advertisement::whereHas('activeAdd', function ($q) {
            $q->where('end_date', '<', Carbon::now());
        })
            ->where('status', 'pending')
            ->where('step', 1)
            ->where('type', 1)
            ->get();

        foreach ($adsStep1 as $adsStep1) {
            $advertisement=$adsStep1;
            $compain_id=$adsStep1->compain_id;

            $user = User::find($adsStep1->user_id);
            $facebook = [
                'fb_client' => $user->fb_client,
                'fb_secret' => $user->fb_secret,
                'fb_token' => $user->fb_token,
                'page_id' => $user->fb_page,
                'fb_account' => $user->fb_account,

            ];

            $ads = AdvertisementAds::where('advertisements_id', $adsStep1->id)->select(
                '*',
                DB::raw('sum(clicks + impressions + cpc + conversation) as total'))
                ->groupBY('id')
                ->orderBy('total', 'DESC')
                ->first();


            //update priority
            $adsDetail = AdvertisementDetail::where('advertisements_id', $adsStep1->id)
                ->where('type', 'heading')
                ->where('data', $ads->heading)
                ->update(['status' => 'final']);
            $adsStep1->step = 2;
            $adsStep1->update();

            if (count(AdvertisementAds::where('advertisements_id', $adsStep1->id)->get()) > 1 || count(AdvertisementDetail::where('advertisements_id', $adsStep1->id)->where('type', 'body')->get()) > 1) {


                //delete add



                $adsDel = AdvertisementAds::where('advertisements_id', $adsStep1->id)->get();
                foreach ($adsDel as $adsDel)
                {
                    $delete = \Http::delete('https://graph.facebook.com/v13.0/'.$adsDel->addSet_id.'', [
                        'access_token' => $facebook['fb_token'],
                    ]);
                    $adsDel->delete();
                }


                //get other
                $body = AdvertisementDetail::where('advertisements_id', $adsStep1->id)->where('type', 'body')->get();
                $heading = AdvertisementDetail::where('advertisements_id', $adsStep1->id)->where('type', 'heading')->where('status', 'final')->first();
                $button = AdvertisementDetail::where('advertisements_id', $adsStep1->id)->where('type', 'button')->first();
                $image = AdvertisementDetail::where('advertisements_id', $adsStep1->id)->where('type', 'image')->first();

                //inserting add
                foreach ($body as $body) {

                    $addSet = \Http::post('https://graph.facebook.com/v13.0/act_'.$facebook['fb_account'].'/adsets', [
                        'campaign_id' => $compain_id,
                        'name' => $heading->data,
                        'lifetime_budget' => ($advertisement->per_day * 3) * 100,
                        'start_time' => Carbon::now(),
                        'end_time' => Carbon::now()->addDays(3),
                        'bid_amount' => $advertisement->per_day * 100,
                        'billing_event' => 'IMPRESSIONS',
                        'optimization_goal' => $advertisement->goal,
                        'targeting' => ['age_min' => intval($advertisement->age[0]), 'age_max' => intval($advertisement->age[1]),
                            //'behaviors' => ['id' => 6002714895372, 'name' => 'All travelers'],
                            'genders' => [1,2],
                            'geo_locations' => ['countries' => ['US']]
                        ],
                        'status' => 'PAUSED',
                        'access_token' => $facebook['fb_token'],
                    ]);
                    if ($addSet->status()==200)
                    {
                        $addSet = json_decode($addSet->body());
                        $addSet_id = $addSet->id;


                        //creating addCreative

                        $adCreative = \Http::post('https://graph.facebook.com/v13.0/act_'.$facebook['fb_account'].'/adcreatives', [
                            'name' => $heading->data,
                            'body'=>$body->data,
                            'object_story_spec' => [
                                'link_data' => [
                                    'image_hash' => md5_file(public_path('images/gallary/'.$image->data.'')),
                                    'link' => $button->url,
                                    'message' => $button->data,

                                ],
                                'page_id' => $facebook['page_id']
                            ],

                            'access_token' => $facebook['fb_token'],
                        ]);


                        if ($adCreative->status()==200)
                        {
                            $adCreative = json_decode($adCreative->body());
                            $addCreative_id = $adCreative->id;

                            //creating add

                            $add = \Http::post('https://graph.facebook.com/v13.0/act_'.$facebook['fb_account'].'/ads', [
                                'name' => $heading->data,
                                'adset_id' => $addSet_id,
                                'creative' => [
                                    'creative_id' => $addCreative_id,
                                ],
                                'status' => 'PAUSED',

                                'access_token' =>$facebook['fb_token'],
                            ]);
                            if ($add->status()==200)
                            {
                                $add=json_decode($add->body());
                                $add_id=$add->id;

                            }
                            else{
                                $add=json_decode($add->body());
                                return back()->with('error',isset($add->error_user_msg) ? $add->error_user_msg : $add->error->message);
                            }

                        }
                        else{
                            $adCreative = json_decode($adCreative->body());

                            return back()->with('error',isset($adCreative->error_user_msg) ? $adCreative->error_user_msg : $adCreative->error->message);
                        }


                    }
                    else{
                        $addSet = json_decode($addSet->body());

                        return back()->with('error',isset($addSet->error_user_msg) ? $addSet->error->error_user_msg  : $addSet->error->message);
                    }





                    $advertisementAdds = new AdvertisementAds();
                    $advertisementAdds->advertisements_id = $adsStep1->id;
                    $advertisementAdds->heading = $heading->data;
                    $advertisementAdds->body = $body->data;
                    $advertisementAdds->button = $button->data;
                    $advertisementAdds->url = $button->url;
                    $advertisementAdds->image = $image->data;
                    $advertisementAdds->start_date = Carbon::now();
                    $advertisementAdds->end_date = Carbon::now()->addDays(3);
                    $advertisementAdds->save();

                }


            }


        }
    }

    public function step2()
    {
        $adsStep2 = Advertisement::whereHas('activeAdd', function ($q) {
            $q->where('end_date', '<', Carbon::now());
        })
            ->where('status', 'pending')
            ->where('step', 2)
            ->where('type', 1)
            ->get();

        foreach ($adsStep2 as $adsStep2) {

            $advertisement=$adsStep2;
            $compain_id=$adsStep2->compain_id;

            $user = User::find($adsStep2->user_id);
            $facebook = [
                'fb_client' => $user->fb_client,
                'fb_secret' => $user->fb_secret,
                'fb_token' => $user->fb_token,
                'page_id' => $user->fb_page,
                'fb_account' => $user->fb_account,

            ];

            $ads = AdvertisementAds::where('advertisements_id', $adsStep2->id)->select(
                '*',
                DB::raw('sum(clicks + impressions + cpc + conversation) as total'))
                ->groupBY('id')
                ->orderBy('total', 'DESC')
                ->first();


            //update priority
            $adsDetail = AdvertisementDetail::where('advertisements_id', $adsStep2->id)
                ->where('type', 'body')
                ->where('data', $ads->body)
                ->update(['status' => 'final']);
            $adsStep2->step = 3;
            $adsStep2->update();

            if (count(AdvertisementAds::where('advertisements_id', $adsStep2->id)->get()) > 1 || count(AdvertisementDetail::where('advertisements_id', $adsStep2->id)->where('type', 'image')->get()) > 1) {
                //delete add
                $adsDel = AdvertisementAds::where('advertisements_id', $adsStep2->id)->get();
                foreach ($adsDel as $adsDel)
                {
                    $delete = \Http::delete('https://graph.facebook.com/v13.0/'.$adsDel->addSet_id.'', [
                        'access_token' => $facebook['fb_token'],
                    ]);
                    $adsDel->delete();
                }


                //get other
                $body = AdvertisementDetail::where('advertisements_id', $adsStep2->id)->where('type', 'body')->where('status', 'final')->first();
                $heading = AdvertisementDetail::where('advertisements_id', $adsStep2->id)->where('type', 'heading')->where('status', 'final')->first();
                $button = AdvertisementDetail::where('advertisements_id', $adsStep2->id)->where('type', 'button')->first();
                $image = AdvertisementDetail::where('advertisements_id', $adsStep2->id)->where('type', 'image')->get();

                //inserting add
                foreach ($image as $image) {



                    $addSet = \Http::post('https://graph.facebook.com/v13.0/act_'.$facebook['fb_account'].'/adsets', [
                        'campaign_id' => $compain_id,
                        'name' => $heading->body,
                        'lifetime_budget' => ($advertisement->per_day * 3) * 100,
                        'start_time' => Carbon::now(),
                        'end_time' => Carbon::now()->addDays(3),
                        'bid_amount' => $advertisement->per_day * 100,
                        'billing_event' => 'IMPRESSIONS',
                        'optimization_goal' => $advertisement->goal,
                        'targeting' => ['age_min' => intval($advertisement->age[0]), 'age_max' => intval($advertisement->age[1]),
                            //'behaviors' => ['id' => 6002714895372, 'name' => 'All travelers'],
                            'genders' => [1,2],
                            'geo_locations' => ['countries' => ['US']]
                        ],
                        'status' => 'PAUSED',
                        'access_token' => $facebook['fb_token'],
                    ]);
                    if ($addSet->status()==200)
                    {
                        $addSet = json_decode($addSet->body());
                        $addSet_id = $addSet->id;


                        //creating addCreative

                        $adCreative = \Http::post('https://graph.facebook.com/v13.0/act_'.$facebook['fb_account'].'/adcreatives', [
                            'name' => $heading->data,
                            'body'=>$body->data,
                            'object_story_spec' => [
                                'link_data' => [
                                    'image_hash' => md5_file(public_path('images/gallary/'.$image->data.'')),
                                    'link' => $button->url,
                                    'message' => $button->data,

                                ],
                                'page_id' => $facebook['page_id']
                            ],

                            'access_token' => $facebook['fb_token'],
                        ]);


                        if ($adCreative->status()==200)
                        {
                            $adCreative = json_decode($adCreative->body());
                            $addCreative_id = $adCreative->id;

                            //creating add

                            $add = \Http::post('https://graph.facebook.com/v13.0/act_'.$facebook['fb_account'].'/ads', [
                                'name' => $heading->data,
                                'adset_id' => $addSet_id,
                                'creative' => [
                                    'creative_id' => $addCreative_id,
                                ],
                                'status' => 'PAUSED',

                                'access_token' =>$facebook['fb_token'],
                            ]);
                            if ($add->status()==200)
                            {
                                $add=json_decode($add->body());
                                $add_id=$add->id;

                            }
                            else{
                                $add=json_decode($add->body());
                                return back()->with('error',isset($add->error_user_msg) ? $add->error_user_msg : $add->error->message);
                            }

                        }
                        else{
                            $adCreative = json_decode($adCreative->body());

                            return back()->with('error',isset($adCreative->error_user_msg) ? $adCreative->error_user_msg : $adCreative->error->message);
                        }


                    }
                    else{
                        $addSet = json_decode($addSet->body());

                        return back()->with('error',isset($addSet->error_user_msg) ? $addSet->error->error_user_msg  : $addSet->error->message);
                    }




                    $advertisementAdds = new AdvertisementAds();
                    $advertisementAdds->advertisements_id = $adsStep2->id;
                    $advertisementAdds->heading = $heading->data;
                    $advertisementAdds->body = $body->data;
                    $advertisementAdds->button = $button->data;
                    $advertisementAdds->url = $button->url;
                    $advertisementAdds->image = $image->data;
                    $advertisementAdds->start_date = Carbon::now();
                    $advertisementAdds->end_date = Carbon::now()->addDays(3);
                    $advertisementAdds->save();

                }


            }


        }
    }

    public function step3()
    {
        $adsStep3 = Advertisement::whereHas('activeAdd', function ($q) {
            $q->where('end_date', '<', Carbon::now());
        })
            ->where('status', 'pending')
            ->where('step', 3)
            ->where('type', 1)
            ->get();

        foreach ($adsStep3 as $adsStep3) {

            $advertisement=$adsStep3;
            $compain_id=$adsStep3->compain_id;

            $user = User::find($adsStep3->user_id);
            $facebook = [
                'fb_client' => $user->fb_client,
                'fb_secret' => $user->fb_secret,
                'fb_token' => $user->fb_token,
                'page_id' => $user->fb_page,
                'fb_account' => $user->fb_account,

            ];

            $ads = AdvertisementAds::where('advertisements_id', $adsStep3->id)->select(
                '*',
                DB::raw('sum(clicks + impressions + cpc + conversation) as total'))
                ->groupBY('id')
                ->orderBy('total', 'DESC')
                ->first();


            //update priority
            $adsDetail = AdvertisementDetail::where('advertisements_id', $adsStep3->id)
                ->where('type', 'image')
                ->where('data', $ads->image)
                ->update(['status' => 'final']);
            $adsStep3->step = 4;
            $adsStep3->update();

            if (count(AdvertisementAds::where('advertisements_id', $adsStep3->id)->get()) > 1 || count(AdvertisementDetail::where('advertisements_id', $adsStep3->id)->where('type', 'button')->get()) > 1) {
                //delete add
                $adsDel = AdvertisementAds::where('advertisements_id', $adsStep3->id)->get();
                foreach ($adsDel as $adsDel)
                {
                    $delete = \Http::delete('https://graph.facebook.com/v13.0/'.$adsDel->addSet_id.'', [
                        'access_token' => $facebook['fb_token'],
                    ]);
                    $adsDel->delete();
                }


                //get other
                $body = AdvertisementDetail::where('advertisements_id', $adsStep3->id)->where('type', 'body')->where('status', 'final')->first();
                $heading = AdvertisementDetail::where('advertisements_id', $adsStep3->id)->where('type', 'heading')->where('status', 'final')->first();
                $button = AdvertisementDetail::where('advertisements_id', $adsStep3->id)->where('type', 'button')->get();
                $image = AdvertisementDetail::where('advertisements_id', $adsStep3->id)->where('type', 'image')->where('status', 'final')->first();

                //inserting add
                foreach ($button as $button) {


                    $addSet = \Http::post('https://graph.facebook.com/v13.0/act_'.$facebook['fb_account'].'/adsets', [
                        'campaign_id' => $compain_id,
                        'name' => $heading->data,
                        'lifetime_budget' => ($advertisement->per_day * 3) * 100,
                        'start_time' => Carbon::now(),
                        'end_time' => Carbon::now()->addDays(3),
                        'bid_amount' => $advertisement->per_day * 100,
                        'billing_event' => 'IMPRESSIONS',
                        'optimization_goal' => $advertisement->goal,
                        'targeting' => ['age_min' => intval($advertisement->age[0]), 'age_max' => intval($advertisement->age[1]),
                            //'behaviors' => ['id' => 6002714895372, 'name' => 'All travelers'],
                            'genders' => [1,2],
                            'geo_locations' => ['countries' => ['US']]
                        ],
                        'status' => 'PAUSED',
                        'access_token' => $facebook['fb_token'],
                    ]);
                    if ($addSet->status()==200)
                    {
                        $addSet = json_decode($addSet->body());
                        $addSet_id = $addSet->id;


                        //creating addCreative

                        $adCreative = \Http::post('https://graph.facebook.com/v13.0/act_'.$facebook['fb_account'].'/adcreatives', [
                            'name' => $heading->data,
                            'body'=>$body->data,
                            'object_story_spec' => [
                                'link_data' => [
                                    'image_hash' => md5_file(public_path('images/gallary/'.$image->data.'')),
                                    'link' => $button->url,
                                    'message' => $button->data,

                                ],
                                'page_id' => $facebook['page_id']
                            ],

                            'access_token' => $facebook['fb_token'],
                        ]);


                        if ($adCreative->status()==200)
                        {
                            $adCreative = json_decode($adCreative->body());
                            $addCreative_id = $adCreative->id;

                            //creating add

                            $add = \Http::post('https://graph.facebook.com/v13.0/act_'.$facebook['fb_account'].'/ads', [
                                'name' => $heading->data,
                                'adset_id' => $addSet_id,
                                'creative' => [
                                    'creative_id' => $addCreative_id,
                                ],
                                'status' => 'PAUSED',

                                'access_token' =>$facebook['fb_token'],
                            ]);
                            if ($add->status()==200)
                            {
                                $add=json_decode($add->body());
                                $add_id=$add->id;

                            }
                            else{
                                $add=json_decode($add->body());
                                return back()->with('error',isset($add->error_user_msg) ? $add->error_user_msg : $add->error->message);
                            }

                        }
                        else{
                            $adCreative = json_decode($adCreative->body());

                            return back()->with('error',isset($adCreative->error_user_msg) ? $adCreative->error_user_msg : $adCreative->error->message);
                        }


                    }
                    else{
                        $addSet = json_decode($addSet->body());

                        return back()->with('error',isset($addSet->error_user_msg) ? $addSet->error->error_user_msg  : $addSet->error->message);
                    }


                    $advertisementAdds = new AdvertisementAds();
                    $advertisementAdds->advertisements_id = $adsStep3->id;
                    $advertisementAdds->heading = $heading->data;
                    $advertisementAdds->body = $body->data;
                    $advertisementAdds->button = $button->data;
                    $advertisementAdds->url = $button->url;
                    $advertisementAdds->image = $image->data;
                    $advertisementAdds->start_date = Carbon::now();
                    $advertisementAdds->end_date = Carbon::now()->addDays(3);
                    $advertisementAdds->save();

                }


            }


        }
    }

    public function step4()
    {
        $adsStep4 = Advertisement::whereHas('activeAdd', function ($q) {
            $q->where('end_date', '<', Carbon::now());
        })
            ->where('status', 'pending')
            ->where('step', 4)
            ->where('type', 1)
            ->get();

        foreach ($adsStep4 as $adsStep4) {


            $advertisement=$adsStep4;
            $compain_id=$adsStep4->compain_id;

            $user = User::find($adsStep4->user_id);
            $facebook = [
                'fb_client' => $user->fb_client,
                'fb_secret' => $user->fb_secret,
                'fb_token' => $user->fb_token,
                'page_id' => $user->fb_page,
                'fb_account' => $user->fb_account,

            ];


            $ads = AdvertisementAds::where('advertisements_id', $adsStep4->id)->select(
                '*',
                DB::raw('sum(clicks + impressions + cpc + conversation) as total'))
                ->groupBY('id')
                ->orderBy('total', 'DESC')
                ->first();


            //update priority
            $adsDetail = AdvertisementDetail::where('advertisements_id', $adsStep4->id)
                ->where('type', 'button')
                ->where('data', $ads->button)
                ->update(['status' => 'final']);
            $adsStep4->step = 5;
            $adsStep4->update();

            if (count(AdvertisementAds::where('advertisements_id', $adsStep4->id)->get()) > 1) {
                //delete add
                $adsDel = AdvertisementAds::where('advertisements_id', $adsStep4->id)->get();
                foreach ($adsDel as $adsDel)
                {
                    $delete = \Http::delete('https://graph.facebook.com/v13.0/'.$adsDel->addSet_id.'', [
                        'access_token' => $facebook['fb_token'],
                    ]);
                    $adsDel->delete();
                }


                //get other
                $body = AdvertisementDetail::where('advertisements_id', $adsStep4->id)->where('type', 'body')->where('status', 'final')->first();
                $heading = AdvertisementDetail::where('advertisements_id', $adsStep4->id)->where('type', 'heading')->where('status', 'final')->first();
                $button = AdvertisementDetail::where('advertisements_id', $adsStep4->id)->where('type', 'button')->where('status', 'final')->first();
                $image = AdvertisementDetail::where('advertisements_id', $adsStep4->id)->where('type', 'image')->where('status', 'final')->first();

                //inserting add



                $addSet = \Http::post('https://graph.facebook.com/v13.0/act_'.$facebook['fb_account'].'/adsets', [
                    'campaign_id' => $compain_id,
                    'name' => $heading->data,
                    'lifetime_budget' => ($advertisement->per_day * 3) * 100,
                    'start_time' => Carbon::now(),
                    'end_time' => Carbon::now()->addDays(3),
                    'bid_amount' => $advertisement->per_day * 100,
                    'billing_event' => 'IMPRESSIONS',
                    'optimization_goal' => $advertisement->goal,
                    'targeting' => ['age_min' => intval($advertisement->age[0]), 'age_max' => intval($advertisement->age[1]),
                        //'behaviors' => ['id' => 6002714895372, 'name' => 'All travelers'],
                        'genders' => [1,2],
                        'geo_locations' => ['countries' => ['US']]
                    ],
                    'status' => 'PAUSED',
                    'access_token' => $facebook['fb_token'],
                ]);
                if ($addSet->status()==200)
                {
                    $addSet = json_decode($addSet->body());
                    $addSet_id = $addSet->id;


                    //creating addCreative

                    $adCreative = \Http::post('https://graph.facebook.com/v13.0/act_'.$facebook['fb_account'].'/adcreatives', [
                        'name' => $heading->data,
                        'body'=>$body->data,
                        'object_story_spec' => [
                            'link_data' => [
                                'image_hash' => md5_file(public_path('images/gallary/'.$image->data.'')),
                                'link' => $button->url,
                                'message' => $button->data,

                            ],
                            'page_id' => $facebook['page_id']
                        ],

                        'access_token' => $facebook['fb_token'],
                    ]);


                    if ($adCreative->status()==200)
                    {
                        $adCreative = json_decode($adCreative->body());
                        $addCreative_id = $adCreative->id;

                        //creating add

                        $add = \Http::post('https://graph.facebook.com/v13.0/act_'.$facebook['fb_account'].'/ads', [
                            'name' => $heading->data,
                            'adset_id' => $addSet_id,
                            'creative' => [
                                'creative_id' => $addCreative_id,
                            ],
                            'status' => 'PAUSED',

                            'access_token' =>$facebook['fb_token'],
                        ]);
                        if ($add->status()==200)
                        {
                            $add=json_decode($add->body());
                            $add_id=$add->id;

                        }
                        else{
                            $add=json_decode($add->body());
                            return back()->with('error',isset($add->error_user_msg) ? $add->error_user_msg : $add->error->message);
                        }

                    }
                    else{
                        $adCreative = json_decode($adCreative->body());

                        return back()->with('error',isset($adCreative->error_user_msg) ? $adCreative->error_user_msg : $adCreative->error->message);
                    }


                }
                else{
                    $addSet = json_decode($addSet->body());

                    return back()->with('error',isset($addSet->error_user_msg) ? $addSet->error->error_user_msg  : $addSet->error->message);
                }


                $advertisementAdds = new AdvertisementAds();
                $advertisementAdds->advertisements_id = $adsStep4->id;
                $advertisementAdds->heading = $heading->data;
                $advertisementAdds->body = $body->data;
                $advertisementAdds->button = $button->data;
                $advertisementAdds->url = $button->url;
                $advertisementAdds->image = $image->data;
                $advertisementAdds->start_date = Carbon::now();
                $advertisementAdds->end_date = Carbon::now()->addDays(3);
                $advertisementAdds->save();


            } else {
                //update end time of add

                AdvertisementAds::where('advertisements_id', $adsStep4->id)->update([
                    'end_date' => Carbon::now()->addDays(3)
                ]);
            }


        }
    }
}
