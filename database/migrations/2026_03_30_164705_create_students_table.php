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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('campus_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('parents')->nullOnDelete();
            $table->string('admission_no');
            $table->string('roll_no')->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->date('dob');
            $table->string('gender'); // Male, Female, Other
            $table->string('blood_group')->nullable();
            $table->string('religion')->nullable();
            $table->string('category')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('photo')->nullable();
            $table->string('status')->default('Active'); // Active, Inactive, Graduated, Dropout
            $table->softDeletes();
            $table->timestamps();

            // Composite index for admission number per school
            $table->unique(['school_id', 'admission_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parents');
    }
};
