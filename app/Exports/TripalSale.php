<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnWidth;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Database\Eloquent\Builder;
use App\Models\SaleFinalTripal;
use App\Models\SaleFinalTripalList;

class TripalSale implements FromQuery,WithStyles, WithHeadings, WithMapping,ShouldAutoSize,WithEvents
{
     use Exportable;

    protected $tripalSale;

     function __construct($tripalSale) {
            $this->sale = $tripalSale;

            $findtripal = SaleFinalTripal::find($this->sale);

            $this->partyname = $findtripal->getParty->name;
            $this->invoice = $findtripal->bill_no;
            $this->gp = $findtripal->gp_no;
            $this->date = $findtripal->bill_date;
           
     }

     public function query()
     {
        $student = SaleFinalTripalList::where('salefinal_id',$this->sale);
        $data = 1;

        $datas = [
            'student' => $student,
            'data' => $data,
        ];
        // dd($datas['student']);
        // dd($datas[0]);

        // Return the array
        return $student;
        
     }

    public function map($student): array
        {
        
            return [
                $student->id,
                $student->name,
                $student->roll,
                $student->gross,
                $student->net,
                $student->meter,
                $student->gram,
            ];

          
        }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
     
    public function  headings(): array
    {
        return 
        [
            [
                'PASHUPATI SYNPACK INDUSTRIES PVT. LTD. (SONAPUR,SUNSARI)',
            ],
            [
                // 'Phone:' .$this->partyname, 'Invoice:' . $this->invoice, 'Date:' . $this->date,

                $this->partyname.", Invoice: ".$this->invoice.", Date: ". $this->date,
            ],
           
            [
                "ID", 
                "Name", 
                "ROll", 
                "Gross",
                "Net",
                "Meter",
                "Gram",
               
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeExport::class  => function(BeforeExport $event) {
                $event->writer->setCreator('Production Management System');
            },
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet
                      ->getPageSetup()
                      ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                // header
                $event->sheet->mergeCells('A1:F1');
                $event->sheet
                      ->getStyle('A1:F1')
                      ->getFont()
                      ->setBold(true)
                      ->setSize(16)
                      ->setColor( new \PhpOffice\PhpSpreadsheet\Style\Color( \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKGREEN ) );
                $event->sheet
                      ->getStyle('A1:F1')
                      ->getAlignment()
                      ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $event->sheet->mergeCells('A2:F2');
                $event->sheet
                      ->getStyle('A2:F2')
                      ->getFont()
                      ->setBold(true)
                      ->setSize(14)
                      ->setColor( new \PhpOffice\PhpSpreadsheet\Style\Color( \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKGREEN ) );
                $event->sheet
                      ->getStyle('A2:F2')
                      ->getAlignment()
                      ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // content
                $event->sheet->getStyle('A3:P3')->applyFromArray([
                    'font' => [
                        'bold' => True,
                        'size' => 12
                    ]
                ]);
                
            },
        ];
    }
    

    public function styles(Worksheet $sheet)
    {
     $sheet->mergeCells('A1:F1');
     $sheet->getStyle('A1:F1')->getFont()->setBold(true);
     $sheet->mergeCells('A2:F2');
     $sheet->getStyle('A2:F2')->getFont()->setBold(true);
     $sheet->getStyle('A3:F3')->getFont()->setBold(true);
    }
}
