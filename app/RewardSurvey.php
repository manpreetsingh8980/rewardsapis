<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RewardSurvey extends Model
{
	public $table = "reward_survey";
	
    protected $fillable = [
        'id','campaign_id','icon', 'name','url','instructions','description','short_description','amount'
    ];
}
