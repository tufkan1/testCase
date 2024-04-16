<?php

namespace App\Services\FixtureDraw;


class HomeAndAwayDraw implements FixtureDrawInterface
{

    protected $teams;

    public function __construct(array $teams)
    {
        $this->teams = $teams;
        shuffle($this->teams);
    }

    public function getFixturesPlan()
    {
        $numberOfWeeks          = $this->getNumberOfWeeks(count($this->teams));
        $numberOfWeeklyFixtures = $this->getNumberOfWeeklyFixtures(count($this->teams));
        $allFixtures            = $this->makeAllFixtures();
        shuffle($allFixtures);
        $weeklyFixtures         = $this->makeWeeklyFixtures($numberOfWeeks, $numberOfWeeklyFixtures, $allFixtures);
        return $this->flatFixtures($weeklyFixtures);
    }

    public function flatFixtures($fixtures)
    {
        $flatFixturesArray = [];
        $allWeekFixtures   = array_values($fixtures);
        foreach ($allWeekFixtures as $weekFixtures) {
            foreach ($weekFixtures as $fixture) {
                $flatFixturesArray[] = $fixture;
            }
        }

        return $flatFixturesArray;
    }

    public function makeWeeklyFixtures(int $numberOfWeeks, int $numberOfWeeklyFixtures, array $allFixtures)
    {

        $generatedWeeklyFixtures = [];
        //loop on weeks to generate all weeks
        for ($i = 1; $i <= $numberOfWeeks; $i++) {
            //loop on each week number of match to generate them
            for ($j = 1; $j <= $numberOfWeeklyFixtures; $j++) {

                foreach ($allFixtures as &$fixture) {

                    if ($fixture['used'] === 1) {
                        continue;
                    }

                    $flag = false;
                    if (!count($generatedWeeklyFixtures) === 0 || array_key_exists($i, $generatedWeeklyFixtures)) {
                        $flag = $this->isMatchDuplicatedInWeek($fixture, $generatedWeeklyFixtures[$i], $i);
                    }

                    if ($flag) {
                        continue;
                    }

                    $generatedWeeklyFixtures[$i][] = [
                        'home' => $fixture['home'],
                        'away' => $fixture['away'],
                        'week' => $i,
                        'status' => 0
                    ];

                    $fixture['used'] = 1;
                    break;
                }
            }
        }

        return $generatedWeeklyFixtures;
    }

    public function isMatchDuplicatedInWeek($fixture, $allFixtures): bool
    {
        $response = false;
        foreach ($allFixtures as $f) {
            if (
            (
                ($fixture['home'] == $f['home'] || $fixture['home'] == $f['away']) ||
                ($fixture['away'] == $f['away'] || $fixture['away'] == $f['home'])
            )
            ) {
                $response = true;
                break;
            }
        }

        return $response;
    }

    public function getNumberOfWeeks($teamCount)
    {
        if ($teamCount % 2 == 0) {
            return ($teamCount - 1) * 2;
        }

        return ($teamCount - 1) / 2;
    }

    public function getNumberOfWeeklyFixtures($teamCount)
    {
        return $teamCount / 2;
    }


    public function makeAllFixtures()
    {
        $fixtures = [];
        foreach ($this->teams as $homeTeam) {
            foreach ($this->teams as $awayTeam) {
                if ($homeTeam === $awayTeam) {
                    continue;
                }
                $fixtures[] = [
                    'home' => $homeTeam,
                    'away' => $awayTeam,
                    'used' => 0
                ];
            }
        }
        return $fixtures;
    }

}
