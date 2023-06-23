<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrintsAndCutsController extends Controller
{
    public function index(){
        return view("admin.bag.printsandcuts.index");
    }
}
