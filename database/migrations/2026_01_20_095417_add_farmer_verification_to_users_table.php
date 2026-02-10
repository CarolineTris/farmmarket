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
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending')->after('role');
            $table->string('id_number')->nullable()->after('verification_status');
            $table->string('id_document')->nullable()->after('id_number');
            $table->string('farm_location')->nullable()->after('id_document');
            $table->string('farm_size')->nullable()->after('farm_location');
            $table->string('crops_grown')->nullable()->after('farm_size');
            $table->text('verification_notes')->nullable()->after('crops_grown');
            $table->timestamp('verified_at')->nullable()->after('verification_notes');
            $table->integer('verified_by')->nullable()->after('verified_at');
            $table->json('verification_data')->nullable()->after('verified_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'verification_status',
                'id_number',
                'id_document',
                'farm_location',
                'farm_size',
                'crops_grown',
                'verification_notes',
                'verified_at',
                'verified_by',
                'verification_data'
            ]);
        });
    }
};
