<?php

namespace Database\Seeders;
use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Team::insert([
            ['name' => 'ManCity', 'shirt' => 'mancity-shirt.png', 'logo' => 'mancity.png'],
            ['name' => 'Chelsea', 'shirt' => 'chelsea-shirt.png', 'logo' => 'chelsea.png'],
            ['name' => 'Everton', 'shirt' => 'everton-shirt.png', 'logo' => 'everton.png'],
            ['name' => 'Leicester', 'shirt' => 'leicester-shirt.png', 'logo' => 'leicester.png'],
        ]);
    }
}
