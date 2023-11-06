<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use Illuminate\Support\Str;
use App\Models\LamWaste;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class LamWstRecoverImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{

    // public $godam_id;
    // public $date_np;

    // public function __construct($godam_id, $date_np)
    // {
    //     $this->godam_id = $godam_id;
    //     $this->date_np = $date_np;
    // }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
        $trimDate = strtolower(trim($row['date']));
        $trimPolo = strtolower(trim($row['polo']));
        $trimFabric = strtolower(trim($row['fabric']));
        

        // $bill_no = "FI" . "-" . getNepaliDate(date('Y-m-d')) . "-" . strtotime(date(("H:i:s")));

        try {
            $detail = LamWaste::create([
                'date' => $trimDate,
                'polo_waste' => $trimPolo,
                'fabric_waste' => $trimFabric,
                'total_waste' => $trimPolo + $trimFabric,
            ]);
            // return response(200);
        } catch (Exception $e) {
            dd($e->getMessage());
            return response([
                "exception" => $e->getMessage(),
            ]);
        }
    }
    }
}
