<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RewardUsers extends Model
{
    protected $fillable = [
        'id','admin_id','device_type', 'device_token','devide_id','datetime'
    ];
	
	public function getUserRewards(){
		return $this->hasMany('App\RewardUsersRewards','reward_user_id','id');
	}
}
