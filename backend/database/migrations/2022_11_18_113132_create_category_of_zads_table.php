<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryOfZadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('category_of_zads')) {
        Schema::create('category_of_zads', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable();
            $table->string('name');
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('category_of_zads');
    }
}
