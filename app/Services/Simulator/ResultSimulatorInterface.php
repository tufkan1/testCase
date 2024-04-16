<?php

namespace App\Services\Simulator;


interface ResultSimulatorInterface
{
    public function simulate($singleInput);

    public function bulkSimulate($arrayInput);
}
