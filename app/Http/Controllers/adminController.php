<?php

namespace App\Http\Controllers;

use App\Models\creditials;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class adminController extends Controller
{
    public function index()
    {
        $user = User::where('role', 'user')->count();
        return view('dashbaord.dashbaord.index', compact('user'));
    }

    public function users()
    {
        $users = User::where('role', 'user')->get();
        return view('dashbaord.users.index', compact('users'));
    }

    public function userDelete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'Record deleted successfully');
    }

    public function create()
    {
        return view('dashbaord.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',

        ]);

        $rand = rand(1111, 9999);
        $admin=creditials::first();
        $google = [
            'dev_token' => $admin->google_developer,
            'manager_id' => $admin->manager,
            'customer_id' => $admin->gg_customer,
            'client_id' => $admin->google_app,
            'secret_id' => $admin->google_secret,
            'accsss_token' => $admin->google_token,
            'refresh_token' => $admin->google_refresh,

        ];

        $client = \Http::withHeaders([

            'developer-token' => $google['dev_token'],
            'login-customer-id' => $google['manager_id'],
        ])->withToken($google['accsss_token'])->
        post('https://googleads.googleapis.com/v10/customers/' . $google['manager_id'] . ':createCustomerClient', [

                'customerClient' => array(
                    'descriptiveName' => $request->name .' '. $rand,
                    'currencyCode' =>'USD',
                    'timeZone' =>'America/New_York'
                )

        ]);
     $response=json_decode($client->body());
     $response=explode('/',$response->resourceName);




        $user = new User();
        $user->name = $request->name;
        $user->role = 'user';
        $user->email = $request->email;
        $user->gg_customer =$response[1];
        $user->password = \Hash::make($request->password);
        $user->save();

        return back()->with('success', 'User created successfully');


    }

    public function userEdit(User $id)
    {

        $user = $id;

        return view('dashbaord.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id . '',


        ]);

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $request->validate([

                'password' => 'required|min:6',

            ]);
            $user->password = \Hash::make($request->password);
        }
        $user->update();

        return back()->with('success', 'User updated successfully');
    }

    public function facebook()
    {
        $rec=creditials::first();

        return view('dashbaord.facebook',compact('rec'));
    }
    public function google()
    {
        $rec=creditials::first();
        return view('dashbaord.google',compact('rec'));
    }

    public function facebookUpdate(Request $request)
    {
        $rec=creditials::first();
        $rec->facebook_app=$request->app;
        $rec->facebook_token=$request->token;
        $rec->facebook_secret=$request->secret;
        $rec->update();

        return back()->with('success','updated successfully');

    }

    public function googleUpdate(Request $request)
    {
        $rec=creditials::first();
        $rec->google_app=$request->google_app;
        $rec->google_secret=$request->google_secret;
        $rec->google_developer=$request->google_developer;
        $rec->google_token=$request->google_token;
        $rec->google_refresh=$request->google_refresh;
        $rec->manager=$request->manager;
        $rec->update();

        return back()->with('success','updated successfully');
    }
}
