<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryOfSwalefsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('category_of_swalefs')) {
        Schema::create('category_of_swalefs', function (Blueprint $table) {
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
        Schema::dropIfExists('category_of_swalefs');
    }
}
