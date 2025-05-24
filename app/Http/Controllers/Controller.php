<?php

namespace App\Http\Controllers;

abstract class Controller
{
    // -------------------------------------------------------------------------
    //  FUNGSI KEANGGOTAAN
    // -------------------------------------------------------------------------

    // Fungsi Linear Turun
    public function fuzzyLinearTurun($x, $a, $b)
    {
        if ($x <= $a) return 1;
        if ($x >= $b) return 0;
        return ($b - $x) / ($b - $a);
    }

    // Fungsi Linear Naik
    public function fuzzyLinearNaik($x, $a, $b)
    {
        if ($x <= $a) return 0;
        if ($x >= $b) return 1;
        return ($x - $a) / ($b - $a);
    }

    // Fungsi Segitiga
    public function fuzzySegitiga($x, $a, $b, $c)
    {
        if ($x <= $a || $x >= $c) return 0;
        if ($x == $b) return 1;
        if ($x > $a && $x < $b) return ($x - $a) / ($b - $a);
        return ($c - $x) / ($c - $b);
    }

    // Fungsi Trapezoid (jika diperlukan)
    public function fuzzyTrapezoid($x, $a, $b, $c, $d)
    {
        if ($x <= $a || $x >= $d) return 0;
        if ($x > $a && $x <= $b) return ($x - $a) / ($b - $a);
        if ($x > $b && $x < $c) return 1;
        return ($d - $x) / ($d - $c);
    }

    // -------------------------------------------------------------------------
    //  FUZZIFIKASI
    // -------------------------------------------------------------------------

    public function fuzzyUsia($usia)
    {
        return [
            'muda' => $this->fuzzyLinearTurun($usia, 25, 30),
            'dewasa' => $this->fuzzySegitiga($usia, 25, 50, 56),
            'tua' => $this->fuzzyLinearNaik($usia, 50, 56),
        ];
    }

    public function fuzzySuhu($suhu)
    {
        return [
            'rendah' => $this->fuzzyLinearTurun($suhu, 35, 36.5),
            'normal' => $this->fuzzySegitiga($suhu, 35, 37.2, 38),
            'tinggi' => $this->fuzzyLinearNaik($suhu, 37.2, 38),
        ];
    }

    public function fuzzyTekananDarah($tekanan)
    {
        return [
            'rendah' => $this->fuzzyLinearTurun($tekanan, 80, 90),
            'normal' => $this->fuzzySegitiga($tekanan, 80, 110, 140),
            'tinggi' => $this->fuzzyLinearNaik($tekanan, 120, 140),
        ];
    }

    public function fuzzyAsamUrat($asam_urat)
    {
        return [
            'rendah' => $this->fuzzyLinearTurun($asam_urat, 2, 2.5),
            'normal' => $this->fuzzySegitiga($asam_urat, 2, 4.5, 7),
            'tinggi' => $this->fuzzyLinearNaik($asam_urat, 7, 7.4),
        ];
    }

    public function fuzzyKadarUrine($kadar)
    {
        return [
            'sedikit' => $this->fuzzyLinearTurun($kadar, 0.4, 0.6),
            'normal' => $this->fuzzySegitiga($kadar, 0.4, 1.1, 1.8),
            'banyak' => $this->fuzzyLinearNaik($kadar, 61.2, 1.8),
        ];
    }

    public function fuzzyKonsumsiAir($konsumsi)
    {
        return [
            'kurang' => $this->fuzzyLinearTurun($konsumsi, 7, 9),
            'cukup' => $this->fuzzySegitiga($konsumsi, 7, 10, 13),
            'banyak' => $this->fuzzyLinearNaik($konsumsi, 11, 13),
        ];
    }

    // Fixed to handle string values properly
    public function fuzzyWarnaUrine($warna)
    {
        // Map string values to numeric scores for fuzzy processing
        $nilai_warna = [
            'jernih' => 1,
            'kuning' => 4.5, 
            'keruh' => 8
        ];
        
        // If string input is provided, use mapped value
        $nilai = is_string($warna) ? ($nilai_warna[$warna] ?? 4.5) : $warna;
        
        return [
            'jernih' => $this->fuzzyLinearTurun($nilai, 1, 4),
            'kuning' => $this->fuzzySegitiga($nilai, 1, 4.5, 8),
            'keruh' => $this->fuzzyLinearNaik($nilai, 6, 8),
        ];
    }
}
?>