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
        if (!Schema::hasTable('setting_translations')) {
        Schema::create('setting_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('setting_id')->unsigned();
            $table->string('locale')->index();
            $table->text('value')->nullable();
            $table->unique(['setting_id', 'locale']);
            $table->foreign('setting_id')->references('id')->on('settings')->onDelete('cascade');
        });

        foreach (\App\Models\Setting::get() as $setting) {
            $value = $setting->value ?? '-';
            $value_en = GoogleTranslate::trans($value, "en");
            $value_ar = GoogleTranslate::trans($value, "ar");
            $setting->translateOrNew('ar')->value = $value_ar;
            $setting->translateOrNew('en')->value = $value_en;
            $setting->save();
        }

        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('value');
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
        Schema::dropIfExists('setting_translations');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
