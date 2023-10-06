<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Godam;
use Illuminate\Http\Request;

class TapeProductionController extends Controller
{
    public function __invoke(Request $request)
    {
        if($request->ajax()){

            $kolsiteArray = $this->getKolsite($request);
            dd($kolsiteArray);

            return response()->json([
                'status' => true,
                'message' => true,
            ]);
        }
        $godams = Godam::all();
        return view('admin.tapeentry.report',compact('godams'));
    }

    private function getKolsite($request){
        $kolsiteArray = [];

        return $kolsiteArray;
    }

}
