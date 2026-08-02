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
        foreach (\App\Models\Feature::all() as $feature) {
            $name = $feature->translate("en")->name;
            $description = $feature->translate("en")->description;
            $name = \Stichoza\GoogleTranslate\GoogleTranslate::trans($name, "ru");
            $description = \Stichoza\GoogleTranslate\GoogleTranslate::trans($description, "ru");
            $feature->translateOrNew('ru')->name = $name;
            $feature->translateOrNew('ru')->description = $description;
            $name = \Stichoza\GoogleTranslate\GoogleTranslate::trans($name, "zh");
            $description = $description ? \Stichoza\GoogleTranslate\GoogleTranslate::trans($description, "zh") : "";
            $feature->translateOrNew('zh')->name = $name;
            $feature->translateOrNew('zh')->description = $description;
            $feature->save();
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
