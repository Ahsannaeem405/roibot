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

        config()->set('services.facebook', $facebook);
        //dd(config()->get('services.facebook', $facebook));
    }

    public function refreshFb(){
        //  dd();
        $user =  config()->get('services.facebook');



    }
}
