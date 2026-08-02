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
        if (!Schema::hasTable('store_translations')) {
        Schema::create('store_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_id')->unsigned();
            $table->string('locale')->index();
            $table->string('title');
            $table->text('description');
            $table->unique(['store_id', 'locale']);
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
        });

        foreach (\App\Models\Store::get() as $store) {
            $title = $store->title ?? '-';
            $description = $store->description ?? '-';

            $store->translateOrNew('ar')->title = $title;
            $store->translateOrNew('ar')->description = $description;

            $title = GoogleTranslate::trans($title, "en");
            $description = GoogleTranslate::trans($description, "en");

            $store->translateOrNew('en')->title = $title;
            $store->translateOrNew('en')->description = $description;
            $store->slug = \Illuminate\Support\Str::slug($title);
            $store->save();
        }

        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('title');
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
        Schema::dropIfExists('store_translations');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
