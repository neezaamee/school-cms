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
        Schema::create('student_fee_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fee_plan_id')->nullable(); // Reference to fee plans
            $table->foreignId('scholarship_id')->nullable(); // Reference to scholarships
            $table->decimal('transport_fee', 10, 2)->default(0);
            $table->decimal('hostel_fee', 10, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_fee_configs');
    }
};
