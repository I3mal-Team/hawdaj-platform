<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePinnedReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pinned_reports', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->date('start')->nullable();
            $table->date('end')->nullable();
            $table->enum('time_type', ['specific', 'dynamic'])->nullable();
            $table->string('time_range')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('global_date')->default(0);
            $table->boolean('default')->default(0);
            $table->boolean('active')->default(0);
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
        Schema::dropIfExists('pinned_reports');
    }
}
