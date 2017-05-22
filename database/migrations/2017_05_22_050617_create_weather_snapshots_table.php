<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeatherSnapshotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weather_snapshots', function (Blueprint $table) {
            $table->increments('id');
            $table->string('country_code', 2);
            $table->string('postcode', 10);
            $table->string('location');
            $table->string('conditions_group');
            $table->string('conditions_details');
            $table->float('temperature');
            $table->float('pressure');
            $table->float('humidity');
            $table->float('wind_speed');
            $table->float('wind_direction');
            $table->text('response');
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
        Schema::dropIfExists('weather_snapshots');
    }
}
