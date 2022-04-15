<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

Route::prefix('')->middleware('auth')->group(function (){

    Route::get('/index', function () {
        return view('index');
    });
    Route::get('/manage_view', function () {
        return view('manage_view');
    });
    Route::get('/create_add', function () {
        return view('Select_advert');
    });
    Route::get('create_ad/{id}',[UserController::class,'create_ad']);

    Route::get('/insight_view', function () {
        return view('insight_view');
    });


    Route::get('/profile', function () {
        return view('profile');
    });

    Route::get('/mediaGallery', function () {
        return view('mediaGallery');
    });

    Route::get('/manage_detail', function () {
        return view('manage_detail');
    });
    Route::get('/insight_detail', function () {
        return view('insights_detail');
    });

    Route::get('/logout', function () {

  Auth::logout();
  return redirect('/');
    });

    Route::get('compainFB',[\App\Http\Controllers\Controller::class,'index']);


});


Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
