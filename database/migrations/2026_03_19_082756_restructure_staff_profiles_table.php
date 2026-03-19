<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('staff_profiles', function (Blueprint $table) {
            $table->string('name')->after('id')->nullable();
            $table->string('email')->after('name')->nullable();
            $table->foreignId('school_id')->after('email')->nullable()->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });

        // Migrate existing data from users to staff_profiles
        $staffProfiles = DB::table('staff_profiles')->get();
        foreach ($staffProfiles as $profile) {
            $user = DB::table('users')->where('id', $profile->user_id)->first();
            if ($user) {
                DB::table('staff_profiles')->where('id', $profile->id)->update([
                    'name' => $user->name,
                    'email' => $user->email,
                    'school_id' => $user->school_id,
                ]);
            }
        }

        // After migration, we can make name and school_id required if we want, 
        // but for now let's keep them nullable to be safe during migration.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_profiles', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->dropForeign(['school_id']);
            $table->dropColumn(['name', 'email', 'school_id']);
        });
    }
};
