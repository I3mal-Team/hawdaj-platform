<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('transporters')) {
        Schema::create('transporters', function (Blueprint $table) {
            $table->id();
            $table->string('company')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->enum('id_type',['type1','type2'])->default('type1');
            $table->bigInteger('id_number')->nullable();
            $table->string('phone')->nullable();
            $table->integer('people_count')->nullable();
            $table->string('vehicle_details')->nullable();
            $table->string('materials')->nullable();
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
        Schema::dropIfExists('transporters');
    }
}
