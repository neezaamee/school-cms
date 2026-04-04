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
        Schema::create('student_guardian_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('name_ur')->nullable();
            $table->string('relation')->nullable();
            $table->string('cnic')->nullable();
            $table->string('mobile')->nullable();
            $table->text('address')->nullable();
            $table->text('address_ur')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_guardian_details');
    }
};
