<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/',function(){
	if(Auth::guard('admin')->guest()){
		return redirect('login');
	}else{
		return redirect('dashboard');
	}
});


Route::resource('login','Admin\AuthController');
Route::group(['middleware'=>'admin'],function(){
	Route::resource('dashboard', 'Admin\DashboardController');
	// Customer lottory //
	Route::resource('customer','Admin\CustomerController');
	// Setting aword of lottory //
	Route::resource('result','Admin\ResultController');
	// Setting aword of lottory //
	Route::resource('setting','Admin\SettingController');
	// Dealer management//
	Route::resource('dealer', 'Admin\DealerController');
	// Administrator management//
	Route::resource('user', 'Admin\AdminController');
	
});

Route::get('tmp', function () {
    return view('welcome');
});
