<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFootballMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('football_matches', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('home_team')->nullable();
            $table->integer('away_team')->nullable();
            $table->integer('week_id')->nullable();
            $table->integer('home_team_goal')->default(0);
            $table->integer('away_team_goal')->default(0);
            $table->boolean('status')->default(0)->comment('0 for not played match and 1 for played');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('football_matches');
    }
}
