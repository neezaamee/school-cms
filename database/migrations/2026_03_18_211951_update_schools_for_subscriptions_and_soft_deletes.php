<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->foreignId('subscription_package_id')->nullable()->constrained();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropForeign(['subscription_package_id']);
            $table->dropColumn(['subscription_package_id', 'deleted_at']);
        });
    }
};
