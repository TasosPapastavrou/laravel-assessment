<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\AuthenticationUser;
use App\Http\Controllers\api\v1\BlogController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login',[AuthenticationUser::class,'login']); 
Route::post('/register',[AuthenticationUser::class,'register']); 


Route::middleware('auth:api')->group( function () {

    Route::get('/logout',[AuthenticationUser::class,'logout']); 
    Route::get('/get-categories',[BlogController::class,'getCategories']);  
    Route::get('/get-user-comments',[BlogController::class,'getUserComments']); 
    Route::get('/get-user-posts',[BlogController::class,'getUserPosts']); 
    Route::post('/add-comment/{postId}',[BlogController::class,'addComment']); 
    Route::get('/delete-post/{postId}',[BlogController::class,'deletePost']); 
    Route::post('/update-post/{postId}',[BlogController::class,'postUpdate']); 
    Route::post('/add-post',[BlogController::class,'addPost']); 
    Route::get('/get-post',[BlogController::class,'getPost']); 
    Route::get('/get-posts',[BlogController::class,'getPosts']); 

});



