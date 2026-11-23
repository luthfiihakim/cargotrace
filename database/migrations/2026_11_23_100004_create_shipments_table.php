<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_number')->unique();
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('carrier_id')->constrained();
            $table->foreignId('scenario_id')->constrained();
            $table->foreignId('origin_location_id')->constrained('locations');
            $table->foreignId('destination_location_id')->constrained('locations');
            $table->foreignId('created_by')->constrained('users');
            $table->string('cargo_type');
            $table->decimal('weight_kg', 12, 2);
            $table->decimal('volume_cbm', 12, 3);
            $table->dateTime('expected_departure_at');
            $table->dateTime('expected_arrival_at')->index();
            $table->dateTime('estimated_arrival_at');
            $table->dateTime('delivered_at')->nullable();
            $table->string('current_status')->default('CREATED')->index();
            $table->boolean('is_delayed')->default(false)->index();
            $table->unsignedInteger('delay_minutes')->default(0);
            $table->string('delay_severity')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['origin_location_id', 'destination_location_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
