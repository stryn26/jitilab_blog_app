<?php

use App\Http\Controllers\auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Blog;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/signup', [auth::class,'register']);
Route::post('/signin', [auth::class,'login'])->name('login');
Route::get('/signout', [auth::class,'logout']);

Route::middleware('auth:sanctum')->group( function () {
    Route::apiResource('/blogs',Blog::class)->parameters(['blogs' => 'uuid']);
});



