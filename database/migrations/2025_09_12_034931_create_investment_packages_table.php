<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestmentPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investment_packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name'); // Nama paket investasi
            $table->decimal('amount', 12, 2); // Jumlah uang paket investasi
            $table->integer('duration_months')->default(6); // Durasi investasi dalam bulan (default 6 bulan)
            $table->decimal('profit_percent', 5, 2)->default(7.50); // Persentase profit per bulan (default 7.50%)
            $table->string('currency', 3)->default('USD'); // Mata uang (USD/IDR)
            $table->timestamps(); // Tanggal dibuat dan diperbarui
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('investment_packages');
    }
}
