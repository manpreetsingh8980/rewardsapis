<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RewardUsersRewards extends Model
{
    protected $fillable = [
        'reward_user_id','reward_id', 'reward_status'
    ];
	
	public function getRewardDetail(){
		return $this->belongsTo('App\RewardAllrewards','reward_id','id');
	}
}
