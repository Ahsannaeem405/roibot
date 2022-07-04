<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class facebookToken
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
        if (\Auth::user()->fb_page == null || \Auth::user()->fb_account== null) {
            return redirect('profile')->with('error', 'Please connect with facebook');
        }

//        $permisions=collect(['ads_management','ads_read','public_profile','pages_show_list']);
//        if (\Auth::user()->fb_client == null) {
//            return redirect('profile')->with('error', 'Please connect with facebook');
//        } else {
//
//            $user = \Auth::user();
//            $url = \Http::get('https://graph.facebook.com/debug_token?input_token=' . $user->fb_token . '&access_token=' . $user->fb_token . '');
//          if ($url->status()==200){
//              $url = json_decode($url->body());
//
//              if ($url->data->is_valid == false) {
//                  return redirect('profile')->with('error', 'Your token is not valid.');
//              }
//              else{
//
//                  $scopes=     collect($url->data->scopes);
//
//                  $diff= count($permisions->diff($scopes));
//
//                  if ($diff>=1)
//                  {
//
//                      return redirect('profile')->with('error', 'please provide these permissions ads_management , ads_read, public_profile, pages_show_list');
//                  }
//                  else{
//                      return $next($request);
//                  }
//
//              }
//          }
//          else{
//              $url = json_decode($url->body());
//              return redirect('profile')->with('error', $url->error->message);
//          }
//
//        }

        return $next($request);
    }
}
