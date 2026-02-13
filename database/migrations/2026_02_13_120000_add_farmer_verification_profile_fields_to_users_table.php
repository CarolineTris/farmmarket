<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number', 20)->nullable()->after('crops_grown');
            $table->unsignedInteger('farming_experience_years')->nullable()->after('phone_number');
            $table->decimal('capital_injected', 15, 2)->nullable()->after('farming_experience_years');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone_number',
                'farming_experience_years',
                'capital_injected',
            ]);
        });
    }
};

