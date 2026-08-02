<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProofOfOwnershipToStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('stores')) {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('status')->nullable();
            $table->integer('added_by_user')->default(0)->nullable()->index();
            $table->text('ownership_proof_file')->nullable();
            $table->text('rejected_reason')->nullable();
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
        if (Schema::hasTable('stores')) {
        Schema::table('stores', function (Blueprint $table) {
            if (Schema::hasColumn('stores', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('stores', 'added_by_user')) {
                $table->dropColumn('added_by_user');
            }
            if (Schema::hasColumn('stores', 'ownership_proof_file')) {
                $table->dropColumn('ownership_proof_file');
            }
            if (Schema::hasColumn('stores', 'rejected_reason')) {
                $table->dropColumn('rejected_reason');
            }
        });
    }
}
