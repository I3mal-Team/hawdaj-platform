<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('sliders')) {
            Schema::create('sliders', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('order_id')->default(0);
                $table->boolean('active')->default(true);
                $table->string('link', 2048)->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('slider_translations')) {
            Schema::create('slider_translations', function (Blueprint $table) {
                $table->id();
                $table->string('locale')->index();
                $table->foreignId('slider_id')->constrained('sliders')->cascadeOnDelete();
                $table->unique(['slider_id', 'locale']);
                $table->string('title');
            });
        }

        $now = now();
        $perms = [
            ['name' => 'read-sliders', 'label' => 'Read Sliders', 'model' => 'sliders'],
            ['name' => 'create-sliders', 'label' => 'Create Sliders', 'model' => 'sliders'],
            ['name' => 'update-sliders', 'label' => 'Update Sliders', 'model' => 'sliders'],
            ['name' => 'delete-sliders', 'label' => 'Delete Sliders', 'model' => 'sliders'],
        ];

        foreach ($perms as $p) {
            $exists = DB::table('permissions')->where('name', $p['name'])->where('guard_name', 'web')->exists();
            if ($exists) {
                continue;
            }
            $id = DB::table('permissions')->insertGetId([
                'name' => $p['name'],
                'label' => $p['label'],
                'model' => $p['model'],
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $rootRoleId = DB::table('roles')->where('name', 'root')->value('id');
            if ($rootRoleId) {
                $dup = DB::table('role_has_permissions')
                    ->where('role_id', $rootRoleId)
                    ->where('permission_id', $id)
                    ->exists();
                if (! $dup) {
                    DB::table('role_has_permissions')->insert([
                        'permission_id' => $id,
                        'role_id' => $rootRoleId,
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('slider_translations');
        Schema::dropIfExists('sliders');
    }
};
