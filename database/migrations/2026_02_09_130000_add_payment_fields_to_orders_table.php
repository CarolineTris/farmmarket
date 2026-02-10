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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_status')->default('pending')->after('status');
            $table->string('payment_provider')->nullable()->after('payment_status');
            $table->string('payment_reference')->nullable()->after('payment_provider');
            $table->string('payment_tx_ref')->nullable()->after('payment_reference');
            $table->string('currency', 3)->default('UGX')->after('payment_tx_ref');
            $table->string('payer_phone')->nullable()->after('currency');
            $table->string('payer_network')->nullable()->after('payer_phone');
            $table->timestamp('paid_at')->nullable()->after('payer_network');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status',
                'payment_provider',
                'payment_reference',
                'payment_tx_ref',
                'currency',
                'payer_phone',
                'payer_network',
                'paid_at',
            ]);
        });
    }
};
