<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('trips')) {
        Schema::table('trips', function (Blueprint $table) {
            // Add enhanced trip fields
            $table->json('enhanced_data')->nullable()->after('items'); // Store enhanced trip data with periods
            $table->json('morning_descriptions')->nullable()->after('enhanced_data'); // Morning descriptions for each day
            $table->json('evening_descriptions')->nullable()->after('morning_descriptions'); // Evening descriptions for each day
            $table->boolean('is_enhanced')->default(false)->after('evening_descriptions'); // Flag to identify enhanced trips
            $table->integer('places_per_period')->nullable()->after('is_enhanced'); // Places per period (morning/evening)
            
            // Add indexes for better performance
            $table->index('is_enhanced');
        });
    }
        }

    public function down(): void
    {
        if (Schema::hasTable('trips')) {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropIndex(['is_enhanced']);
            $table->dropColumn([
                'enhanced_data',
                'morning_descriptions', 
                'evening_descriptions',
                'is_enhanced',
                'places_per_period'
            ]);
        });
    }
};
