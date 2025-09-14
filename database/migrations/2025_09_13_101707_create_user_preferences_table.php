<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_preferences', function (Blueprint $table) {
            $table->string('bank_name')->nullable()->after('theme');
            $table->string('bank_number')->nullable()->after('bank_name');
            $table->string('academy_url')->nullable()->after('bank_number');
            $table->string('tutorial_url')->nullable()->after('academy_url');
        });
    }

    public function down(): void
    {
        Schema::table('user_preferences', function (Blueprint $table) {
            $table->dropColumn(['bank_name','bank_number','academy_url','tutorial_url']);
        });
    }
};
