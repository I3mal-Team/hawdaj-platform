<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add x to guides table
        if (Schema::hasTable('guides') && !Schema::hasColumn('guides', 'x')) {
            Schema::table('guides', function (Blueprint $table) {
                $table->text('x')->nullable()->after('facebook');
            });
        }

        // Add x to events table
        if (Schema::hasTable('events') && !Schema::hasColumn('events', 'x')) {
            Schema::table('events', function (Blueprint $table) {
                $table->text('x')->nullable()->after('facebook');
            });
        }

        // Add x_link to stores table
        if (Schema::hasTable('stores') && !Schema::hasColumn('stores', 'x_link')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->text('x_link')->nullable()->after('facebook_link');
            });
        }

        // Add x_link to places table
        if (Schema::hasTable('places') && !Schema::hasColumn('places', 'x_link')) {
            Schema::table('places', function (Blueprint $table) {
                $table->text('x_link')->nullable()->after('facebook_link');
            });
        }

        // Add x_link to zad_elgadels table
        if (Schema::hasTable('zad_elgadels') && !Schema::hasColumn('zad_elgadels', 'x_link')) {
            Schema::table('zad_elgadels', function (Blueprint $table) {
                $table->text('x_link')->nullable()->after('facebook_link');
            });
        }

        // Add x to user_socials table
        if (Schema::hasTable('user_socials') && !Schema::hasColumn('user_socials', 'x')) {
            Schema::table('user_socials', function (Blueprint $table) {
                $table->text('x')->nullable()->after('facebook');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('guides') && Schema::hasColumn('guides', 'x')) {
            Schema::table('guides', function (Blueprint $table) {
                $table->dropColumn('x');
            });
        }

        if (Schema::hasTable('events') && Schema::hasColumn('events', 'x')) {
            Schema::table('events', function (Blueprint $table) {
                $table->dropColumn('x');
            });
        }

        if (Schema::hasTable('stores') && Schema::hasColumn('stores', 'x_link')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->dropColumn('x_link');
            });
        }

        if (Schema::hasTable('places') && Schema::hasColumn('places', 'x_link')) {
            Schema::table('places', function (Blueprint $table) {
                $table->dropColumn('x_link');
            });
        }

        if (Schema::hasTable('zad_elgadels') && Schema::hasColumn('zad_elgadels', 'x_link')) {
            Schema::table('zad_elgadels', function (Blueprint $table) {
                $table->dropColumn('x_link');
            });
        }

        if (Schema::hasTable('user_socials') && Schema::hasColumn('user_socials', 'x')) {
            Schema::table('user_socials', function (Blueprint $table) {
                $table->dropColumn('x');
            });
        }
    }
};

