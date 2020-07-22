<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RewardRequest extends Model
{
	public $table = "reward_request";
	
    protected $fillable = [
        'id','reward_user_id','reward_id', 'reward_status'
    ];
	
	public function getUser(){
		return $this->belongsTo('App\RewardUsers','reward_user_id','id');
	}
	public function getReward(){
		return $this->belongsTo('App\RewardAllrewards','reward_id','id');
	}
}
