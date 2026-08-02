<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProofOfOwnershipToZadElgadelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('zad_elgadels')) {
            Schema::table('zad_elgadels', function (Blueprint $table) {
                if (!Schema::hasColumn('zad_elgadels', 'status')) {
                    $table->string('status')->nullable();
                }
                if (!Schema::hasColumn('zad_elgadels', 'added_by_user')) {
                    $table->integer('added_by_user')->default(0)->nullable()->index();
                }
                if (!Schema::hasColumn('zad_elgadels', 'ownership_proof_file')) {
                    $table->text('ownership_proof_file')->nullable();
                }
                if (!Schema::hasColumn('zad_elgadels', 'rejected_reason')) {
                    $table->text('rejected_reason')->nullable();
                }
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
        if (Schema::hasTable('zad_elgadels')) {
            Schema::table('zad_elgadels', function (Blueprint $table) {
                if (Schema::hasColumn('zad_elgadels', 'status')) {
                    $table->dropColumn('status');
                }
                if (Schema::hasColumn('zad_elgadels', 'added_by_user')) {
                    $table->dropColumn('added_by_user');
                }
                if (Schema::hasColumn('zad_elgadels', 'ownership_proof_file')) {
                    $table->dropColumn('ownership_proof_file');
                }
                if (Schema::hasColumn('zad_elgadels', 'rejected_reason')) {
                    $table->dropColumn('rejected_reason');
                }
            });
        }
    }
