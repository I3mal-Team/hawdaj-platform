<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaravansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('caravans')) {
        Schema::create('caravans', function (Blueprint $table) {
            $table->id();
            $table->longText('name')->nullable();
            $table->text('image')->nullable();
            $table->longText('description')->nullable();
            $table->string('color')->nullable();
            $table->string('brand')->nullable();
            $table->date('licence_expiry_date')->nullable();
            $table->longText('place_of_delivery')->nullable();
            $table->longText('delivery_place')->nullable();
            $table->tinyInteger('active')->default(0);
            $table->timestamps();
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
        Schema::dropIfExists('caravans');
    }
}
