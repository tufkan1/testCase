<?php
namespace App\Repositories;


use App\Models\FootballMatch;
use App\Models\Team;
use App\Models\Week;

class MatchRepository
{

    protected $team;
    protected $match;
    protected $week;

    public function __construct(Team $team, FootballMatch $match, Week $week)
    {
        $this->team  = $team;
        $this->match = $match;
        $this->week  = $week;
    }


    public function getTeamsId()
    {
        return $this->team->pluck('id')->toArray();
    }


    public function getWeeksId()
    {
        return $this->week->pluck('id');
    }


    public function getWeeks()
    {
        return $this->week->get();
    }

    public function createFixture($fixtures)
    {
        foreach ($fixtures as $fixture) {
            $this->match->create([
                'home_team' => $fixture['home'],
                'away_team' => $fixture['away'],
                'week_id' => $fixture['week']
            ]);
        }
    }

    public function checkIfFixturesDrawn()
    {
        return $this->match->count() ? true : false;
    }


    /**
     * @return mixed
     */
    public function getFixture()
    {

        return $this->match->select(
            'football_matches.id',
            'football_matches.status',
            'football_matches.week_id',
            'football_matches.home_team_goal',
            'football_matches.away_team_goal',
            'week_id',
            'home.name as home_team',
            'home.logo as home_logo',
            'home.shirt as home_shirt',
            'away.logo as away_logo',
            'away.shirt as away_shirt',
            'away.name as away_team')
            ->join('weeks', 'weeks.id', '=', 'football_matches.week_id')
            ->join('teams as home', 'home.id', '=', 'football_matches.home_team')
            ->join('teams as away', 'away.id', '=', 'football_matches.away_team')
            ->orderBy('week_id', 'ASC')
            ->get();
    }

    /**
     * @param $week_id
     * @return mixed
     */
    public function getFixtureByWeekId($week_id)
    {

        return $this->match->select(
            'football_matches.id',
            'football_matches.status',
            'football_matches.week_id',
            'football_matches.home_team_goal',
            'football_matches.away_team_goal',
            'week_id',
            'weeks.title',
            'home.logo as home_logo',
            'away.logo as away_logo',
            'home.name as home_team',
            'away.name as away_team')
            ->join('weeks', 'weeks.id', '=', 'football_matches.week_id')
            ->join('teams as home', 'home.id', '=', 'football_matches.home_team')
            ->join('teams as away', 'away.id', '=', 'football_matches.away_team')
            ->where('football_matches.week_id', '=', $week_id)
            ->orderBy('football_matches.id', 'ASC')
            ->get();
    }

    /**
     * @param $week
     * @return mixed
     */
    public function getMatchesFromWeek($week)
    {
        return $this->match->where([['week_id', '=', $week], ['status', '=', 0]])->get();
    }


    public function getAllMatches($status = 0)
    {
        return $this->match->where('status', '=', $status)->get();
    }

    public function getAllMatchesByTeamId($teamId)
    {
        return $this->match
            ->where(function ($q) use ($teamId) {
                $q->where('home_team', '=', $teamId)
                    ->orWhere('away_team', '=', $teamId);
            })
            ->where('status', '=', 0)
            ->get();
    }

    /**
     * @param $homeScore
     * @param $awayScore
     * @param $home
     * @param $away
     */
    public function updateMatchScore($homeScore, $awayScore, $home, $away)
    {
        $goalDrawn = abs($awayScore - $homeScore);

        if ($homeScore > $awayScore) {
            $home->won($goalDrawn);
            $away->lose($goalDrawn);

        } elseif ($awayScore > $homeScore) {
            $away->won($goalDrawn);
            $home->lose($goalDrawn);
        } else {
            $home->draw();
            $away->draw();
        }

        $home->save();
        $away->save();
    }


    /**
     *
     */
    public function truncateMatches()
    {
        $this->match->truncate();
    }

    public function resultSaver($match, $homeScore, $awayScore)
    {
        $match->home_team_goal = $homeScore;
        $match->away_team_goal = $awayScore;
        $match->status         = 1;
        return $match->save();
    }

}
