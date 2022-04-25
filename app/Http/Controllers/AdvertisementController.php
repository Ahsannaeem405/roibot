<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\AdvertisementAds;
use App\Models\AdvertisementDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function PostAdd(Request $request)
    {
   //     dd(explode('to',$request->age));
        //dd($request->title);
      $facebook=config()->get('services.facebook');
        $i=0;
        $img1=null;



        //saving compain facebook

if($request->advert_type==1)
{
    $compain = \Http::post('https://graph.facebook.com/v13.0/act_'.$facebook['fb_account'].'/campaigns', [
        'name' => $request->title,
        'objective' => $request->goal,
        'status' => 'PAUSED',
        'special_ad_categories' => [],
        'access_token' => $facebook['fb_token'],
    ]);
    if ($compain->status()==200)
    {
        $compain = json_decode($compain->body());

        $compain_id = $compain->id;

    }
    else{
        $compain = json_decode($compain->body());
      // dd($compain);
        return back()->with('error',isset($compain->error->error_user_msg) ? $compain->error->error_user_msg : $compain->error->message);
    }
}
else{
    //saving compain google
    $compain_id=0;
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
        $advertisement->save();



        //save body
        foreach ($request->body as $body) {
            $advertisementDetail = new AdvertisementDetail();
            $advertisementDetail->data = $body;
            $advertisementDetail->advertisements_id = $advertisement->id;
            $advertisementDetail->type = 'body';
            $advertisementDetail->save();



        }


        //save button and url
        for ( $j=0;  $j< count($request->btn);  $j++) {
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


            if($i==0)
            {
                $img1=$image;
                $i=1;
            }

            $advertisementDetail->data = $image;
            $advertisementDetail->advertisements_id = $advertisement->id;
            $advertisementDetail->type = 'image';
            $advertisementDetail->save();

        }


        //save heading and creating ads

        foreach ($request->heading as $heading) {

            //creating add facebook
            if($request->advert_type==1)
            {
//creating addset
                $addSet = \Http::post('https://graph.facebook.com/v13.0/act_'.$facebook['fb_account'].'/adsets', [
                    'campaign_id' => $compain_id,
                    'name' => $heading,
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
                        'name' => $heading,
                        'body'=>$request->body[0],
                        'object_story_spec' => [
                            'link_data' => [
                                'image_hash' => md5_file(public_path('images/gallary/'.$img1.'')),
                                'link' => $request->url[0],
                                'message' => $request->btn[0],

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
                            'name' => $heading,
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
                 //   dd($addSet);

                    return back()->with('error',isset($addSet->error_user_msg) ? $addSet->error->error_user_msg  : $addSet->error->message);
                }



            }
            else{
                //creating add facebook google
            }







            $advertisementDetail = new AdvertisementDetail();
            $advertisementDetail->data = $heading;
            $advertisementDetail->advertisements_id = $advertisement->id;
            $advertisementDetail->type = 'heading';
            $advertisementDetail->save();

            $advertisementAdds=new AdvertisementAds();
            $advertisementAdds->advertisements_id=$advertisement->id;
            $advertisementAdds->heading=$heading;
            $advertisementAdds->body=$request->body[0];
            $advertisementAdds->button=$request->btn[0];
            $advertisementAdds->url=$request->url[0];
            $advertisementAdds->image=$img1;
            $advertisementAdds->start_date=Carbon::now();
            $advertisementAdds->end_date=Carbon::now()->addDays(3);
            $advertisementAdds->addSet_id=$addSet_id;
            $advertisementAdds->addCreative_id=$addCreative_id;
            $advertisementAdds->add_id=$add_id;

          //  dd($compain_id,$addSet_id,$addCreative_id,$add_id);
            $advertisementAdds->save();


        }




        return redirect('manage_view')->with('success','Add created successfully');
    }


    public function conpainDelete($id)
    {
        $facebook=config()->get('services.facebook');
        $compain=Advertisement::find($id);
        if ($compain->type==1)
        {
         //facebooke delete
            $delete = \Http::delete('https://graph.facebook.com/v13.0/'.$compain->compain_id.'', [
                        'access_token' => $facebook['fb_token'],
                    ]);
                   if ($delete->status()==200)
                   {

                       $compain->delete();
                       return redirect('manage_view')->with('success','Compain deleted successfully');
                   }
                   else{
                       $delete=json_decode($delete->body());
                       return back()->with('error',isset($delete->error_user_msg) ? $delete->error->error_user_msg  : $delete->error->message);
                   }

        }
        else{
            //google delete


        }


          //  ->delete();
    }

    public function activeCompain($id)
    {
        $facebook=config()->get('services.facebook');
        $compain=Advertisement::find($id);
        if ($compain->type==1) {
            //facebooke active

            $active = \Http::post('https://graph.facebook.com/v13.0/'.$compain->compain_id.'', [
                'status'=>'ACTIVE',
                'access_token' => $facebook['fb_token'],

            ]);
            if ($active->status()==200)
            {

                return back()->with('success','Compain status updated successfully');
            }
            else{
                $delete=json_decode($active->body());
                return back()->with('error',isset($active->error_user_msg) ? $active->error->error_user_msg  : $active->error->message);
            }

        }
        else{
            //google active
        }

    }

    public function pauseCompain($id)
    {
        $facebook=config()->get('services.facebook');
        $compain=Advertisement::find($id);
        if ($compain->type==1) {
            //facebooke active

            $pause = \Http::post('https://graph.facebook.com/v13.0/'.$compain->compain_id.'', [
                'status'=>'PAUSED',
                'access_token' => $facebook['fb_token'],

            ]);
            if ($pause->status()==200)
            {

                return back()->with('success','Compain status updated successfully');
            }
            else{
                $delete=json_decode($pause->body());
                return back()->with('error',isset($pause->error_user_msg) ? $pause->error->error_user_msg  : $pause->error->message);
            }
        }
        else{
            //google active
        }

    }
}
