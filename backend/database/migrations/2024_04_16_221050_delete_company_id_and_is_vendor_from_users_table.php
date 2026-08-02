<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteCompanyIdAndIsVendorFromUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('users')) {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('is_vendor');
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
            if (!Schema::hasColumn('users', 'company_id')) {
            $table->integer('company_id')->nullable();
            }
            if (!Schema::hasColumn('users', 'is_vendor')) {
            $table->tinyInteger('is_vendor')->default(0);
            }
        });
    }
}
