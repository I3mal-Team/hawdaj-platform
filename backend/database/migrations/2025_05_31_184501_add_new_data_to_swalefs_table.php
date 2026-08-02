<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewDataToSwalefsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('swalefs')) {
        Schema::table('swalefs', function (Blueprint $table) {
            $table->text('address')->nullable();
            $table->text('audioStoryLink')->nullable();
            $table->text('mainCharacters')->nullable();
            $table->text('categories')->nullable();
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
        if (Schema::hasTable('swalefs')) {
        Schema::table('swalefs', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('audioStoryLink');
            $table->dropColumn('mainCharacters');
            $table->dropColumn('categories');
        });
    }
}
