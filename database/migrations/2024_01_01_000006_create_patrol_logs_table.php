<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patrol_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patrol_id')->constrained()->onDelete('cascade');
            $table->foreignId('checkpoint_id')->constrained()->onDelete('cascade');
            $table->foreignId('guard_id')->constrained('guards')->onDelete('cascade');
            $table->dateTime('scan_time');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('distance', 8, 2)->nullable();
            $table->string('photo')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['safe', 'unsafe', 'pending'])->default('safe');
            $table->string('condition', 50)->nullable();
            $table->string('device_info')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patrol_logs');
    }
};
