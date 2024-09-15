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
        Schema::table('users', function (Blueprint $table) {
            //add-use-rtype
            $table->string('user_type')->default('regular')->after('email');
        });

        // DB-update-foradminrole-aspremium-users
        DB::table('users')->where('role', 'admin')->update(['user_type' => 'premium']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('user_type');
        });
    }
};
