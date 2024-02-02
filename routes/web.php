<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\FeedbackController;
//use Event;
use App\Events\NewFeedback;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Auth::routes();


Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => ['auth:web']], function () {


    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/profile', [App\Http\Controllers\HomeController::class, 'profile'])->name('profile');
    Route::post('/profile', [App\Http\Controllers\HomeController::class, 'profile_update'])->name('profile_update');
    Route::resource('/feedback', FeedbackController::class);
    Route::post('/feedback/{id}', [FeedbackController::class, 'update']); //over ride the route for update the data
    Route::get('/feedback/page/getdata', [FeedbackController::class, 'getdata']); //over ride the route for update the data
    Route::get('/feedback/home/getdata', [FeedbackController::class, 'get_data_home']); //over ride the route for update the data
    Route::get('/feedback/like/{id}', [FeedbackController::class, 'like']); //over ride the route for update the data
    Route::get('/feedback/dislike/{id}', [FeedbackController::class, 'dislike']); //over ride the route for update the data
    Route::post('/comment/feedback/{id}', [FeedbackController::class, 'comment']); //over ride the route for update the data

    Route::post('/save-token', [App\Http\Controllers\HomeController::class, 'saveToken'])->name('save-token');

});
