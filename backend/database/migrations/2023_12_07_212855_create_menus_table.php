<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('menus')) {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->foreignId('zad_id')->constrained('zad_elgadels')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement("INSERT INTO `permissions` ( `name`, `label`, `model`, `guard_name`, `created_at`, `updated_at`) VALUES
                            ( 'read-menus', 'Read Menu', 'Menu', 'web', '2023-12-08 14:33:21', '2023-12-08 14:33:21'),
                            ('create-menus', 'Create Menu', 'Menu', 'web', '2023-12-08 14:33:22', '2023-12-08 14:33:22'),
                            ('update-menus', 'Update Menu', 'Menu', 'web', '2023-12-08 14:33:22', '2023-12-08 14:33:22'),
                            ('delete-menus', 'Delete Menu', 'Menu', 'web', '2023-12-08 14:33:22', '2023-12-08 14:33:22')");
    }
        }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
