<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('settings')) {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->boolean('isEnv')->default(false);
            $table->mediumText('value')->nullable();
            $table->string('group')->nullable();
            $table->enum('type', ['text', 'textarea', 'file', 'password', 'number', 'boolean'])->default('text');
            $table->boolean('editable')->default(false);
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->timestamps();
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
        Schema::dropIfExists('settings');
    }
}
