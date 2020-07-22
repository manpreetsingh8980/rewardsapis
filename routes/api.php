<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

#Signup
Route::get('dashboard','ApiController@dashboard'); 
#get rewards
Route::get('get_rewards','ApiController@getRewardsUser'); 
#user reward detail
Route::get('reward_details/{reward_id}','ApiController@rewardDetails');
#user profile
Route::get('user_profile/{user_id}','ApiController@userDetail');
#user contact
Route::post('contact_us','ApiController@userContactUs');
#survey
Route::get('get_survey','ApiController@getSurvey');
Route::get('survey_detail/{compaign_id}','ApiController@getSurveyDetail');