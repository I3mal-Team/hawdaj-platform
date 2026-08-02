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
        foreach (\App\Models\Category::all() as $category) {
            $name = $category->translate("en")->name;
            $notes = $category->translate("en")->notes;

            $name = \Stichoza\GoogleTranslate\GoogleTranslate::trans($name, "ru");
            $notes = \Stichoza\GoogleTranslate\GoogleTranslate::trans($notes, "ru");
            $category->translateOrNew('ru')->name = $name;
            $category->translateOrNew('ru')->notes = $notes;

            $name = \Stichoza\GoogleTranslate\GoogleTranslate::trans($name, "zh");
            $notes = $notes ? \Stichoza\GoogleTranslate\GoogleTranslate::trans($notes, "zh") : "";
            $category->translateOrNew('zh')->name = $name;
            $category->translateOrNew('zh')->notes = $notes;
            $category->save();
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
