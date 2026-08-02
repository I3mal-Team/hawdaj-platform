<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('media')) {
        Schema::create('media', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->morphs('model');
            $table->uuid('uuid')->nullable()->unique();
            $table->string('collection_name');
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->string('disk');
            $table->string('conversions_disk')->nullable();
            $table->unsignedBigInteger('size');
            $table->mediumText('manipulations');
            $table->mediumText('custom_properties');
            $table->mediumText('generated_conversions');
            $table->mediumText('responsive_images');
            $table->unsignedInteger('order_column')->nullable();

            $table->nullableTimestamps();
        });
    }

        }}
