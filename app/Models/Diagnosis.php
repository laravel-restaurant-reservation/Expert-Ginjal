<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    use HasFactory;

    protected $table = 'diagnoses';

    protected $fillable = [
        'usia',
        'suhu_tubuh',
        'tekanan_darah',
        'asam_urat',
        'kadar_urine',
        'warna_urine',
        'konsumsi_air_putih',
        'sering_berkemih',
        'nyeri_pinggang',
        'nyeri_buang_air_kecil',
        'ruam_kulit',
        'mudah_lelah',
        'mual_muntah',
        'sesak_nafas',
        'nyeri_pinggul',
        'riwayat_ginjal',
        'riwayat_hipertensi',
        'riwayat_diabetes',
        'risk_score',
    ];
}
