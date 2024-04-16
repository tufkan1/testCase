<?php
namespace App\Services\FixtureDraw;


interface FixtureDrawInterface
{

    public function __construct(array $teams);

    public function getFixturesPlan();

}
