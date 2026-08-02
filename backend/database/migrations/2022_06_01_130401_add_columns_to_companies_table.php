<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('companies')) {
        Schema::table('companies', function (Blueprint $table) {
            $table->enum('type', ['personal', 'commercial'])->after('email');
            $table->string('url')->nullable()->after('type');
            $table->mediumText('description')->nullable()->after('url');
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
        if (Schema::hasTable('companies')) {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('url');
            $table->dropColumn('description');
            $table->dropColumn('deleted_at');
        });
    }
}
