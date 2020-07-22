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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('admin/home', 'HomeController@adminHome')->name('admin.home')->middleware('is_admin');
Route::get('/logout', 'HomeController@adminLogout');
Route::get('allusers', 'HomeController@allUsers')->name('admin.allusers')->middleware('is_admin');
Route::get('user/{id}', 'HomeController@userDetail')->name('admin.user')->middleware('is_admin');
Route::get('rewards', 'HomeController@allRewards')->name('admin.allrewards')->middleware('is_admin');

Route::get('add_reward', function () {
    return view('addReward');
})->name('admin.addReward')->middleware('is_admin');

Route::post('submit_reward', 'HomeController@submitReward')->name('admin.submitReward')->middleware('is_admin');
Route::get('edit_reward/{id}', 'HomeController@editReward')->name('admin.editReward')->middleware('is_admin');
Route::get('delete_reward/{id}', 'HomeController@deleteReward')->name('admin.deleteReward')->middleware('is_admin');
Route::get('contact_us', 'HomeController@contactUs')->name('admin.contactUs')->middleware('is_admin');
Route::get('tasks', 'HomeController@tasks')->name('admin.tasks')->middleware('is_admin');

Route::get('add_task', function () {
    return view('addTask');
})->name('admin.addTask')->middleware('is_admin');

Route::post('submit_task', 'HomeController@submitTask')->name('admin.submitTask')->middleware('is_admin');
Route::get('edit_task/{id}', 'HomeController@editTask')->name('admin.editTask')->middleware('is_admin');
Route::get('delete_task/{id}', 'HomeController@deleteTask')->name('admin.deleteTask')->middleware('is_admin');
Route::get('reward_request', 'HomeController@rewardRequest')->name('admin.rewardRequest')->middleware('is_admin');
Route::get('reward_request_approve/{id}', 'HomeController@rewardRequestAppr')->name('admin.rewardRequestappr')->middleware('is_admin');
Route::get('reward_request_deny/{id}', 'HomeController@rewardRequestDeny')->name('admin.rewardRequestdeny')->middleware('is_admin');
