<?php

use App\Libraries\Nepali_Calendar;
use App\Models\Storeout;
use Carbon\Carbon;
use App\Models\BagBundelItem;


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
    function getNepaliDate($date){
        $splitDate = explode("-", $date);
        $cal = new Nepali_Calendar();
        $nep = $cal->eng_to_nep($splitDate[0], $splitDate[1], $splitDate[2]);
        return ($nep["year"] . '-' . str_pad($nep["month"], 2, '0', STR_PAD_LEFT) . '-' .str_pad($nep["date"], 2, '0', STR_PAD_LEFT) );
    }
}
if (!function_exists('getTodayNepaliDate')) {
    function getTodayNepaliDate(){
        $currentDate = \Carbon\Carbon::now()->format('Y-m-d');
        return getNepaliDate($currentDate);
    }
}
if (!function_exists('generateBagBundelNumber')) {

}



