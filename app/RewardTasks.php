<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RewardTasks extends Model
{
    protected $fillable = [
        'id','title','description', 'icon','coins','repeat'
    ];
}
