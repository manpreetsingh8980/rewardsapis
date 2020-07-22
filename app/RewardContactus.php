<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RewardContactus extends Model
{
	
	public $table = "reward_contactus";
	
    protected $fillable = [
        'id','reward_user_id','email', 'question'
    ];
}
