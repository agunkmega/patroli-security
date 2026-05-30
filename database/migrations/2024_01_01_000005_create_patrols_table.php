<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patrols', function (Blueprint $table) {
            $table->id();
            $table->string('patrol_number', 30)->unique();
            $table->foreignId('guard_id')->constrained('guards')->onDelete('cascade');
            $table->foreignId('area_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('schedule_id')->nullable()->constrained()->onDelete('set null');
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->integer('total_checkpoints')->default(0);
            $table->integer('scanned_checkpoints')->default(0);
            $table->enum('status', ['in_progress', 'completed', 'missed', 'cancelled'])->default('in_progress');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patrols');
    }
};
