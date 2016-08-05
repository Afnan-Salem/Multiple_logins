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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::auth();

Route::get('/home','HomeController@redirectHome');
Route::get('/role', 'HomeController@index');
Route::get('/home/{role}', function ($role) {
    return view('role',['role'=>$role]);
});


Route::get('/verify',function(){
    return view('verify');
});
Route::get('/verify/{code}','HomeController@verify');


Route::get('/register/{role}', function ($role) {
    return view('auth.register',['role'=>$role]);
});
Route::post('/register/{role}/user','Auth\AuthController@validateForm');


Route::get('/member/register', function () {
    return view('register_member');
});
Route::post('/register','memberController@register_member');



