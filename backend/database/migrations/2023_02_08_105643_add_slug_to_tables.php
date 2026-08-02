<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlugToTables extends Migration
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
                if (!Schema::hasColumn('places', 'slug')) {
                    $table->string('slug')->nullable()->after('id');
                }
            });
        }
        if (Schema::hasTable('stores')) {
            Schema::table('stores', function (Blueprint $table) {
                if (!Schema::hasColumn('stores', 'slug')) {
                    $table->string('slug')->nullable()->after('id');
                }
            });
        }
        if (Schema::hasTable('swalefs')) {
            Schema::table('swalefs', function (Blueprint $table) {
                if (!Schema::hasColumn('swalefs', 'slug')) {
                    $table->string('slug')->nullable()->after('id');
                }
            });
        }
        if (Schema::hasTable('zad_elgadels')) {
            Schema::table('zad_elgadels', function (Blueprint $table) {
                if (!Schema::hasColumn('zad_elgadels', 'slug')) {
                    $table->string('slug')->nullable()->after('id');
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
                $table->dropColumn('slug');
            });
        }
        if (Schema::hasTable('stores')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
        if (Schema::hasTable('swalefs')) {
            Schema::table('swalefs', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
        if (Schema::hasTable('zad_elgadels')) {
            Schema::table('zad_elgadels', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
}
