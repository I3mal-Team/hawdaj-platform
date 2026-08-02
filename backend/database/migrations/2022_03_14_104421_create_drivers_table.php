<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('drivers')) {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('contact_person_name')->nullable();
            $table->string('email')->nullable();
            $table->bigInteger('id_number')->nullable();
            $table->string('phone')->nullable();
            $table->string('vehicle_details')->nullable();
            $table->string('licence')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('drivers');
    }
}
