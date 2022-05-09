<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\AdvertisementAds;
use App\Models\AdvertisementDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use FacebookAds\Object\AdImage;
use FacebookAds\Object\Fields\AdImageFields;

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
        $img1 = $hash1=null;
        $radius = $request->radius;
        $cities = array();
        $countries = array();
        $intrest = array();
        $behaviour = array();
        $demographics = array();
        if ($request->city) {

            foreach ($request->city as $city) {
                $cities[] = array(
                    'key' => $city,
                    'radius' => $radius,
                    'distance_unit' => 'mile',
                );
            }

        }
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
                $countries[] = $contry;
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
        if ($request->demo) {

            foreach ($request->demo as $demo) {
                $data = explode(',', $demo);
                $demographics[] = array(
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
        $advertisement->demo = json_encode($demographics);
        $advertisement->behaviour = json_encode($behaviour);


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
                CURLOPT_POSTFIELDS => array('filename'=> new \CURLFile('images/gallary/'.$image.'')),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '. $facebook['fb_token'].''
                ),
            ));


            $addImage =json_decode(curl_exec($curl));

            curl_close($curl);

//dd($addImage,$image);

            if ($i == 0) {
                $img1 = $image;

                $img1 =$image;
                $hash1=$addImage->images->$image->hash;

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
                        'life_events' => $advertisement->demo,
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
                                "call_to_action"=>[
                                    'type'=>$request->btn[0],
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
        $com = Advertisement::find($id);
        $com->per_day = $request->per_day;
        $com->update();


        $adsDel = AdvertisementAds::where('advertisements_id', $com->id)->get();
        foreach ($adsDel as $adsDel) {
            $delete = \Http::delete('https://graph.facebook.com/v13.0/' . $adsDel->addSet_id . '', [
                'access_token' => $facebook['fb_token'],
            ]);
            $adsDel->delete();
        }


        $body = AdvertisementDetail::where('advertisements_id', $com->id)->where('type', 'body')->where('status', 'final')->first();
        $heading = AdvertisementDetail::where('advertisements_id', $com->id)->where('type', 'heading')->where('status', 'final')->first();
        $button = AdvertisementDetail::where('advertisements_id', $com->id)->where('type', 'button')->where('status', 'final')->first();
        $image = AdvertisementDetail::where('advertisements_id', $com->id)->where('type', 'image')->where('status', 'final')->first();


        $addSet = \Http::post('https://graph.facebook.com/v13.0/act_' . $facebook['fb_account'] . '/adsets', [
            'campaign_id' => $com->compain_id,
            'name' => $heading->data,
            'lifetime_budget' => ($com->per_day * 3) * 100,
            'start_time' => Carbon::now(),
            'end_time' => Carbon::now()->addDays(3),
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
                'life_events' => $com->demo,
            ],
            'status' => env('FB_STATUS'),
            'access_token' => $facebook['fb_token'],
        ]);
        if ($addSet->status() == 200) {
            $addSet = json_decode($addSet->body());
            $addSet_id = $addSet->id;


            //creating addCreative

            $adCreative = \Http::post('https://graph.facebook.com/v13.0/act_' . $facebook['fb_account'] . '/adcreatives', [

               // 'body' => $body->data,
                'object_story_spec' => [
                    'link_data' => [
                        'image_hash' => md5_file(('images/gallary/' . $image->data . '')),
                        'link' => $button->url,
                        'message' => $body->data,
                        'name' => $heading->data,
                        "call_to_action"=>[
                            'type'=>$button->data[0],
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
        $advertisementAdds->save();

        $com->step = 6;
        $com->update();
        return back()->with('success', 'Add published successfully');

    }


    public function conpainDelete($id)
    {
        $facebook = config()->get('services.facebook');
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


        }


        //  ->delete();
    }

    public function activeCompain($id)
    {
        $facebook = config()->get('services.facebook');
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
            //google active
        }

    }

    public function pauseCompain($id)
    {
        $facebook = config()->get('services.facebook');
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
            //google active
        }

    }

    public function searchCity(Request $request)
    {
        $facebook = config()->get('services.facebook');
        $city = \Http::get('https://graph.facebook.com/v13.0/search', [
            'location_types' => ["city"],
            'type' => 'adgeolocation',
            'limit' => 100,
            'q' => $request->city,
            'access_token' => $facebook['fb_token'],

        ]);

        return response()->json(json_decode($city->body()));
    }

    public function searchInterest(Request $request)
    {
        $facebook = config()->get('services.facebook');
        $city = \Http::get('https://graph.facebook.com/v13.0/search', [


            'type' => 'adinterest',
            'q' => $request->interest,
            'access_token' => $facebook['fb_token'],

        ]);

        return response()->json(json_decode($city->body()));
    }
}
