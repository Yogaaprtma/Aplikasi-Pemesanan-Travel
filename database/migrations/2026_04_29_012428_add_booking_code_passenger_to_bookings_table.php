<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('booking_code')->unique()->nullable()->after('id');
            $table->string('passenger_name')->nullable()->after('seats'); // nullable for existing data
            $table->string('passenger_phone')->nullable()->after('passenger_name'); // nullable for existing data
            $table->timestamp('cancelled_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['booking_code', 'passenger_name', 'passenger_phone', 'cancelled_at']);
        });
    }
};
