<?php

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
    return view('welcome');
});













































































Route::get('/api/{users}/{id}', function (\Illuminate\Http\Request $request) {
    $request->headers->set('Accept', 'application/json');
    return \App\Models\User::findOrFail($request->route()->parameter('id'));
})->where('users', 'users');
