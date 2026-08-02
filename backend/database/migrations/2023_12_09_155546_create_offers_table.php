<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('offers')) {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->date('to');
            $table->date('from');
            $table->double('discount');
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

        });

        DB::statement("INSERT INTO `permissions` ( `name`, `label`, `model`, `guard_name`) VALUES
                            ( 'read-offers', 'Read Offer', 'Offer', 'web'),
                            ('create-offers', 'Create Offer', 'Offer', 'web'),
                            ('update-offers', 'Update Offer', 'Offer', 'web'),
                            ('delete-offers', 'Delete Offer', 'Offer', 'web')");
    }
        }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offers');
    }
}
