<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// small test to check connection to frontend
/* Route::get('/test', function () {
    return response()->json(['message' => 'Connection works!']);
}); */
