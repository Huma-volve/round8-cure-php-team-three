<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
                $table->unsignedTinyInteger('experience_years')->default(1)->after('specializations_id');
        });

        // Add CHECK constraint to ensure experience_years is between 1 and 30
        DB::statement('ALTER TABLE doctors ADD CONSTRAINT check_experience_years_range CHECK (experience_years >= 1 AND experience_years <= 30)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the CHECK constraint first
        DB::statement('ALTER TABLE doctors DROP CONSTRAINT IF EXISTS check_experience_years_range');

        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn('experience_years');
        });
    }
};
