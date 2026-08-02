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
        if (!Schema::hasTable('region_translations')) {
        Schema::create('region_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('region_id')->unsigned()->nullable();
            $table->string('locale')->index();
            $table->string('name');
            $table->unique(['region_id', 'locale']);
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
        });

        foreach (\App\Models\Region::all() as $region) {
            $name = $region->name ?? '-';
            $region->translateOrNew('ar')->name = $name;
            $region->translateOrNew('en')->name = $name;
            $region->save();
        }

        Schema::table('regions', function (Blueprint $table) {
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
        Schema::dropIfExists('region_translations');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
