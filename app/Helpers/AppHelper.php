<?php

namespace App\Helpers;

use App\Models\GeneralSetting;
use App\Libraries\Nepali_Calendar;
use App\Models\RawMaterial;
use App\Models\AutoLoad;
use App\Models\Storeout;
use Carbon\Carbon;

class AppHelper
{
    public $allSettings;
    function __construct()
    {
        $this->allSettings = GeneralSetting::get();
    }
    // get app general setting
    public function getGeneralSettigns()
    {
        $settings = [
            'companyName' => $this->allSettings->where('key', 'company_name')->first()->value,
            'currencyName' => $this->allSettings->where('key', 'currency_name')->first()->value,
            'compnayTagline' => $this->allSettings->where('key', 'compnay_tagline')->first()->value,
            'logo' => asset('img') . '/' . $this->allSettings->where('key', 'logo')->first()->value,
            'currencySymbol' => $this->allSettings->where('key', 'currency_symbol')->first()->value,
            'currencyPosition' => $this->allSettings->where('key', 'currency_position')->first()->value,
            //'timezone' => $this->allSettings->where('key', 'timezone')->first()->value,
            'codePefix' => $this->allSettings->where('key', 'purchase_code_prefix')->first()->value,
            'processingCodePefix' => $this->allSettings->where('key', 'processing_code_prefix')->first()->value,
            'finishedCodePefix' => $this->allSettings->where('key', 'finished_code_prefix')->first()->value,
            'transferredCodePrefix' => $this->allSettings->where('key', 'transferred_code_prefix')->first()->value,
        ];
        $settings = (object) $settings;
        return $settings;
    }
    public static function getAutoLoadReceiptNo()
    {
        $todayEnglishDate = Carbon::now()->format('Y-n-j');
        $date = self::getNepaliDate($todayEnglishDate);

        $autoLoad = AutoLoad::latest()->first();
        $receipt = "";
        if (!$autoLoad) {
            $receipt = 'AL'.'-'.$date . '-' . '1';
            return $receipt;
        } else {
            $receipt = 'AL'.'-'.$date . '-' . $autoLoad->id + 1;
            return $receipt;
        }
    }

    public static function getStoreOutReceiptNo()
    {
        $todayEnglishDate = Carbon::now()->format('Y-n-j');
        $date = self::getNepaliDate($todayEnglishDate);

        $storeOut = Storeout::latest()->first();
        $receipt = "";
        if (!$storeOut) {
            $receipt = $date . '-' . '1';
            return $receipt;
        } else {
            $receipt = $date . '-' . $storeOut->id + 1;
            return $receipt;
        }
    }

    public static function getRawMaterialReceiptNo()
    {
        $todayEnglishDate = Carbon::now()->format('Y-n-j');
        $date = self::getNepaliDate($todayEnglishDate);

        $rawMaterial = RawMaterial::latest()->first();
        $receipt = "";
        if (!$rawMaterial) {
            $receipt = $date . '-' . '1';
            return $receipt;
        } else {
            $receipt = $date . '-' . $rawMaterial->id + 1;
            return $receipt;
        }
    }

    public static function getNepaliDate($date)
    {
        $splitDate = explode("-", $date);
        $cal = new Nepali_Calendar();
        $nep = $cal->eng_to_nep($splitDate[0], $splitDate[1], $splitDate[2]);
        return ($nep["year"] . '-' . $nep["month"] . '-' . $nep["date"]);
    }

    public static function getTodayNepaliDate($yy, $mm, $dd, $date)
    {
        $splitDate = explode("-", $date);
        $cal = new Nepali_Calendar();
        $nep = $cal->eng_to_nep($yy, $mm, $dd);
        return ($nep["year"] . '-' . $nep["month"] . '-' . $nep["date"]);
    }

    // return formatted currency
    public function formattedCurrency($amount)
    {
        if ($this->getGeneralSettigns()->currencyPosition == 'left') {
            return $amount > 0 ? $this->getGeneralSettigns()->currencySymbol . $amount : $this->getGeneralSettigns()->currencySymbol . "0";
        } else {
            return $amount > 0 ? $amount . $this->getGeneralSettigns()->currencySymbol : "0" . $this->getGeneralSettigns()->currencySymbol;
        }
    }

    // return discount amount
    public function discountAmount($discount, $subtotal)
    {
        return ($discount / 100) * $subtotal;
    }

    // return purchase code
    public function pruchaseCode($code)
    {
        return $this->getGeneralSettigns()->codePefix . $code;
    }

    // return processing code
    public function processingCode($code)
    {
        return $this->getGeneralSettigns()->processingCodePefix . $code;
    }

    // return finished code
    public function finishedCode($code)
    {
        return $this->getGeneralSettigns()->finishedCodePefix . $code;
    }

    // return transferred code
    public function transferredCode($code)
    {
        return $this->getGeneralSettigns()->transferredCodePrefix . $code;
    }

    public function startQueryLog()
    {
        \DB::enableQueryLog();
    }

    public function showQueries()
    {
        dd(\DB::getQueryLog());
    }

    public static function instance()
    {
        return new AppHelper();
    }
}
