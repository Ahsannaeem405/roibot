<?php
namespace App\Traits;

use App\Models\creditials;
use App\Models\User;

trait credientials {


    public function setCredientials(){
      //  dd();
        $user = \Auth::user();
        $admin = creditials::first();
        $facebook = [
            'fb_client' => $admin->facebook_app,
            'fb_secret' => $admin->facebook_secret,
            'fb_token' => $user->fb_token,
            'page_id' => $user->fb_page,
            'fb_account' => $user->fb_account,

        ];



        $google = [
            'dev_token' => $admin->google_developer,
            'manager_id' => $admin->manager,
            'customer_id' => $user->gg_customer,
            'client_id' => $admin->google_app,
            'secret_id' => $admin->google_secret,
            'accsss_token' => $admin->google_token,
            'refresh_token' => $admin->google_refresh,

        ];


        config()->set('services.facebook', $facebook);
        config()->set('services.google', $google);
        //dd(config()->get('services.facebook', $facebook));
    }

    public function refreshFb(){
        //  dd();
        $user =  config()->get('services.facebook');



    }
}
