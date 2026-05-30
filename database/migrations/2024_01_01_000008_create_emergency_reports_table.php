<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emergency_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_number', 30)->unique();
            $table->foreignId('guard_id')->constrained('guards')->onDelete('cascade');
            $table->foreignId('patrol_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['sos', 'fire', 'theft', 'accident', 'medical', 'other'])->default('sos');
            $table->text('description');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('photo')->nullable();
            $table->enum('status', ['pending', 'responded', 'resolved'])->default('pending');
            $table->timestamp('responded_at')->nullable();
            $table->foreignId('responded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('response_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_reports');
    }
};
