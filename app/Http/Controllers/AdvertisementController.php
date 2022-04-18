<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\AdvertisementAds;
use App\Models\AdvertisementDetail;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function PostAdd(Request $request)
    {
        $i=0;
        //dd($request->input());

        $advertisement = new Advertisement();
        $advertisement->goal = $request->goal;
        $advertisement->user_id = \Auth::user()->id;
        $advertisement->url = $request->url;
        $advertisement->action_btn = $request->btn;
        $advertisement->age = $request->age;
        $advertisement->gender = $request->gender;
        $advertisement->budget = $request->total_budget;
        $advertisement->per_day = $request->perday_budget;
        $advertisement->duration = $request->duration;
        $advertisement->type = $request->advert_type;
        $advertisement->save();



        foreach ($request->body as $body) {
            $advertisementDetail = new AdvertisementDetail();
            $advertisementDetail->data = $body;
            $advertisementDetail->advertisements_id = $advertisement->id;
            $advertisementDetail->type = 'body';
            $advertisementDetail->save();



        }


        foreach ($request->image as $image) {

            $advertisementDetail = new AdvertisementDetail();

            $file =$image;
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename = time().'.' . $extension;
            $file->move('images/ads/', $filename);
            if($i==0)
            {
                $img1=$filename;
            }

            $advertisementDetail->data = $filename;
            $advertisementDetail->advertisements_id = $advertisement->id;
            $advertisementDetail->type = 'image';
            $advertisementDetail->save();

        }




        foreach ($request->heading as $heading) {
            $advertisementDetail = new AdvertisementDetail();
            $advertisementDetail->data = $heading;
            $advertisementDetail->advertisements_id = $advertisement->id;
            $advertisementDetail->type = 'heading';
            $advertisementDetail->save();

            $advertisementAdds=new AdvertisementAds();
            $advertisementAdds->advertisements_id=$advertisement->id;
            $advertisementAdds->heading=$heading;
            $advertisementAdds->body=$request->body[0];
            $advertisementAdds->image=$img1;

            $advertisementAdds->save();


        }




        return redirect('manage_view')->with('success','Add created successfully');
    }
}
