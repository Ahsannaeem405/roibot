<?php

namespace App\Console\Commands;

use App\Models\creditials;
use App\Models\User;
use Illuminate\Console\Command;

class googleTokenUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:googleToken';

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
        $users=creditials::all();
        foreach ($users as $user)
        {




            $api = \Http::post('https://www.googleapis.com/oauth2/v3/token', [
                'grant_type' => 'refresh_token',
                'client_id' => $user->google_app,
                'client_secret' => $user->google_secret,
                'refresh_token' => $user->google_refresh,
            ]);
            if ($api->status() == 200) {
                $api = json_decode($api->body());

                $user->google_token = $api->access_token;
                $user->update();

            }


        }

    }
}
