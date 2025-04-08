<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DiagnoseController extends Controller
{
    public function proses(Request $request)
    {
        $data = $request->validate([
            'usia' => 'required|numeric',
            'suhu_tubuh' => 'required|numeric',
            'tekanan_darah' => 'required|numeric',
            'asam_urat' => 'required|numeric',
            'kadar_urine' => 'required|numeric',
            'warna_urine' => 'required',
            'konsumsi_air_putih' => 'required|numeric',
            'nyeri_pinggang' => 'required',
            'sering_berkemih' => 'required',
            'mudah_lelah' => 'required',
            'mual_muntah' => 'required',
            'riwayat_ginjal' => 'required',
            'riwayat_hipertensi' => 'required',
            'riwayat_diabetes' => 'required',
        ]);

        // Skoring sederhana
        $skor = 0;
        if ($data['nyeri_pinggang'] === 'Ya') $skor++;
        if ($data['sering_berkemih'] === 'Ya') $skor++;
        if ($data['mual_muntah'] === 'Ya') $skor++;
        if ($data['riwayat_ginjal'] === 'Ya') $skor++;
        if ($data['riwayat_hipertensi'] === 'Ya') $skor++;
        if ($data['riwayat_diabetes'] === 'Ya') $skor++;

        $hasil = $skor >= 3
            ? "Kemungkinan gangguan ginjal. Disarankan konsultasi ke dokter."
            : "Tidak terindikasi gangguan ginjal berdasarkan data yang diberikan.";

        return view('hasil', compact('hasil', 'data', 'skor'));
    }
}
