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
        foreach (\App\Models\Store::all() as $store) {
            $title = $store->translate("en")->title;
            $description = $store->translate("en")->description;

            $title = \Stichoza\GoogleTranslate\GoogleTranslate::trans($title, "ru");
            $description = \Stichoza\GoogleTranslate\GoogleTranslate::trans($description, "ru");
            $store->translateOrNew('ru')->title = $title;
            $store->translateOrNew('ru')->description = $description;

            $title = \Stichoza\GoogleTranslate\GoogleTranslate::trans($title, "zh");
            $description = $description ? \Stichoza\GoogleTranslate\GoogleTranslate::trans($description, "zh") : "";
            $store->translateOrNew('zh')->title = $title;
            $store->translateOrNew('zh')->description = $description;
            $store->save();
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
