<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('events')) {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->text('categories')->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->text('ticket_link')->nullable();
            $table->text('image')->nullable();
            $table->text('facebook')->nullable();
            $table->text('whatsapp')->nullable();
            $table->text('instagram')->nullable();
            $table->text('website')->nullable();
            $table->string("display_type")->nullable();
            $table->string("type")->nullable();
            $table->text('address')->nullable();
            $table->text('link')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->text('city_id')->nullable();
            $table->text('region_id')->nullable();
            $table->bigInteger('views_num')->default(0)->nullable();
            $table->boolean('featured')->nullable()->default(1);
            $table->boolean('visited')->default(0)->nullable();
            $table->tinyInteger('active')->default(0)->nullable();
            $table->text('related')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('events');
    }
}
