<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
  public function register(Request $request)
  {

      try {
          $user=User::firstOrCreate(
              ['email'=>$request->email],
              [
                 'name'=> $request->name,
                 'pawsword'=> Hash::make($request->password),
                  'username'=>$request->username

              ]
          );


      return response()->json(['success'=>true]);
      }
      catch (\Exception $exception)
      {
          return response()->json(['success'=>false,'error'=>$exception->getMessage()]);
      }
  }

  public function checklogin(Request $request){
      $data=$request->data;
      $email=openssl_decrypt($request->data,'AES-128-CTR','test',0,1234567812345678);
      $user=User::where('email',$email)->first();
      if ($user)
      {
          \Auth::login($user);

          return redirect('/');
      }
      else{
          return  redirect('/');
      }
  }
}
