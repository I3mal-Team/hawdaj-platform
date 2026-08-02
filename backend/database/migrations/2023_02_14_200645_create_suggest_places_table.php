<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuggestPlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('suggest_places')) {
        Schema::create('suggest_places', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->text('name')->nullable();
            $table->text('email')->nullable();
            $table->string("type")->nullable();
            $table->text('address')->nullable();
            $table->text('link')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->tinyInteger('active')->default(0)->nullable();
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
        Schema::dropIfExists('suggest_places');
    }
}
