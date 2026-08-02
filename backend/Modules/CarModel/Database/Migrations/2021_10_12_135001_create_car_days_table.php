<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_days', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('risk_duration')->default(0);
            $table->unsignedBigInteger('no_risk_duration')->default(0);
            $table->unsignedBigInteger('invitation')->default(0);
            $table->unsignedBigInteger('no_invitation')->default(0);
            $table->date('day');
            $table->foreignId('site_id')->constrained('sites');
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
        Schema::dropIfExists('car_days');
    }
}
