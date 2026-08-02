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
        foreach (\App\Models\CategoryOfStore::all() as $categoryOfStore) {
            $name = $categoryOfStore->translate("en")->name;
            $notes = $categoryOfStore->translate("en")->notes;

            $name = \Stichoza\GoogleTranslate\GoogleTranslate::trans($name, "ru");
            $notes = \Stichoza\GoogleTranslate\GoogleTranslate::trans($notes, "ru");
            $categoryOfStore->translateOrNew('ru')->name = $name;
            $categoryOfStore->translateOrNew('ru')->notes = $notes;

            $name = \Stichoza\GoogleTranslate\GoogleTranslate::trans($name, "zh");
            $notes = $notes ? \Stichoza\GoogleTranslate\GoogleTranslate::trans($notes, "zh") : "";
            $categoryOfStore->translateOrNew('zh')->name = $name;
            $categoryOfStore->translateOrNew('zh')->notes = $notes;
            $categoryOfStore->save();
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
