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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('exam_term_id')->constrained()->onDelete('cascade');
            $table->foreignId('grade_level_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->date('exam_date')->nullable();
            $table->decimal('total_marks', 8, 2)->default(100);
            $table->decimal('passing_marks', 8, 2)->default(33);
            $table->decimal('weightage', 5, 2)->default(100); // 100% of the term by default
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
