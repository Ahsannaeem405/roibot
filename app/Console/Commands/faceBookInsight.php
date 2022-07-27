<?php

namespace App\Console\Commands;

use App\Models\AdvertisementAds;
use App\Models\creditials;
use App\Models\insightDetail;
use Carbon\Carbon;
use Illuminate\Console\Command;

class faceBookInsight extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'facebook:insight';

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
        $admin = creditials::first();
        $ads=AdvertisementAds::where('add_id','!=',null)->get();
        foreach ($ads as $ad)
        {
            $insight = \Http::get('https://graph.facebook.com/v14.0/'.$ad->add_id.'/insights', [
                "date_preset"=>"maximum",
                "fields"=>'impressions,clicks,cpc,reach',
                'access_token' => $ad->compain->user->fb_token,

            ]);
            if ($insight->status()==200)
            {


            $insight=json_decode($insight->body());

            if (count($insight->data)>=1){

                $ad->clicks=intval($insight->data[0]->clicks);
                $ad->impressions=intval($insight->data[0]->impressions);
                $ad->cpc=$insight->data[0]->cpc;
                $ad->total= intval($ad->clicks+  $ad->impressions);
                $ad->update();

                $ins_detail=insightDetail::updateOrCreate(
                    ['add_id'=>$ad->id,'date'=>Carbon::now()->format('Y-m-d')],
                    ['cpc'=>$ad->cpc],
                    ['impressions'=> $ad->impressions],
                    ['clicks'=>$ad->clicks],

                );


            }
            }
        }
    }
}
