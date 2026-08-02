<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('places')) {
        Schema::table('places', function (Blueprint $table) {
            $table->text('facebook_link')->nullable()->after("region_id");
            $table->text('whatsapp')->nullable()->after("region_id");
            $table->text('instagram_link')->nullable()->after("region_id");
            $table->text('website_link')->nullable()->after("region_id");
            $table->text('ticket_link')->nullable()->after("region_id");
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
        if (Schema::hasTable('users')) {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('facebook_link');
            $table->dropColumn('whatsapp');
            $table->dropColumn('instagram_link');
            $table->dropColumn('website_link');
            $table->dropColumn('ticket_link');
        });
    }
};
