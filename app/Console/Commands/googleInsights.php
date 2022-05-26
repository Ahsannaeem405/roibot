<?php

namespace App\Console\Commands;

use App\Models\AdvertisementAds;
use Illuminate\Console\Command;

class googleInsights extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:insight';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ads=AdvertisementAds::where('add_id','!=',null)->get();
        foreach ($ads as $ad)
        {
            $insight = \Http::get('https://graph.facebook.com/v13.0/'.$ad->add_id.'/insights', [
                "date_preset"=>"maximum",
                "fields"=>'impressions,clicks,cpc,reach',
                'access_token' => $ad->compain->user->fb_token,

            ]);
            $insight=json_decode($insight->body());
            if (count($insight->data)>=1){

                $ad->clicks=intval($insight->data[0]->clicks);
                $ad->impressions=intval($insight->data[0]->impressions);
                $ad->cpc=intval($insight->data[0]->cpc);
                $ad->conversation=intval($insight->data[0]->reach);
                $ad->total= intval($ad->clicks+  $ad->impressions +  $ad->cpc+  $ad->conversation);


                $ad->update();

            }
        }
    }
}
