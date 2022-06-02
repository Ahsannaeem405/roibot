<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\AdvertisementAds;
use App\Models\AdvertisementDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use FacebookAds\Object\AdImage;
use FacebookAds\Object\Fields\AdImageFields;
use Intervention\Image\Image;
use Intervention\Image\ImageManagerStatic;

class AdvertisementController extends Controller
{
    public function PostAdd(Request $request)
    {
        $facebook = config()->get('services.facebook');


        if (!$request->countries) {
            return back()->with('error', 'Please add a Country');
        }

        if (!$request->image) {
            return back()->with('error', 'Please add a image');
        }


        $i = 0;
        $img1 = $hash1 = null;
        $radius = $request->radius;
        $cities = array();
        $countries = array();
        $intrest = array();
        $behaviour = array();
        $life_events = $family_statuses = $industries = $income = array();
        if ($request->city) {

            foreach ($request->city as $city) {
                $cities[] = array(
                    'key' => $city,
                    'radius' => $radius,
                    'distance_unit' => 'mile',
                );
            }

        }
        //  dd($cities);
        if ($request->interest) {
            foreach ($request->interest as $int) {
                $data = explode(',', $int);
                $intrest[] = array(
                    'id' => $data[0],
                    'name' => $data[1],
                );
            }
        }
        if ($request->countries) {

            foreach ($request->countries as $contry) {
                $data = explode(',', $contry);

                $countries[] = $data[0];
            }

        }
        if ($request->behaviour) {
            foreach ($request->behaviour as $beh) {
                $data = explode(',', $beh);
                $behaviour[] = array(
                    'id' => $data[0],
                    'name' => $data[1],
                );
            }

        }
        if ($request->life_events) {

            foreach ($request->life_events as $demo) {
                $data = explode(',', $demo);
                $life_events[] = array(
                    'id' => $data[0],
                    'name' => $data[1],
                );
            }

        }

        if ($request->family_statuses) {

            foreach ($request->family_statuses as $demo) {
                $data = explode(',', $demo);
                $family_statuses[] = array(
                    'id' => $data[0],
                    'name' => $data[1],
                );
            }

        }

        if ($request->industries) {

            foreach ($request->industries as $demo) {
                $data = explode(',', $demo);
                $industries[] = array(
                    'id' => $data[0],
                    'name' => $data[1],
                );
            }

        }

        if ($request->income) {

            foreach ($request->income as $demo) {
                $data = explode(',', $demo);
                $income[] = array(
                    'id' => $data[0],
                    'name' => $data[1],
                );
            }

        }


        //saving compain facebook

        if ($request->advert_type == 1) {
            $compain = \Http::post('https://graph.facebook.com/v13.0/act_' . $facebook['fb_account'] . '/campaigns', [
                'name' => $request->title,
                'objective' => $request->goal,
                'status' => env('FB_STATUS'),
                'special_ad_categories' => [],
                'access_token' => $facebook['fb_token'],
            ]);
            if ($compain->status() == 200) {
                $compain = json_decode($compain->body());

                $compain_id = $compain->id;

            } else {
                $compain = json_decode($compain->body());
                // dd($compain);
                return back()->with('error', isset($compain->error->error_user_msg) ? $compain->error->error_user_msg : $compain->error->message);
            }
        } else {
            //saving compain google
            $compain_id = 0;
        }


        $advertisement = new Advertisement();
        $advertisement->goal = $request->goal;
        $advertisement->title = $request->title;
        $advertisement->user_id = \Auth::user()->id;

        $advertisement->age = $request->age;
        $advertisement->gender = $request->gender;
        $advertisement->per_day = $request->perday_budget;

        $advertisement->type = $request->advert_type;
        $advertisement->compain_id = $compain_id;

        $advertisement->cities = json_encode($cities);
        $advertisement->countries = json_encode($countries);
        $advertisement->interest = json_encode($intrest);
        $advertisement->life_events = json_encode($life_events);
        $advertisement->family_statuses = json_encode($family_statuses);
        $advertisement->industries = json_encode($industries);
        $advertisement->income = json_encode($income);
        $advertisement->behaviour = json_encode($behaviour);
        $advertisement->start_date = $request->start_date;
        $advertisement->end_date = $request->end_date;


        $advertisement->save();
        //  dd($advertisement,$countries);


        //save body
        foreach ($request->body as $body) {
            $advertisementDetail = new AdvertisementDetail();
            $advertisementDetail->data = $body;
            $advertisementDetail->advertisements_id = $advertisement->id;
            $advertisementDetail->type = 'body';
            $advertisementDetail->save();


        }


        //save button and url
        for ($j = 0; $j < count($request->btn); $j++) {
            $advertisementDetail = new AdvertisementDetail();
            $advertisementDetail->data = $request->btn[$j];
            $advertisementDetail->url = $request->url[$j];
            $advertisementDetail->advertisements_id = $advertisement->id;
            $advertisementDetail->type = 'button';
            $advertisementDetail->save();

        }


        //save images
        foreach ($request->image as $image) {

            $advertisementDetail = new AdvertisementDetail();


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://graph.facebook.com/v13.0/act_' . $facebook['fb_account'] . '/adimages',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('filename' => new \CURLFile('images/gallary/' . $image . '')),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $facebook['fb_token'] . ''
                ),
            ));


            $addImage = json_decode(curl_exec($curl));

            curl_close($curl);

