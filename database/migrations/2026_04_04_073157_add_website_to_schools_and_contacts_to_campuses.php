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
        Schema::table('schools', function (Blueprint $table) {
            $table->string('website')->nullable()->after('phone');
        });

        Schema::table('campuses', function (Blueprint $table) {
            $table->string('email')->nullable()->after('name');
            $table->string('phone')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn('website');
        });

        Schema::table('campuses', function (Blueprint $table) {
            if (Schema::hasColumn('campuses', 'email')) {
                $table->dropColumn(['email', 'phone']);
            }
        });
    }
};
