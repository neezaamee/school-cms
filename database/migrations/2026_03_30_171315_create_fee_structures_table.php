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
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('campus_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fee_category_id')->constrained('fee_categories')->cascadeOnDelete();
            $table->string('class_name');
            $table->decimal('amount', 15, 2);
            $table->integer('due_day')->default(10); // Day of month
            $table->string('fine_type')->default('Fixed'); // Fixed, Percentage
            $table->decimal('fine_amount', 15, 2)->default(0);
            $table->unique(['campus_id', 'fee_category_id', 'class_name'], 'campus_fee_unique');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_structures');
    }
};
