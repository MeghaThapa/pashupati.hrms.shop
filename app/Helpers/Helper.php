<?php

use App\Libraries\Nepali_Calendar;
use App\Models\Storeout;
use Carbon\Carbon;

if (!function_exists('getStoreOutReceiptNo')) {
    function getStoreOutReceiptNo()
    {
        $todayEnglishDate = Carbon::now()->format('Y-n-j');
        $todayNepaliDate = getNepaliDate($todayEnglishDate);
        $storeOut = Storeout::latest()->first();
        $receipt = "";
        if (!$storeOut) {
            $receipt = $todayNepaliDate . '-' . '1';
            return $receipt;
        } else {
            $receipt = $todayNepaliDate . '-' . $storeOut->id + 1;
            return $receipt;
        }
    }
}
if (!function_exists('getNepaliDate')) {
    function getNepaliDate($date)
    {
        $splitDate = explode("-", $date);
        $cal = new Nepali_Calendar();
        $nep = $cal->eng_to_nep($splitDate[0], $splitDate[1], $splitDate[2]);
        return ($nep["year"] . '-' . $nep["month"] . '-' . $nep["date"]);
    }
}
if (!function_exists('getTodayNepaliDate')) {
    function getTodayNepaliDate($yy, $mm, $dd, $date)
    {
        $splitDate = explode("-", $date);
        $cal = new Nepali_Calendar();
        $nep = $cal->eng_to_nep($yy, $mm, $dd);
        return ($nep["year"] . '-' . $nep["month"] . '-' . $nep["date"]);
    }
}
