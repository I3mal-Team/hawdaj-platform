<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('stores')) {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->text('categories')->nullable();
            $table->text('related_stores')->nullable();
            $table->text('near_places')->nullable();
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->text('image')->nullable();
            $table->text('facebook_link')->nullable();
            $table->text('whatsapp')->nullable();
            $table->text('Instagram_link')->nullable();
            $table->text('website_link')->nullable();
            $table->decimal('lat')->nullable();
            $table->decimal('long')->nullable();
            $table->tinyInteger('active')->default(0);
            $table->enum("address_type", ['link','map','latlong'])->default('link')->nullable();
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
        Schema::dropIfExists('stores');
    }
}
