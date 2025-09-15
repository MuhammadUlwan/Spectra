<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom referral_code jika belum ada
            if (!Schema::hasColumn('users', 'referral_code')) {
                $table->string('referral_code')->nullable()->after('id');
            }

            // Tambah kolom referred_by jika belum ada
            if (!Schema::hasColumn('users', 'referred_by')) {
                $table->unsignedBigInteger('referred_by')->nullable()->after('referral_code');
                $table->foreign('referred_by')->references('id')->on('users')->onDelete('set null');
            }
        });

        // Isi referral_code untuk user lama yang masih null
        $users = DB::table('users')->whereNull('referral_code')->get();
        foreach ($users as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'referral_code' => strtoupper(Str::random(8))
                ]);
        }

        // Pastikan referral_code unik
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'referral_code')) {
                $table->string('referral_code')->unique()->change();
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'referred_by')) {
                $table->dropForeign(['referred_by']);
                $table->dropColumn('referred_by');
            }

            if (Schema::hasColumn('users', 'referral_code')) {
                $table->dropColumn('referral_code');
            }
        });
    }
};
