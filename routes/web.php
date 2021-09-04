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

Route::post('/app/tags_create',[App\Http\Controllers\TestController::class,'create']);
Route::post('/app/cats_create',[App\Http\Controllers\TestController::class,'cats_create']);
Route::get('app/get_tags',[App\Http\Controllers\TestController::class,'get_tags']);
Route::get('app/get_categories',[App\Http\Controllers\TestController::class,'get_categories']);
Route::post('/app/tags_edit',[App\Http\Controllers\TestController::class,'tags_edit']);
Route::post('/tag/delete',[App\Http\Controllers\TestController::class,'tags_delete']);
Route::post('/app/upload',[App\Http\Controllers\TestController::class,'upload']);
Route::post('/delete/photo',[App\Http\Controllers\TestController::class,'delete_photo']);



Route::any('/{salam}', function ($salam) {
    return view('welcome');
});

Route::any('/', function () {
    return view('welcome');
});

