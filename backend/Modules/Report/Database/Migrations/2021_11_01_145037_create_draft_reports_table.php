<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDraftReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('draft_reports', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->date('start')->nullable();
            $table->date('end')->nullable();
            $table->enum('time_type', ['specific', 'dynamic']);
            $table->string('report_type')->nullable();
            $table->string('time_range')->nullable();
            $table->string('model_type');
            $table->enum('unit', ['time', 'number','mixed'])->default('number');
            $table->string('report_list')->default('total');
            $table->string('groupBy')->default('site_name');
            $table->mediumText('site_id');
            $table->mediumText('columns');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('draft_reports');
    }
}
