<?php

namespace App\Http\Controllers;

use App\Repositories\MatchRepository;
use App\Repositories\StandingRepository;
use App\Services\Simulator\MatchSimulator;

class SimulatorController extends Controller
{

    private $standingRepository;
    private $matchRepository;

    public function __construct(StandingRepository $standingRepository, MatchRepository $matchRepository)
    {
        $this->standingRepository = $standingRepository;
        $this->matchRepository    = $matchRepository;
    }

    public function playAllWeeks()
    {
        $matches = $this->matchRepository->getAllMatches();
        (new MatchSimulator($this->standingRepository, $this->matchRepository))->bulkSimulate($matches);
        return response()->json(['status' => 'ok'], 200);
    }


    public function playWeekly($week)
    {
        $matches = $this->matchRepository->getMatchesFromWeek($week);
        (new MatchSimulator($this->standingRepository, $this->matchRepository))->bulkSimulate($matches);
        $result = $this->matchRepository->getFixtureByWeekId($week);

        return response()->json([
            'status' => 'ok',
            'matches' => $result
        ], 201);
    }
}
