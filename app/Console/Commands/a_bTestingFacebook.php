<?php

namespace App\Console\Commands;

use App\Models\Advertisement;
use App\Models\AdvertisementAds;
use App\Models\AdvertisementDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class a_bTestingFacebook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ab:facebook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

//step 1
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

                            //'body'=>$body->data,
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
                    $advertisementAdds->addSet_id = $addSet_id;
                    $advertisementAdds->addCreative_id = $addCreative_id;
                    $advertisementAdds->add_id = $add_id;
                    $advertisementAdds->save();

                }


            }


        }


        //step 2

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

            if ($adsStep2) {
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
                    $advertisementAdds->addSet_id = $addSet_id;
                    $advertisementAdds->addCreative_id = $addCreative_id;
                    $advertisementAdds->add_id = $add_id;
                    $advertisementAdds->save();

                }


            }


        }


        //step 3

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

            if ($adsStep3)
            {
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
                    $advertisementAdds->addSet_id = $addSet_id;
                    $advertisementAdds->addCreative_id = $addCreative_id;
                    $advertisementAdds->add_id = $add_id;
                    $advertisementAdds->save();

                }


            }


        }

        //step 4

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

            if ($adsStep4->end_date>Carbon::now()) {
                $date1=new \DateTime($adsStep4->end_date);
                $date2=new \DateTime(Carbon::now());
                $f=  $date1->diff($date2)->days;
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
                $advertisementAdds->end_date = $advertisement->end_date;
                $advertisementAdds->addSet_id = $addSet_id;
                $advertisementAdds->addCreative_id = $addCreative_id;
                $advertisementAdds->add_id = $add_id;
                $advertisementAdds->save();


       }
// else {
//                //update end time of add
//
//                AdvertisementAds::where('advertisements_id', $adsStep4->id)->update([
//                    'end_date' => Carbon::now()->addDays(3)
//                ]);
//            }


        }


    }
}
