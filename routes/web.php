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

Route::get('index',[UserController::class,'main']);

    Route::get('/create_add', function () {
        return view('Select_advert');
    });

    //add
    Route::get('create_ad/{id}',[UserController::class,'create_ad']);
    Route::post('post/add',[\App\Http\Controllers\AdvertisementController::class,'PostAdd']);
    Route::get('manage_view',[\App\Http\Controllers\UserController::class,'ManageAdd']);
    Route::get('insight_view',[\App\Http\Controllers\UserController::class,'insightView']);





    Route::get('/profile', function () {
        return view('profile');
    });

Route::get('mediaGallery',[UserController::class,'mediaGallery']);
Route::post('gallery/delete',[UserController::class,'galleryDelete']);

 Route::get('manage_detail/{id}',[UserController::class,'mangeDetail']);
 Route::get('insight_detail/{id}',[UserController::class,'insightDetail']);
 Route::post('upload/image',[UserController::class,'uploadImgae']);


    Route::get('/logout', function () {

  Auth::logout();
  return redirect('/');
    });




});

Route::get('compainFB',[\App\Http\Controllers\Controller::class,'index']);
Route::get('compainGG',[\App\Http\Controllers\Controller::class,'index2']);
Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
