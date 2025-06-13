<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;



/* Route::get('test', function () {
    return response()->json(['message' => 'API is working']);
}); */


Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class);


Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', LogoutController::class);

});
