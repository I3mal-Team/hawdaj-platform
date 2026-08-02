<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('visit_sites')) {
        Schema::create('visit_sites', function (Blueprint $table) {
            $table->id();
            $table->text('ip')->nullable();
            $table->integer('visits')->default(0);
            $table->string('page')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('visit_sites');
    }
}
