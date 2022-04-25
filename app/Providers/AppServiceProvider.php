<?php

namespace App\Providers;

use App\Http\Controllers\MediaGallaryController;
use App\Models\User;

use App\Traits\credientials;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
use  credientials;
    /**
     * Register any application services.
     *
     * @return void
     *
     *
     *
     */



    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        //URL::forceScheme('https');



       // $data= new MediaGallaryController();
       // $data->index();


        //dd($user);



    }
}
