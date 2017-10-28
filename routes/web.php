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


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::resource('datatable/users', 'DataTable\UserController');

Route::get('/admin/users', function () {
    return view('admin.users.index');
});

Route::get('/admin/zxc', function () {
    dd(route('users.index'));
    return view('admin.users.index');
});

Route::get('/admin/master1', function () {
    return 'master1';
});

Route::get('/admin/master2', function () {
    return 'master2';
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
