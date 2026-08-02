<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_settings', function (Blueprint $table) {
            $table->id();
            $table->string('start_time');
            $table->string('end_time');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->foreignId('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->boolean('notification')->default(false);
            $table->boolean('screenshot')->default(false);
            $table->boolean('active')->default(true);
            $table->softDeletes();
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
        Schema::dropIfExists('car_settings');
    }
}
