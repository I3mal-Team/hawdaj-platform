<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDraftChartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('draft_charts', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('title');
            $table->string('description')->nullable();
            $table->enum('time_unit',['minute','hour','day'])->default('minute');
            $table->boolean('active')->default(1);
            $table->foreignId('draft_id')->constrained('draft_reports')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('draft_charts');
    }
}
