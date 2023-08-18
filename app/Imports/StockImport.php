<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\Items;
use App\Models\Stock;
use Illuminate\Support\Str;
use App\Models\StoreinDepartment;
use App\Models\StoreinCategory;
use App\Models\ItemsOfStorein;
use App\Models\OpeningStoreinReport;
use App\Models\Size;
use App\Models\Unit;
use  DB;
use Carbon\Carbon;

class StockImport implements ToCollection,WithHeadingRow,WithCalculatedFormulas
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        try{
        DB::beginTransaction();
        foreach ($rows as $row) {
           // dd($row);
            $trimDepartment = trim($row['department']);
            $trimCategory = trim($row['category']);
            $trimItem = trim($row['item_name']);
            $trimSize = trim($row['size']);
            $trimUnit =  trim($row['unit']);
            $trimProductNumber =trim($row['parts_number']);
            $trimQuantity = trim($row['quantity']);
            $trimTotal = trim($row['total_amount']);
            $trimAverage = trim($row['average_rate']);

            /*******trims spaces in between**********/
            $department = StoreinDepartment::whereRaw('LOWER(REPLACE(name, " ", "")) = LOWER(?)', [str_replace(' ', '', $trimDepartment)])->value('id');
            $category = StoreinCategory::whereRaw('LOWER(REPLACE(name, " ", "")) = LOWER(?)', [str_replace(' ', '', $trimCategory)])->value('id');
            $size = Size::whereRaw('LOWER(REPLACE(name, " ", "")) = LOWER(?)', [str_replace(' ', '', $trimSize)])->value('id');
            $unit = Unit::whereRaw('LOWER(REPLACE(name, " ", "")) = LOWER(?)', [str_replace(' ', '', $trimUnit)])->value('id');
            /******* end trims spaces in between**********/

            if($size == null) {
                $code = substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', 5)), 0, 10);
                $createSize = Size::create([
                    "name" => isset($trimSize) ? $trimSize : "N/A",
                    "code" => $code,
                    'slug' => $code,
                    'note' => "Excel Import",
                    "status" => "1"
                ]);
                $size = $createSize->id;
            }

            if($unit == null){
                $code = rand(0,9999);
                $slug = Str::slug($trimUnit);
                $unitCreate = Unit::firstOrCreate([
                    'slug' => $slug
                ], [
                    "name" => $trimUnit,
                    "slug" => Str::slug($trimUnit),
                    "code" => $code
                ]);
                $unit = $unitCreate->id;
            }

            if ($category === null) {
                 $slug = Str::slug($trimCategory);

                 $createcategory = StoreinCategory::firstOrCreate([
                     'slug' => $slug
                 ], [
                     'name' =>  trim(strtolower($trimCategory)),
                     'slug' => strtolower(Str::slug($trimCategory)),
                     'note' => isset($row['note'])? $row['note'] : 'N/A',
                     'status' => "active"
                 ]);
                 $category = $createcategory->id;
            }

            if ($department === null) {
                $slug = Str::slug($trimDepartment);
                $createdepartment = StoreinDepartment::firstOrCreate([
                    'slug' => $slug
                ], [
                    'name' => trim(strtolower($trimDepartment)),
                    'slug' => strtolower(Str::slug($trimDepartment)),
                    'category_id' => $category,
                    'status' => "active"
                ]);
                $department = $createdepartment->id;
            }

            $rowitem = ItemsOfStorein::whereRaw('LOWER(REPLACE(name, " ", "")) = LOWER(?)', [str_replace(' ', '', $trimItem)])
            ->where('size_id',$size)
            ->where('unit_id',$unit)
            ->where('department_id',$department)
            ->where('category_id',$category)
            ->where('pnumber',$trimProductNumber)
            ->value('id');
            if($department && $category && $rowitem && $size && $unit){
                $item = $rowitem;
            }else{
                $item = null;
            }
            if ($item === null) {
                $createitem= ItemsOfStorein::create([
                    'name' =>strtolower($trimItem),
                    'department_id'=> $department,
                    "category_id" => $category,
                    'status' => "1",
                    "unit_id" => $unit,
                    "size_id" => $size,
                    'pnumber' => isset($trimProductNumber)? trim(strtolower($trimProductNumber)) : "Any",
                ]);
                $item = $createitem->id;
            }
            if($department && $category && $item && $size && $unit){
                $exists = Stock::where('department_id',$department)
                        ->where('category_id',$category)
                        ->where('item_id',$item)
                        ->where('size',$size)
                        ->where('unit',$unit)
                        ->first();
                if($exists){
                    $total_amount = $exists->total_amount + $trimTotal;
                    $total_quantity =$exists->quantity + $trimQuantity;
                    Stock::where('id',$exists->id)->update([
                        "total_amount" => $total_amount,
                        "quantity"=>$total_quantity,
                        "avg_price" => $total_amount/$total_quantity
                    ]);

                }else{
                    Stock::create([
                    'item_id' => $item,
                    'size' => $size,
                    'quantity' => $trimQuantity,
                    'unit' => $unit,
                    'avg_price' => $trimAverage,
                    'total_amount' => $trimTotal,
                    'department_id' => $department,
                    'category_id' => $category,
                    ]);
                }

             $openingStockReport= OpeningStoreinReport::with(['itemsOfStorein' => function($query) use($category, $department, $item, $size, $unit,$trimProductNumber) {
                    $query->where('category_id', $category)
                          ->where('department_id', $department)
                          ->where('size_id', $size)
                          ->where('unit_id', $unit)
                          ->where('pnumber',$trimProductNumber);
                        }])
                    ->where('name', $item)
                    ->first();

                if($openingStockReport){
                    $total_amount = $openingStockReport->total + $trimTotal;
                    $total_quantity =$openingStockReport->quantity + $trimQuantity;
                    $openingStockReport->quantity =$total_quantity;
                    $openingStockReport->total =$total_amount ;
                    $openingStockReport->rate= $total_amount /$total_quantity;
                    $openingStockReport->save();
                }else{
                    $stockReport= new OpeningStoreinReport();
                    $stockReport->date =Carbon::now()->format('Y-n-j');
                    $stockReport->name =$item;
                    $stockReport->quantity =$trimQuantity;
                    $stockReport->rate = $trimAverage;
                    $stockReport->total =$stockReport->quantity * $stockReport->rate;
                    $stockReport->save();
                }
            }else{
                DB::rollback();
                dd($department, $category , $item , $size , $unit);
            }
        }
            DB::commit();
        }catch(Exception $ex){
            DB::rollback();
        }

    }
}
