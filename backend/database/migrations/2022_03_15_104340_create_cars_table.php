<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('cars')) {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('plate_ar')->nullable();
            $table->string('plate_en')->nullable();
            $table->text('description')->nullable();
            $table->string('licence')->nullable();
            $table->string('type')->nullable();
            $table->enum('status', ['approved', 'rejected'])->default('approved');
            $table->timestamps();
            $table->softDeletes();
        });
    }
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
