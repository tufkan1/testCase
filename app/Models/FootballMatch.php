<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FootballMatch extends Model
{
    protected $fillable = [
        'week_id',
        'home_team',
        'away_team'
    ];
}
