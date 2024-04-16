<?php
namespace Database\Seeders;

use App\Models\Week;
use Illuminate\Database\Seeder;

class WeeksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Week::insert([
            ['title' => '1 st'],
            ['title' => '2 nd'],
            ['title' => '3 rd'],
            ['title' => '4 th'],
            ['title' => '5 th'],
            ['title' => '6 th'],
        ]);
    }
}
