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
        Schema::create('task_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade'); // Rad na koji se student prijavljuje
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Student koji se prijavljuje
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status prijave
            $table->timestamps();
            
            // Student se može prijaviti samo jednom na isti rad
            $table->unique(['task_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_applications');
    }
};
