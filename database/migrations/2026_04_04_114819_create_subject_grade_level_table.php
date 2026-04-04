<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create pivot table
        Schema::create('subject_grade_level', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('grade_level_id');
            $table->boolean('is_elective')->default(false);
            $table->timestamps();

            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('grade_level_id')->references('id')->on('grade_levels')->onDelete('cascade');
        });

        // 2. Data Migration: Move existing links to pivot
        $subjects = DB::table('subjects')->whereNotNull('grade_level_id')->get();
        foreach ($subjects as $subject) {
            DB::table('subject_grade_level')->insert([
                'subject_id' => $subject->id,
                'grade_level_id' => $subject->grade_level_id,
                'is_elective' => $subject->is_elective ?? false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Cleanup subjects table
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['grade_level_id']);
            $table->dropColumn(['grade_level_id', 'is_elective']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->unsignedBigInteger('grade_level_id')->nullable();
            $table->boolean('is_elective')->default(false);
            $table->foreign('grade_level_id')->references('id')->on('grade_levels')->onDelete('cascade');
        });

        // Potentially reverse data transfer (complicated, but here is a simple attempt)
        // Only works for subjects that are linked to exactly one class.
        $links = DB::table('subject_grade_level')->get();
        foreach ($links as $link) {
            DB::table('subjects')->where('id', $link->subject_id)->update([
                'grade_level_id' => $link->grade_level_id,
                'is_elective' => $link->is_elective,
            ]);
        }

        Schema::dropIfExists('subject_grade_level');
    }
};
