<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('prepare_trips')) {
        Schema::table('prepare_trips', function (Blueprint $table) {
            // Add enhanced prepare trip fields
            $table->json('enhanced_generated_data')->nullable()->after('places'); // Store enhanced data with periods
            $table->boolean('is_enhanced')->default(false)->after('enhanced_generated_data'); // Flag to identify enhanced trips
            $table->integer('places_per_period')->nullable()->after('is_enhanced'); // Places per period (morning/evening)
            $table->json('categories_array')->nullable()->after('places_per_period'); // Store categories as proper array
            $table->json('price_range_array')->nullable()->after('categories_array'); // Store price range as proper array
            
            // Add indexes for better performance
            $table->index('is_enhanced');
        });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('prepare_trips')) {
        Schema::table('prepare_trips', function (Blueprint $table) {
            $table->dropIndex(['is_enhanced']);
            $table->dropColumn([
                'enhanced_generated_data',
                'is_enhanced',
                'places_per_period',
                'categories_array',
                'price_range_array'
            ]);
        });
        }
    }
};
