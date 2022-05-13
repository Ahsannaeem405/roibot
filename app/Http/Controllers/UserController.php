<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\AdvertisementAds;
use App\Models\Behaviour;
use App\Models\Demographics;
use App\Models\Intrests;
use App\Models\mediaGallary;
use App\Models\User;
use App\Traits\credientials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    use credientials;

    public function profile()
    {
        $user = \Auth::user();
//        $url=\Http::get('https://graph.facebook.com/debug_token?input_token='.$user->fb_token.'&access_token='.$user->fb_token.'');
//   dd(json_decode($url->body()));
        return view('profile', compact('user'));
    }

    public function profileUpdate(Request $request)
    {

        $user = User::find(\Auth::user()->id);

        if ($request->old_password && $request->password) {

            $request->validate([

                'password' => ['required', 'confirmed'],


            ]);


            if (Hash::check($request->old_password, $user->password)) {
                $user->fill([
                    'password' => Hash::make($request->password)
                ])->save();

                // return back()->with('success', 'Password Update successfully');

            } else {

                return back()->with('error', 'Password does not match');

            }


        }

        $user->name = $request->name;
        if ($request->hasfile('profile')) {
            $file = $request->file('profile');
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename = time() . '.' . $extension;
            $file->move('images/profile/', $filename);
            $user->profile = $filename;
        }


        $user->update();
        return back()->with('success', 'Profile Update successfully');
    }

    public function updateFb(Request $request)
    {


        $url = 'https://graph.facebook.com/v13.0/oauth/access_token?grant_type=fb_exchange_token&client_id=' . $request->fb_client . '&client_secret=' . $request->fb_secret . '&fb_exchange_token=' . $request->fb_token . '';
        $api = \Http::get($url);
        //  dd($url);

        if ($api->status() == 200) {
            $api = json_decode($api->body());


            $user = User::find(\Auth::user()->id);
            $user->fb_client = $request->fb_client;
            $user->fb_secret = $request->fb_secret;
            $user->fb_token = $api->access_token;
            $user->fb_page = $request->fb_page;
            $user->fb_account = $request->fb_account;

            $user->update();
            return back()->with('success', 'Profile updated successfully');

        } else {
            $api = json_decode($api->body());
            return back()->with('error', $api->error->message);
        }


    }

    function create_ad($id)
    {
        $facebook = config()->get('services.facebook');

        $country = \Http::get('https://graph.facebook.com/v13.0/search', [
            'location_types' => ["country"],
            'type' => 'adgeolocation',
            'limit' => 300,
            //'q'=>'united',
            'access_token' => $facebook['fb_token'],

        ]);
        $country = json_decode($country);

        $city = \Http::get('https://graph.facebook.com/v13.0/search', [
            'location_types' => ["city"],
            'type' => 'adgeolocation',
            'limit' => 500,
            'q' => 'uk',
            'access_token' => $facebook['fb_token'],

        ]);
        $city = json_decode($city->body());

        $intrests = Intrests::where('parent', 0)->get();
//dd($intrests[0]->child[0]->child);

        $behaviour = Behaviour::where('parent', 0)->get();


        $demographics = Demographics::where('parent', 0)->get();
        //  dd($demographics);


        $advert = $id;
        $gallary = mediaGallary::where('user_id', \Auth::user()->id)->OrderBY('id', 'DESC')->get();
        return view('create_add', compact('advert', 'city', 'gallary', 'country', 'behaviour', 'intrests', 'demographics'));
    }

    public function ManageAdd()
    {
        $compain = Advertisement::where('user_id', \Auth::user()->id)->orderBy('id', 'DESC')->get();
        return view('manage_view', compact('compain'));
    }

    public function main()
    {
        $compain = Advertisement::
           whereHas('activeAdd')->
            withSum('activeAdd as cpc', 'cpc')
            ->withSum('activeAdd as clicks', 'clicks')
            ->withSum('activeAdd as impressions', 'impressions')
            ->withSum('activeAdd as conversation', 'conversation')
            ->withSum('activeAdd as total', 'total')
            ->where('user_id', \Auth::user()->id)->orderBy('total', 'DESC')->take(5)->get();


        //dd($compain);

        return view('index', compact('compain'));
    }

    public function mangeDetail($id)
    {
        $compain = Advertisement::where('id', $id)->orderBy('id', 'DESC')->get();
        return view('manage_detail', compact('compain'));
    }

    public function insightDetail($id, $add)
    {

        $compain = Advertisement::where('id', $id)->orderBy('id', 'DESC')->first();
        $add = AdvertisementAds::find($add);
        //   dd($add);

        $facebook = config()->get('services.facebook');
        $addData = \Http::get('https://graph.facebook.com/v13.0/' . $add->addSet_id . '/ads', [

            'fields' => 'status,name',
            'access_token' => $facebook['fb_token'],
        ]);
        $data = json_decode($addData->body());

        $facebook = config()->get('services.facebook');



        return view('insights_detail', compact('compain', 'add','data'));
    }

    public function insightView()
    {
        $compain = Advertisement::
        whereHas('activeAdd')->
        where('user_id', \Auth::user()->id)->orderBy('id', 'DESC')->get();
        return view('insight_view', compact('compain'));
    }

    public function uploadImgae(Request $request)
    {
        if ($request->hasFile('file')) {
            $gallary = new mediaGallary();
            $gallary->user_id = \Auth::user()->id;

            $file = $request->file;
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename = $file->getClientOriginalName();
            $file->move('images/gallary/', $filename);


            $gallary->image = $filename;
            $gallary->save();
        }
    }

    public function getImages()
    {
        $gallary = mediaGallary::where('user_id', \Auth::user()->id)->OrderBY('id', 'DESC')->get();

        return view('getImage', compact('gallary'));
    }

    public function mediaGallery()
    {
        $gallary = mediaGallary::where('user_id', \Auth::user()->id)->OrderBY('id', 'DESC')->get();
        return view('mediaGallery', compact('gallary'));
    }

    public function galleryDelete(Request $request)
    {
        if ($request->check) {
            mediaGallary::whereIn('id', $request->check)->delete();
            return back()->with('success', 'Record deleted successfully');
        } else {
            return back()->with('error', 'Please select image to delete');
        }

    }
}