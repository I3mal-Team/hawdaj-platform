<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('applications')) {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('slug')->nullable();
            $table->string('type')->nullable()->default('app');
            $table->text('link')->nullable();
            $table->tinyInteger('active')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('applications');
    }
}
