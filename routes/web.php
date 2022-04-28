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



    //add
    Route::get('/create_add', function () {
        return view('Select_advert');
    });

    Route::get('create_ad/{id}',[UserController::class,'create_ad'])->middleware('facebookToken');
    Route::post('post/add',[\App\Http\Controllers\AdvertisementController::class,'PostAdd'])->middleware('facebookToken');
    Route::post('publish/{id}',[\App\Http\Controllers\AdvertisementController::class,'publish'])->middleware('facebookToken');
    Route::get('search/city',[\App\Http\Controllers\AdvertisementController::class,'searchCity'])->middleware('facebookToken');
    Route::get('search/interest',[\App\Http\Controllers\AdvertisementController::class,'searchInterest'])->middleware('facebookToken');

    //manage add
    Route::get('manage_view',[\App\Http\Controllers\UserController::class,'ManageAdd'])->middleware('facebookToken');
    Route::get('manage_detail/{id}',[UserController::class,'mangeDetail'])->middleware('facebookToken');

    //compain
    Route::get('compain/delete/{id}',[\App\Http\Controllers\AdvertisementController::class,'conpainDelete'])->middleware('facebookToken');
    Route::get('compain/pause/{id}',[\App\Http\Controllers\AdvertisementController::class,'pauseCompain'])->middleware('facebookToken');
    Route::get('compain/reactive/{id}',[\App\Http\Controllers\AdvertisementController::class,'activeCompain'])->middleware('facebookToken');


    //insights
    Route::get('insight_view',[\App\Http\Controllers\UserController::class,'insightView'])->middleware('facebookToken');
    Route::get('insight_detail/{compain}/{add}',[UserController::class,'insightDetail'])->middleware('facebookToken');



//profile
Route::get('profile',[UserController::class,'profile']);
Route::post('profile/update',[UserController::class,'profileUpdate']);
Route::post('update/fb',[UserController::class,'updateFb']);

    //gallary
Route::get('mediaGallery',[UserController::class,'mediaGallery']);
Route::post('gallery/delete',[UserController::class,'galleryDelete']);
 Route::post('upload/image',[UserController::class,'uploadImgae']);
 Route::get('get/images',[UserController::class,'getImages']);


 //logout
    Route::get('/logout', function () {
  Auth::logout();
  return redirect('/');
    });




});

//test
Route::get('compainFB',[\App\Http\Controllers\Controller::class,'index']);
Route::get('compainGG',[\App\Http\Controllers\Controller::class,'index2']);
Route::get('insightFB',[\App\Http\Controllers\Controller::class,'insightFB']);
Route::get('step1',[\App\Http\Controllers\Controller::class,'step1']);
Route::get('step2',[\App\Http\Controllers\Controller::class,'step2']);
Route::get('step3',[\App\Http\Controllers\Controller::class,'step3']);
Route::get('step4',[\App\Http\Controllers\Controller::class,'step4']);
Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
