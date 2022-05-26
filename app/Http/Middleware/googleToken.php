<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class googleToken
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (\Auth::user()->gg_client != null && \Auth::user()->gg_secret != null && \Auth::user()->gg_refresh != null) {

            $api = \Http::post('https://www.googleapis.com/oauth2/v3/token', [
                'grant_type' => 'refresh_token',
                'client_id' => \Auth::user()->gg_client,
                'client_secret' => \Auth::user()->gg_secret,
                'refresh_token' => \Auth::user()->gg_refresh,
            ]);
            if ($api->status() == 200) {
                $api = json_decode($api->body());

                $user = User::find(\Auth::user()->id);
                $user->gg_access = $api->access_token;
                $user->update();
                return $next($request);

            }
            else{

                return redirect('profile')->with('error', 'Invalid credentials');
            }
        }
        else{
            return redirect('profile')->with('error', 'Please connect with google');
        }

    }

}
