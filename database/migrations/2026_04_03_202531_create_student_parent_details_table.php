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
        Schema::create('student_parent_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('father_name')->nullable();
            $table->string('father_name_ur')->nullable();
            $table->string('father_profession')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_name_ur')->nullable();
            $table->string('mother_profession')->nullable();
            $table->string('father_cnic')->nullable();
            $table->string('mother_cnic')->nullable();
            $table->string('father_mobile')->nullable();
            $table->string('mother_mobile')->nullable();
            $table->string('father_email')->nullable();
            $table->string('monthly_income')->nullable();
            $table->text('home_address')->nullable();
            $table->text('home_address_ur')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_parent_details');
    }
};
