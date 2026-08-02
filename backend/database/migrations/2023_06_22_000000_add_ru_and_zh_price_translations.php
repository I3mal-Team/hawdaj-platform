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
        foreach (\App\Models\Price::all() as $price) {
            $name = $price->translate("en")->name;
            $name = \Stichoza\GoogleTranslate\GoogleTranslate::trans($name, "ru");
            $price->translateOrNew('ru')->name = $name;
            $name = \Stichoza\GoogleTranslate\GoogleTranslate::trans($name, "zh");
            $price->translateOrNew('zh')->name = $name;
            $price->save();
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
