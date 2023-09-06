<?php

namespace App\Exports;

use App\Models\FabricSendAndReceiveUnlaminatedFabric;
use App\Models\FabricStock;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FabricStocksExport implements FromCollection, WithHeadings,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = FabricSendAndReceiveUnlaminatedFabric::get();
        // $data = FabricStock::where('is_laminated','false')->where('godam_id',1)->get();
        return $data;
    }


    public function map($data): array
    {
        return [
            [
                $data->getfabric->name,
                $data->getfabric->roll_no,
                $data->getfabric->average_wt,
                $data->getfabric->gram_wt,
                $data->getfabric->gross_wt,
                $data->getfabric->net_wt,
                $data->getfabric->meter,
                // $data->getfabricBill->bill_number,
            ]
        ];

    }

    public function headings(): array
       {
           return [
              ['name','ROll','Average','Gram','Gross','Net','Meter','Date'],
           ];
       }
}
