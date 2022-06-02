<?php

namespace App\Console\Commands;

use App\Models\Advertisement;
use App\Models\AdvertisementAds;
use App\Models\AdvertisementDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic;

class a_bTestingGoogleResponsiveImageAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ab:googleResponsiveImage';

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
            ->where('type', 2)
            ->where('goal', 'DISPLAY2')
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
                                'advertisingChannelType' => 'DISPLAY',
                                "geoTargetTypeSetting" => array(
                                    "positiveGeoTargetType" => 'PRESENCE_OR_INTEREST',
                                    "negativeGeoTargetType" => 'PRESENCE_OR_INTEREST',
                                ),

                                'name' => "$adsStep1->title $rand",
                                'campaignBudget' => $compain_budget,

                                'targetSpend' => array(
                                    'cpcBidCeilingMicros' => $adsStep1->target
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
                                        'text' => $adsStep1->keywords
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
                $advertisement->goal = $adsStep1->goal;
                $advertisement->dimentions = $adsStep1->dimentions;
                $advertisement->business = $adsStep1->business;
                $advertisement->keywords = $adsStep1->keywords;
                $advertisement->title = $adsStep1->title;
                $advertisement->target = $adsStep1->target;
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
                    DB::raw('sum(clicks + impressions) as total'))
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

                $image = AdvertisementDetail::where('advertisements_id', $advertisement->id)->where('type', 'image')->get();
                $button = AdvertisementDetail::where('advertisements_id', $advertisement->id)->where('type', 'button')->get();
                $body = AdvertisementDetail::where('advertisements_id', $advertisement->id)->where('type', 'body')->get();
                $heading = AdvertisementDetail::where('advertisements_id', $advertisement->id)->where('type', 'heading')->where('status', 'final')->first();

                //save heading and creating ads
                $check = 0;
                //    $dimentions=explode(' x ',$advertisement->dimentions);
                // dd($dimentions);
                foreach ($body as $body) {

                    $images = $image[0]->data;
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
                                        'businessName' => $adsStep1->business,

                                    ),

//                                'name' => $images,
                                    "finalUrls" => [$button[0]->url],
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

                        $advertisementAdds->url = $button[0]->url;
                        $advertisementAdds->image = $image[0]->data;
                        $advertisementAdds->body = $body->data;
                        $advertisementAdds->heading = $heading->data;
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
    }
}
