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

Route::get('tmp', function () {
    return view('welcome');
});
