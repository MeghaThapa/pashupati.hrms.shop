<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
// use App\Models\Department;
// use App\Models\Category;
use App\Models\Items;
use App\Models\Stock;
use Illuminate\Support\Str;
use App\Models\StoreinDepartment;
use App\Models\StoreinCategory;
use App\Models\ItemsOfStorein;
use App\Models\Size;
use App\Models\Unit;

//for silent creations
class StockImport implements ToCollection,WithHeadingRow,WithCalculatedFormulas
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $trimDepartment = trim($row['department']);
            $trimCategory = trim($row['category']);
            $trimItem = trim($row['item_name']);
            $trimSize = trim($row['size']);
            $trimUnit =  trim($row['unit']);

            /*******trims spaces in between**********/
            $department = StoreinDepartment::whereRaw('LOWER(REPLACE(name, " ", "")) = LOWER(?)', [str_replace(' ', '', $trimDepartment)])->value('id');
            $category = StoreinCategory::whereRaw('LOWER(REPLACE(name, " ", "")) = LOWER(?)', [str_replace(' ', '', $trimCategory)])->value('id');
            $rowitem = ItemsOfStorein::whereRaw('LOWER(REPLACE(name, " ", "")) = LOWER(?)', [str_replace(' ', '', $trimItem)])->value('id');
            $size = Size::whereRaw('LOWER(REPLACE(name, " ", "")) = LOWER(?)', [str_replace(' ', '', $trimSize)])->value('id');
            $unit = Unit::whereRaw('LOWER(REPLACE(name, " ", "")) = LOWER(?)', [str_replace(' ', '', $trimUnit)])->value('id');
            /******* end trims spaces in between**********/

            if($department && $category && $rowitem && $size && $unit){
                $item = $rowitem;
            }else{
                $item = null;
            }

            if($size == null) {
                $code = substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', 5)), 0, 10);
                $createSize = Size::create([
                    "name" => isset($row['size'])?$row['size']:"N/A",
                    "code" => $code,
                    'slug' => $code,
                    'note' => "Excel Import",
                    "status" => "1"
                ]);
                $size = $createSize->id;
             }

             if($unit == null){
                $code = rand(0,9999);
                $unitCreate = Unit::create([
                    "name" => $row['unit'],
                    "slug" => Str::slug($row['unit'].$code),
                    "code" => $code
                ]);
                $unit = $unitCreate->id;
             }

            if ($department === null) {
                $createdepartment = StoreinDepartment::create([
                    'name' => $row['department'],
                    'slug' => Str::slug($row['department']),
                    'status' => "active"
                ]);
                $department = $createdepartment->id;
                // return back()->with(['message_err'=>"Department:".$department." Not Found"]);
            }

            if ($category === null) {
                $createcategory = StoreinCategory::create([
                    'name' => $row['category'],
                    'slug' => Str::slug($row['category']),
                    'note' => isset($row['note'])? $row['note'] : 'N/A',
                    'status' => "active"
                ]);
                $category = $createcategory->id;
                // return back()->with(['message_err'=>"Category:".$category ."Not Found"]);
            }

            if ($item === null) {
                $createitem= ItemsOfStorein::create([
                    'name' => $row['item_name'],
                    'department_id'=> $department,
                    "category_id" => $category,
                    'status' => "1",
                    "unit_id" => $unit,
                    "size_id" => $size,
                    'pnumber' => isset($row['parts_number'])? $row['parts_number'] : "Any",
                ]);
                $item = $createitem->id;
                // return back()->with(['message_err'=>"Category:".$item ."Not Found"]); }
            }


            if($department && $category && $item && $size && $unit){
                Stock::create([
                'item_id' => $item,
                'size' => $size,
                'quantity' => $row['quantity'],
                'unit' => $unit,
                'avg_price' => $row['average_rate'],
                'total_amount' => $row['total_amount'],
                'department_id' => $department,
                'category_id' => $category,
                ]);
            }else{
                dd($department, $category , $item , $size , $unit);
            }
        }
    }
}


//for popups
//  class StockImport implements ToCollection, WithHeadingRow
//     {
//         // private $errorMessages = [];
//         private $errorMessages = [];

//         public function collection(Collection $rows)
//         {
//             foreach ($rows as $rowIndex => $row) {
//                 $department = Department::where('department', $row['department'])->value('id');
//                 $category = Category::where('name', $row['category'])->value('id');
//                 $item = Items::where('item', $row['item_name'])->value('id');

//                 if (!$department) {
//                     // $this->addError("Row " . ($rowIndex + 2) . ": Department '{$row['department']}' not found");
//                     // $this->addError($row['department']);
//                     // $this->addError("department",$row['department']);
//                     $createdep = Department::create([
//                         "department" => $row['department'],
//                         'slug' => Slug::str($row['department']),
//                         'status' => 'active'
//                     ]);
//                     if(!$createdep){
//                         $this->addError("department",$row['department']);
//                     }
//                 } elseif (!$category) {
//                     // $this->addError("Row " . ($rowIndex + 2) . ": Category '{$row['category']}' not found");
//                     $this->addError($row['category']);
//                 } elseif (!$item) {
//                     $this->addError($row['item_name']);
//                     // $this->addError("Row " . ($rowIndex + 2) . ": Item '{$row['item_name']}' not found");
//                 } else {
//                     Stock::create([
//                         'item_id' => $item,
//                         'size' => $row['size'],
//                         'quantity' => $row['qty'],
//                         'unit' => $row['unit'],
//                         'avg_price' => $row['avg_rate'],
//                         'total_amount' => $row['total_amt'],
//                         'department_id' => $department,
//                         'category_id' => $category,
//                     ]);
//                 }
//             }
//         }

//         public function onError(\Throwable $e)
//         {
//             // $this->addError($e->getMessage());
//             $this->addError("General Error", $e->getMessage());
//         }

//         public function addError($type,$errorMessage)
//         {
//             // $this->errorMessages[] = $errorMessage;
//             // $this->errorMessages = $errorMessage;

//             $this->errorMessages[] = array(
//                 "type" => $type,
//                 "data" => $errorMessage
//             );
//         }

//         public function getErrorMessages()
//         {
//             // dd($this->errorMessages);
//             return $this->errorMessages;
//         }
//     }



