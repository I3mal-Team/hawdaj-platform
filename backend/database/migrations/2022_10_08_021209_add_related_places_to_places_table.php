<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelatedPlacesToPlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('places')) {
        Schema::table('places', function (Blueprint $table) {
            $table->longText('related_places')->nullable();
            $table->longText('near_stores')->nullable();
            $table->boolean('featured')->nullable()->default(1);
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
        if (Schema::hasTable('places')) {
        Schema::table('places', function (Blueprint $table) {
            $table->dropColumn('related_places');
            $table->dropColumn('near_stores');
            $table->dropColumn('featured');
        });
    }
}
