<?php

namespace App\Http\Controllers;

use App\Models\AutoloadItems;
use App\Models\AutoLoadItemStock;
use Illuminate\Http\Request;
use App\Models\RawMaterialStock;

class AutoloadItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request;
         $validator = $request->validate([
            'autoload_id' => 'required',
            'dana_group_id' => 'required',
            'dana_name_id' => 'required',
            'from_godam_id' => 'required',
            'plant_name_id' => 'required',
            'plant_type_id' => 'required',
            'quantity' => 'required',
            'shift_id' => 'required',

        ]);
        $autoloadItem= new AutoloadItems();
        $autoloadItem->from_godam_id = $request->from_godam_id;
        $autoloadItem->plant_type_id = $request->plant_type_id;
        $autoloadItem->plant_name_id = $request->plant_name_id;
        $autoloadItem->shift_id=$request->shift_id;
        $autoloadItem->dana_group_id = $request->dana_group_id;
        $autoloadItem->dana_name_id = $request->dana_name_id;
        $autoloadItem->quantity = $request->quantity;
        $autoloadItem->autoload_id =$request->autoload_id;
        $autoloadItem->save();

        //For autoload item stock
        //$AutoLoadItemStock=AutoLoadItemStock::with('autoloadItems');
        $autoLoadItemStocks=AutoLoadItemStock::where('from_godam_id',$autoloadItem->from_godam_id)
        ->where('plant_type_id',$autoloadItem->plant_type_id)
        ->where('plant_name_id',$autoloadItem->plant_name_id)
        ->where('shift_id',$autoloadItem->shift_id)
        ->where('dana_group_id',$autoloadItem->dana_group_id)
        ->where('dana_name_id',$autoloadItem->dana_name_id)
        ->first();
        if($autoLoadItemStocks){
               $autoLoadItemStocks->quantity += $autoloadItem->quantity;
               $autoLoadItemStocks->save();
        }else{
            $autoloadItemStock = new AutoLoadItemStock();
            $autoloadItemStock->from_godam_id= $autoloadItem->from_godam_id;
            $autoloadItemStock->plant_type_id = $autoloadItem->plant_type_id;
            $autoloadItemStock->plant_name_id =$autoloadItem->plant_name_id;
            $autoloadItemStock->shift_id = $autoloadItem->shift_id;
            $autoloadItemStock->dana_group_id = $autoloadItem->dana_group_id;
            $autoloadItemStock->dana_name_id = $autoloadItem->dana_name_id;
            $autoloadItemStock->quantity = $autoloadItem->quantity;
            $autoloadItemStock->save();
        }



        //deduct loaded items from the raw material item socks
        $autoloadItems=AutoLoadItems::with('autoload','plantName','plantType','shift','fromGodam','danaGroup','danaName')->find($autoloadItem->id);

        $rawMaterialStock=RawMaterialStock::where('department_id',$autoloadItems->from_godam_id)
        ->where('dana_group_id',$autoloadItems->dana_group_id)
        ->where('dana_name_id',$autoloadItems->dana_name_id)
        ->first();
        if ($rawMaterialStock) {
                        if ($rawMaterialStock->quantity < $autoloadItems->quantity) {
                            return response()->json([
                                'message' => "you don't have enough stock available.",
                            ], 400);
                        } else {
                           // console.log('dfghjjbg');
                            $rawMaterialStock->quantity -=  $autoloadItems->quantity;
                            $rawMaterialStock->save();
                            if ($rawMaterialStock->quantity <= 0) {
                                $rawMaterialStock->delete();
                            }
                        }
                    } else {
                        return response()->json([
                            'message' => 'Something went wrong.',
                        ], 400);
                        // throw new Exception("Stock not found");
                    }

        // return $rawMaterial;
         return response()->json([
                'message' => 'Autoload item created successfully',
                'autoloadItems' => $autoloadItems,
                'autoLoadItemStocks' => $autoLoadItemStocks,
            ], 200);
    }

    public function getEditAutoloadItemData($autoloadItem_id){
        $autoLoadItem= AutoloadItems::find($autoloadItem_id);
          return response()->json([
                'autoLoadItem' => $autoLoadItem
            ], 200);
    }
    public function getAutoloadItemsData($autoload_id){
           $autoloadItems=AutoloadItems::with('autoload','plantName','plantType','shift','fromGodam','danaGroup','danaName')->where('autoload_id',$autoload_id)->get();
            return response()->json([

                'autoloadItems' => $autoloadItems
            ], 200);
        //    return $autoloadItems;
    }
    public function update(Request $request){

        //return $request;
        $validator = $request->validate([
            'autoload_item_id' => 'required',
            'dana_group_id' => 'required',
            'dana_name_id' => 'required',
            'from_godam_id' => 'required',
            'plant_name_id' => 'required',
            'plant_type_id' => 'required',
            'quantity' => 'required',
            'shift_id' => 'required',
        ]);
       // return $request;
       $autoloadItem =AutoloadItems::find($request->autoload_item_id);

       $autoLoadItemStocks=AutoLoadItemStock::where('from_godam_id',$autoloadItem->from_godam_id)
       ->where('plant_type_id',$autoloadItem->plant_type_id)
       ->where('plant_name_id',$autoloadItem->plant_name_id)
       ->where('shift_id',$autoloadItem->shift_id)
       ->where('dana_group_id',$autoloadItem->dana_group_id)
       ->where('dana_name_id',$autoloadItem->dana_name_id)
       ->first();

       $rawMaterialStock=RawMaterialStock::where('department_id',$autoloadItem->from_godam_id)
        ->where('dana_group_id',$autoloadItem->dana_group_id)
        ->where('dana_name_id',$autoloadItem->dana_name_id)
        ->first();

        //making the autoloadstock as it was before create
       $autoLoadItemStocks->quantity += $autoloadItem->quantity;
       $autoLoadItemStocks->save();

       $rawMaterialStock->quantity += $autoloadItem->quantity;
       $rawMaterialStock->save();

       $autoloadItem->from_godam_id = $request->from_godam_id;
       $autoloadItem->plant_type_id = $request->plant_type_id;
       $autoloadItem->plant_name_id = $request->plant_name_id;
       $autoloadItem->shift_id=$request->shift_id;
       $autoloadItem->dana_group_id = $request->dana_group_id;
       $autoloadItem->dana_name_id = $request->dana_name_id;
       $autoloadItem->quantity = $request->quantity;
       $autoloadItem->save();

       if($request->dana_name_id == $autoLoadItemStocks->dana_name_id
       && $request->dana_group_id ==  $autoLoadItemStocks->dana_group_id
       && $request->from_godam_id == $autoLoadItemStocks->from_godam_id
       && $request->shift_id == $autoLoadItemStocks->shift_id
       && $request->plant_type_id == $autoLoadItemStocks->plant_type_id
       && $request->plant_name_id == $autoLoadItemStocks->plant_name_id
       )
       {
        $autoLoadItemStocks->quantity -= $request->quantity;
        $autoLoadItemStocks->save();

        $rawMaterialStock->quantity -= $autoloadItem->quantity;
        $rawMaterialStock->save();
       }
       else{
        $newAutoItemStock=AutoLoadItemStock::where('from_godam_id',$request->from_godam_id)
        ->where('plant_type_id',$request->plant_type_id)
        ->where('plant_name_id',$request->plant_name_id)
        ->where('shift_id',$request->shift_id)
        ->where('dana_group_id',$request->dana_group_id)
        ->where('dana_name_id',$request->dana_name_id)
        ->first();

        $newAutoItemStock->quantity -=$request->quantity;
        $newAutoItemStock->save();

        $newRawMaterialStock=RawMaterialStock::where('department_id',$autoloadItem->from_godam_id)
        ->where('dana_group_id',$autoloadItem->dana_group_id)
        ->where('dana_name_id',$autoloadItem->dana_name_id)
        ->first();

        $newRawMaterialStock->quantity -= $autoloadItem->quantity;
        $newRawMaterialStock->save();

       }
    }

    public function show(AutoloadItems $autoloadItems)
    {
        //
    }
    public function edit(AutoloadItems $autoloadItems)
    {
        //
    }


    public function destroy(AutoloadItems $autoloadItems)
    {
        //
    }
}
