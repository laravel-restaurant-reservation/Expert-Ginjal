<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DiagnoseController extends Controller
{
    public function proses(Request $request)
    {
        $data = $request->validate([
            'usia' => 'required|integer|min:0|max:120',
            'suhu_tubuh' => 'required|numeric|between:30,45',
            'tekanan_darah' => 'required|numeric|min:70|max:200',
            'asam_urat' => 'required|numeric|min:0|max:20',
            'kadar_urine' => 'required|numeric|min:0|max:1000',
            'warna_urine' => 'required|in:jernih,kuning,keruh',
            'konsumsi_air_putih' => 'required|integer|min:0|max:20',
            'nyeri_pinggang' => 'required|in:Ya,Tidak',
            'sering_berkemih' => 'required|in:Ya,Tidak',
            'mudah_lelah' => 'required|in:Ya,Tidak',
            'mual_muntah' => 'required|in:Ya,Tidak',
            'riwayat_ginjal' => 'required|in:Ya,Tidak',
            'riwayat_hipertensi' => 'required|in:Ya,Tidak',
            'riwayat_diabetes' => 'required|in:Ya,Tidak',
        ]);

        // Step 1: Fuzzy Membership Functions (example: suhu tubuh)
        $usia_score = $this->fuzzyUsia($data['usia']);
        $suhu_score = $this->fuzzySuhu($data['suhu_tubuh']);
        $tekanan_score = $this->fuzzyTekanan($data['tekanan_darah']);
        $asam_score = $this->fuzzyAsamUrat($data['asam_urat']);
        $urine_score = $this->fuzzyUrine($data['kadar_urine']);
        $air_score = $this->fuzzyAirPutih($data['konsumsi_air_putih']);

        // Convert Ya/Tidak to numeric
        $gejala = collect([
            'nyeri_pinggang' => $data['nyeri_pinggang'] === 'Ya' ? 1 : 0,
            'sering_berkemih' => $data['sering_berkemih'] === 'Ya' ? 1 : 0,
            'mudah_lelah' => $data['mudah_lelah'] === 'Ya' ? 1 : 0,
            'mual_muntah' => $data['mual_muntah'] === 'Ya' ? 1 : 0,
            'riwayat_ginjal' => $data['riwayat_ginjal'] === 'Ya' ? 1 : 0,
            'riwayat_hipertensi' => $data['riwayat_hipertensi'] === 'Ya' ? 1 : 0,
            'riwayat_diabetes' => $data['riwayat_diabetes'] === 'Ya' ? 1 : 0,
        ])->sum() / 7; // Normalize gejala score between 0–1


        // Step 2: Combine scores with simple rule weighting (defuzzification )
        $risk_score = (
            $usia_score * 0.1 +
            $suhu_score * 0.1 +
            $tekanan_score * 0.1 +
            $asam_score * 0.15 +
            $urine_score * 0.15 +
            $air_score * 0.1 +
            $gejala * 0.3
        );

        // Step 3: Interpret result
        if ($risk_score >= 0.7) {
            $hasil = "Kemungkinan gangguan ginjal. Disarankan konsultasi ke dokter.";
        } elseif ($risk_score >= 0.4) {
            $hasil = "Perlu waspada. Perhatikan gaya hidup dan konsultasi jika perlu.";
        } else {
            $hasil = "Tidak terindikasi gangguan ginjal berdasarkan data yang diberikan.";
        }

        session([
            'hasil' => $hasil,
            'data' => $data,
            'skor' => round($risk_score, 2),
        ]);
    
        return view('hasil', compact('hasil', 'data'))->with('skor', round($risk_score, 2));
    }


    public function exportPDF()
    {
        $hasil = session('hasil');
        $data = session('data');
        $skor = session('skor');

        if (!$hasil || !$data || !$skor) {
            return redirect()->route('form')->with('error', 'Tidak ada data diagnosa untuk di-export.');
        }

        $pdf = Pdf::loadView('hasil_pdf', compact('hasil', 'data', 'skor'))->setPaper('a4', 'portrait');
        return $pdf->download('hasil_diagnosa.pdf');
    }
}
