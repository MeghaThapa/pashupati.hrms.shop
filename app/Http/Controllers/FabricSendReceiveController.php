<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FabricSendReceiveController extends Controller
{
    public function index()
    {
       return view('admin.fabricSendReceive.index');
    }
}
