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
        foreach (\App\Models\Region::all() as $region) {
            $name = $region->translate("en")->name;
            $name = \Stichoza\GoogleTranslate\GoogleTranslate::trans($name, "ru");
            $region->translateOrNew('ru')->name = $name;
            $name = \Stichoza\GoogleTranslate\GoogleTranslate::trans($name, "zh");
            $region->translateOrNew('zh')->name = $name;
            $region->save();
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
