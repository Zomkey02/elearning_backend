<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;

/* Route::get('test', function () {
    return response()->json(['message' => 'API is working']);
}); */


Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class);

Route::get('courses', [CourseController::class, 'index']);
Route::get('/course/{courseId}', [CourseController::class, 'show']);


Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user', function (Request $request) { return $request->user(); });
    
    Route::post('/logout', LogoutController::class);

    Route::post('/course', [CourseController::class, 'store']);

    /* Route::post('/lesson', [LessonController::class, 'store']);
    Route::get('/lesson/{lessonId}', [LessonController::class, 'show']);
 */

});
