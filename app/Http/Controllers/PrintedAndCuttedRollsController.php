<?php

namespace App\Http\Controllers;

use App\Models\PrintedAndCuttedRolls;
use Illuminate\Http\Request;

class PrintedAndCuttedRollsController extends Controller
{
    /*************** For Entry *********/
    public function index(){
        // $data = 
        return view("admin.bag.printsandcuts.index");
    }
    public function createEntry(){
        return view("admin.bag.printsandcuts.createEntry");
    }

    public function storeEntry(Request $request){
       $request->validate([
        "receipt_number" => "required",
        "date" => "required",
        "date_np" => "required"
       ]);

       PrintedAndCuttedRolls::create([
            "receipt_number" => $request->receipt_number,
            "date" => $request->date,
            "date_np" => $request->date_np
       ]);

       return $this->index();
    }
    /*************** For Entry *********/
    public function createPrintedRolls(){
        return "here"; 
    }
}
