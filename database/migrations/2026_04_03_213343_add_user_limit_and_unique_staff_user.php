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
        // 1. Add user_limit to subscription_packages
        if (Schema::hasTable('subscription_packages')) {
            Schema::table('subscription_packages', function (Blueprint $table) {
                if (!Schema::hasColumn('subscription_packages', 'user_limit')) {
                    $table->integer('user_limit')->default(0)->after('staff_limit');
                }
            });
        }

        // 2. Enforce unique user_id on staff_profiles
        if (Schema::hasTable('staff_profiles')) {
            // In Laravel 11, we can check for index existence using getIndexes()
            $indexes = Schema::getIndexes('staff_profiles');
            $hasUnique = collect($indexes)->contains(function($index) {
                return $index['columns'][0] === 'user_id' && $index['unique'];
            });

            if (!$hasUnique) {
                Schema::table('staff_profiles', function (Blueprint $table) {
                    $table->unique('user_id');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('subscription_packages')) {
            Schema::table('subscription_packages', function (Blueprint $table) {
                if (Schema::hasColumn('subscription_packages', 'user_limit')) {
                    $table->dropColumn('user_limit');
                }
            });
        }

        if (Schema::hasTable('staff_profiles')) {
            Schema::table('staff_profiles', function (Blueprint $table) {
                $table->dropUnique(['user_id']);
            });
        }
    }
};
