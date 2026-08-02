<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePinnedChartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pinned_charts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pinned_id')->constrained('pinned_reports')->onDelete('cascade');
            $table->foreignId('chart_id')->constrained('draft_charts')->onDelete('cascade');
            $table->unsignedSmallInteger('column_width')->default(6);
            $table->unsignedSmallInteger('sort')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pinned_charts');
    }
}
