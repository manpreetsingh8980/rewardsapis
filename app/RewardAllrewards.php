<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RewardAllrewards extends Model
{
    protected $fillable = [
        'id','admin_id','reward_title', 'reward_description','reward_icons','reward_coins','legal_text'
    ];
}
