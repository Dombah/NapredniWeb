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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Nastavnik koji je dodao rad
            $table->string('naziv_rada'); // Naziv rada
            $table->string('naziv_rada_eng'); // Naziv rada na engleskom
            $table->text('zadatak_rada'); // Zadatak rada (opis)
            $table->enum('tip_studija', ['stručni', 'preddiplomski', 'diplomski']); // Tip studija
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
