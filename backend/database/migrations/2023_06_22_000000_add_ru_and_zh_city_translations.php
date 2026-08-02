<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (\App\Models\City::all() as $city) {
            $name = $city->translate("en")->name;
            $name = \Stichoza\GoogleTranslate\GoogleTranslate::trans($name, "ru");
            $city->translateOrNew('ru')->name = $name;
            $name = \Stichoza\GoogleTranslate\GoogleTranslate::trans($name, "zh");
            $city->translateOrNew('zh')->name = $name;
            $city->save();
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
