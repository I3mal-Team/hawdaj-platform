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
        foreach (\App\Models\Setting::all() as $setting) {
            $value = $setting->translate("en")->value;
            $value = \Stichoza\GoogleTranslate\GoogleTranslate::trans($value, "ru");
            $setting->translateOrNew('ru')->value = $value;
            $value = $value ? \Stichoza\GoogleTranslate\GoogleTranslate::trans($value, "zh") : "";
            $setting->translateOrNew('zh')->value = $value;
            $setting->save();
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
