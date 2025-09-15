<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
    {
        Schema::create('sponsor_fee', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('level')->comment('Level referral (1=direct, 2=indirect)');
            $table->decimal('percentage', 5, 2)->comment('Persentase sponsor fee');
            $table->timestamps();
        });

        Schema::create('profit_sharing', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('level')->comment('Level profit sharing (1,2,3)');
            $table->decimal('percentage', 5, 2)->comment('Persentase profit sharing');
            $table->timestamps();
        });

        Schema::create('bonus_profit', function (Blueprint $table) {
            $table->id();
            $table->decimal('min_omset_usd', 15, 2)->comment('Omset minimal agar bonus berlaku');
            $table->tinyInteger('level')->comment('Level profit sharing yang mendapat bonus');
            $table->decimal('bonus_percentage', 5, 2)->comment('Tambahan persentase profit sharing');
            $table->boolean('status')->default(1)->comment('1=aktif, 0=nonaktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bonus_profit');
        Schema::dropIfExists('profit_sharing');
        Schema::dropIfExists('sponsor_fee');
    }
};
