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
        foreach (\App\Models\Swalef::all() as $swalef) {
            $title = $swalef->translate("en")->title;
            $description = $swalef->translate("en")->description;

            $title = \Stichoza\GoogleTranslate\GoogleTranslate::trans($title, "ru");
            $description = \Stichoza\GoogleTranslate\GoogleTranslate::trans($description, "ru");
            $swalef->translateOrNew('ru')->title = $title;
            $swalef->translateOrNew('ru')->description = $description;

            $title = \Stichoza\GoogleTranslate\GoogleTranslate::trans($title, "zh");
            $description = $description ? \Stichoza\GoogleTranslate\GoogleTranslate::trans($description, "zh") : "";
            $swalef->translateOrNew('zh')->title = $title;
            $swalef->translateOrNew('zh')->description = $description;
            $swalef->save();
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
