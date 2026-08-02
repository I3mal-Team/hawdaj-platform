<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('visitors')) {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->bigInteger('id_number');
            $table->string('id_type')->nullable();
            $table->string('mobile');
            $table->string('email')->nullable();
            $table->enum('gender',['male','female'])->nullable();
            $table->string('nationality')->nullable();
            $table->string('vehicle_detail')->nullable();
            $table->string('vehicle_material')->nullable();
            $table->string('vehicle_remark')->nullable();
            $table->string('personal_photo')->nullable();
            $table->string('id_copy')->nullable();
            $table->foreignId('company_id')->constrained('companies');
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('visitors');
    }
}
