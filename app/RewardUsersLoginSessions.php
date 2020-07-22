<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RewardUsersLoginSessions extends Model
{
    protected $fillable = [
        'reward_user_id','api_token', 'token_status'
    ];
}
