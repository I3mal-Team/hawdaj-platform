<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodCategoryOfZadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('food_category_of_zads')) {
        Schema::create('food_category_of_zads', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable();
            $table->text('icon')->nullable();
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
        Schema::dropIfExists('food_category_of_zads');
    }
}
