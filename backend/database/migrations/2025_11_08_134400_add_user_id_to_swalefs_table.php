<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToSwalefsTable extends Migration
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
                if (!Schema::hasColumn('swalefs', 'user_id')) {
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
        if (Schema::hasTable('swalefs')) {
            Schema::table('swalefs', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }
    }
}
