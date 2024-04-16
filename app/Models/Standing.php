<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Standing extends Model
{
    protected $fillable = [
        'team_id',
        'points',
        'won',
        'lose',
        'draw',
        'played',
        'goal_drawn'
    ];

    public function won($goalDrawn)
    {
        $this->played     += 1;
        $this->won        += 1;
        $this->points     += 3;
        $this->goal_drawn += $goalDrawn;
    }

    public function lose($goalDrawn)
    {
        $this->played     += 1;
        $this->goal_drawn += -$goalDrawn;
        $this->lose       += 1;
    }

    public function draw()
    {
        $this->played += 1;
        $this->draw   += 1;
        $this->points += 1;
    }

}
