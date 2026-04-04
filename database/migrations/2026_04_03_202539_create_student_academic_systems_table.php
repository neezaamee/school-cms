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
        Schema::create('student_academic_systems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('board_type')->nullable(); // BISE, Cambridge, etc.
            $table->string('medium')->nullable(); // Urdu, English
            $table->string('previous_class')->nullable();
            $table->string('previous_school')->nullable();
            $table->string('last_result_grade')->nullable();
            $table->json('subjects_selected')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_academic_systems');
    }
};
