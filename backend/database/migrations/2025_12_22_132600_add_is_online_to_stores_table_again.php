<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsOnlineToStoresTableAgain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('stores')) {
            Schema::table('stores', function (Blueprint $table) {
                if (!Schema::hasColumn('stores', 'is_online')) {
                    $table->boolean('is_online')->default(0)->after('active');
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
        if (Schema::hasTable('stores')) {
            Schema::table('stores', function (Blueprint $table) {
                if (Schema::hasColumn('stores', 'is_online')) {
                    $table->dropColumn('is_online');
                }
            });
        }
    }
}

