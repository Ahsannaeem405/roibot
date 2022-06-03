<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\AdvertisementAds;
use App\Models\AdvertisementDetail;
use App\Models\Behaviour;
use App\Models\Demographics;
use App\Models\insightDetail;
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
use Intervention\Image\ImageManagerStatic;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function insightFB()
    {
        $ads = AdvertisementAds::where('add_id', '!=', null)->get();
        foreach ($ads as $ad) {
            $insight = \Http::get('https://graph.facebook.com/v13.0/' . $ad->add_id . '/insights', [
                "date_preset" => "maximum",
                "fields" => 'impressions,clicks,cpc,reach',
                'access_token' => $ad->compain->user->fb_token,

            ]);
            $insight = json_decode($insight->body());
            // dd($insight);
            if (count($insight->data) >= 1) {

                $ad->clicks = intval($insight->data[0]->clicks);
                $ad->impressions = intval($insight->data[0]->impressions);
                $ad->cpc = intval($insight->data[0]->cpc);
                $ad->conversation = intval($insight->data[0]->reach);
                $ad->update();

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
            'class' => 'interests',
            'access_token' => $facebook['fb_token'],

        ]);
        $data = json_decode($interest->body());
        $data = collect($data->data);
        //  $data=$data->where('name','Sports');
        //  dd($data);

        Intrests::truncate();
        foreach ($data as $intrest) {
            $length = count($intrest->path);
            $total = 1;
            foreach ($intrest->path as $path) {
                $find = Intrests::where('name', $path)->first();
                if ($total == 1) {
                    if (!$find) {
                        $ins = new Intrests();
                        $ins->name = $path;
                        // $ins->data_id=intval($intrest->id);
                        $ins->save();
                        $prev = $ins->id;
                    } else {
                        $prev = $find->id;
                    }

                } else {

                    if (!$find) {
                        $rec = $data->where('name', "$path")->first();
                        if ($rec) {
                            // dd($path);

                            $ins = new Intrests();
                            $ins->name = $path;
                            // dd($rec);
                            $ins->data_id = intval($rec->id);
                            $ins->parent = intval($prev);
                            $ins->save();

                            $prev = $ins->id;
                        }
//                       else{
//                           echo $path.',';
//                       }
                    } else {

                        $prev = $find->id;

                    }
                }


                $total = $total + 1;


            }

        }

    }

    public function behaviour()
    {
        $facebook = config()->get('services.facebook');


        $behaviour = \Http::get('https://graph.facebook.com/v13.0/search', [

            'type' => 'adTargetingCategory',
            //   'limit'=>500,
            'class' => 'behaviors',
            'access_token' => $facebook['fb_token'],

        ]);
        $data = json_decode($behaviour->body());
        $data = collect($data->data);
        //$data=$data->where('name','Home and garden');
        //  dd($data);

        Behaviour::truncate();
        foreach ($data as $behaviour) {
            $length = count($behaviour->path);
            $total = 1;
            foreach ($behaviour->path as $path) {
                $find = Behaviour::where('name', $path)->first();
                if ($total == 1) {
                    if (!$find) {
                        $ins = new Behaviour();
                        $ins->name = $path;
                        $ins->data_id = intval($behaviour->id);
                        $ins->save();
                        $prev = $ins->id;
                    } else {
                        $prev = $find->id;
                    }

                } else {

                    if (!$find) {
                        $rec = $data->where('name', "$path")->first();
                        if ($rec) {
                            // dd($path);
                            $ins = new Behaviour();
                            $ins->name = $path;
                            // dd($rec);
                            $ins->data_id = intval($rec->id);
                            $ins->parent = intval($prev);
                            $ins->save();

                            $prev = $ins->id;
                        }

                    } else {

                        $prev = $find->id;

                    }
                }


                $total = $total + 1;


            }

        }

    }

    public function dempgraphics()
    {

        $facebook = config()->get('services.facebook');


        $demographics = \Http::get('https://graph.facebook.com/v13.0/search', [

            'type' => 'adTargetingCategory',
            'limit' => 500,
            'class' => 'demographics',
            'access_token' => $facebook['fb_token'],

        ]);


        $data = json_decode($demographics->body());
        //   dd($data);
        $data = collect($data->data);

        $data = $data->groupBy('type');
        //dd($data);

        Demographics::truncate();
        foreach ($data as $key => $demographics) {


            $ins = new Demographics();
            $ins->name = str_replace("_", ' ', "$key");
            $ins->type = $key;
            $ins->save();

            $prev = $ins->id;

            foreach ($demographics as $path) {

                $find = Demographics::where('name', $path->name)->first();


                if (!$find) {


                    // dd($path);

                    $ins = new Demographics();
                    $ins->name = $path->name;
                    // dd($rec);
                    $ins->data_id = intval($path->id);
                    $ins->parent = intval($prev);
                    $ins->type = $path->type;
                    $ins->save();


                }


            }

        }
    }

    public function data()
    {



        //step 4
        $adsStep4 = Advertisement::whereHas('activeAdd', function ($q) {
            $q->where('end_date', '<', Carbon::now());
        })
            ->where('status', 'pending')
            ->where('step', 4)
            ->where('type', 2)
            ->where('goal', 'DISPLAY2')
            ->get();
       // dd($adsStep2);

        foreach ($adsStep4 as $adsStep4) {


            $date1 = new \DateTime($adsStep4->end_date);
            $date2 = new \DateTime(Carbon::now());
            $f = $date1->diff($date2)->days;
            $f = $f >= 1 ? $f : 3;


            $rand = rand(1111, 9999);
            $budget = intval($adsStep4->per_day) * 1000000;

            $user = User::find($adsStep4->user_id);
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
                $radius = $adsStep4->radius;
                $cities = array();
                $countries = array();
                $intrest = array();
                $behaviour = array();
                $life_events = $family_statuses = $industries = $income = array();
                if ($adsStep4->cities) {

                    foreach ($adsStep4->cities as $city) {

                        $cities[] = $city;
                    }

                }


                if ($adsStep4->countries) {

                    foreach ($adsStep4->countries as $contry) {

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
                                'advertisingChannelType' => 'DISPLAY',
                                "geoTargetTypeSetting" => array(
                                    "positiveGeoTargetType" => 'PRESENCE_OR_INTEREST',
                                    "negativeGeoTargetType" => 'PRESENCE_OR_INTEREST',
                                ),

                                'name' => "$adsStep4->title $rand",
                                'campaignBudget' => $compain_budget,

                                'targetSpend' => array(
                                    'cpcBidCeilingMicros' => $adsStep4->target
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
                                        'type' => $adsStep4->age2
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
                                        'type' => $adsStep4->gender
                                    ),


                                )
                            ]
                        ]);


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
                                    "keyword" => array(
                                        'matchType' => 'PHRASE',
                                        'text' => $adsStep4->keywords
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
                                    'type' => 'DISPLAY_STANDARD',
                                    'campaign' => $compain,


                                )
                            ]
                        ]);

                        if ($adgroup->status()==200) {
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
                $advertisement->goal = $adsStep4->goal;
                $advertisement->dimentions = $adsStep4->dimentions;
                $advertisement->business = $adsStep4->business;
                $advertisement->keywords = $adsStep4->keywords;
                $advertisement->title = $adsStep4->title;
                $advertisement->target = $adsStep4->target;
                $advertisement->user_id = $adsStep4->user_id;

                $advertisement->age2 = $adsStep4->age2;
                $advertisement->gender = $adsStep4->gender;
                $advertisement->per_day = $adsStep4->per_day;

                $advertisement->type = 2;
                $advertisement->step = 5;
                $advertisement->compain_id = $compain;

                $advertisement->cities = json_encode($cities);
                $advertisement->countries = json_encode($countries);

                $advertisement->start_date = $adsStep4->start_date;
                $advertisement->end_date = $adsStep4->end_date;

                $advertisement->save();


                //  dd($advertisement,$countries);


                $ads = AdvertisementAds::where('advertisements_id', $adsStep4->id)->select(
                    '*',
                    DB::raw('sum(clicks + impressions) as total'))
                    ->groupBY('id')
                    ->orderBy('total', 'DESC')
                    ->first();


                //update priority
                $adsDetail = AdvertisementDetail::where('advertisements_id', $adsStep4->id)
                    ->where('type', 'image')
                    ->where('data', $ads->image)
                    ->update(['status' => 'final']);
                $detail = AdvertisementDetail::where('advertisements_id', $adsStep4->id)->update(['advertisements_id' => $advertisement->id]);

                $adsStep4->delete();


            //  var_dump($adsStep1->business);
                $image = AdvertisementDetail::where('advertisements_id', $advertisement->id)->where('type', 'image')->where('status', 'final')->first();
                $button = AdvertisementDetail::where('advertisements_id', $advertisement->id)->where('type', 'button')->where('status', 'final')->first();
                $body = AdvertisementDetail::where('advertisements_id', $advertisement->id)->where('type', 'body')->where('status', 'final')->first();
                $heading = AdvertisementDetail::where('advertisements_id', $advertisement->id)->where('type', 'heading')->where('status', 'final')->first();

                //save heading and creating ads
                $check = 0;
                //    $dimentions=explode(' x ',$advertisement->dimentions);
                // dd($dimentions);


                    $images = $image->data;
                    $img1 = public_path('images/gallary/') . $images . '';

                    $imgp = ImageManagerStatic::make($img1);
                    $imgp->resize(600, 314);
                    $imgp->save(public_path('images/gallary/resize/') . $images . '');

                    $img = 'images/gallary/resize/' . $images . '';
                    $imgContent = file_get_contents($img);
                    $imgType = pathinfo($img, PATHINFO_EXTENSION);
                    $imageData = base64_encode($imgContent);


                    $img1 = public_path('images/gallary/') . $images . '';

                    $imgp = ImageManagerStatic::make($img1);
                    $imgp->resize(300, 300);
                    $imgp->save(public_path('images/gallary/resize/' ). $images . '');

                    $img = public_path('images/gallary/resize/') . $images . '';
                    $imgContent = file_get_contents($img);
                    $imgType = pathinfo($img, PATHINFO_EXTENSION);
                    $imageData2 = base64_encode($imgContent);

//marketing image
                    $asset = \Http::withHeaders([

                        'developer-token' => $google['dev_token'],
                        'login-customer-id' => $google['manager_id'],
                    ])->withToken($google['accsss_token'])->
                    post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/assets:mutate', [
                        'operations' => [
                            'create' => array(

                                "name" => $images,
                                "imageAsset" => array(

                                    'mimeType' => 'IMAGE_PNG',
                                    'data' => $imageData,

                                )

                            )
                        ]
                    ]);

