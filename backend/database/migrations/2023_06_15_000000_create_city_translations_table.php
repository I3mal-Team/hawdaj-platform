<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('city_translations')) {
        Schema::create('city_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('city_id')->unsigned()->nullable();
            $table->string('locale')->index();
            $table->string('name');
            $table->unique(['city_id', 'locale']);
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });

        foreach (\App\Models\City::all() as $city) {
            $name = $city->name;
            $city->translateOrNew('ar')->name = $name;
            $city->translateOrNew('en')->name = $name;
            $city->save();
        }

        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn('name');
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
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('city_translations');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
