<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('users')
            ->whereIn('role', ['buyer', 'admin'])
            ->where('verification_status', 'pending')
            ->update(['verification_status' => 'verified']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')
            ->whereIn('role', ['buyer', 'admin'])
            ->where('verification_status', 'verified')
            ->update(['verification_status' => 'pending']);
    }
};
