<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProofOfOwnershipToEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('events')) {
        Schema::table('events', function (Blueprint $table) {
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
        if (Schema::hasTable('events')) {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('events', 'added_by_user')) {
                $table->dropColumn('added_by_user');
            }
            if (Schema::hasColumn('events', 'ownership_proof_file')) {
                $table->dropColumn('ownership_proof_file');
            }
            if (Schema::hasColumn('events', 'rejected_reason')) {
                $table->dropColumn('rejected_reason');
            }
        });
    }
}
