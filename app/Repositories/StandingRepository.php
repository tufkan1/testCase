<?php

namespace App\Repositories;

use App\Models\Standing;
use App\Models\Team;

class StandingRepository
{

    private $standing;
    private $team;

    public function __construct(Standing $standing, Team $team)
    {
        $this->standing = $standing;
        $this->team = $team;
    }

    public function checkStanding()
    {
        $result = $this->standing->get();
        return $result->isEmpty() ? false : true;
    }

    public function createStanding()
    {
        $result = $this->standing->get();
        if (!$result->isEmpty()) {
            return;
        }
        foreach ($this->getTeams() as $value) {
            $data = [
                'team_id'    => $value,
                'played'     => 0,
                'won'        => 0,
                'lose'       => 0,
                'draw'       => 0,
                'goal_drawn' => 0,
                'points'     => 0
            ];
            $this->standing->create($data);
        }

    }

    public function getTeams()
    {
        return $this->team->pluck('id');
    }

    public function getAll()
    {
        return $this->team->leftJoin('standings', 'teams.id', '=', 'standings.team_id')
            ->orderBy('standings.points', 'DESC')
            ->orderBy('standings.goal_drawn', 'DESC')
            ->orderBy('standings.won', 'DESC')
            ->get();
    }

    public function getStandingByTeamId($team_id)
    {
        return $this->standing->where('team_id', $team_id)->first();
    }

    public function truncateStanding()
    {
        $this->standing->truncate();
    }

    public function checkStandingStatus()
    {
        return $this->standing->select('played')->first();
    }

}
