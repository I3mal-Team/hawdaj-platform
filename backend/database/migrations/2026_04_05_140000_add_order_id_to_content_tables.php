<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'places',
        'stores',
        'events',
        'zad_elgadels',
        'swalefs',
        'applications',
        'guides',
        'categories',
        'category_of_stores',
        'category_of_swalefs',
        'category_of_applications',
        'category_of_zads',
        'food_category_of_zads',
        'menus',
        'languages',
        'prices',
        'offers',
        'stories',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table) && ! Schema::hasColumn($table, 'order_id')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->unsignedInteger('order_id')->default(0)->index();
                });
            }
        }

        if (Schema::hasTable('sliders')) {
            if (! Schema::hasColumn('sliders', 'order_id')) {
                Schema::table('sliders', function (Blueprint $blueprint) {
                    $blueprint->unsignedInteger('order_id')->default(0)->index();
                });
            }
            if (Schema::hasColumn('sliders', 'sort_order')) {
                DB::statement('UPDATE sliders SET order_id = sort_order');
                Schema::table('sliders', function (Blueprint $blueprint) {
                    $blueprint->dropColumn('sort_order');
                });
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'order_id')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->dropColumn('order_id');
                });
            }
        }

        if (Schema::hasTable('sliders') && Schema::hasColumn('sliders', 'order_id')) {
            Schema::table('sliders', function (Blueprint $blueprint) {
                $blueprint->unsignedInteger('sort_order')->default(0);
            });
            DB::statement('UPDATE sliders SET sort_order = order_id');
            Schema::table('sliders', function (Blueprint $blueprint) {
                $blueprint->dropColumn('order_id');
            });
        }
    }
};
