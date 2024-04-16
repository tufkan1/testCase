<?php

namespace App\Services\Simulator;

use App\Repositories\MatchRepository;
use App\Repositories\StandingRepository;

class MatchSimulator implements ResultSimulatorInterface
{

    protected $standingRepository;
    protected $matchRepository;

    public function __construct(StandingRepository $standingRepository, MatchRepository $matchRepository)
    {
        $this->standingRepository = $standingRepository;
        $this->matchRepository = $matchRepository;
    }

    public function simulate($match)
    {
        $home = $this->standingRepository->getStandingByTeamId($match->home_team);
        $away = $this->standingRepository->getStandingByTeamId($match->away_team);
        $homeScore = $this->generateScore(true, $home->id);
        $awayScore = $this->generateScore(false, $away->id);

        $this->updateMatchScore($homeScore, $awayScore, $home, $away);
        return $this->matchRepository->resultSaver($match, $homeScore, $awayScore);

    }

    public function bulkSimulate($matches)
    {
        foreach ($matches as $match) {
            $this->simulate($match);
        }
    }

    public function updateMatchScore($homeScore, $awayScore, $home, $away)
    {
        $this->matchRepository->updateMatchScore($homeScore, $awayScore, $home, $away);
    }


    public function generateScore(bool $is_home, int $teamRank)
    {
        //this generator is assuming home team and also current rank to generate result
        return $is_home ? rand(0, 10) : rand(0, 10 - $teamRank);
    }

}
