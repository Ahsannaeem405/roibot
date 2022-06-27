<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class adminController extends Controller
{
    public function index()
    {
        $user=User::where('role','user')->count();
        return view('dashbaord.dashbaord.index',compact('user'));
    }

    public function users()
    {
        $users=User::where('role','user')->get();
        return view('dashbaord.users.index',compact('users'));
    }
    public function userDelete($id)
    {
        $user=User::findOrFail($id);
        $user->delete();
        return back()->with('success','Record deleted successfully');
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

        $user=new User();
        $user->name=$request->name;
        $user->role='user';
        $user->email=$request->email;
        $user->password=\Hash::make($request->password);
        $user->save();

        return back()->with('success','User created successfully');


    }

    public function userEdit(User $id){

        $user=$id;

        return view('dashbaord.users.edit',compact('user'));
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id.'',


        ]);

        $user=User::find($id);
        $user->name=$request->name;
        $user->email=$request->email;
        if ($request->password)
        {
            $request->validate([

                'password' => 'required|min:6',

            ]);
        $user->password=\Hash::make($request->password);
        }
        $user->update();

        return back()->with('success','User updated successfully');
    }
}
