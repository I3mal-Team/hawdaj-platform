<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarPlatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_plates', function (Blueprint $table) {
            $table->id();
            $table->string('camID')->nullable();
            $table->boolean('status')->default(false);
            $table->string('plate_card')->nullable();
            $table->string('plate_ar')->nullable();
            $table->string('plate_en')->nullable();
            $table->string('image')->nullable();
            $table->enum('detection_status',['pending','success','error'])->default('pending');
            $table->timestamp('notice_time')->nullable();
            $table->unsignedBigInteger('last_risk')->nullable();
            $table->boolean('first_row')->default(0);
            $table->foreignId('site_id')->nullable()->constrained('sites');
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
        Schema::dropIfExists('cars');
    }
}