//squre image
                    $asset2 = \Http::withHeaders([

                        'developer-token' => $google['dev_token'],
                        'login-customer-id' => $google['manager_id'],
                    ])->withToken($google['accsss_token'])->
                    post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/assets:mutate', [
                        'operations' => [
                            'create' => array(

                                "name" => $images,
                                "imageAsset" => array(

                                    'mimeType' => 'IMAGE_PNG',
                                    'data' => $imageData2,

                                )

                            )
                        ]
                    ]);

                    $asset = json_decode($asset->body());
                    $asset = $asset->results[0]->resourceName;
                    $asset2 = json_decode($asset2->body());
                    $asset2 = $asset2->results[0]->resourceName;


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

                                    "responsiveDisplayAd" => array(

                                        "marketingImages" => [
                                            array('asset' => $asset),
                                        ],

                                        "squareMarketingImages" => [
                                            array('asset' => $asset2),
                                        ],

                                        'headlines' => [
                                            array(
                                                "text" => $heading->data
                                            )
                                        ],
                                        'longHeadline' => array('text' => $heading->data2),
                                        'descriptions' => [
                                            array(
                                                "text" => $body->data
                                            )
                                        ],
                                        // 'data' => $imageData,
                                        'businessName' => $advertisement->business,

                                    ),

