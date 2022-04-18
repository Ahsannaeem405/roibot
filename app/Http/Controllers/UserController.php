<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\mediaGallary;
use Illuminate\Http\Request;

class UserController extends Controller
{
    function create_ad($id)
    {
        $advert = $id;
        return view('create_add', compact('advert'));
    }

    public function ManageAdd()
    {
        $compain = Advertisement::where('user_id', \Auth::user()->id)->orderBy('id', 'DESC')->get();

        return view('manage_view', compact('compain'));
    }

    public function main()
    {
        $compain = Advertisement::where('user_id', \Auth::user()->id)->orderBy('id', 'DESC')->get();

        return view('index', compact('compain'));
    }

    public function mangeDetail($id)
    {
        $compain = Advertisement::where('id', $id)->orderBy('id', 'DESC')->get();
        return view('manage_detail', compact('compain'));
    }

    public function insightDetail($id)
    {

        $compain = Advertisement::where('id', $id)->orderBy('id', 'DESC')->first();
        return view('insights_detail', compact('compain'));
    }

    public function insightView()
    {
        $compain = Advertisement::where('user_id', \Auth::user()->id)->orderBy('id', 'DESC')->get();
        return view('insight_view', compact('compain'));
    }

    public function uploadImgae(Request $request)
    {
        if ($request->hasFile('file')) {
            $gallary = new mediaGallary();
            $gallary->user_id = \Auth::user()->id;

            $file = $request->file;
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename = $file->getClientOriginalName();
            $file->move('images/gallary/', $filename);


            $gallary->image = $filename;
            $gallary->save();
        }
    }

    public function mediaGallery()
    {
        $gallary=mediaGallary::where('user_id',\Auth::user()->id)->get();
        return view('mediaGallery',compact('gallary'));
    }

    public function galleryDelete(Request $request)
    {
        if($request->check)
        {
            mediaGallary::whereIn('id',$request->check)->delete();
            return back()->with('success','Record deleted successfully');
        }
        else{
            return back()->with('error','Please select image to delete');
        }

    }
}
