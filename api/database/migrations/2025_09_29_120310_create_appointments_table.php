<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('barber_id')->constrained('barbers')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();

            // Use DATETIME to avoid MySQL timestamp default issues
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');

            $table->enum('status', ['pending','confirmed','completed','cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['barber_id','starts_at']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('appointments');
    }
};
