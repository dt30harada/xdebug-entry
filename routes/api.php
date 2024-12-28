<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// CSRFトークン取得
Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(status: 204);
});

// ログイン
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // ユーザー情報登録
    Route::post('/users', [UserController::class, 'store']);

    // ログインユーザー情報取得
    Route::get('/users/me', [UserController::class, 'showMe']);

});
