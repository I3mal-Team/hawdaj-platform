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
        if (!Schema::hasTable('feature_translations')) {
        Schema::create('feature_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('feature_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name');
            $table->string('description');
            $table->unique(['feature_id', 'locale']);
            $table->foreign('feature_id')->references('id')->on('features')->onDelete('cascade');
        });

        foreach (\App\Models\Feature::get() as $feature) {
            $name = $feature->name;
            $description = $feature->description;
            $feature->translateOrNew('ar')->name = $name;
            $feature->translateOrNew('ar')->description = $description;
            $name = GoogleTranslate::trans($name, "en");
            $description = GoogleTranslate::trans($description, "en");
            $feature->translateOrNew('en')->name = $name;
            $feature->translateOrNew('en')->description = $description;
            $feature->save();
        }

        Schema::table('features', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('description');
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
        Schema::dropIfExists('feature_translations');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
