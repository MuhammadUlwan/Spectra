<?php

namespace App\Helpers;

class ProfitHelper
{
    /**
     * Hitung estimasi profit berdasarkan durasi
     */
    public static function calculate($amount, $baseRate, $duration)
    {
        switch ($duration) {
            case '15d': // 15 hari
                $rate = 3.25;
                break;
            case '1m': // 1 bulan
                $rate = 7.5;
                break;
            case '6m': // 6 bulan
                $rate = $baseRate; // default dari tabel
                break;
            default:
                $rate = 0;
        }

        $profit = ($amount * $rate) / 100;
        return [
            'rate'   => $rate,
            'profit' => $profit,
        ];
    }
}