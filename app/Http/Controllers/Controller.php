<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\AdvertisementAds;
use App\Models\AdvertisementDetail;
use App\Models\Behaviour;
use App\Models\creditials;
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
        $users=creditials::all();
        foreach ($users as $user)
        {




            $api = \Http::post('https://www.googleapis.com/oauth2/v3/token', [
                'grant_type' => 'refresh_token',
                'client_id' => $user->google_app,
                'client_secret' => $user->google_secret,
                'refresh_token' => $user->google_refresh,
            ]);

            if ($api->status() == 200) {
                $api = json_decode($api->body());

                $user->google_token = $api->access_token;
                $user->update();


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
