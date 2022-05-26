<?php
namespace App\Traits;

use App\Models\User;

trait credientials {


    public function setCredientials(){
      //  dd();
        $user = \Auth::user();
        $facebook = [
            'fb_client' => $user->fb_client,
            'fb_secret' => $user->fb_secret,
            'fb_token' => $user->fb_token,
            'page_id' => $user->fb_page,
            'fb_account' => $user->fb_account,

        ];

        $google = [
            'dev_token' => $user->gg_dev,
            'manager_id' => $user->gg_manager,
            'customer_id' => $user->gg_customer,
            'client_id' => $user->gg_client,
            'secret_id' => $user->gg_secret,
            'accsss_token' => $user->gg_access,
            'refresh_token' => $user->gg_refresh,

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
