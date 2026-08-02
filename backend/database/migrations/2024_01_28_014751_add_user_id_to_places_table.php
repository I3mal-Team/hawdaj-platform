<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToPlacesTable extends Migration
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
                if (!Schema::hasColumn('places', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable();
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                }
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
                $table->dropColumn('user_id');
            });
        }
    }
