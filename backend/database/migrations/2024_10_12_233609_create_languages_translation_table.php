<?php

use App\Models\Language;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Stichoza\GoogleTranslate\GoogleTranslate;

class CreateLanguagesTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('languages_translation')) {
        Schema::create('languages_translation', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');
            $table->unique(['language_id', 'locale']);
            $table->string('name')->nullable();
            $table->text('description')->nullable();
        });

        $default_languages = [
            [
                'name' => 'العربية',
            ],
            [
                'name' => 'الانجليزية',
            ],
            [
                'name' => 'الروسية',
            ],
            [
                'name' => 'الصينية',
            ],
        ];

        foreach ($default_languages as $language) {

            $lang = Language::create($language);
            $lang->translateOrNew('ar')->name = $language['name'];

            $locales = locales("ar");
            foreach ($locales as $locale) {
                $name = GoogleTranslate::trans($language['name'] ?? '', $locale);
                $lang->translateOrNew($locale)->name = $name;
            }

            $lang->save();
        }
    }
        }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('languages_translation');
    }
}
