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
        Schema::create('diagnoses', function (Blueprint $table) {
            $table->id();
            $table->integer('usia');
            $table->float('suhu_tubuh');
            $table->float('tekanan_darah');
            $table->float('asam_urat');
            $table->float('kadar_urine');
            $table->string('warna_urine');
            $table->integer('konsumsi_air_putih');
            $table->string('sering_berkemih');
            $table->string('nyeri_pinggang');
            $table->string('nyeri_buang_air_kecil');
            $table->string('ruam_kulit');
            $table->string('mudah_lelah');
            $table->string('mual_muntah');
            $table->string('sesak_nafas');
            $table->string('nyeri_pinggul');
            $table->string('riwayat_ginjal');
            $table->string('riwayat_hipertensi');
            $table->string('riwayat_diabetes');
            $table->string('risk_score'); // Your fuzzy logic result
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnoses');
    }
};
