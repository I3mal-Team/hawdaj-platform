<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Stichoza\GoogleTranslate\GoogleTranslate;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('price_translations')) {
        Schema::create('price_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('price_id')->unsigned();
            $table->string('locale')->index();
            $table->text('name');
            $table->unique(['price_id', 'locale']);
            $table->foreign('price_id')->references('id')->on('prices')->onDelete('cascade');
        });

        foreach (\App\Models\Price::get() as $price) {
            $name = $price->name;
            $price->translateOrNew('ar')->name = $name;
            $name = GoogleTranslate::trans($name, "en");
            $price->translateOrNew('en')->name = $name;
            $price->save();
        }

        Schema::table('prices', function (Blueprint $table) {
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
        Schema::dropIfExists('price_translations');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