//dd($addImage,$image);

            if ($i == 0) {
                $img1 = $image;

                $img1 = $image;
                $hash1 = $addImage->images->$image->hash;

                $i = 1;
            }

            $advertisementDetail->data = $image;
            $advertisementDetail->hash = $addImage->images->$image->hash;
            $advertisementDetail->advertisements_id = $advertisement->id;
            $advertisementDetail->type = 'image';
            $advertisementDetail->save();

        }


        //save heading and creating ads

        foreach ($request->heading as $heading) {

            //creating add facebook
            if ($request->advert_type == 1) {
//creating addset
                $addSet = \Http::post('https://graph.facebook.com/v13.0/act_' . $facebook['fb_account'] . '/adsets', [
                    'campaign_id' => $compain_id,
                    'name' => $heading,
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
                if ($addSet->status() == 200) {

                    $addSet = json_decode($addSet->body());
                    //    dd($addSet);
                    $addSet_id = $addSet->id;


                    //creating addCreative

                    $adCreative = \Http::post('https://graph.facebook.com/v13.0/act_' . $facebook['fb_account'] . '/adcreatives', [

                        // 'body' => $request->body[0],
                        'object_story_spec' => [
                            'link_data' => [
                                'name' => $heading,
                                'image_hash' => $hash1,
                                'link' => $request->url[0],
                                'message' => $request->body[0],
                                "call_to_action" => [
                                    'type' => $request->btn[0],
                                ]
                            ],
                            'page_id' => $facebook['page_id']
                        ],

                        'access_token' => $facebook['fb_token'],
                    ]);
                    if ($adCreative->status() == 200) {
                        $adCreative = json_decode($adCreative->body());
                        $addCreative_id = $adCreative->id;

                        //creating add

                        $add = \Http::post('https://graph.facebook.com/v13.0/act_' . $facebook['fb_account'] . '/ads', [
                            'name' => $heading,
                            'adset_id' => $addSet_id,
                            'creative' => [
                                'creative_id' => $addCreative_id,
                            ],
                            'status' => env('FB_STATUS'),

                            'access_token' => $facebook['fb_token'],
                        ]);
                        if ($add->status() == 200) {
                            $add = json_decode($add->body());
                            $add_id = $add->id;

                        } else {
                            $this->deleteCompain($advertisement->id);
                            $add = json_decode($add->body());
                            return back()->with('error', isset($add->error->error_user_msg) ? $add->error->error_user_msg : $add->error->message);
                        }


                    } else {

                        $this->deleteCompain($advertisement->id);
                        $adCreative = json_decode($adCreative->body());
                        dd($adCreative);
                        return back()->with('error', isset($adCreative->error->error_user_msg) ? $adCreative->error->error_user_msg : $adCreative->error->message);
                    }


                } else {


                    $this->deleteCompain($advertisement->id);
                    // $advertisement->delete();


                    $addSet = json_decode($addSet->body());
                    //  dd($addSet);

                    return back()->with('error', isset($addSet->error->error_user_msg) ? $addSet->error->error_user_msg : $addSet->error->message);
                }


            } else {
                //creating add facebook google
            }


            $advertisementDetail = new AdvertisementDetail();
            $advertisementDetail->data = $heading;
            $advertisementDetail->advertisements_id = $advertisement->id;
            $advertisementDetail->type = 'heading';
            $advertisementDetail->save();

            $advertisementAdds = new AdvertisementAds();
            $advertisementAdds->advertisements_id = $advertisement->id;
            $advertisementAdds->heading = $heading;
            $advertisementAdds->body = $request->body[0];
            $advertisementAdds->button = $request->btn[0];
            $advertisementAdds->url = $request->url[0];
            $advertisementAdds->image = $img1;
            $advertisementAdds->start_date = Carbon::now();
            $advertisementAdds->end_date = Carbon::now()->addDays(3);
            $advertisementAdds->addSet_id = $addSet_id;
            $advertisementAdds->addCreative_id = $addCreative_id;
            $advertisementAdds->add_id = $add_id;

            //  dd($compain_id,$addSet_id,$addCreative_id,$add_id);
            $advertisementAdds->save();


        }

        //    dd($adCreative);
        return redirect('manage_view')->with('success', 'Add created successfully');
    }


    public function PostAddGoogle(Request $request)
    {
//dd($request->header());

        $chanel = $request->chanel;
        $chanel = $request->chanel == 'DISPLAY2' ? 'DISPLAY' : $chanel;

        $rand = rand(1111, 9999);
        $budget = intval($request->perday_budget) * 1000000;
        $google = config()->get('services.google');


        if (!$request->city) {
            return back()->with('error', 'Please add a Country');
        }

        if ($request->chanel != 'SEARCH') {
            if (!$request->image) {
                return back()->with('error', 'Please add a image');
            }
        }


        $i = 0;
        $img1 = $hash1 = null;
        $radius = $request->radius;
        $cities = array();
        $countries = array();

        $intrest = array();
        $behaviour = array();
        $life_events = $family_statuses = $industries = $income = array();
        if ($request->city) {

            foreach ($request->city as $city) {
                $cities[] = $city;
            }

        }
        if ($request->countries) {

            foreach ($request->countries as $contry) {

                $countries[] = $contry;
            }

        }

//        if ($request->bidding == 'targetCpa') {
//
//            $rec = array(
//                'name' => "my bidding $rand",
//                'targetCpa' => array(
//                    'targetCpaMicros' => 5 *1000000,
//                    'cpcBidCeilingMicros' => intval($request->first) *1000000,
//                    'cpcBidFloorMicros' =>intval( $request->first) *1000000,
//                )
//            );
//        }
//        else{
//            $rec = array(
//                'name' => "my bidding $rand",
//                'targetRoas' => array(
//                    'targetRoas' => $request->target2,
//                    'cpcBidCeilingMicros' => $request->first2 *1000000,
//                    'cpcBidFloorMicros' => $request->first2 *1000000,
//                )
//            );
//        }




//        $bidding = \Http::withHeaders([
//
//            'developer-token' => $google['dev_token'],
//            'login-customer-id' => $google['manager_id'],
//        ])->withToken($google['accsss_token'])->
//        post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/biddingStrategies:mutate', [
//            'operations' => [
//                'create' => $rec
//
//            ]
//        ]);
//
//        if ($bidding->status()==200)
//        {
//            $bidding=json_decode($bidding->body());
//            $bidding=$bidding->results[0]->resourceName;
//        }
//        else{
//            $bidding = json_decode($bidding->body());
//            return back()->with('error', isset($bidding->error->message) ? $bidding->error->message : 'Something went wrong');
//
//        }



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
                        'advertisingChannelType' => $chanel,
                        "geoTargetTypeSetting" => array(
                            "positiveGeoTargetType" => 'PRESENCE_OR_INTEREST',
                            "negativeGeoTargetType" => 'PRESENCE_OR_INTEREST',
                        ),

                       // 'biddingStrategyType'=>'TARGET_CPA',
                        'name' => "$request->title $rand",
                        'campaignBudget' => $compain_budget,
                       // 'biddingStrategy'=>$bidding,

//                        'targetCpa'=>array(
//                            'targetCpaMicros'=>5 * 1000000,
//                           // 'cpcBidCeilingMicros'=>5 * 1000000,
//
//                        ),
//
                        'targetSpend' => array(
                            'cpcBidCeilingMicros' => $request->target * 1000000,
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
                                'type' => $request->age
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
                                'type' => $request->gender
                            ),


                        )
                    ]
                ]);


                if ($request->keywords) {

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
                                    'text' => $request->keywords
                                ),


                            )
                        ]
                    ]);
                    //dd(json_decode($compain_criteria3->body()));
                }


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
                            'type' => $chanel . '_STANDARD',
                            'campaign' => $compain,


                        )
                    ]
                ]);
                if ($adgroup->status() ==200) {
                    $adgroup = json_decode($adgroup->body());
                    $adgroup = $adgroup->results[0]->resourceName;


//dd(json_decode($adgroupadd));

                } else {
                    $adgroup = json_decode($adgroup->body());
                  //  dd($adgroup);
                    return back()->with('error', isset($adgroup->error->message) ? $adgroup->error->message : 'Something went wrong');


                }


            } else {
                $compain = json_decode($compain->body());
             //   dd($compain);

                return back()->with('error', isset($compain->error->message) ? $compain->error->message : 'Something went wrong');

            }
        } else {
            $compain_budget = json_decode($compain_budget->body());

            return back()->with('error', isset($compain_budget->error->message) ? $compain_budget->error->message : 'Something went wrong');
        }

        //adgeoup ad


        $advertisement = new Advertisement();
        $advertisement->goal = $request->chanel;
        $advertisement->target = $request->target;
        $advertisement->dimentions = $request->dimentions;
        $advertisement->title = $request->title;
        $advertisement->business = $request->business;
        $advertisement->keywords = $request->keywords;
        $advertisement->user_id = \Auth::user()->id;

        $advertisement->age2 = $request->age;
        $advertisement->gender = $request->gender;
        $advertisement->per_day = $request->perday_budget;

        $advertisement->type = 2;
        $advertisement->compain_id = $compain;

        $advertisement->cities = json_encode($cities);
        $advertisement->countries = json_encode($countries);
        $advertisement->start_date = $request->start_date;
        $advertisement->end_date = $request->end_date;


        $advertisement->save();
        //  dd($advertisement,$countries);


        //save body
        if ($request->chanel == 'SEARCH' || $request->chanel == 'DISPLAY2') {

            foreach ($request->body as $body) {
                $advertisementDetail = new AdvertisementDetail();
                $advertisementDetail->data = $body;
                $advertisementDetail->advertisements_id = $advertisement->id;
                $advertisementDetail->type = 'body';
                $advertisementDetail->save();


            }
        }


        //save button and url
        for ($j = 0; $j < count($request->url); $j++) {
            $advertisementDetail = new AdvertisementDetail();
            // $advertisementDetail->data = $request->btn[$j];
            $advertisementDetail->url = $request->url[$j];
            $advertisementDetail->advertisements_id = $advertisement->id;
            $advertisementDetail->type = 'button';
            $advertisementDetail->save();
        }


        //save heading and creating ads
        $check = 0;

        if ($request->chanel == 'SEARCH') {
            foreach ($request->heading as $heading) {


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
                                    'headlinePart1' => $heading,
                                    'headlinePart2' => $request->heading2[$check],
                                    'description' => $request->body[0]
                                ),
                                "finalUrls" => [$request->url[0]]
                            )

                        )
                    ]
                ]);

                if ($adgroupadd->status() == 200) {
                    $advertisementDetail = new AdvertisementDetail();
                    $advertisementDetail->data = $heading . ' | ' . $request->heading2[$check];
                    $advertisementDetail->advertisements_id = $advertisement->id;
                    $advertisementDetail->type = 'heading';
                    $advertisementDetail->save();

                    $advertisementAdds = new AdvertisementAds();
                    $advertisementAdds->advertisements_id = $advertisement->id;
                    $advertisementAdds->heading = $heading . ' | ' . $request->heading2[$check];

                    $advertisementAdds->body = $request->body[0];
                    // $advertisementAdds->button = $request->btn[0];
                    $advertisementAdds->url = $request->url[0];
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
                    $advertisement->delete();
                    $adgroupadd = json_decode($adgroupadd->body());

                    return back()->with('error', isset($adgroupadd->error->message) ? $adgroupadd->error->message : 'Something went wrong');
                }


            }
        }
        if ($request->chanel == 'DISPLAY') {

            $dimentions = explode(' x ', $request->dimentions);

            foreach ($request->image as $images) {
                $img1 = 'images/gallary/' . $images . '';

                $imgp = ImageManagerStatic::make($img1);
                $imgp->resize($dimentions[0], $dimentions[1]);
                $imgp->save('images/gallary/resize/' . $images . '');

                $img = 'images/gallary/resize/' . $images . '';
                $imgContent = file_get_contents($img);
                $imgType = pathinfo($img, PATHINFO_EXTENSION);
                $imageData = base64_encode($imgContent);


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

                                "imageAd" => array(


                                    'mimeType' => 'IMAGE_PNG',
                                    'imageUrl' => $request->url[0],
                                    'data' => $imageData,


                                ),
                                'name' => $images,
                                "finalUrls" => [$request->url[0]],
                                "displayUrl" => $request->url[0]

                            )

                        )
                    ]
                ]);

                unlink('images/gallary/resize/' . $images . '');
                //dd(json_decode($adgroupadd->body()));

                if ($adgroupadd->status() == 200) {
                    $advertisementDetail = new AdvertisementDetail();
                    $advertisementDetail->data = $images;
                    $advertisementDetail->advertisements_id = $advertisement->id;
                    $advertisementDetail->type = 'image';
                    $advertisementDetail->save();

                    $advertisementAdds = new AdvertisementAds();
                    $advertisementAdds->advertisements_id = $advertisement->id;
                    //$advertisementAdds->heading = $heading . ' | ' . $request->heading2[$check];

                    //  $advertisementAdds->body = $request->body[0];
                    // $advertisementAdds->button = $request->btn[0];
                    $advertisementAdds->url = $request->url[0];
                    $advertisementAdds->image = $images;
                    $advertisementAdds->start_date = Carbon::now();
                    $advertisementAdds->end_date = Carbon::now()->addDays(3);
                    $advertisementAdds->addSet_id = $adgroup;
                    //$advertisementAdds->addCreative_id = $compain_budget;
                    $advertisementAdds->add_id = $adgroupadd;

                    //  dd($compain_id,$addSet_id,$addCreative_id,$add_id);
                    $advertisementAdds->save();

                    $check++;
                } else {
                    $advertisement->delete();
                    $adgroupadd = json_decode($adgroupadd->body());

                    return back()->with('error', isset($adgroupadd->error->message) ? $adgroupadd->error->message : 'Something went wrong');
                }


            }

        }
        if ($request->chanel == 'DISPLAY2') {


            foreach ($request->image as $imgdata)
            {
                $advertisementDetail = new AdvertisementDetail();
                $advertisementDetail->data = $imgdata;
                $advertisementDetail->advertisements_id = $advertisement->id;
                $advertisementDetail->type = 'image';
                $advertisementDetail->save();

            }
            foreach ($request->heading as $heading) {


                $images = $request->image[0];
                $img1 = 'images/gallary/' . $images . '';

                $imgp = ImageManagerStatic::make($img1);
                $imgp->resize(600, 314);
                $imgp->save('images/gallary/resize/' . $images . '');

                $img = 'images/gallary/resize/' . $images . '';
                $imgContent = file_get_contents($img);
                $imgType = pathinfo($img, PATHINFO_EXTENSION);
                $imageData = base64_encode($imgContent);


                $img1 = 'images/gallary/' . $images . '';

                $imgp = ImageManagerStatic::make($img1);
                $imgp->resize(300, 300);
                $imgp->save('images/gallary/resize/' . $images . '');

                $img = 'images/gallary/resize/' . $images . '';
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
                                            "text" => $heading
                                        )
                                    ],
                                    'longHeadline' => array('text' => $request->long[$check]),
                                    'descriptions' => [
                                        array(
                                            "text" => $request->body[0]
                                        )
                                    ],
                                    // 'data' => $imageData,
                                    'businessName' => $request->business,

                                ),

