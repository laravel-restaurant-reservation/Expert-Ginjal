<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Diagnosis;

class DiagnoseController extends Controller
{
    public function proses(Request $request)
    {
        // Validasi input
        $data = $request->validate([
            'usia' => 'required|integer|min:0|max:120',
            'suhu_tubuh' => 'required|numeric|between:30,45',
            'tekanan_darah' => 'required|numeric|min:70|max:200',
            'asam_urat' => 'required|numeric|min:0|max:20',
            'kadar_urine' => 'required|numeric|min:0|max:1000',
            'warna_urine' => 'required|in:jernih,kuning,keruh',
            'konsumsi_air_putih' => 'required|integer|min:0|max:20',
            'sering_berkemih' => 'required|in:Ya,Tidak',
            'nyeri_pinggang' => 'required|in:Ya,Tidak',
            'nyeri_buang_air_kecil' => 'required|in:Ya,Tidak',
            'ruam_kulit' => 'required|in:Ya,Tidak',
            'mudah_lelah' => 'required|in:Ya,Tidak',
            'mual_muntah' => 'required|in:Ya,Tidak',
            'sesak_nafas' => 'required|in:Ya,Tidak',
            'nyeri_pinggul' => 'required|in:Ya,Tidak',
            'riwayat_ginjal' => 'required|in:Ya,Tidak',
            'riwayat_hipertensi' => 'required|in:Ya,Tidak',
            'riwayat_diabetes' => 'required|in:Ya,Tidak',
        ]);

        // Fuzzyfikasi - Hitung fuzzy sets sekali saja
        $data_fuzzy = [
            'usia' => $this->fuzzyUsia($data['usia']),
            'suhu_tubuh' => $this->fuzzySuhu($data['suhu_tubuh']),
            'tekanan_darah' => $this->fuzzyTekananDarah($data['tekanan_darah']),
            'asam_urat' => $this->fuzzyAsamUrat($data['asam_urat']),
            'kadar_urine' => $this->fuzzyKadarUrine($data['kadar_urine']),
            'warna_urine' => $this->fuzzyWarnaUrine($data['warna_urine']),
            'konsumsi_air_putih' => $this->fuzzyKonsumsiAir($data['konsumsi_air_putih']),
        ];

        // Konversi Ya/Tidak ke 0/1 untuk gejala
        $gejala = [
            'sering_berkemih' => $data['sering_berkemih'] === 'Ya' ? 1 : 0,
            'nyeri_pinggang' => $data['nyeri_pinggang'] === 'Ya' ? 1 : 0,
            'nyeri_buang_air_kecil' => $data['nyeri_buang_air_kecil'] === 'Ya' ? 1 : 0,
            'ruam_kulit' => $data['ruam_kulit'] === 'Ya' ? 1 : 0,
            'mudah_lelah' => $data['mudah_lelah'] === 'Ya' ? 1 : 0,
            'mual_muntah' => $data['mual_muntah'] === 'Ya' ? 1 : 0,
            'sesak_nafas' => $data['sesak_nafas'] === 'Ya' ? 1 : 0,
            'nyeri_pinggul' => $data['nyeri_pinggul'] === 'Ya' ? 1 : 0,
            'riwayat_ginjal' => $data['riwayat_ginjal'] === 'Ya' ? 1 : 0,
            'riwayat_hipertensi' => $data['riwayat_hipertensi'] === 'Ya' ? 1 : 0,
            'riwayat_diabetes' => $data['riwayat_diabetes'] === 'Ya' ? 1 : 0,
        ];

        // Rule Fuzzy (dari data yang diberikan)
        $rules = [
            [
                'kondisi' => [
                    'usia' => 'dewasa',
                    'suhu_tubuh' => 'normal',
                    'tekanan_darah' => 'tinggi',
                    'asam_urat' => 'tinggi',
                    'kadar_urine' => 'sedikit',
                    'warna_urine' => 'keruh',
                    'konsumsi_air_putih' => 'normal',
                    'sering_berkemih' => 1,
                    'nyeri_pinggang' => 1,
                    'nyeri_buang_air_kecil' => 0,
                    'ruam_kulit' => 1,
                    'mudah_lelah' => 1,
                    'mual_muntah' => 1,
                    'sesak_nafas' => 1,
                    'nyeri_pinggul' => 0,
                    'riwayat_ginjal' => 1,
                    'riwayat_hipertensi' => 1,
                    'riwayat_diabetes' => 1,
                ],
                'cf_pakar' => 0.6,
            ],
            [
                'kondisi' => [
                    'usia' => 'dewasa',
                    'suhu_tubuh' => 'normal',
                    'tekanan_darah' => 'tinggi',
                    'asam_urat' => 'tinggi',
                    'kadar_urine' => 'sedikit',
                    'warna_urine' => 'keruh',
                    'konsumsi_air_putih' => 'kurang',
                    'sering_berkemih' => 1,
                    'nyeri_pinggang' => 1,
                    'nyeri_buang_air_kecil' => 0,
                    'ruam_kulit' => 1,
                    'mudah_lelah' => 1,
                    'mual_muntah' => 1,
                    'sesak_nafas' => 1,
                    'nyeri_pinggul' => 0,
                    'riwayat_ginjal' => 1,
                    'riwayat_hipertensi' => 1,
                    'riwayat_diabetes' => 1,
                ],
                'cf_pakar' => 0.7,
            ],
            [
                'kondisi' => [
                    'usia' => 'dewasa',
                    'suhu_tubuh' => 'tinggi',
                    'tekanan_darah' => 'tinggi',
                    'asam_urat' => 'tinggi',
                    'kadar_urine' => 'sedikit',
                    'warna_urine' => 'keruh',
                    'konsumsi_air_putih' => 'normal',
                    'sering_berkemih' => 1,
                    'nyeri_pinggang' => 1,
                    'nyeri_buang_air_kecil' => 0,
                    'ruam_kulit' => 1,
                    'mudah_lelah' => 1,
                    'mual_muntah' => 1,
                    'sesak_nafas' => 1,
                    'nyeri_pinggul' => 0,
                    'riwayat_ginjal' => 1,
                    'riwayat_hipertensi' => 1,
                    'riwayat_diabetes' => 1,
                ],
                'cf_pakar' => 0.7,
            ],
            [
                'kondisi' => [
                    'usia' => 'dewasa',
                    'suhu_tubuh' => 'tinggi',
                    'tekanan_darah' => 'tinggi',
                    'asam_urat' => 'tinggi',
                    'kadar_urine' => 'sedikit',
                    'warna_urine' => 'keruh',
                    'konsumsi_air_putih' => 'kurang',
                    'sering_berkemih' => 1,
                    'nyeri_pinggang' => 1,
                    'nyeri_buang_air_kecil' => 0,
                    'ruam_kulit' => 1,
                    'mudah_lelah' => 1,
                    'mual_muntah' => 1,
                    'sesak_nafas' => 1,
                    'nyeri_pinggul' => 0,
                    'riwayat_ginjal' => 1,
                    'riwayat_hipertensi' => 1,
                    'riwayat_diabetes' => 1,
                ],
                'cf_pakar' => 0.8,
            ],
            [
                'kondisi' => [
                    'usia' => 'tua',
                    'suhu_tubuh' => 'normal',
                    'tekanan_darah' => 'tinggi',
                    'asam_urat' => 'tinggi',
                    'kadar_urine' => 'sedikit',
                    'warna_urine' => 'keruh',
                    'konsumsi_air_putih' => 'normal',
                    'sering_berkemih' => 1,
                    'nyeri_pinggang' => 1,
                    'nyeri_buang_air_kecil' => 0,
                    'ruam_kulit' => 1,
                    'mudah_lelah' => 1,
                    'mual_muntah' => 1,
                    'sesak_nafas' => 1,
                    'nyeri_pinggul' => 0,
                    'riwayat_ginjal' => 1,
                    'riwayat_hipertensi' => 1,
                    'riwayat_diabetes' => 1,
                ],
                'cf_pakar' => 0.7,
            ],
            [
                'kondisi' => [
                    'usia' => 'tua',
                    'suhu_tubuh' => 'normal',
                    'tekanan_darah' => 'tinggi',
                    'asam_urat' => 'tinggi',
                    'kadar_urine' => 'sedikit',
                    'warna_urine' => 'keruh',
                    'konsumsi_air_putih' => 'kurang',
                    'sering_berkemih' => 1,
                    'nyeri_pinggang' => 1,
                    'nyeri_buang_air_kecil' => 0,
                    'ruam_kulit' => 1,
                    'mudah_lelah' => 1,
                    'mual_muntah' => 1,
                    'sesak_nafas' => 1,
                    'nyeri_pinggul' => 0,
                    'riwayat_ginjal' => 1,
                    'riwayat_hipertensi' => 1,
                    'riwayat_diabetes' => 1,
                ],
                'cf_pakar' => 0.8,
            ],
            [
                'kondisi' => [
                    'usia' => 'tua',
                    'suhu_tubuh' => 'tinggi',
                    'tekanan_darah' => 'tinggi',
                    'asam_urat' => 'tinggi',
                    'kadar_urine' => 'sedikit',
                    'warna_urine' => 'keruh',
                    'konsumsi_air_putih' => 'normal',
                    'sering_berkemih' => 1,
                    'nyeri_pinggang' => 1,
                    'nyeri_buang_air_kecil' => 0,
                    'ruam_kulit' => 1,
                    'mudah_lelah' => 1,
                    'mual_muntah' => 1,
                    'sesak_nafas' => 1,
                    'nyeri_pinggul' => 0,
                    'riwayat_ginjal' => 1,
                    'riwayat_hipertensi' => 1,
                    'riwayat_diabetes' => 1,
                ],
                'cf_pakar' => 0.8,
            ],
            [
                'kondisi' => [
                    'usia' => 'tua',
                    'suhu_tubuh' => 'tinggi',
                    'tekanan_darah' => 'tinggi',
                    'asam_urat' => 'tinggi',
                    'kadar_urine' => 'sedikit',
                    'warna_urine' => 'keruh',
                    'konsumsi_air_putih' => 'kurang',
                    'sering_berkemih' => 1,
                    'nyeri_pinggang' => 1,
                    'nyeri_buang_air_kecil' => 0,
                    'ruam_kulit' => 1,
                    'mudah_lelah' => 1,
                    'mual_muntah' => 1,
                    'sesak_nafas' => 1,
                    'nyeri_pinggul' => 0,
                    'riwayat_ginjal' => 1,
                    'riwayat_hipertensi' => 1,
                    'riwayat_diabetes' => 1,
                ],
                'cf_pakar' => 0.8,
            ],
        ];

        // Step 1: Hitung derajat keanggotaan
        $fuzzy_summary = [];
        foreach ($data_fuzzy as $variabel => $kategori) {
            $input_value = $data[$variabel];
            foreach ($kategori as $label => $nilai) {
                if ($nilai > 0) {
                    $fuzzy_summary[] = [
                        'variabel' => ucwords(str_replace('_', ' ', $variabel)),
                        'input' => $input_value,
                        'kategori' => ucfirst($label),
                        'derajat' => round($nilai, 3),
                    ];
                }
            }
        }

        $hasil = "Langkah 1 selesai: Derajat keanggotaan berhasil dihitung.";

        // Step 2: Rule Matching dan Inferensi
        $matching_rules = [];
        foreach ($rules as $rule) {
            $match = true;
            $cf_user = 1; // Inisialisasi CF user
            foreach ($rule['kondisi'] as $variabel => $kategori) {
                if (isset($data_fuzzy[$variabel][$kategori])) {
                    $cf_user = min($cf_user, $data_fuzzy[$variabel][$kategori]);
                } elseif (isset($gejala[$variabel]) && $gejala[$variabel] !== $kategori) {
                    $match = false;
                    break;
                }
            }
            if ($match) {
                $matching_rules[] = [
                    'nama' => 'Rule ' . (count($matching_rules) + 1), // Generate nama rule
                    'cfpakar' => $rule['cf_pakar'],
                    'cfuser' => $cf_user,
                ];
            }
        }

        // Step 3: Defuzzifikasi (Weighted Average / Centroid)
        $total_numerator = 0;
        $total_denominator = 0;
        $rule_summary = [];

        foreach ($matching_rules as $rule) {
            $wi = $rule['cfuser']; // Use cfuser (wi)
            $zi = $rule['cfpakar'];

            $total_numerator += ($wi * $zi);
            $total_denominator += $zi;

            $rule_summary[] = [
                'nama' => $rule['nama'],
                'cfpakar' => $zi,
                'cfuser' => $wi,
                'cfhe' => $wi * $zi,
            ];
        }

        $risk_score = $total_denominator > 0 ? $total_numerator / $total_denominator : 0;
        $risk_score = round($risk_score * 100, 2);
        
        // simpan hasil ke db
        Diagnosis::create(array_merge($data, ['risk_score' => $risk_score]));

        // View output
        return view('hasil', compact('hasil', 'data', 'fuzzy_summary', 'rule_summary'))
            ->with('skor', $risk_score);


    }
    // -------------------------------------------------------------------------
    //  EXPORT PDF
    // -------------------------------------------------------------------------

    public function exportPDF()
    {
        $hasil = session('hasil');
        $data = session('data');
        $skor = session('skor');

        if (!$hasil || !$data || !$skor) {
            return redirect()->route('form')->with('error', 'Tidak ada data diagnosa untuk di-export.');
        }

        # $pdf = Pdf::loadView('hasil_pdf', compact('hasil', 'data', 'skor'))->setPaper('a4', 'portrait');
        # return $pdf->download('hasil_diagnosa.pdf');
        
        $pdf = Pdf::loadView('hasil_pdf', compact('hasil', 'data', 'skor'))->setPaper('a4', 'portrait');
        $filename = 'hasil_diagnosa_' . now()->timestamp . '.pdf';
        Storage::disk('s3')->put("diagnoses/$filename", $pdf->output());

        return redirect()->route('form')->with('success', "Hasil telah disimpan ke S3 sebagai $filename");
    }
}