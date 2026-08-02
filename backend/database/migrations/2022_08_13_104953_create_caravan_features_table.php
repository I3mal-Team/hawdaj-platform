<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaravanFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('caravan_features')) {
        Schema::create('caravan_features', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('caravan_id');
            $table->unsignedBigInteger('feature_id');
            $table->text('value')->nullable();
            $table->integer('status')->default(0);
            $table->timestamps();
            $table->foreign('caravan_id')->references('id')->on('caravans');
            $table->foreign('feature_id')->references('id')->on('features');
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
        Schema::dropIfExists('caravan_features');
    }
}
