<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('invoice_number')->unique()->nullable()->after('id');
            $table->decimal('amount', 10, 2)->default(0)->after('payment_method');
            $table->string('payment_proof')->nullable()->after('amount');
            $table->timestamp('paid_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['invoice_number', 'amount', 'payment_proof', 'paid_at']);
        });
    }
};
