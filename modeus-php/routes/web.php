<?php

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
    return view('start');
});

Route::get('/login', ['as' => 'login', 'uses' => function () {
    Auth::logout();
    return view('login');
}]);

Route::get("/admin", ['as' => 'admin', 'uses' =>function (){
    return view('admin');
}])->middleware(['auth.admin']);

Route::post('admin',  ['as' => 'admin', 'uses' => 'Auth\LoginController@login']);

Route::namespace('Admin')->prefix('admin')->middleware(['auth.admin'])->name('admin.')->group(function (){
    Route::resource('/extra_subjects','ExtraSubjectsController');
});

Route::namespace('Admin')->prefix('admin')->middleware(['auth.admin'])->name('admin.')->group(function (){
    Route::resource('/basic_subjects','BasicSubjectsController');
});

Route::namespace('Admin')->prefix('admin')->middleware(['auth.admin'])->name('admin.')->group(function (){
    Route::resource('/occupations','OccupationsController');
});

Route::namespace('Admin')->prefix('admin')->middleware(['auth.admin'])->name('admin.')->group(function (){
    Route::resource('/curriculum','CurriculumsController');
});

//Route::resource('statistic','StatisticController');
