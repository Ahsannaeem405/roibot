<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\AdvertisementAds;
use App\Models\Behaviour;
use App\Models\countries;
use App\Models\creditials;
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

        $admin=creditials::first();

        $user = \Auth::user();
        $pages = \Http::get('https://graph.facebook.com/v14.0/me/accounts?access_token=' . $user->fb_token . '');
        $accounts = \Http::get('https://graph.facebook.com/v14.0/me/adaccounts?access_token=' . $user->fb_token . '');

        if ($pages->status() == 200) {
            $pages = json_decode($pages->body());
            $pages = $pages->data;
        } else {
            $pages = [];
        }
        if ($accounts->status() == 200) {
            $accounts = json_decode($accounts->body());
            $accounts = $accounts->data;
        } else {
            $accounts = [];
        }

        return view('profile', compact('user', 'pages', 'accounts','admin'));
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

        $admin=creditials::first();

        $url = 'https://graph.facebook.com/v14.0/oauth/access_token?grant_type=fb_exchange_token&client_id=' . $admin->facebook_app . '&client_secret=' . $admin->facebook_secret . '&fb_exchange_token=' . $request->fb_token . '';
        $api = \Http::get($url);


        if ($api->status() == 200) {
          $api = json_decode($api->body());

        $user = User::find(\Auth::user()->id);

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

    public function updateGoogle(Request $request)
    {

        $rand = rand(1111, 9999);
        $admin = creditials::first();
        $user = User::find(\Auth::user()->id);

        $google = [
            'dev_token' => $admin->google_developer,
            'manager_id' => $admin->manager,
            'customer_id' => $user->gg_customer,
            'client_id' => $admin->google_app,
            'secret_id' => $admin->google_secret,
            'accsss_token' => $admin->google_token,
            'refresh_token' => $admin->google_refresh,
        ];
//
//        $client = \Http::withHeaders([
//            'developer-token' => $google['dev_token'],
//            'login-customer-id' => $google['manager_id'],
//        ])->withToken($google['accsss_token'])->
//        post('https://googleads.googleapis.com/v10/customers/'.$google['manager_id'].'/accountLinks:create', [
//            'accountLink' => array(
//                'status'=>'REQUESTED',
//                'type'=>'GOOGLE_ADS',
//                'googleAds'=>array(
//                    'customer'=>"customers/8298276417",
//                )
//            )
//
//        ]);
        $client = \Http::withHeaders([
            'developer-token' => $google['dev_token'],
            'login-customer-id' => $google['manager_id'],
        ])->withToken($google['accsss_token'])->
        post('https://googleads.googleapis.com/v10/customers/' . $google['manager_id'] . '/customerClientLinks:mutate', [
            'operation' => array(

                'create' => array(
                    'status' => 'ACTIVE',
                    'clientCustomer' => "customers/$request->gg_customer",
                )
            )

        ]);

        if ($client->status() == 200) {
            $response = json_decode($client->body());


            $user = User::find(\Auth::user()->id);
            $user->gg_client = $request->gg_client;
            $user->gg_secret = $request->gg_secret;
            $user->gg_dev = $request->gg_dev;
            $user->gg_manager = $request->gg_manager;
            $user->gg_customer = $request->gg_customer;
            $user->gg_access = $request->gg_access;
            $user->gg_refresh = $request->gg_refresh;
            $user->update();
            return back()->with('success', 'Profile updated successfully');
        } else {
            $response = json_decode($client->body());

            return back()->with('error', $response->error->message);
        }


    }

    function create_ad_fb(Request $request)
    {

        try {
            $facebook = config()->get('services.facebook');
            $country = \Http::get('https://graph.facebook.com/v14.0/search', [
                'location_types' => ["country"],
                'type' => 'adgeolocation',
                'limit' => 300,
                //'q'=>'united',
                'access_token' => $facebook['fb_token'],

            ]);
            $country = json_decode($country);
            //  dd($country->err)
            if (isset($country->error->message)) {
                return back()->with('error', $country->error->message);
            }


            $city = \Http::get('https://graph.facebook.com/v14.0/search', [
                'location_types' => ["city"],
                'type' => 'adgeolocation',
                'limit' => 500,
                'q' => 'uk',
                'access_token' => $facebook['fb_token'],

            ]);
            $city = json_decode($city->body());
            $city = [];

            $intrests = Intrests::where('parent', 0)->get();
//dd($intrests[0]->child[0]->child);

            $behaviour = Behaviour::where('parent', 0)->get();


            $demographics = Demographics::where('parent', 0)->get();
            //  dd($demographics);


            $advert = 1;
            $gallary = mediaGallary::where('user_id', \Auth::user()->id)->OrderBY('id', 'DESC')->get();


            return view('create_add', compact('advert', 'city', 'gallary', 'country', 'behaviour', 'intrests', 'demographics'));


        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }

    }

    function create_ad_gg(Request $request)
    {


        $google = config()->get('services.google');
        //    dd($request->city);


        $country = countries::all();
        $advert = 2;
        $gallary = mediaGallary::where('user_id', \Auth::user()->id)->OrderBY('id', 'DESC')->get();


        return view('google_add', compact('advert', 'gallary', 'country'));


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
            ->withSum('activeAdd as total', 'total')
            ->where('user_id', \Auth::user()->id)->orderBy('total', 'DESC')->take(5)->get();


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
        $add = AdvertisementAds::with('insightDetail')->find($add);


        $facebook = config()->get('services.facebook');
        $addData = \Http::get('https://graph.facebook.com/v14.0/' . $add->addSet_id . '/ads', [

            'fields' => 'status,name,effective_status',
            'access_token' => $facebook['fb_token'],
        ]);
        $data = json_decode($addData->body());
        //  dd($data);

        $facebook = config()->get('services.facebook');


        return view('insights_detail', compact('compain', 'add', 'data'));
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
