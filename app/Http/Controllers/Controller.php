<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\AdvertisementAds;
use App\Models\AdvertisementDetail;
use App\Models\Behaviour;
use App\Models\Demographics;
use App\Models\Intrests;
use App\Models\User;
use Carbon\Carbon;
use Edujugon\GoogleAds\Services\Campaign;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Edujugon\GoogleAds\GoogleAds;
use Google\AdsApi\AdWords\v201809\cm\CampaignService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {

        $compain = \Http::post('https://graph.facebook.com/v13.0/act_1060535174543124/campaigns', [
            'name' => 'my new',
            'objective' => 'LINK_CLICKS',
            'status' => env('FB_STATUS'),
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
            'status' => env('FB_STATUS'),
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
            'status' => env('FB_STATUS'),

            'access_token' => env("ACCESS_TOKEN"),
        ]);
        dd(json_decode($add->body()));

    }

    public function index2()
    {

        $ads = new GoogleAds();
        $ads->env('test');
        $service = google_service(CampaignService::class);
  $com=      $ads->service(CampaignService::class)
            ->select(['Id', 'Name', 'Status', 'ServingStatus', 'StartDate', 'EndDate'])
            ->get();
  dd($com);




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

//        $api = \Http::post('https://oauth2.googleapis.com/token',
//
//            [
//                'client_id' => '368856619669-2442dc6p657s23vdg8efnorgter8nv6o.apps.googleusercontent.com',
//                'clientSecret' => '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',
//                'refresh_token' => '4%2F0AX4XfWhU8hYEqQ65ZPaYoCF5mCUAU_ZIsgf4StWwq0XjtbliK_1Q1_lmZ_F1ytuEOdQyYA',
//                'grant_type' => 'refresh_token',
//            ]);
//        dd($api->body());
//
//
//        $ads = new GoogleAds();
//        $ads->getUserCredentials();


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
           // dd($insight);
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

            if ($adsStep1) {


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
                        'status' => env('FB_STATUS'),
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
                                'status' => env('FB_STATUS'),

                                'access_token' =>$facebook['fb_token'],
                            ]);
                            if ($add->status()==200)
                            {
                                $add=json_decode($add->body());
                                $add_id=$add->id;

                            }
                            else{
                                $add=json_decode($add->body());
                               return 0;// return back()->with('error',isset($add->error_user_msg) ? $add->error_user_msg : $add->error->message);
                            }

                        }
                        else{
                            $adCreative = json_decode($adCreative->body());

                           return 0;// return back()->with('error',isset($adCreative->error_user_msg) ? $adCreative->error_user_msg : $adCreative->error->message);
                        }


                    }
                    else{
                        $addSet = json_decode($addSet->body());

                       return 0;// return back()->with('error',isset($addSet->error_user_msg) ? $addSet->error->error_user_msg  : $addSet->error->message);
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

            if ( $adsStep2) {
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
                        'status' => env('FB_STATUS'),
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
                                'status' => env('FB_STATUS'),

                                'access_token' =>$facebook['fb_token'],
                            ]);
                            if ($add->status()==200)
                            {
                                $add=json_decode($add->body());
                                $add_id=$add->id;

                            }
                            else{
                                $add=json_decode($add->body());
                               return 0;// return back()->with('error',isset($add->error_user_msg) ? $add->error_user_msg : $add->error->message);
                            }

                        }
                        else{
                            $adCreative = json_decode($adCreative->body());

                           return 0;// return back()->with('error',isset($adCreative->error_user_msg) ? $adCreative->error_user_msg : $adCreative->error->message);
                        }


                    }
                    else{
                        $addSet = json_decode($addSet->body());

                       return 0;// return back()->with('error',isset($addSet->error_user_msg) ? $addSet->error->error_user_msg  : $addSet->error->message);
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

            if ($adsStep3) {
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
                        'status' => env('FB_STATUS'),
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
                                'status' => env('FB_STATUS'),

                                'access_token' =>$facebook['fb_token'],
                            ]);
                            if ($add->status()==200)
                            {
                                $add=json_decode($add->body());
                                $add_id=$add->id;

                            }
                            else{
                                $add=json_decode($add->body());
                               return 0;// return back()->with('error',isset($add->error_user_msg) ? $add->error_user_msg : $add->error->message);
                            }

                        }
                        else{
                            $adCreative = json_decode($adCreative->body());

                           return 0;// return back()->with('error',isset($adCreative->error_user_msg) ? $adCreative->error_user_msg : $adCreative->error->message);
                        }


                    }
                    else{
                        $addSet = json_decode($addSet->body());

                       return 0;// return back()->with('error',isset($addSet->error_user_msg) ? $addSet->error->error_user_msg  : $addSet->error->message);
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
      // dd($adsStep4);

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
            $adsStep4->step = 5; //fake
            $adsStep4->update();

            if ($adsStep4->end_date>Carbon::now()) {
               $date1=new \DateTime($adsStep4->end_date);
               $date2=new \DateTime(Carbon::now());
             $f=  $date1->diff($date2)->days;



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
                    'lifetime_budget' => ($advertisement->per_day * $f) * 100,
                    'start_time' => Carbon::now(),
                    'end_time' => $advertisement->end_date,
                    'bid_amount' => $advertisement->per_day * 100,
                    'billing_event' => 'IMPRESSIONS',
                    'optimization_goal' => $advertisement->goal,
                    'targeting' => ['age_min' => intval($advertisement->age[0]), 'age_max' => intval($advertisement->age[1]),
                        'behaviors' => $advertisement->behaviour,

                        'genders' => [$advertisement->gender],
                        'geo_locations' => [
                            'countries' => $advertisement->countries,
                            'cities' => $advertisement->cities,

                        ],
                        'interests' => $advertisement->interest,
                        'life_events' => $advertisement->life_events,
                        'family_statuses' => $advertisement->family_statuses,
                        'industries' => $advertisement->industries,
                        'income' => $advertisement->income,
                    ],
                    'status' => env('FB_STATUS'),
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
                                'name' => $heading->data,
                                'image_hash' => $image->hash,
                                'link' => $button->url,
                                'message' => $body->data,
                                "call_to_action"=>[
                                    'type'=>$button->data,
                                ]

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
                            'status' => env('FB_STATUS'),

                            'access_token' =>$facebook['fb_token'],
                        ]);
                        if ($add->status()==200)
                        {
                            $add=json_decode($add->body());
                            $add_id=$add->id;

                        }
                        else{
                            $add=json_decode($add->body());
                           return 0;// return back()->with('error',isset($add->error_user_msg) ? $add->error_user_msg : $add->error->message);
                        }

                    }
                    else{
                        $adCreative = json_decode($adCreative->body());

                       return 0;// return back()->with('error',isset($adCreative->error_user_msg) ? $adCreative->error_user_msg : $adCreative->error->message);
                    }


                }
                else{

                    $addSet = json_decode($addSet->body());
                    dd($addSet);

                   return 0;// return back()->with('error',isset($addSet->error_user_msg) ? $addSet->error->error_user_msg  : $addSet->error->message);
                }


                $advertisementAdds = new AdvertisementAds();
                $advertisementAdds->advertisements_id = $adsStep4->id;
                $advertisementAdds->heading = $heading->data;
                $advertisementAdds->body = $body->data;
                $advertisementAdds->button = $button->data;
                $advertisementAdds->url = $button->url;
                $advertisementAdds->image = $image->data;
                $advertisementAdds->start_date = Carbon::now();
                $advertisementAdds->end_date = $advertisement->end_date;
                $advertisementAdds->save();


            }


        }
    }

    public function intrest()
    {
        $facebook = config()->get('services.facebook');


        $interest = \Http::get('https://graph.facebook.com/v13.0/search', [

            'type' => 'adTargetingCategory',
            //   'limit'=>500,
           // 'q' => 'all',
              'class'=>'interests',
            'access_token' => $facebook['fb_token'],

        ]);
        $data=json_decode($interest->body());
        $data= collect($data->data);
    //  $data=$data->where('name','Sports');
    //  dd($data);

       Intrests::truncate();
       foreach ($data as $intrest)
       {
           $length=count($intrest->path);
           $total=1;
           foreach($intrest->path as $path){
               $find=Intrests::where('name',$path)->first();
               if ($total==1){
                   if(!$find){
                       $ins=new Intrests();
                       $ins->name=$path;
                      // $ins->data_id=intval($intrest->id);
                       $ins->save();
                       $prev=$ins->id;
                   }
                   else{
                       $prev=$find->id;
                   }

               }
               else{

                   if(!$find){
                       $rec=$data->where('name',"$path")->first();
                       if ($rec){
                          // dd($path);

                       $ins=new Intrests();
                       $ins->name=$path;
                // dd($rec);
                       $ins->data_id=intval($rec->id);
                       $ins->parent=intval($prev);
                       $ins->save();

                       $prev=$ins->id;
                       }
//                       else{
//                           echo $path.',';
//                       }
                   }
                   else{

                       $prev=$find->id;

                   }
               }


               $total=$total+1;


           }

       }

}

    public function behaviour()
    {
        $facebook = config()->get('services.facebook');


        $behaviour = \Http::get('https://graph.facebook.com/v13.0/search', [

            'type' => 'adTargetingCategory',
            //   'limit'=>500,
            'class'=>'behaviors',
            'access_token' => $facebook['fb_token'],

        ]);
        $data=json_decode($behaviour->body());
        $data= collect($data->data);
        //$data=$data->where('name','Home and garden');
       //  dd($data);

        Behaviour::truncate();
        foreach ($data as $behaviour)
        {
            $length=count($behaviour->path);
            $total=1;
            foreach($behaviour->path as $path){
                $find=Behaviour::where('name',$path)->first();
                if ($total==1){
                    if(!$find){
                        $ins=new Behaviour();
                        $ins->name=$path;
                        $ins->data_id=intval($behaviour->id);
                        $ins->save();
                        $prev=$ins->id;
                    }
                    else{
                        $prev=$find->id;
                    }

                }
                else{

                    if(!$find){
                        $rec=$data->where('name',"$path")->first();
                        if ($rec){
                            // dd($path);
                            $ins=new Behaviour();
                            $ins->name=$path;
                            // dd($rec);
                            $ins->data_id=intval($rec->id);
                            $ins->parent=intval($prev);
                            $ins->save();

                            $prev=$ins->id;
                        }

                    }
                    else{

                        $prev=$find->id;

                    }
                }


                $total=$total+1;


            }

        }

    }

    public function dempgraphics(){

        $facebook = config()->get('services.facebook');


        $demographics= \Http::get('https://graph.facebook.com/v13.0/search', [

            'type' => 'adTargetingCategory',
            'limit'=>500,
            'class'=>'demographics',
            'access_token' => $facebook['fb_token'],

        ]);


        $data=json_decode($demographics->body());
     //   dd($data);
        $data= collect($data->data);

        $data=$data->groupBy('type');
          //dd($data);

        Demographics::truncate();
        foreach ($data as $key => $demographics)
        {


            $ins=new Demographics();
            $ins->name=str_replace("_",' ',"$key");
            $ins->type=$key;
            $ins->save();

            $prev=$ins->id;

            foreach($demographics as  $path){

                $find=Demographics::where('name',$path->name)->first();


                    if(!$find){


                            // dd($path);

                            $ins=new Demographics();
                            $ins->name=$path->name;
                            // dd($rec);
                            $ins->data_id=intval($path->id);
                            $ins->parent=intval($prev);
                            $ins->type=$path->type;
                            $ins->save();



                    }







            }

        }
    }

    public function data()
    {
        //step 1
        $adsStep1 = Advertisement::whereHas('activeAdd', function ($q) {
            $q->where('end_date', '<', Carbon::now());
        })

            ->where('status', 'pending')
            ->where('step', 1)
            ->where('type', 2)
            ->where('goal', 'SEARCH')
            ->get();

        foreach ($adsStep1 as $adsStep1) {


            $rand = rand(1111, 9999);
            $budget = intval($adsStep1->per_day) * 1000000;

            $user = User::find($adsStep1->user_id);
            $api = \Http::post('https://www.googleapis.com/oauth2/v3/token', [
                'grant_type' => 'refresh_token',
                'client_id' => $user->gg_client,
                'client_secret' => $user->gg_secret,
                'refresh_token' => $user->gg_refresh,
            ]);
            if ($api->status() == 200) {
                $api = json_decode($api->body());

                $user->gg_access = $api->access_token;
                $user->update();


                $google = [
                    'dev_token' => $user->gg_dev,
                    'manager_id' => $user->gg_manager,
                    'customer_id' => $user->gg_customer,
                    'client_id' => $user->gg_client,
                    'secret_id' => $user->gg_secret,
                    'accsss_token' => $user->gg_access,
                    'refresh_token' => $user->gg_refresh,

                ];


                $i = 0;
                $img1 = $hash1 = null;
                $radius = $adsStep1->radius;
                $cities = array();
                $countries = array();
                $intrest = array();
                $behaviour = array();
                $life_events = $family_statuses = $industries = $income = array();
                if ($adsStep1->cities) {

                    foreach ($adsStep1->cities as $city) {

                        $cities[] = $city;
                    }

                }



                if ($adsStep1->countries) {

                    foreach ($adsStep1->countries as $contry) {

                        $countries[] = $contry;
                    }

                }


                //saving compain google

//dd($google);
                $compain_budget = \Http::withHeaders([

                    'developer-token' => $google['dev_token'],
                    'login-customer-id' => $google['manager_id'],
                ])->withToken($google['accsss_token'])->
                post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/campaignBudgets:mutate', [
                    'operations' => [
                        'create' => array(
                            'name' => "my budget $rand",
                            'amountMicros' => $budget * 3
                        )
                    ]
                ]);

                if ($compain_budget->status() == 200) {

                    $compain_budget = json_decode($compain_budget->body());
                    $compain_budget = $compain_budget->results[0]->resourceName;


                    $compain = \Http::withHeaders([

                        'developer-token' => $google['dev_token'],
                        'login-customer-id' => $google['manager_id'],
                    ])->withToken($google['accsss_token'])->
                    post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/campaigns:mutate', [
                        'operations' => [
                            'create' => array(
                                'status' => env('GA_STATUS'),
                                'advertisingChannelType' => $adsStep1->goal,
                                "geoTargetTypeSetting" => array(
                                    "positiveGeoTargetType" => 'PRESENCE_OR_INTEREST',
                                    "negativeGeoTargetType" => 'PRESENCE_OR_INTEREST',
                                ),

                                'name' => "$adsStep1->title $rand",
                                'campaignBudget' => $compain_budget,

                                'targetSpend' => array(
                                    'cpcBidCeilingMicros' => 1
                                ),
                                'startDate' => Carbon::create(Carbon::now())->format('Y-m-d'),
                                'endDate' => Carbon::create(Carbon::now()->addDays(3))->format('Y-m-d'),

                            )
                        ]
                    ]);

                    if ($compain->status() == 200) {

                        $compain = json_decode($compain->body());
                        $compain = $compain->results[0]->resourceName;


                        $compain_criteria = \Http::withHeaders([

                            'developer-token' => $google['dev_token'],
                            'login-customer-id' => $google['manager_id'],
                        ])->withToken($google['accsss_token'])->
                        post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/campaignCriteria:mutate', [
                            'operations' => [
                                'create' => array(
                                    'displayName' => "my campaign criteria $rand",
                                    'campaign' => $compain,
                                    'negative' => true,
                                    "ageRange" => array(
                                        'type' => $adsStep1->age2
                                    ),


                                )
                            ]
                        ]);


                        $compain_criteria2 = \Http::withHeaders([

                            'developer-token' => $google['dev_token'],
                            'login-customer-id' => $google['manager_id'],
                        ])->withToken($google['accsss_token'])->
                        post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/campaignCriteria:mutate', [
                            'operations' => [
                                'create' => array(
                                    'displayName' => "my campaign criteria $rand",
                                    'campaign' => $compain,
                                    'negative' => true,
                                    "gender" => array(
                                        'type' => $adsStep1->gender
                                    ),


                                )
                            ]
                        ]);


                        foreach ($cities as $cit) {

                            $compain_criteria3 = \Http::withHeaders([

                                'developer-token' => $google['dev_token'],
                                'login-customer-id' => $google['manager_id'],
                            ])->withToken($google['accsss_token'])->
                            post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/campaignCriteria:mutate', [
                                'operations' => [
                                    'create' => array(
                                        'displayName' => "my campaign criteria $rand",
                                        'campaign' => $compain,
                                        'negative' => true,
                                        "location" => array(
                                            'geoTargetConstant' => $cit
                                        ),
                                    )
                                ]
                            ]);


                        }

                        //      dd(json_decode($compain_criteria3->body()),$compain,$cities);

                        //        dd(json_decode($compain_criteria->body()), json_decode($compain_criteria2->body()));


                        $adgroup = \Http::withHeaders([

                            'developer-token' => $google['dev_token'],
                            'login-customer-id' => $google['manager_id'],
                        ])->withToken($google['accsss_token'])->
                        post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/adGroups:mutate', [
                            'operations' => [
                                'create' => array(
                                    'status' => env('GA_STATUS'),
                                    'name' => "my adgroup $rand",
                                    //  'type' => "SEARCH_DYNAMIC_ADS",
                                    'type' => "SEARCH_STANDARD",
                                    'campaign' => $compain,


                                )
                            ]
                        ]);

                        if ($adgroup->status()) {
                            $adgroup = json_decode($adgroup->body());
                            $adgroup = $adgroup->results[0]->resourceName;


//dd(json_decode($adgroupadd));

                        } else {
                            $adgroup = json_decode($adgroup->body());
                           return 0;// return back()->with('error', isset($adgroup->error->message) ? $adgroup->error->message : 'Something went wrong');


                        }


                    } else {
                        $compain = json_decode($compain->body());

                       return 0;// return back()->with('error', isset($compain->error->message) ? $compain->error->message : 'Something went wrong');

                    }
                } else {
                    $compain_budget = json_decode($compain_budget->body());

                   return 0;// return back()->with('error', isset($compain_budget->error->message) ? $compain_budget->error->message : 'Something went wrong');
                }

                //adgeoup ad


                $advertisement = new Advertisement();
                $advertisement->goal = $adsStep1->goal;
                $advertisement->title = $adsStep1->title;
                $advertisement->user_id = $adsStep1->user_id;

                $advertisement->age2 = $adsStep1->age2;
                $advertisement->gender = $adsStep1->gender;
                $advertisement->per_day = $adsStep1->per_day;

                $advertisement->type = 2;
                $advertisement->step = 2;
                $advertisement->compain_id = $compain;

                $advertisement->cities = json_encode($cities);
                $advertisement->countries = json_encode($countries);

                $advertisement->start_date = $adsStep1->start_date;
                $advertisement->end_date = $adsStep1->end_date;

              $advertisement->save();



                //  dd($advertisement,$countries);



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
                $detail = AdvertisementDetail::where('advertisements_id', $adsStep1->id)->update(['advertisements_id' => $advertisement->id]);

               $adsStep1->delete();

                $bodies = AdvertisementDetail::where('advertisements_id', $advertisement->id)->where('type', 'body')->get();
                $heading = AdvertisementDetail::where('advertisements_id', $advertisement->id)->where('type', 'heading')->where('status', 'final')->first();
                $button = AdvertisementDetail::where('advertisements_id', $advertisement->id)->where('type', 'button')->first();
                $headingExp = explode('|', $heading->data);


                //save heading and creating ads
                $check = 0;
                foreach ($bodies as $body) {


                    $adgroupadd = \Http::withHeaders([

                        'developer-token' => $google['dev_token'],
                        'login-customer-id' => $google['manager_id'],
                    ])->withToken($google['accsss_token'])->
                    post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/adGroupAds:mutate', [
                        'operations' => [
                            'create' => array(
                                'status' => env('GA_STATUS'),
                                'adGroup' => $adgroup,
                                "ad" => array(

                                    "expandedTextAd" => array(
                                        'headlinePart1' => $headingExp[0],
                                        'headlinePart2' => $headingExp[1],
                                        'description' => $body->data
                                    ),
                                    "finalUrls" => [$button->url]
                                )

                            )
                        ]
                    ]);

                    if ($adgroupadd->status() == 200) {


                        $advertisementAdds = new AdvertisementAds();
                        $advertisementAdds->advertisements_id = $advertisement->id;
                        $advertisementAdds->heading = $heading->data;

                        $advertisementAdds->body = $body->data;
                        // $advertisementAdds->button = $request->btn[0];
                        $advertisementAdds->url = $button->url;
                        // $advertisementAdds->image = $img1;
                        $advertisementAdds->start_date = Carbon::now();
                        $advertisementAdds->end_date = Carbon::now()->addDays(3);
                        $advertisementAdds->addSet_id = $adgroup;
                        //$advertisementAdds->addCreative_id = $compain_budget;
                        $advertisementAdds->add_id = $adgroupadd;

                        //  dd($compain_id,$addSet_id,$addCreative_id,$add_id);
                        $advertisementAdds->save();

                        $check++;
                    } else {

                        $adgroupadd = json_decode($adgroupadd->body());

                        $advertisement->delete();

                       return 0;// return back()->with('error', isset($adgroupadd->error->message) ? $adgroupadd->error->message : 'Something went wrong');
                    }


                }

            }
        }


        //step 2

        $adsStep2 = Advertisement::whereHas('activeAdd', function ($q) {
            $q->where('end_date', '<', Carbon::now());
        })

            ->where('status', 'pending')
            ->where('step', 2)
            ->where('type', 2)
            ->where('goal', 'SEARCH')
            ->get();

        foreach ($adsStep2 as $adsStep2) {


            $rand = rand(1111, 9999);
            $budget = intval($adsStep2->per_day) * 1000000;

            $user = User::find($adsStep2->user_id);
            $api = \Http::post('https://www.googleapis.com/oauth2/v3/token', [
                'grant_type' => 'refresh_token',
                'client_id' => $user->gg_client,
                'client_secret' => $user->gg_secret,
                'refresh_token' => $user->gg_refresh,
            ]);
            if ($api->status() == 200) {
                $api = json_decode($api->body());

                $user->gg_access = $api->access_token;
                $user->update();


                $google = [
                    'dev_token' => $user->gg_dev,
                    'manager_id' => $user->gg_manager,
                    'customer_id' => $user->gg_customer,
                    'client_id' => $user->gg_client,
                    'secret_id' => $user->gg_secret,
                    'accsss_token' => $user->gg_access,
                    'refresh_token' => $user->gg_refresh,

                ];


                $i = 0;
                $img1 = $hash1 = null;
                $radius = $adsStep2->radius;
                $cities = array();
                $countries = array();
                $intrest = array();
                $behaviour = array();
                $life_events = $family_statuses = $industries = $income = array();
                if ($adsStep2->cities) {

                    foreach ($adsStep2->cities as $city) {

                        $cities[] = $city;
                    }

                }



                if ($adsStep2->countries) {

                    foreach ($adsStep2->countries as $contry) {

                        $countries[] = $contry;
                    }

                }


                //saving compain google

//dd($google);
                $compain_budget = \Http::withHeaders([

                    'developer-token' => $google['dev_token'],
                    'login-customer-id' => $google['manager_id'],
                ])->withToken($google['accsss_token'])->
                post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/campaignBudgets:mutate', [
                    'operations' => [
                        'create' => array(
                            'name' => "my budget $rand",
                            'amountMicros' => $budget * 3
                        )
                    ]
                ]);

                if ($compain_budget->status() == 200) {

                    $compain_budget = json_decode($compain_budget->body());
                    $compain_budget = $compain_budget->results[0]->resourceName;


                    $compain = \Http::withHeaders([

                        'developer-token' => $google['dev_token'],
                        'login-customer-id' => $google['manager_id'],
                    ])->withToken($google['accsss_token'])->
                    post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/campaigns:mutate', [
                        'operations' => [
                            'create' => array(
                                'status' => env('GA_STATUS'),
                                'advertisingChannelType' => $adsStep2->goal,
                                "geoTargetTypeSetting" => array(
                                    "positiveGeoTargetType" => 'PRESENCE_OR_INTEREST',
                                    "negativeGeoTargetType" => 'PRESENCE_OR_INTEREST',
                                ),

                                'name' => "$adsStep2->title $rand",
                                'campaignBudget' => $compain_budget,

                                'targetSpend' => array(
                                    'cpcBidCeilingMicros' => 1
                                ),
                                'startDate' => Carbon::create(Carbon::now())->format('Y-m-d'),
                                'endDate' => Carbon::create(Carbon::now()->addDays(3))->format('Y-m-d'),

                            )
                        ]
                    ]);

                    if ($compain->status() == 200) {

                        $compain = json_decode($compain->body());
                        $compain = $compain->results[0]->resourceName;


                        $compain_criteria = \Http::withHeaders([

                            'developer-token' => $google['dev_token'],
                            'login-customer-id' => $google['manager_id'],
                        ])->withToken($google['accsss_token'])->
                        post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/campaignCriteria:mutate', [
                            'operations' => [
                                'create' => array(
                                    'displayName' => "my campaign criteria $rand",
                                    'campaign' => $compain,
                                    'negative' => true,
                                    "ageRange" => array(
                                        'type' => $adsStep2->age2
                                    ),


                                )
                            ]
                        ]);


                        $compain_criteria2 = \Http::withHeaders([

                            'developer-token' => $google['dev_token'],
                            'login-customer-id' => $google['manager_id'],
                        ])->withToken($google['accsss_token'])->
                        post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/campaignCriteria:mutate', [
                            'operations' => [
                                'create' => array(
                                    'displayName' => "my campaign criteria $rand",
                                    'campaign' => $compain,
                                    'negative' => true,
                                    "gender" => array(
                                        'type' => $adsStep2->gender
                                    ),


                                )
                            ]
                        ]);


                        foreach ($cities as $cit) {

                            $compain_criteria3 = \Http::withHeaders([

                                'developer-token' => $google['dev_token'],
                                'login-customer-id' => $google['manager_id'],
                            ])->withToken($google['accsss_token'])->
                            post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/campaignCriteria:mutate', [
                                'operations' => [
                                    'create' => array(
                                        'displayName' => "my campaign criteria $rand",
                                        'campaign' => $compain,
                                        'negative' => true,
                                        "location" => array(
                                            'geoTargetConstant' => $cit
                                        ),
                                    )
                                ]
                            ]);


                        }

                        //      dd(json_decode($compain_criteria3->body()),$compain,$cities);

                        //        dd(json_decode($compain_criteria->body()), json_decode($compain_criteria2->body()));


                        $adgroup = \Http::withHeaders([

                            'developer-token' => $google['dev_token'],
                            'login-customer-id' => $google['manager_id'],
                        ])->withToken($google['accsss_token'])->
                        post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/adGroups:mutate', [
                            'operations' => [
                                'create' => array(
                                    'status' => env('GA_STATUS'),
                                    'name' => "my adgroup $rand",
                                    //  'type' => "SEARCH_DYNAMIC_ADS",
                                    'type' => "SEARCH_STANDARD",
                                    'campaign' => $compain,


                                )
                            ]
                        ]);

                        if ($adgroup->status()) {
                            $adgroup = json_decode($adgroup->body());
                            $adgroup = $adgroup->results[0]->resourceName;


//dd(json_decode($adgroupadd));

                        } else {
                            $adgroup = json_decode($adgroup->body());
                            return 0;// return back()->with('error', isset($adgroup->error->message) ? $adgroup->error->message : 'Something went wrong');


                        }


                    } else {
                        $compain = json_decode($compain->body());

                        return 0;// return back()->with('error', isset($compain->error->message) ? $compain->error->message : 'Something went wrong');

                    }
                } else {
                    $compain_budget = json_decode($compain_budget->body());

                    return 0;// return back()->with('error', isset($compain_budget->error->message) ? $compain_budget->error->message : 'Something went wrong');
                }

                //adgeoup ad


                $advertisement = new Advertisement();
                $advertisement->goal = $adsStep2->goal;
                $advertisement->title = $adsStep2->title;
                $advertisement->user_id = $adsStep2->user_id;

                $advertisement->age2 = $adsStep2->age2;
                $advertisement->gender = $adsStep2->gender;
                $advertisement->per_day = $adsStep2->per_day;

                $advertisement->type = 2;
                $advertisement->step = 3;
                $advertisement->compain_id = $compain;

                $advertisement->cities = json_encode($cities);
                $advertisement->countries = json_encode($countries);

                $advertisement->start_date = $adsStep2->start_date;
                $advertisement->end_date = $adsStep2->end_date;

                $advertisement->save();



                //  dd($advertisement,$countries);



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
                $detail = AdvertisementDetail::where('advertisements_id', $adsStep2->id)->update(['advertisements_id' => $advertisement->id]);

                $adsStep2->delete();

                $body = AdvertisementDetail::where('advertisements_id', $advertisement->id)->where('type', 'body')->where('status', 'final')->first();
                $heading = AdvertisementDetail::where('advertisements_id', $advertisement->id)->where('type', 'heading')->where('status', 'final')->first();
                $button = AdvertisementDetail::where('advertisements_id', $advertisement->id)->where('type', 'button')->get();
                $headingExp = explode('|', $heading->data);


                //save heading and creating ads
                $check = 0;
                foreach ($button as $button) {


                    $adgroupadd = \Http::withHeaders([

                        'developer-token' => $google['dev_token'],
                        'login-customer-id' => $google['manager_id'],
                    ])->withToken($google['accsss_token'])->
                    post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/adGroupAds:mutate', [
                        'operations' => [
                            'create' => array(
                                'status' => env('GA_STATUS'),
                                'adGroup' => $adgroup,
                                "ad" => array(

                                    "expandedTextAd" => array(
                                        'headlinePart1' => $headingExp[0],
                                        'headlinePart2' => $headingExp[1],
                                        'description' => $body->data
                                    ),
                                    "finalUrls" => [$button->url]
                                )

                            )
                        ]
                    ]);

                    if ($adgroupadd->status() == 200) {


                        $advertisementAdds = new AdvertisementAds();
                        $advertisementAdds->advertisements_id = $advertisement->id;
                        $advertisementAdds->heading = $heading->data;

                        $advertisementAdds->body = $body->data;
                        // $advertisementAdds->button = $request->btn[0];
                        $advertisementAdds->url = $button->url;
                        // $advertisementAdds->image = $img1;
                        $advertisementAdds->start_date = Carbon::now();
                        $advertisementAdds->end_date = Carbon::now()->addDays(3);
                        $advertisementAdds->addSet_id = $adgroup;
                        //$advertisementAdds->addCreative_id = $compain_budget;
                        $advertisementAdds->add_id = $adgroupadd;

                        //  dd($compain_id,$addSet_id,$addCreative_id,$add_id);
                        $advertisementAdds->save();

                        $check++;
                    } else {

                        $adgroupadd = json_decode($adgroupadd->body());

                        $advertisement->delete();

                        return 0;// return back()->with('error', isset($adgroupadd->error->message) ? $adgroupadd->error->message : 'Something went wrong');
                    }


                }

            }
        }




        //step 3

        $adsStep3 = Advertisement::whereHas('activeAdd', function ($q) {
            $q->where('end_date', '<', Carbon::now());
        })

            ->where('status', 'pending')
            ->where('step', 3)
            ->where('type', 2)
            ->where('goal', 'SEARCH')
            ->get();

        foreach ($adsStep3 as $adsStep3) {


            $rand = rand(1111, 9999);
            $budget = intval($adsStep3->per_day) * 1000000;

            $user = User::find($adsStep3->user_id);
            $api = \Http::post('https://www.googleapis.com/oauth2/v3/token', [
                'grant_type' => 'refresh_token',
                'client_id' => $user->gg_client,
                'client_secret' => $user->gg_secret,
                'refresh_token' => $user->gg_refresh,
            ]);
            if ($api->status() == 200) {
                $api = json_decode($api->body());

                $user->gg_access = $api->access_token;
                $user->update();


                $google = [
                    'dev_token' => $user->gg_dev,
                    'manager_id' => $user->gg_manager,
                    'customer_id' => $user->gg_customer,
                    'client_id' => $user->gg_client,
                    'secret_id' => $user->gg_secret,
                    'accsss_token' => $user->gg_access,
                    'refresh_token' => $user->gg_refresh,

                ];


                $i = 0;
                $img1 = $hash1 = null;
                $radius = $adsStep3->radius;
                $cities = array();
                $countries = array();
                $intrest = array();
                $behaviour = array();
                $life_events = $family_statuses = $industries = $income = array();
                if ($adsStep3->cities) {

                    foreach ($adsStep3->cities as $city) {

                        $cities[] = $city;
                    }

                }



                if ($adsStep3->countries) {

                    foreach ($adsStep3->countries as $contry) {

                        $countries[] = $contry;
                    }

                }


                //saving compain google

//dd($google);
                $date1=new \DateTime($adsStep3->end_date);
                $date2=new \DateTime(Carbon::now());
                $f=  $date1->diff($date2)->days;
                $f=$f>=1 ? $f : 3;
                $compain_budget = \Http::withHeaders([

                    'developer-token' => $google['dev_token'],
                    'login-customer-id' => $google['manager_id'],
                ])->withToken($google['accsss_token'])->
                post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/campaignBudgets:mutate', [
                    'operations' => [
                        'create' => array(
                            'name' => "my budget $rand",
                            'amountMicros' => $budget * $f
                        )
                    ]
                ]);

                if ($compain_budget->status() == 200) {




                    $compain_budget = json_decode($compain_budget->body());
                    $compain_budget = $compain_budget->results[0]->resourceName;


                    $compain = \Http::withHeaders([

                        'developer-token' => $google['dev_token'],
                        'login-customer-id' => $google['manager_id'],
                    ])->withToken($google['accsss_token'])->
                    post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/campaigns:mutate', [
                        'operations' => [
                            'create' => array(
                                'status' => env('GA_STATUS'),
                                'advertisingChannelType' => $adsStep3->goal,
                                "geoTargetTypeSetting" => array(
                                    "positiveGeoTargetType" => 'PRESENCE_OR_INTEREST',
                                    "negativeGeoTargetType" => 'PRESENCE_OR_INTEREST',
                                ),

                                'name' => "$adsStep3->title $rand",
                                'campaignBudget' => $compain_budget,

                                'targetSpend' => array(
                                    'cpcBidCeilingMicros' => 1
                                ),
                                'startDate' => Carbon::create(Carbon::now())->format('Y-m-d'),
                                'endDate' => Carbon::create(Carbon::now()->addDays($f))->format('Y-m-d'),

                            )
                        ]
                    ]);

                    if ($compain->status() == 200) {

                        $compain = json_decode($compain->body());
                        $compain = $compain->results[0]->resourceName;


                        $compain_criteria = \Http::withHeaders([

                            'developer-token' => $google['dev_token'],
                            'login-customer-id' => $google['manager_id'],
                        ])->withToken($google['accsss_token'])->
                        post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/campaignCriteria:mutate', [
                            'operations' => [
                                'create' => array(
                                    'displayName' => "my campaign criteria $rand",
                                    'campaign' => $compain,
                                    'negative' => true,
                                    "ageRange" => array(
                                        'type' => $adsStep3->age2
                                    ),


                                )
                            ]
                        ]);


                        $compain_criteria2 = \Http::withHeaders([

                            'developer-token' => $google['dev_token'],
                            'login-customer-id' => $google['manager_id'],
                        ])->withToken($google['accsss_token'])->
                        post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/campaignCriteria:mutate', [
                            'operations' => [
                                'create' => array(
                                    'displayName' => "my campaign criteria $rand",
                                    'campaign' => $compain,
                                    'negative' => true,
                                    "gender" => array(
                                        'type' => $adsStep3->gender
                                    ),


                                )
                            ]
                        ]);


                        foreach ($cities as $cit) {

                            $compain_criteria3 = \Http::withHeaders([

                                'developer-token' => $google['dev_token'],
                                'login-customer-id' => $google['manager_id'],
                            ])->withToken($google['accsss_token'])->
                            post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/campaignCriteria:mutate', [
                                'operations' => [
                                    'create' => array(
                                        'displayName' => "my campaign criteria $rand",
                                        'campaign' => $compain,
                                        'negative' => true,
                                        "location" => array(
                                            'geoTargetConstant' => $cit
                                        ),
                                    )
                                ]
                            ]);


                        }

                        //      dd(json_decode($compain_criteria3->body()),$compain,$cities);

                        //        dd(json_decode($compain_criteria->body()), json_decode($compain_criteria2->body()));


                        $adgroup = \Http::withHeaders([

                            'developer-token' => $google['dev_token'],
                            'login-customer-id' => $google['manager_id'],
                        ])->withToken($google['accsss_token'])->
                        post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/adGroups:mutate', [
                            'operations' => [
                                'create' => array(
                                    'status' => env('GA_STATUS'),
                                    'name' => "my adgroup $rand",
                                    //  'type' => "SEARCH_DYNAMIC_ADS",
                                    'type' => "SEARCH_STANDARD",
                                    'campaign' => $compain,


                                )
                            ]
                        ]);

                        if ($adgroup->status()) {
                            $adgroup = json_decode($adgroup->body());
                            $adgroup = $adgroup->results[0]->resourceName;


//dd(json_decode($adgroupadd));

                        } else {
                            $adgroup = json_decode($adgroup->body());
                            return 0;// return back()->with('error', isset($adgroup->error->message) ? $adgroup->error->message : 'Something went wrong');


                        }


                    } else {
                        $compain = json_decode($compain->body());

                        return 0;// return back()->with('error', isset($compain->error->message) ? $compain->error->message : 'Something went wrong');

                    }
                } else {
                    $compain_budget = json_decode($compain_budget->body());

                    return 0;// return back()->with('error', isset($compain_budget->error->message) ? $compain_budget->error->message : 'Something went wrong');
                }

                //adgeoup ad


                $advertisement = new Advertisement();
                $advertisement->goal = $adsStep3->goal;
                $advertisement->title = $adsStep3->title;
                $advertisement->user_id = $adsStep3->user_id;

                $advertisement->age2 = $adsStep3->age2;
                $advertisement->gender = $adsStep3->gender;
                $advertisement->per_day = $adsStep3->per_day;

                $advertisement->type = 2;
                $advertisement->step = 5;
                $advertisement->compain_id = $compain;

                $advertisement->cities = json_encode($cities);
                $advertisement->countries = json_encode($countries);

                $advertisement->start_date = $adsStep3->start_date;
                $advertisement->end_date = $adsStep3->end_date;

                $advertisement->save();



                //  dd($advertisement,$countries);



                $ads = AdvertisementAds::where('advertisements_id', $adsStep3->id)->select(
                    '*',
                    DB::raw('sum(clicks + impressions + cpc + conversation) as total'))
                    ->groupBY('id')
                    ->orderBy('total', 'DESC')
                    ->first();


                //update priority
                $adsDetail = AdvertisementDetail::where('advertisements_id', $adsStep3->id)
                    ->where('type', 'button')
                    ->where('url', $ads->url)
                    ->update(['status' => 'final']);
                $detail = AdvertisementDetail::where('advertisements_id', $adsStep3->id)->update(['advertisements_id' => $advertisement->id]);

                $adsStep3->delete();

                $body = AdvertisementDetail::where('advertisements_id', $advertisement->id)->where('type', 'body')->where('status', 'final')->first();
                $heading = AdvertisementDetail::where('advertisements_id', $advertisement->id)->where('type', 'heading')->where('status', 'final')->first();
                $button = AdvertisementDetail::where('advertisements_id', $advertisement->id)->where('type', 'button')->where('status', 'final')->first();
                $headingExp = explode('|', $heading->data);


                //save heading and creating ads
                $check = 0;



                    $adgroupadd = \Http::withHeaders([

                        'developer-token' => $google['dev_token'],
                        'login-customer-id' => $google['manager_id'],
                    ])->withToken($google['accsss_token'])->
                    post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/adGroupAds:mutate', [
                        'operations' => [
                            'create' => array(
                                'status' => env('GA_STATUS'),
                                'adGroup' => $adgroup,
                                "ad" => array(

                                    "expandedTextAd" => array(
                                        'headlinePart1' => $headingExp[0],
                                        'headlinePart2' => $headingExp[1],
                                        'description' => $body->data
                                    ),
                                    "finalUrls" => [$button->url]
                                )

                            )
                        ]
                    ]);

                    if ($adgroupadd->status() == 200) {


                        $advertisementAdds = new AdvertisementAds();
                        $advertisementAdds->advertisements_id = $advertisement->id;
                        $advertisementAdds->heading = $heading->data;

                        $advertisementAdds->body = $body->data;
                        // $advertisementAdds->button = $request->btn[0];
                        $advertisementAdds->url = $button->url;
                        // $advertisementAdds->image = $img1;
                        $advertisementAdds->start_date = Carbon::now();
                        $advertisementAdds->end_date = Carbon::now()->addDays($f);
                        $advertisementAdds->addSet_id = $adgroup;
                        //$advertisementAdds->addCreative_id = $compain_budget;
                        $advertisementAdds->add_id = $adgroupadd;

                        //  dd($compain_id,$addSet_id,$addCreative_id,$add_id);
                        $advertisementAdds->save();

                        $check++;
                    } else {

                        $adgroupadd = json_decode($adgroupadd->body());

                        $advertisement->delete();

                        return 0;// return back()->with('error', isset($adgroupadd->error->message) ? $adgroupadd->error->message : 'Something went wrong');
                    }


                }

            }




    }

}
