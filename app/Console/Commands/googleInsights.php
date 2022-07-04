<?php

namespace App\Console\Commands;

use App\Models\Advertisement;
use App\Models\AdvertisementAds;
use App\Models\creditials;
use App\Models\insightDetail;
use App\Models\User;
use Carbon\Carbon;
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
        $admin = creditials::first();
        $adv = Advertisement::whereHas('activeAdd', function ($q) {
            //  $q->where('end_date', '<', Carbon::now());
        })
            ->where('status', 'pending')
            ->where('type', 2)
            ->get();


        foreach ($adv as $advs) {

            $adsStep1 = $advs->activeAdd;


            $rand = rand(1111, 9999);


            $user = User::find($advs->user_id);


            $google = [
                'dev_token' => $admin->google_developer,
                'manager_id' => $admin->manager,
                'customer_id' => $user->gg_customer,
                'client_id' => $admin->google_app,
                'secret_id' => $admin->google_secret,
                'accsss_token' => $admin->google_token,
                'refresh_token' => $admin->google_refresh,

            ];


            foreach ($adsStep1 as $adsStep1) {
                $ad = json_decode($adsStep1->add_id);
                $ad = $ad->results[0]->resourceName;
                // dd($ad);


                $insight = \Http::withHeaders([

                    'developer-token' => $google['dev_token'],
                    'login-customer-id' => $google['manager_id'],
                ])->withToken($google['accsss_token'])->
                post('https://googleads.googleapis.com/v10/customers/' . $google['customer_id'] . '/googleAds:search', [

                    "query" => "SELECT
    metrics.clicks,
    metrics.impressions,
    metrics.ctr,
    metrics.average_cpc,
    metrics.cost_micros
  FROM ad_group_ad
  where  ad_group_ad.resource_name='$ad'"
                ]);
                if ($insight->status() == 200) {


                    $res = json_decode($insight->body());

                    $adsStep1->cpc = isset($res->results[0]->metrics->averageCpc) ? $res->results[0]->metrics->averageCpc : 0;
                    $adsStep1->clicks = intval($res->results[0]->metrics->clicks);
                    $adsStep1->impressions = intval($res->results[0]->metrics->impressions);
                    $adsStep1->total = intval($adsStep1->clicks + $adsStep1->impressions);
                    $adsStep1->update();

                    $ins_detail = insightDetail::updateOrCreate(
                        ['add_id' => $adsStep1->id, 'date' => Carbon::now()->format('Y-m-d')],
                        ['cpc' => isset($res->results[0]->metrics->averageCpc) ? $res->results[0]->metrics->averageCpc : 0],
                        ['impressions' => intval($res->results[0]->metrics->impressions)],
                        ['clicks' => intval($res->results[0]->metrics->clicks)],

                    );

                }
            }


        }


    }
}
