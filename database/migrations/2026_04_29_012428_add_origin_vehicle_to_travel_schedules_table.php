<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('travel_schedules', function (Blueprint $table) {
            $table->string('origin')->after('id')->default('Tidak Disebutkan');
            $table->enum('vehicle_type', ['bus', 'minivan', 'car'])->after('price')->default('bus');
            $table->text('description')->nullable()->after('vehicle_type');
        });
    }

    public function down(): void
    {
        Schema::table('travel_schedules', function (Blueprint $table) {
            $table->dropColumn(['origin', 'vehicle_type', 'description']);
        });
    }
};
