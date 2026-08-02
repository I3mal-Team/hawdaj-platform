<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMimeTypeToGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('galleries')) {
        Schema::table('galleries', function (Blueprint $table) {
            $table->string('mime_type')->nullable()->default('image/png');
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
        if (Schema::hasTable('galleries')) {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropColumn('mime_type');
        });
    }
}
