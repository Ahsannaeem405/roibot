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
Route::get('checklogin', [\App\Http\Controllers\ApiController::class, 'checklogin']);

Route::get('/', function () {
    return redirect('/login');
});

Route::prefix('')->middleware('auth')->group(function () {

    Route::get('index', [UserController::class, 'main']);


    //add
    Route::get('/create_add', function () {
        return view('Select_advert');
    });

    Route::get('create_ad/1', [UserController::class, 'create_ad_fb'])->middleware('facebookToken');
    Route::get('create_ad/2', [UserController::class, 'create_ad_gg'])->middleware('googleToken');
    Route::post('post/add', [\App\Http\Controllers\AdvertisementController::class, 'PostAdd'])->middleware('facebookToken');
    Route::post('post/add/google', [\App\Http\Controllers\AdvertisementController::class, 'PostAddGoogle'])->middleware('facebookToken');
    Route::post('publish/{id}', [\App\Http\Controllers\AdvertisementController::class, 'publish'])->middleware('facebookToken');
    Route::get('search/city', [\App\Http\Controllers\AdvertisementController::class, 'searchCity'])->middleware('facebookToken');
    Route::get('search/city/google', [\App\Http\Controllers\AdvertisementController::class, 'searchCityGoogle']);
    Route::get('search/interest', [\App\Http\Controllers\AdvertisementController::class, 'searchInterest'])->middleware('facebookToken');

    //manage add
    Route::get('manage_view', [\App\Http\Controllers\UserController::class, 'ManageAdd'])->middleware('facebookToken');
    Route::get('manage_detail/{id}', [UserController::class, 'mangeDetail'])->middleware('facebookToken');

    //compain
    Route::get('compain/delete/{id}', [\App\Http\Controllers\AdvertisementController::class, 'conpainDelete'])->middleware('facebookToken');
    Route::get('compain/pause/{id}', [\App\Http\Controllers\AdvertisementController::class, 'pauseCompain'])->middleware('facebookToken');
    Route::get('compain/reactive/{id}', [\App\Http\Controllers\AdvertisementController::class, 'activeCompain'])->middleware('facebookToken');


    //insights
    Route::get('insight_view', [\App\Http\Controllers\UserController::class, 'insightView'])->middleware('facebookToken');
    Route::get('insight_detail/{compain}/{add}', [UserController::class, 'insightDetail'])->middleware('facebookToken');


//profile
    Route::get('profile', [UserController::class, 'profile']);
    Route::post('profile/update', [UserController::class, 'profileUpdate']);
    Route::post('update/fb', [UserController::class, 'updateFb']);
    Route::post('update/google', [UserController::class, 'updateGoogle']);

    //gallary
    Route::get('mediaGallery', [UserController::class, 'mediaGallery']);
    Route::post('gallery/delete', [UserController::class, 'galleryDelete']);
    Route::post('upload/image', [UserController::class, 'uploadImgae']);
    Route::get('get/images', [UserController::class, 'getImages']);


    //logout
    Route::get('/logout', function () {
        Auth::logout();
        return redirect('/');
    });


});
Route::get('connect-with-facebook', [\App\Http\Controllers\AdvertisementController::class, 'connectWithFacebook']);
Route::get('connect-with-google', [\App\Http\Controllers\AdvertisementController::class, 'connectWithGoogle']);
//test


Route::get('insightFB', [\App\Http\Controllers\Controller::class, 'insightFB']);
Route::get('intrest', [\App\Http\Controllers\Controller::class, 'intrest']);
Route::get('behaviour', [\App\Http\Controllers\Controller::class, 'behaviour']);
Route::get('dempgraphics', [\App\Http\Controllers\Controller::class, 'dempgraphics']);
Route::get('data', [\App\Http\Controllers\Controller::class, 'data']);
Route::get('data2', [\App\Http\Controllers\Controller::class, 'data2']);

Auth::routes([]);


Route::prefix('admin')->middleware('auth', 'admin')->group(function () {
    Route::get('/index', [\App\Http\Controllers\adminController::class, 'index']);
    Route::get('/users', [\App\Http\Controllers\adminController::class, 'users']);
    Route::get('user/delete/{id}', [\App\Http\Controllers\adminController::class, 'userDelete']);
    Route::get('create/user', [\App\Http\Controllers\adminController::class, 'create']);
    Route::get('user/edit/{id}', [\App\Http\Controllers\adminController::class, 'userEdit']);
    Route::post('user/store', [\App\Http\Controllers\adminController::class, 'store'])->name('user.store');
    Route::post('user/update/{id}', [\App\Http\Controllers\adminController::class, 'update']);
});

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
