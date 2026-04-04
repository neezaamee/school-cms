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
        Schema::table('students', function (Blueprint $table) {
            $table->string('b_form')->nullable()->after('admission_no');
            $table->string('first_name_ur')->nullable()->after('first_name');
            $table->string('last_name_ur')->nullable()->after('last_name');
            $table->string('nationality')->default('Pakistani')->after('religion');
            $table->boolean('is_hafiz_e_quran')->default(false)->after('blood_group');
            $table->boolean('is_pwd')->default(false)->after('is_hafiz_e_quran');
            $table->text('medical_notes')->nullable()->after('is_pwd');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'b_form',
                'first_name_ur',
                'last_name_ur',
                'nationality',
                'is_hafiz_e_quran',
                'is_pwd',
                'medical_notes'
            ]);
        });
    }
};