//                                'name' => $images,
                                    "finalUrls" => [$button->url],
//                                "displayUrl" => $request->url[0]

                                )

                            )
                        ]
                    ]);


                    //  dd(json_decode($adgroupadd->body()));

                    unlink(public_path('images/gallary/resize/') . $images . '');
                    //dd(json_decode($adgroupadd->body()));


                    if ($adgroupadd->status() == 200) {


                        $advertisementAdds = new AdvertisementAds();
                        $advertisementAdds->advertisements_id = $advertisement->id;

                        $advertisementAdds->url = $button->url;
                        $advertisementAdds->image = $image->data;
                        $advertisementAdds->body = $body->data;
                        $advertisementAdds->heading = $heading->data;
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

    public function data2()
    {

        $adv = Advertisement::whereHas('activeAdd', function ($q) {
            //  $q->where('end_date', '<', Carbon::now());
        })
            ->where('status', 'pending')
            ->where('type', 2)
            ->get();


        foreach ($adv as $advs) {

            $adsStep1 = $advs->activeAdd;


            $rand = rand(1111, 9999);


            $user = User::find($advs->user_id);
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


                foreach ($adsStep1 as $adsStep1) {
                    $ad = json_decode($adsStep1->add_id);
                    $ad = $ad->results[0]->resourceName;
                    // dd($ad);


                    $insight = \Http::withHeaders([

                        'developer-token' => $google['dev_token'],
                        'login-customer-id' => $google['manager_id'],
                    ])->withToken($google['accsss_token'])->
                    post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/googleAds:search', [

                        "query" => "SELECT
    metrics.clicks,
    metrics.impressions,
    metrics.ctr,
    metrics.average_cpc,
    metrics.cost_micros
  FROM ad_group_ad
  where  ad_group_ad.resource_name='$ad'"
                    ]);
                    if ($insight->status()==200)
                    {


                        $res = json_decode($insight->body());

                        $adsStep1->cpc = isset($res->results[0]->metrics->averageCpc) ? $res->results[0]->metrics->averageCpc : 0;
                        $adsStep1->clicks = intval($res->results[0]->metrics->clicks);
                        $adsStep1->impressions = intval($res->results[0]->metrics->impressions);
                        $adsStep1->total= intval($adsStep1->clicks+  $adsStep1->impressions);
                        $adsStep1->update();

                        $ins_detail=insightDetail::updateOrCreate(
                            ['add_id'=>$adsStep1->id,'date'=>Carbon::now()->format('Y-m-d')],
                            ['cpc'=>isset($res->results[0]->metrics->averageCpc) ? $res->results[0]->metrics->averageCpc : 0],
                            ['impressions'=> intval($res->results[0]->metrics->impressions)],
                            ['clicks'=>intval($res->results[0]->metrics->clicks)],

                        );

                    }
                }


            }
        }


    }
}
