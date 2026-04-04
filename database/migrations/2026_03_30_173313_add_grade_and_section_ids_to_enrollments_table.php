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
        Schema::table('enrollments', function (Blueprint $table) {
            $table->foreignId('grade_level_id')->nullable()->after('campus_id')->constrained()->onDelete('set null');
            $table->foreignId('section_id')->nullable()->after('grade_level_id')->constrained()->onDelete('set null');
            $table->string('class_name')->nullable()->change();
            $table->string('section_name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropForeign(['grade_level_id']);
            $table->dropForeign(['section_id']);
            $table->dropColumn(['grade_level_id', 'section_id']);
            $table->string('class_name')->nullable(false)->change();
            $table->string('section_name')->nullable(false)->change();
        });
    }
};