//                                'name' => $images,
                                "finalUrls" => [$request->url[0]],
//                                "displayUrl" => $request->url[0]

                            )

                        )
                    ]
                ]);


                //  dd(json_decode($adgroupadd->body()));

                unlink('images/gallary/resize/' . $images . '');
                //dd(json_decode($adgroupadd->body()));

                if ($adgroupadd->status() == 200) {
                    $advertisementDetail = new AdvertisementDetail();
                    $advertisementDetail->data = $heading;
                    $advertisementDetail->data2 = $request->long[$check];
                    $advertisementDetail->advertisements_id = $advertisement->id;
                    $advertisementDetail->type = 'heading';
                    $advertisementDetail->save();

                    $advertisementAdds = new AdvertisementAds();
                    $advertisementAdds->advertisements_id = $advertisement->id;
                    $advertisementAdds->heading = $heading;

                    $advertisementAdds->body = $request->body[0];
                    //  $advertisementAdds->button = $request->btn[0];
                    $advertisementAdds->url = $request->url[0];
                    $advertisementAdds->image = $images;
                    $advertisementAdds->start_date = Carbon::now();
                    $advertisementAdds->end_date = Carbon::now()->addDays(3);
                    $advertisementAdds->addSet_id = $adgroup;
                    //$advertisementAdds->addCreative_id = $compain_budget;
                    $advertisementAdds->add_id = $adgroupadd;

                    //  dd($compain_id,$addSet_id,$addCreative_id,$add_id);
                    $advertisementAdds->save();

                    $check++;
                } else {
                    $advertisement->delete();
                    $adgroupadd = json_decode($adgroupadd->body());

                    return back()->with('error', isset($adgroupadd->error->message) ? $adgroupadd->error->message : 'Something went wrong');
                }
            }


        }


        //    dd($adCreative);
        return redirect('manage_view')->with('success', 'Add created successfully');
    }

    public function deleteCompain($id)
    {
        $adv = Advertisement::find($id);

        $facebook = config()->get('services.facebook');


        $delete = \Http::delete('https://graph.facebook.com/v13.0/' . $adv->compain_id . '', [
            'access_token' => $facebook['fb_token'],
        ]);
        $adv->delete();


    }


    public function publish($id, Request $request)
    {
        $facebook = config()->get('services.facebook');
        $google = config()->get('services.google');
        $com = Advertisement::find($id);
        $date1 = new \DateTime($com->end_date);
        $date2 = new \DateTime(Carbon::now());
        $f = $date1->diff($date2)->days;
        $f = $f >= 1 ? $f : 3;
        if ($com->type == 1) {
            $com->per_day = $request->per_day;
            $com->start_date = $request->start;
            $com->end_date = $request->end;
            $com->update();


//dd($f);

            $adsDel = AdvertisementAds::where('advertisements_id', $com->id)->get();
            foreach ($adsDel as $adsDel) {
                $delete = \Http::delete('https://graph.facebook.com/v13.0/' . $adsDel->addSet_id . '', [
                    'access_token' => $facebook['fb_token'],
                ]);
                //  $adsDel->delete();
            }


            $body = AdvertisementDetail::where('advertisements_id', $com->id)->where('type', 'body')->where('status', 'final')->first();
            $heading = AdvertisementDetail::where('advertisements_id', $com->id)->where('type', 'heading')->where('status', 'final')->first();
            $button = AdvertisementDetail::where('advertisements_id', $com->id)->where('type', 'button')->where('status', 'final')->first();
            $image = AdvertisementDetail::where('advertisements_id', $com->id)->where('type', 'image')->where('status', 'final')->first();
//dd($button);

            $addSet = \Http::post('https://graph.facebook.com/v13.0/act_' . $facebook['fb_account'] . '/adsets', [
                'campaign_id' => $com->compain_id,
                'name' => $heading->data,
                'lifetime_budget' => ($com->per_day * $f) * 100,
                'start_time' => $com->start_date,
                'end_time' => $com->end_date,
                'bid_amount' => $com->per_day * 100,
                'billing_event' => 'IMPRESSIONS',
                'optimization_goal' => $com->goal,
                'targeting' => ['age_min' => intval($com->age[0]), 'age_max' => intval($com->age[1]),
                    'behaviors' => $com->behaviour,

                    'genders' => [$com->gender],
                    'geo_locations' => [
                        'countries' => $com->countries,
                        'cities' => $com->cities,

                    ],
                    'interests' => $com->interest,
                    'life_events' => $com->life_events,
                    'family_statuses' => $com->family_statuses,
                    'industries' => $com->industries,
                    'income' => $com->income,
                ],
                'status' => env('FB_STATUS'),
                'access_token' => $facebook['fb_token'],
            ]);
            if ($addSet->status() == 200) {
                $addSet = json_decode($addSet->body());
                $addSet_id = $addSet->id;


                //creating addCreative

                $adCreative = \Http::post('https://graph.facebook.com/v13.0/act_' . $facebook['fb_account'] . '/adcreatives', [
                    'name' => $heading->data,
                    'body' => $body->data,
                    'object_story_spec' => [
                        'link_data' => [
                            'name' => $heading->data,
                            'image_hash' => $image->hash,
                            'link' => $button->url,
                            'message' => $body->data,
                            "call_to_action" => [
                                'type' => $button->data,
                            ]

                        ],
                        'page_id' => $facebook['page_id']
                    ],

                    'access_token' => $facebook['fb_token'],
                ]);


                if ($adCreative->status() == 200) {
                    $adCreative = json_decode($adCreative->body());
                    $addCreative_id = $adCreative->id;

                    //creating add

                    $add = \Http::post('https://graph.facebook.com/v13.0/act_' . $facebook['fb_account'] . '/ads', [
                        'name' => $heading->data,
                        'adset_id' => $addSet_id,
                        'creative' => [
                            'creative_id' => $addCreative_id,
                        ],
                        'status' => env('FB_STATUS'),

                        'access_token' => $facebook['fb_token'],
                    ]);

                    if ($add->status() == 200) {
                        $add = json_decode($add->body());
                        $add_id = $add->id;

                    } else {
                        $add = json_decode($add->body());
                        return back()->with('error', isset($add->error->error_user_msg) ? $add->error->error_user_msg : $add->error->message);
                    }

                } else {
                    $adCreative = json_decode($adCreative->body());

                    return back()->with('error', isset($adCreative->error->error_user_msg) ? $adCreative->error->error_user_msg : $adCreative->error->message);
                }


            } else {
                $addSet = json_decode($addSet->body());

                return back()->with('error', isset($addSet->error->error_user_msg) ? $addSet->error->error_user_msg : $addSet->error->message);
            }


            $advertisementAdds = new AdvertisementAds();
            $advertisementAdds->advertisements_id = $com->id;
            $advertisementAdds->heading = $heading->data;
            $advertisementAdds->body = $body->data;
            $advertisementAdds->button = $button->data;
            $advertisementAdds->url = $button->url;
            $advertisementAdds->image = $image->data;
            $advertisementAdds->start_date = $request->start;
            $advertisementAdds->end_date = $request->end;

            $advertisementAdds->addSet_id = $addSet_id;
            $advertisementAdds->addCreative_id = $addCreative_id;
            $advertisementAdds->add_id = $add_id;

            $advertisementAdds->save();

            $com->step = 6;
            $com->update();
            return back()->with('success', 'Add published successfully');
        }

        else {
            $com->per_day = $request->per_day;
            $com->start_date = $request->start;
            $com->end_date = $request->end;
            $com->update();




            $rand = rand(1111, 9999);
            $budget = intval($com->per_day) * 1000000;


            $i = 0;
            $img1 = $hash1 = null;
            $radius = $com->radius;
            $cities = array();
            $countries = array();
            $intrest = array();
            $behaviour = array();
            $life_events = $family_statuses = $industries = $income = array();
            if ($com->cities) {

                foreach ($com->cities as $city) {

                    $cities[] = $city;
                }

            }


            if ($com->countries) {

                foreach ($com->countries as $contry) {

                    $countries[] = $contry;
                }

            }

            //saving compain google

//dd($google);
//            $date1 = new \DateTime($com->end_date);
//            $date2 = new \DateTime(Carbon::now());
//            $f = $date1->diff($date2)->days;
//            $f = $f >= 1 ? $f : 3;
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
                            'advertisingChannelType' => $com->goal=='DISPLAY2' ? 'DISPLAY' : $com->goal,
                            "geoTargetTypeSetting" => array(
                                "positiveGeoTargetType" => 'PRESENCE_OR_INTEREST',
                                "negativeGeoTargetType" => 'PRESENCE_OR_INTEREST',
                            ),

                            'name' => "$com->title $rand",
                            'campaignBudget' => $compain_budget,

                            'targetSpend' => array(
                                'cpcBidCeilingMicros' => $com->target
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
                                    'type' => $com->age2
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
                                    'type' => $com->gender
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
                                    'text' => $com->keywords
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
                                'type' => $com->goal=='DISPLAY2'?'DISPLAY_STANDARD': $com->goal.'_STANDARD',
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

                        return back()->with('error', isset($adgroup->error->message) ? $adgroup->error->message : 'Something went wrong');


                    }


                } else {
                    $compain = json_decode($compain->body());

                    return back()->with('error', isset($compain->error->message) ? $compain->error->message : 'Something went wrong');

                }
            } else {
                $compain_budget = json_decode($compain_budget->body());

                return back()->with('error', isset($compain_budget->error->message) ? $compain_budget->error->message : 'Something went wrong');
            }

            //adgeoup ad


            $advertisement = new Advertisement();
            $advertisement->goal = $com->goal;
            $advertisement->target = $com->target;
            $advertisement->business = $com->business;
            $advertisement->keywords = $com->keywords;
            $advertisement->title = $com->title;
            $advertisement->dimentions = $com->dimentions;
            $advertisement->user_id = $com->user_id;

            $advertisement->age2 = $com->age2;
            $advertisement->gender = $com->gender;
            $advertisement->per_day = $com->per_day;

            $advertisement->type = 2;
            $advertisement->step = 5;
            $advertisement->compain_id = $compain;

            $advertisement->cities = json_encode($cities);
            $advertisement->countries = json_encode($countries);

            $advertisement->start_date = $com->start_date;
            $advertisement->end_date = $com->end_date;

            $advertisement->save();


            $detail = AdvertisementDetail::where('advertisements_id', $com->id)->update(['advertisements_id' => $advertisement->id]);

            $com->delete();

            if ($advertisement->goal == 'SEARCH') {
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

                    return redirect('manage_view')->with('error', isset($adgroupadd->error->message) ? $adgroupadd->error->message : 'Something went wrong');
                }

            }

            if ($advertisement->goal == 'DISPLAY') {


                $image = AdvertisementDetail::where('advertisements_id', $advertisement->id)->where('type', 'image')->where('status', 'final')->first();
                $url = AdvertisementDetail::where('advertisements_id', $advertisement->id)->where('type', 'button')->where('status', 'final')->first();

                //save heading and creating ads
                $check = 0;
                $dimentions = explode(' x ', $advertisement->dimentions);


                $img1 = 'images/gallary/' . $image->data . '';

                $imgp = ImageManagerStatic::make($img1);
                $imgp->resize($dimentions[0], $dimentions[1]);
                $imgp->save('images/gallary/resize/' . $image->data . '');

                $img = 'images/gallary/resize/' . $image->data . '';
                $imgContent = file_get_contents($img);
                $imgType = pathinfo($img, PATHINFO_EXTENSION);
                $imageData = base64_encode($imgContent);


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

                                "imageAd" => array(


                                    'mimeType' => 'IMAGE_PNG',
                                    'imageUrl' => $url->url,
                                    'data' => $imageData,


                                ),
                                'name' => $image->data,
                                "finalUrls" => [$url->url],
                                "displayUrl" => $url->url

                            )

                        )
                    ]
                ]);

                unlink('images/gallary/resize/' . $image->data . '');
                if ($adgroupadd->status() == 200) {


                    $advertisementAdds = new AdvertisementAds();
                    $advertisementAdds->advertisements_id = $advertisement->id;

                    $advertisementAdds->url = $url->url;
                    $advertisementAdds->image = $image->data;
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

                    return redirect('manage_view')->with('error', isset($adgroupadd->error->message) ? $adgroupadd->error->message : 'Something went wrong');
                }


            }

            if ($advertisement->goal=='DISPLAY2')
            {
                $image = AdvertisementDetail::where('advertisements_id', $advertisement->id)->where('type', 'image')->where('status', 'final')->first();
                $button = AdvertisementDetail::where('advertisements_id', $advertisement->id)->where('type', 'button')->where('status', 'final')->first();
                $body = AdvertisementDetail::where('advertisements_id', $advertisement->id)->where('type', 'body')->where('status', 'final')->first();
                $heading = AdvertisementDetail::where('advertisements_id', $advertisement->id)->where('type', 'heading')->where('status', 'final')->first();

                //save heading and creating ads
                $check = 0;
                //    $dimentions=explode(' x ',$advertisement->dimentions);
                // dd($dimentions);


                    $images = $image->data;
                    $img1 = ('images/gallary/') . $images . '';

                    $imgp = ImageManagerStatic::make($img1);
                    $imgp->resize(600, 314);
                    $imgp->save(('images/gallary/resize/') . $images . '');

                    $img = 'images/gallary/resize/' . $images . '';
                    $imgContent = file_get_contents($img);
                    $imgType = pathinfo($img, PATHINFO_EXTENSION);
                    $imageData = base64_encode($imgContent);


                    $img1 = ('images/gallary/') . $images . '';

                    $imgp = ImageManagerStatic::make($img1);
                    $imgp->resize(300, 300);
                    $imgp->save('images/gallary/resize/' . $images . '');

                    $img = ('images/gallary/resize/') . $images . '';
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

                    unlink(('images/gallary/resize/') . $images . '');
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
//dd($adgroupadd);
                //        $advertisement->delete();

                        return redirect('manage_view')->with('error', isset($adgroupadd->error->message) ? $adgroupadd->error->message : 'Something went wrong');
                    }


                }



            $advertisement->step = 6;
            $advertisement->update();
            return redirect("manage_detail/$advertisement->id")->with('success', 'Add published successfully');

        }


    }


    public function conpainDelete($id)
    {
        $facebook = config()->get('services.facebook');
        $google = config()->get('services.google');
        $compain = Advertisement::find($id);
        if ($compain->type == 1) {
            //facebooke delete
            $delete = \Http::delete('https://graph.facebook.com/v13.0/' . $compain->compain_id . '', [
                'access_token' => $facebook['fb_token'],
            ]);
            if ($delete->status() == 200) {

                $compain->delete();
                return redirect('manage_view')->with('success', 'campaign deleted successfully');
            } else {
                $delete = json_decode($delete->body());
                return back()->with('error', isset($delete->error->error_user_msg) ? $delete->error->error_user_msg : $delete->error->message);
            }

        } else {
            //google delete


            $delete = \Http::withHeaders([

                'developer-token' => $google['dev_token'],
                'login-customer-id' => $google['manager_id'],
            ])->withToken($google['accsss_token'])->
            post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/campaigns:mutate', [
                'operations' => [
                    'remove' => $compain->compain_id,
                ]
            ]);

            if ($delete->status() == 200) {


                $compain->delete();
                return redirect('manage_view')->with('success', 'campaign deleted successfully');


            } else {
                $delete = json_decode($delete->body());

                return back()->with('error', isset($delete->error->message) ? $delete->error->message : 'Something went wrong');

            }
        }


        //  ->delete();
    }

    public function activeCompain($id)
    {
        $facebook = config()->get('services.facebook');
        $google = config()->get('services.google');
        $compain = Advertisement::find($id);
        if ($compain->type == 1) {
            //facebooke active

            $active = \Http::post('https://graph.facebook.com/v13.0/' . $compain->compain_id . '', [
                'status' => 'ACTIVE',
                'access_token' => $facebook['fb_token'],

            ]);
            if ($active->status() == 200) {

                return back()->with('success', 'campaign status updated successfully');
            } else {
                $delete = json_decode($active->body());
                return back()->with('error', isset($active->error->error_user_msg) ? $active->error->error_user_msg : $active->error->message);
            }

        } else {
            $update = \Http::withHeaders([

                'developer-token' => $google['dev_token'],
                'login-customer-id' => $google['manager_id'],
            ])->withToken($google['accsss_token'])->
            post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/campaigns:mutate', [
                'operations' => [
                    'updateMask' => 'status',
                    'update' => array(
                        'resourceName' => $compain->compain_id,
                        'status' => 'ENABLED'
                    )
                ]
            ]);

            if ($update->status() == 200) {
                return back()->with('success', 'campaign status updated successfully');

            } else {

                $delete = json_decode($update->body());
                return back()->with('error', isset($update->error->error_user_msg) ? $update->error->error_user_msg : $active->error->message);
            }
        }

    }

    public function pauseCompain($id)
    {
        $facebook = config()->get('services.facebook');
        $google = config()->get('services.google');
        $compain = Advertisement::find($id);
        if ($compain->type == 1) {
            //facebooke active

            $pause = \Http::post('https://graph.facebook.com/v13.0/' . $compain->compain_id . '', [
                'status' => 'PAUSED',
                'access_token' => $facebook['fb_token'],

            ]);
            if ($pause->status() == 200) {

                return back()->with('success', 'campaign status updated successfully');
            } else {
                $delete = json_decode($pause->body());
                return back()->with('error', isset($pause->error->error_user_msg) ? $pause->error->error_user_msg : $pause->error->message);
            }
        } else {


            $update = \Http::withHeaders([

                'developer-token' => $google['dev_token'],
                'login-customer-id' => $google['manager_id'],
            ])->withToken($google['accsss_token'])->
            post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/campaigns:mutate', [
                'operations' => [
                    'updateMask' => 'status',
                    'update' => array(
                        'resourceName' => $compain->compain_id,
                        'status' => 'PAUSED'
                    )
                ]
            ]);

            if ($update->status() == 200) {
                return back()->with('success', 'campaign status updated successfully');

            } else {

                $delete = json_decode($update->body());
                return back()->with('error', isset($update->error->error_user_msg) ? $update->error->error_user_msg : $active->error->message);
            }


        }

    }

    public function searchCity(Request $request)
    {
        $total = [];
        $facebook = config()->get('services.facebook');
        //    dd($request->city);
        foreach ($request->city as $citi) {
            $data = explode(',', $citi);
            $city = \Http::get('https://graph.facebook.com/v13.0/search', [
                'location_types' => ["city"],
                'type' => 'adgeolocation',
                'limit' => 500,
                'q' => $data[1],
                'access_token' => $facebook['fb_token'],

            ]);
            $city = json_decode($city->body());
            $city = collect($city->data);
            $total = collect($total);
            $total = $total->merge($city);
            $total->all();

        }


//dd(json_decode($city->body()));
        return response()->json($total);
    }

    public function searchCityGoogle(Request $request)
    {
        $total = [];
        $google = config()->get('services.google');
        //    dd($request->city);


        $city = \Http::withHeaders([

            'developer-token' => $google['dev_token'],
            'login-customer-id' => $google['manager_id'],
        ])->withToken($google['accsss_token'])->
        post('https://googleads.googleapis.com/v10/geoTargetConstants:suggest', [
                'locationNames' => array(
                    'names' => [$request->city],
                ),
            ]
        );
        $city = json_decode($city->body());


//dd(json_decode($city->body()));
        return response()->json($city->geoTargetConstantSuggestions);
    }


    public function connectWithFacebook()
    {
        return redirect('/pdf/connect-with-facebook.pdf');
    }

    public function connectWithGoogle()
    {
        return redirect('/pdf/connect-with-google.pdf');
    }
}
