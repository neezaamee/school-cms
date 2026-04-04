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
        Schema::table('student_academic_systems', function (Blueprint $table) {
            $table->string('academic_group')->nullable()->after('subjects_selected');
            $table->string('shift')->nullable()->after('academic_group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_academic_systems', function (Blueprint $table) {
            $table->dropColumn(['academic_group', 'shift']);
        });
    }
};
