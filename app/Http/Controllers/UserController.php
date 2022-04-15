<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    function create_ad($id){
        $advert=$id;
        return view('create_add',compact('advert'));
    }
}
