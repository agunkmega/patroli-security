<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('guard_number', 20)->unique();
            $table->string('full_name', 100);
            $table->string('nik', 30)->nullable()->unique();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('photo')->nullable();
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->date('birth_date')->nullable();
            $table->date('join_date');
            $table->string('emergency_contact', 20)->nullable();
            $table->string('emergency_name', 100)->nullable();
            $table->string('blood_type', 5)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guards');
    }
};
