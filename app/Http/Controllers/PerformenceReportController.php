<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcessingSubcat;
use App\Models\FabricDetail;
use App\Models\FabricSendAndReceiveEntry;
use App\Models\NonwovenBill;
use App\Models\PrintedAndCuttedRollsEntry;
use App\Models\Godam;
use App\Models\BagBundelEntry;
use App\Models\DanaGroup;
use DB;

class PerformenceReportController extends Controller
{
 
    public function __invoke(Request $request)
    {
        if ($request->ajax()) {

           

            $datas = $this->data($request);
            $loomRollDown =$this->data1($request);
            $loomAvgMeter =$this->data2($request);
            $laminationProdReport= $this->data3($request);  
            $nonWovenProduction = $this->data4($request);
          
            $ppBags=$this->data5($request);

            $erimaPlantProd =$this->data6($request);
            $ccplant = $this->data7($request);

            $view = view('admin.performenceReport.ssr.report', compact('datas', 'request'))->render();
            $view1 = view('admin.performenceReport.ssr.loomRollDown', compact('loomRollDown', 'request'))->render();
            $view2 = view('admin.performenceReport.ssr.lommAvgMeter', compact('loomAvgMeter', 'request'))->render();
            $view3 = view('admin.performenceReport.ssr.lam', compact('laminationProdReport', 'request'))->render();
            $view4 = view('admin.performenceReport.ssr.nonwoven', compact('nonWovenProduction', 'request'))->render();
            $view5 = view('admin.performenceReport.ssr.eremaPlant', compact('erimaPlantProd', 'request'))->render();
            $view6 = view('admin.performenceReport.ssr.ccprod', compact('ccplant', 'request'))->render();

            $view7 = view('admin.performenceReport.ssr.ppbag', compact('ppBags', 'request'))->render();


            return response(['status' => true,
             'data'=>$view, 
             'loomRollDown'=> $view1, 
             'loomAvgMeter'=> $view2,
             'laminationProdReport' =>$view3,
             'nonWovenProduction' =>$view4,
             'erimaPlantProd' => $view5,
             'ccplant' =>$view6,
             'ppBags' =>$view7,
            ]);
        }
        return view('admin.performenceReport.report');
    }

    private function data($request)
        {
            $plantName= [1, 2, 3, 4, 9, 14];
            $result = DB::table('tape_entry_items')
            ->join('tape_entry', 'tape_entry_items.tape_entry_id', '=', 'tape_entry.id')
            ->join('processing_subcats', 'tape_entry_items.plantName_id', '=', 'processing_subcats.id')
            ->where('tape_entry.tape_entry_date', '=', $request->given_date)
            ->whereIn('processing_subcats.id',[1,2,3,4,9,14])
            ->groupBy('tape_entry_items.plantName_id', 'tape_entry.tape_entry_date')
            ->select('tape_entry_items.plantName_id', 
                    'tape_entry.tape_entry_date',
                    DB::raw('SUM(tape_entry_items.tape_qty_in_kg) as today_total_tape_qty'), 
                    DB::raw('SUM(tape_entry_items.bypass_wast) as today_total_bypass_wast'))
            ->get()
            ->toArray();
            // dd($result);


            $result1 = DB::table('tape_entry_items')
            ->join('tape_entry', 'tape_entry_items.tape_entry_id', '=', 'tape_entry.id')
            ->join('processing_subcats', 'tape_entry_items.plantName_id', '=', 'processing_subcats.id')
            ->whereBetween('tape_entry.tape_entry_date', [date('Y-m-01', strtotime($request->given_date)), $request->given_date])
            ->whereIn('processing_subcats.id', [1, 2, 3, 4, 9, 14])
            ->groupBy('tape_entry_items.plantName_id', DB::raw('MONTH(tape_entry.tape_entry_date)'))
            ->select(
                'tape_entry_items.plantName_id',
                DB::raw('MONTH(tape_entry.tape_entry_date) as month'),
                DB::raw('SUM(tape_entry_items.tape_qty_in_kg) as monthly_total_tape_qty'),
                DB::raw('SUM(tape_entry_items.bypass_wast) as monthly_total_bypass_wast')
            )
            ->get()
            ->toArray();
            // dd($result1);
           
            $result2 = DB::table('tape_entry_items')
            ->join('tape_entry', 'tape_entry_items.tape_entry_id', '=', 'tape_entry.id')
            ->join('processing_subcats', 'tape_entry_items.plantName_id', '=', 'processing_subcats.id')
            ->whereBetween('tape_entry.tape_entry_date', [date('Y-01-01', strtotime($request->given_date)), $request->given_date])      
            ->whereIn('processing_subcats.id',[1,2,3,4,9,14])
            ->groupBy('tape_entry_items.plantName_id', DB::raw('YEAR(tape_entry.tape_entry_date)'))
            ->select(
                'tape_entry_items.plantName_id',
                DB::raw('YEAR(tape_entry.tape_entry_date) as year'),
                DB::raw('SUM(tape_entry_items.tape_qty_in_kg) as yearly_total_tape_qty'),
                DB::raw('SUM(tape_entry_items.bypass_wast) as yearly_total_bypass_wast')
            )
            ->get()
            ->toArray();
            
            $mergeData=[];       
            foreach( $plantName as $plant_id){
                $processingSubcat =ProcessingSubcat::find($plant_id);
                $mergeData[$processingSubcat->name]['plant_name'] =  $processingSubcat->name;
                
                foreach($result as $todayData){
                    if( $todayData->plantName_id == $plant_id){
                        $mergeData[$processingSubcat->name]['today_tape_quantity'] =  $todayData->today_total_tape_qty;
                        $mergeData[$processingSubcat->name]['today_waste'] =  $todayData->today_total_bypass_wast;
                    }                 
                }

                foreach($result1 as $monthly){
                    if( $monthly->plantName_id == $plant_id){
                    $mergeData[$processingSubcat->name]['monthly_tape_quantity'] =  $monthly->monthly_total_tape_qty;
                    $mergeData[$processingSubcat->name]['monthly_total_wastages'] =  $monthly->monthly_total_bypass_wast;
                 }   
                }

                foreach($result2 as $yearly){
                    if( $yearly->plantName_id == $plant_id){
                    $mergeData[$processingSubcat->name]['yearly_tape_quantity'] =  $yearly->yearly_total_tape_qty;
                    $mergeData[$processingSubcat->name]['yearly_total_waste'] =  $yearly->yearly_total_bypass_wast;
                 }   
                }

            }
            // dd( $mergeData);
            $resultData=[];
            foreach($mergeData as $data){
                $resultData[$data['plant_name']]['plant_name'] = $data['plant_name'];
                $resultData[$data['plant_name']]['today_tape_quantity'] = isset($data['today_tape_quantity']) ? $data['today_tape_quantity']:0;
                $resultData[$data['plant_name']]['today_waste'] = isset($data['today_waste']) ? $data['today_waste']:0;
                $resultData[$data['plant_name']]['monthly_tape_quantity'] = isset($data['monthly_tape_quantity']) ? $data['monthly_tape_quantity']:0;
                $resultData[$data['plant_name']]['monthly_total_waste'] = isset($data['monthly_total_waste']) ? $data['monthly_total_waste']:0;
                $resultData[$data['plant_name']]['yearly_tape_quantity'] = isset($data['yearly_tape_quantity']) ? $data['yearly_tape_quantity']:0;
                $resultData[$data['plant_name']]['yearly_total_waste'] = isset($data['yearly_total_waste']) ? $data['yearly_total_waste']:0;

            }

            $tapePlantData=[];
            foreach($resultData as $data){
                $tapePlantData[$data['plant_name']]['plant_name'] = $data['plant_name'];
                $tapePlantData[$data['plant_name']]['today_tape_quantity'] = isset($data['today_tape_quantity']) ? $data['today_tape_quantity']:0;
                $tapePlantData[$data['plant_name']]['today_waste'] = isset($data['today_waste']) ? $data['today_waste']:0;

                $today_waste = isset($data['today_waste']) ? $data['today_waste'] : 0;
                $today_tape_quantity = isset($data['today_tape_quantity']) ? $data['today_tape_quantity'] : 1; // Use 1 as a default value to avoid division by zero
                $tapePlantData[$data['plant_name']]['today_wastage_perc'] = $today_tape_quantity != 0
                    ? ($today_waste / $today_tape_quantity * 100)
                    : 0;
                
                $tapePlantData[$data['plant_name']]['monthly_tape_quantity'] = isset($data['monthly_tape_quantity']) ? $data['monthly_tape_quantity']:0;
                $tapePlantData[$data['plant_name']]['monthly_total_waste'] = isset($data['monthly_total_waste']) ? $data['monthly_total_waste']:0;
                $monthly_total_waste = isset($data['monthly_total_waste']) ? $data['monthly_total_waste'] : 0;
                $monthly_tape_quantity = isset($data['monthly_tape_quantity']) ? $data['monthly_tape_quantity'] : 1; // Use 1 as a default value to avoid division by zero
                
                $tapePlantData[$data['plant_name']]['monthly_wastage_perc'] = $monthly_tape_quantity != 0
                    ? ($monthly_total_waste / $monthly_tape_quantity * 100)
                    : 0;
                
                $tapePlantData[$data['plant_name']]['yearly_tape_quantity'] = isset($data['yearly_tape_quantity']) ? $data['yearly_tape_quantity']:0;
                $tapePlantData[$data['plant_name']]['yearly_total_waste'] = isset($data['yearly_total_waste']) ? $data['yearly_total_waste']:0;
                $yearly_total_waste = isset($data['yearly_total_waste']) ? $data['yearly_total_waste'] : 0;
                $yearly_tape_quantity = isset($data['yearly_tape_quantity']) ? $data['yearly_tape_quantity'] : 1; // Use 1 as a default value to avoid division by zero
                
                $tapePlantData[$data['plant_name']]['yearly_wastage_perc'] = $yearly_tape_quantity != 0
                    ? ($yearly_total_waste / $yearly_tape_quantity * 100)
                    : 0;
                
            }
            return $tapePlantData;

        }
    
    private function data1($request){

            $todayResult = FabricDetail::whereDate('bill_date_en', '=', $request->given_date)
            ->join('godam', 'fabric_details.godam_id', '=', 'godam.id')
            ->select('godam_id','fabric_details.bill_date_en', 'godam.name',
             DB::raw('SUM(total_netweight) as today_netweight_sum'),
              DB::raw('SUM(total_wastage) as today_wastage_sum'))
            ->groupBy('godam_id', 'godam.name','fabric_details.bill_date_en')
            ->get()
            ->toArray();
            // dd($todayResult);

            $monthlyResult = FabricDetail::whereBetween('bill_date_en', [date('Y-m-01', strtotime($request->given_date)), $request->given_date])
            ->join('godam', 'fabric_details.godam_id', '=', 'godam.id')
            ->groupBy('godam_id', 'godam.name', DB::raw('MONTH(fabric_details.bill_date_en)'))
            ->select(
                'godam_id',
                'godam.name',
                DB::raw('MONTH(fabric_details.bill_date_en) as month'),
                DB::raw('SUM(total_netweight) as monthly_netweight_sum'),
                DB::raw('SUM(total_wastage) as monthly_wastage_sum')
            )
            ->get()
            ->toArray();
            // dd($monthlyResult);

            $yearlyResult = FabricDetail::whereBetween('bill_date_en', [date('Y-01-01', strtotime($request->given_date)), $request->given_date])
            ->join('godam', 'fabric_details.godam_id', '=', 'godam.id')
            ->groupBy('godam_id', 'godam.name', DB::raw('YEAR(fabric_details.bill_date_en)'))
            ->select(
                'godam_id',
                'godam.name',
                DB::raw('YEAR(fabric_details.bill_date_en) as year'),
                DB::raw('SUM(total_netweight) as yearly_netweight_sum'),
                DB::raw('SUM(total_wastage) as yearly_wastage_sum')
            )
            ->get()
            ->toArray();
            // dd($yearlyResult);

            $godams=[1,2,3];
            $mergeData=[];       
            foreach ($godams as $godamId) {
                $godam = Godam::find($godamId);
                $mergeData[$godam->name]['name'] = $godam->name;
            
                foreach ($todayResult as $todayData) {
                    if ($todayData['godam_id'] ==$godamId) {
                        $mergeData[$godam->name]['today_netweight_sum'] = $todayData['today_netweight_sum'];
                        $mergeData[$godam->name]['today_waste'] = $todayData['today_wastage_sum'];
                    }
                }
            
                foreach ($monthlyResult as $monthly) {
                    if ($monthly['godam_id'] ==$godamId) {
                        $mergeData[$godam->name]['monthly_netweight_sum'] = $monthly['monthly_netweight_sum'];
                        $mergeData[$godam->name]['monthly_total_waste'] = $monthly['monthly_wastage_sum'];
                    }
                }
            
                foreach ($yearlyResult as $yearly) {
                    if ($yearly['godam_id'] ==$godamId) {
                        $mergeData[$godam->name]['yearly_netweight_sum'] = $yearly['yearly_netweight_sum'];
                        $mergeData[$godam->name]['yearly_wastage_sum'] = $yearly['yearly_wastage_sum'];
                    }
                }
            }
            
            // dd($mergeData);
            $resultData=[];
            foreach($mergeData as $data){
                $resultData[$data['name']]['name'] = $data['name'];
                $resultData[$data['name']]['today_netweight_sum'] = isset($data['today_netweight_sum']) ? $data['today_netweight_sum']:0;
                $resultData[$data['name']]['today_waste'] = isset($data['today_waste']) ? $data['today_waste']:0;
                $resultData[$data['name']]['monthly_netweight_sum'] = isset($data['monthly_netweight_sum']) ? $data['monthly_netweight_sum']:0;
                $resultData[$data['name']]['monthly_total_waste'] = isset($data['monthly_total_waste']) ? $data['monthly_total_waste']:0;
                $resultData[$data['name']]['yearly_netweight_sum'] = isset($data['yearly_netweight_sum']) ? $data['yearly_netweight_sum']:0;
                $resultData[$data['name']]['yearly_wastage_sum'] = isset($data['yearly_wastage_sum']) ? $data['yearly_wastage_sum']:0;

            }
            // dd($resultData);
            $loomRollDownData = [];
            foreach($resultData as $data){
                $loomRollDownData[$data['name']]['name'] = $data['name'];
                $loomRollDownData[$data['name']]['today_netweight_sum'] = isset($data['today_netweight_sum']) ? $data['today_netweight_sum']:0;
                $loomRollDownData[$data['name']]['today_waste'] = isset($data['today_waste']) ? $data['today_waste']:0;

                $today_waste = isset($data['today_waste']) ? $data['today_waste'] : 0;
                $today_netWeight_sum = isset($data['today_netweight_sum']) ? $data['today_netweight_sum'] : 1; // Use 1 as a default value to avoid division by zero
                $loomRollDownData[$data['name']]['today_wastage_perc'] = $today_netWeight_sum != 0
                    ? number_format($today_waste /  $today_netWeight_sum * 100,2)
                    : 0;

                $loomRollDownData[$data['name']]['monthly_netweight_sum'] = isset($data['monthly_netweight_sum']) ? $data['monthly_netweight_sum']:0;
                $loomRollDownData[$data['name']]['monthly_total_waste'] = isset($data['monthly_total_waste']) ? $data['monthly_total_waste']:0;

                $monthly_waste = isset($data['monthly_total_waste']) ? $data['monthly_total_waste'] : 0;
                $monthly_netWeight_sum = isset($data['monthly_netweight_sum']) ? $data['monthly_netweight_sum'] : 1;
                $loomRollDownData[$data['name']]['monthly_waste_perc'] =  $monthly_netWeight_sum !=0?
                number_format($monthly_waste /  $monthly_netWeight_sum * 100,2)
                    : 0;

                $loomRollDownData[$data['name']]['yearly_netweight_sum'] = isset($data['yearly_netweight_sum']) ? $data['yearly_netweight_sum']:0;
                $loomRollDownData[$data['name']]['yearly_total_waste'] = isset($data['yearly_wastage_sum']) ? $data['yearly_wastage_sum']:0;
               
                $yearly_waste = isset($data['yearly_wastage_sum']) ? $data['yearly_wastage_sum'] : 0;
                $yearly_netWeight_sum = isset($data['yearly_netweight_sum']) ? $data['yearly_netweight_sum'] : 1;
                $loomRollDownData[$data['name']]['yearly_waste_perc'] =  $yearly_netWeight_sum !=0?
                number_format($yearly_waste /  $yearly_netWeight_sum * 100,2)
                    : 0;

    
            } 
            // dd($loomRollDownData);
        return ($loomRollDownData);            

    } 
    private function data2($request){

        $todayResult = FabricDetail::whereDate('bill_date_en', '=', $request->given_date)
        ->join('godam', 'fabric_details.godam_id', '=', 'godam.id')
        ->select('godam_id','fabric_details.bill_date_en', 'godam.name',
         DB::raw('SUM(run_loom) as today_run_loom_sum'),
          DB::raw('SUM(total_meter) as today_total_meter'))
        ->groupBy('godam_id', 'godam.name','fabric_details.bill_date_en')
        ->get()
        ->toArray();
        // dd($todayResult);

        $monthlyResult = FabricDetail::whereBetween('bill_date_en', [date('Y-m-01', strtotime($request->given_date)), $request->given_date])
        ->join('godam', 'fabric_details.godam_id', '=', 'godam.id')
        ->groupBy('godam_id', 'godam.name', DB::raw('MONTH(fabric_details.bill_date_en)'))
        ->select(
            'godam_id',
            'godam.name',
            DB::raw('MONTH(fabric_details.bill_date_en) as month'),
            DB::raw('SUM(run_loom) as monthly_run_loom_sum'),
            DB::raw('SUM(total_meter) as monthly_total_meter_sum')
        )
        ->get()
        ->toArray();
        // dd($monthlyResult);

        $yearlyResult = FabricDetail::whereBetween('bill_date_en', [date('Y-01-01', strtotime($request->given_date)), $request->given_date])
        ->join('godam', 'fabric_details.godam_id', '=', 'godam.id')
        ->groupBy('godam_id', 'godam.name', DB::raw('YEAR(fabric_details.bill_date_en)'))
        ->select(
            'godam_id',
            'godam.name',
            DB::raw('YEAR(fabric_details.bill_date_en) as year'),
            DB::raw('SUM(run_loom) as yearly_run_loom_sum'),
            DB::raw('SUM(total_meter) as yearly_total_meter_sum')
        )
        ->get()
        ->toArray();
        // dd($yearlyResult);

        $godams=[1,2,3];
        $mergeData=[];       
        foreach ($godams as $godamId) {
            $godam = Godam::find($godamId);
            $mergeData[$godam->name]['name'] = $godam->name;
        
            foreach ($todayResult as $todayData) {
                if ($todayData['godam_id'] ==$godamId) {
                    $mergeData[$godam->name]['today_run_loom_sum'] = $todayData['today_run_loom_sum'];
                    $mergeData[$godam->name]['today_total_meter'] = $todayData['today_total_meter'];
                }
            }
        
            foreach ($monthlyResult as $monthly) {
                if ($monthly['godam_id'] ==$godamId) {
                    $mergeData[$godam->name]['monthly_run_loom_sum'] = $monthly['monthly_run_loom_sum'];
                    $mergeData[$godam->name]['monthly_total_meter_sum'] = $monthly['monthly_total_meter_sum'];
                }
            }
        
            foreach ($yearlyResult as $yearly) {
                if ($yearly['godam_id'] ==$godamId) {
                    $mergeData[$godam->name]['yearly_run_loom_sum'] = $yearly['yearly_run_loom_sum'];
                    $mergeData[$godam->name]['yearly_total_meter_sum'] = $yearly['yearly_total_meter_sum'];
                }
            }
        }
        
        // dd($mergeData);
        $resultData=[];
        foreach($mergeData as $data){
            $resultData[$data['name']]['name'] = $data['name'];
            $resultData[$data['name']]['today_run_loom_sum'] = isset($data['today_run_loom_sum']) ? $data['today_run_loom_sum']:0;
            $resultData[$data['name']]['today_total_meter'] = isset($data['today_total_meter']) ? $data['today_total_meter']:0;
            $resultData[$data['name']]['monthly_run_loom_sum'] = isset($data['monthly_run_loom_sum']) ? $data['monthly_run_loom_sum']:0;
            $resultData[$data['name']]['monthly_total_meter_sum'] = isset($data['monthly_total_meter_sum']) ? $data['monthly_total_meter_sum']:0;
            $resultData[$data['name']]['yearly_run_loom_sum'] = isset($data['yearly_run_loom_sum']) ? $data['yearly_run_loom_sum']:0;
            $resultData[$data['name']]['yearly_total_meter_sum'] = isset($data['yearly_total_meter_sum']) ? $data['yearly_total_meter_sum']:0;

        }
        // dd($resultData);
        $loomAvgMeter = [];
        foreach($resultData as $data){
            $loomAvgMeter[$data['name']]['name'] = $data['name'];
            $loomAvgMeter[$data['name']]['today_run_loom_sum'] = isset($data['today_run_loom_sum']) ? $data['today_run_loom_sum']:0;
            $loomAvgMeter[$data['name']]['today_total_meter'] = isset($data['today_total_meter']) ? $data['today_total_meter']:0;

            $today_total_meter = isset($data['today_total_meter']) ? $data['today_total_meter'] : 0;
            $today_run_loom_sum = isset($data['today_run_loom_sum']) ? $data['today_run_loom_sum'] : 1; // Use 1 as a default value to avoid division by zero
            $loomAvgMeter[$data['name']]['today_loomAvg_meter'] = $today_run_loom_sum != 0
                ? ($today_total_meter /  $today_run_loom_sum)
                : 0;

            $loomAvgMeter[$data['name']]['monthly_run_loom_sum'] = isset($data['monthly_run_loom_sum']) ? $data['monthly_run_loom_sum']:0;
            $loomAvgMeter[$data['name']]['monthly_total_meter_sum'] = isset($data['monthly_total_meter_sum']) ? $data['monthly_total_meter_sum']:0;

            $monthly_total_meter_sum = isset($data['monthly_total_meter_sum']) ? $data['monthly_total_meter_sum'] : 0;
            $monthly_run_loom_sum = isset($data['monthly_run_loom_sum']) ? $data['monthly_run_loom_sum'] : 1;
            $loomAvgMeter[$data['name']]['monthly_loomAvg_meter'] =  $monthly_run_loom_sum !=0?
            ($monthly_total_meter_sum /  $monthly_run_loom_sum)
                : 0;

            $loomAvgMeter[$data['name']]['yearly_run_loom_sum'] = isset($data['yearly_run_loom_sum']) ? $data['yearly_run_loom_sum']:0;
            $loomAvgMeter[$data['name']]['yearly_total_meter_sum'] = isset($data['yearly_total_meter_sum']) ? $data['yearly_total_meter_sum']:0;
           
            $yearly_total_meter_sum = isset($data['yearly_total_meter_sum']) ? $data['yearly_total_meter_sum'] : 0;
            $yearly_run_loom_sum = isset($data['yearly_run_loom_sum']) ? $data['yearly_run_loom_sum'] : 1;
            $loomAvgMeter[$data['name']]['yearly_loomAvg_meter'] = $yearly_run_loom_sum != 0
            ? (($yearly_total_meter_sum / $yearly_run_loom_sum))
            : 0;
           
        


        } 
        // dd($loomAvgMeter);
    return ($loomAvgMeter);            
    } 
    private function data3($request)
        {
                $plantName= [6, 5, 10, 14];   
                //today     
                $result = FabricSendAndReceiveEntry::join('fabric_send_and_receive_dana_consumption', 'fabric_send_and_receive_entry.id', '=', 'fabric_send_and_receive_dana_consumption.fsr_entry_id')
                ->join('processing_subcats', 'fabric_send_and_receive_dana_consumption.plant_name_id', '=', 'processing_subcats.id')
                ->where('fabric_send_and_receive_entry.bill_date', '=', $request->given_date)
                ->selectRaw('processing_subcats.id as plantName_id, 
                            SUM(fabric_send_and_receive_entry.polo_waste) as today_total_polo_waste, 
                            SUM(fabric_send_and_receive_dana_consumption.consumption_quantity) as today_total_consumption_quantity')
                ->whereIn('processing_subcats.id', [6, 5, 10, 14])
                ->groupBy('processing_subcats.id', 'fabric_send_and_receive_entry.bill_date')
                ->get()
                ->toArray();
                // dd($result);
            
                $result1 = FabricSendAndReceiveEntry::join('fabric_send_and_receive_dana_consumption', 'fabric_send_and_receive_entry.id', '=', 'fabric_send_and_receive_dana_consumption.fsr_entry_id')
                ->join('processing_subcats', 'fabric_send_and_receive_dana_consumption.plant_name_id', '=', 'processing_subcats.id')
                ->whereBetween('fabric_send_and_receive_entry.bill_date', [date('Y-m-01', strtotime($request->given_date)), $request->given_date])
                ->whereIn('processing_subcats.id', [6, 5, 10, 14])
                ->selectRaw('processing_subcats.id as plantName_id,  
                            MONTH(fabric_send_and_receive_entry.bill_date) as month, 
                            SUM(fabric_send_and_receive_entry.polo_waste) as monthly_total_polo_waste, 
                            SUM(fabric_send_and_receive_dana_consumption.consumption_quantity) as montly_total_consumption_quantity')
                ->groupBy('processing_subcats.id', 'month')
                ->get()
                ->toArray();
                // dd($result1);
                
                //yearly
                $result2 = FabricSendAndReceiveEntry::join('fabric_send_and_receive_dana_consumption', 'fabric_send_and_receive_entry.id', '=', 'fabric_send_and_receive_dana_consumption.fsr_entry_id')
                ->join('processing_subcats', 'fabric_send_and_receive_dana_consumption.plant_name_id', '=', 'processing_subcats.id')
                ->whereBetween('fabric_send_and_receive_entry.bill_date', [date('Y-01-01', strtotime($request->given_date)), $request->given_date])
                ->whereIn('processing_subcats.id', [6, 5, 10, 14])
                ->selectRaw('processing_subcats.id as plantName_id,  
                                YEAR(fabric_send_and_receive_entry.bill_date) as year, 
                                SUM(fabric_send_and_receive_entry.polo_waste) as yearly_total_polo_waste, 
                                SUM(fabric_send_and_receive_dana_consumption.consumption_quantity) as yearly_total_consumption_quantity')
                ->groupBy('processing_subcats.id', 'year')
                ->get()
                ->toArray();
                // dd($result2);
       
             $mergeData=[];       
                foreach( $plantName as $plant_id){
                    $processingSubcat =ProcessingSubcat::find($plant_id);
                    $mergeData[$processingSubcat->name]['plant_name'] =  $processingSubcat->name;
                    
                    foreach($result as $todayData){
                        if( $todayData['plantName_id'] == $plant_id){
                            $mergeData[$processingSubcat->name]['today_total_polo_waste'] =  $todayData['today_total_polo_waste'];
                            $mergeData[$processingSubcat->name]['today_total_consumption_quantity'] =  $todayData['today_total_consumption_quantity'];
                        }                 
                    }

                    foreach($result1 as $monthly){
                        if( $monthly['plantName_id'] == $plant_id){
                        $mergeData[$processingSubcat->name]['monthly_total_polo_waste'] =  $monthly['monthly_total_polo_waste'];
                        $mergeData[$processingSubcat->name]['montly_total_consumption_quantity'] =  $monthly['montly_total_consumption_quantity'];
                    }   
                    }

                    foreach($result2 as $yearly){
                        if( $yearly['plantName_id'] == $plant_id){
                        $mergeData[$processingSubcat->name]['yearly_total_polo_waste'] =  $yearly['yearly_total_polo_waste'];
                        $mergeData[$processingSubcat->name]['yearly_total_consumption_quantity'] =  $yearly['yearly_total_consumption_quantity'];
                    }   
                    }

                }
            
            $resultData=[];
            foreach($mergeData as $data){
                $resultData[$data['plant_name']]['plant_name'] = $data['plant_name'];
                $resultData[$data['plant_name']]['today_total_polo_waste'] = isset($data['today_total_polo_waste']) ? $data['today_total_polo_waste']:0;
                $resultData[$data['plant_name']]['today_total_consumption_quantity'] = isset($data['today_total_consumption_quantity']) ? $data['today_total_consumption_quantity']:0;
                $resultData[$data['plant_name']]['monthly_total_polo_waste'] = isset($data['monthly_total_polo_waste']) ? $data['monthly_total_polo_waste']:0;
                $resultData[$data['plant_name']]['montly_total_consumption_quantity'] = isset($data['montly_total_consumption_quantity']) ? $data['montly_total_consumption_quantity']:0;
                $resultData[$data['plant_name']]['yearly_total_polo_waste'] = isset($data['yearly_total_polo_waste']) ? $data['yearly_total_polo_waste']:0;
                $resultData[$data['plant_name']]['yearly_total_consumption_quantity'] = isset($data['yearly_total_consumption_quantity']) ? $data['yearly_total_consumption_quantity']:0;

            }
            // dd( $resultData);
            $tapePlantData=[];
            foreach($resultData as $data){
                $tapePlantData[$data['plant_name']]['plant_name'] = $data['plant_name'];
                $tapePlantData[$data['plant_name']]['today_total_consumption_quantity'] = isset($data['today_total_consumption_quantity']) ? $data['today_total_consumption_quantity']:0;
                $tapePlantData[$data['plant_name']]['today_total_polo_waste'] = isset($data['today_total_polo_waste']) ? $data['today_total_polo_waste']:0;

                $today_total_polo_waste = isset($data['today_total_polo_waste']) ? $data['today_total_polo_waste'] : 0;
                $today_total_consumption_quantity = isset($data['today_total_consumption_quantity']) ? $data['today_total_consumption_quantity'] : 1; // Use 1 as a default value to avoid division by zero
                $tapePlantData[$data['plant_name']]['today_wastage_perc'] = $today_total_consumption_quantity != 0
                    ? ($today_total_polo_waste / $today_total_consumption_quantity * 100)
                    : 0;
                
                $tapePlantData[$data['plant_name']]['montly_total_consumption_quantity'] = isset($data['montly_total_consumption_quantity']) ? $data['montly_total_consumption_quantity']:0;
                $tapePlantData[$data['plant_name']]['monthly_total_polo_waste'] = isset($data['monthly_total_polo_waste']) ? $data['monthly_total_polo_waste']:0;
                $monthly_total_polo_waste = isset($data['monthly_total_polo_waste']) ? $data['monthly_total_polo_waste'] : 0;
                $montly_total_consumption_quantity = isset($data['montly_total_consumption_quantity']) ? $data['montly_total_consumption_quantity'] : 1; // Use 1 as a default value to avoid division by zero
                
                $tapePlantData[$data['plant_name']]['monthly_wastage_perc'] = $montly_total_consumption_quantity != 0
                    ? ($monthly_total_polo_waste / $montly_total_consumption_quantity * 100)
                    : 0;
                
                $tapePlantData[$data['plant_name']]['yearly_total_consumption_quantity'] = isset($data['yearly_total_consumption_quantity']) ? $data['yearly_total_consumption_quantity']:0;
                $tapePlantData[$data['plant_name']]['yearly_total_polo_waste'] = isset($data['yearly_total_polo_waste']) ? $data['yearly_total_polo_waste']:0;
                $yearly_total_polo_waste = isset($data['yearly_total_polo_waste']) ? $data['yearly_total_polo_waste'] : 0;
                $yearly_total_consumption_quantity = isset($data['yearly_total_consumption_quantity']) ? $data['yearly_total_consumption_quantity'] : 1; // Use 1 as a default value to avoid division by zero
                
                $tapePlantData[$data['plant_name']]['yearly_wastage_perc'] = $yearly_total_consumption_quantity != 0
                    ? ($yearly_total_polo_waste / $yearly_total_consumption_quantity * 100)
                    : 0;
                
            }
            // dd($tapePlantData);
            return $tapePlantData;

        }
  
        private function data4($request) {
            $plantName= [7];   
            //today   
            
            $result = DB::table('nonwoven_bills')
            ->join('fabric_non_woven_recive_entries', 'nonwoven_bills.id', '=', 'fabric_non_woven_recive_entries.bill_id')
            ->join('processing_subcats', 'nonwoven_bills.plantname_id', '=', 'processing_subcats.id')
            ->where('nonwoven_bills.bill_date_en', '=', $request->given_date)
            ->whereIn('processing_subcats.id', [7])
            ->selectRaw('processing_subcats.id as plantName_id,
                        processing_subcats.name as plant_name,
                        nonwoven_bills.bill_date_en as date,
                        SUM(nonwoven_bills.total_waste) as today_total_waste,
                        SUM(fabric_non_woven_recive_entries.net_weight) as today_total_net_weight')
            ->groupBy('processing_subcats.id', 'processing_subcats.name', 'nonwoven_bills.bill_date_en')
            ->get()
            ->toArray();
        
            // dd($result);
        
            $result1 = DB::table('nonwoven_bills')
            ->join('fabric_non_woven_recive_entries', 'nonwoven_bills.id', '=', 'fabric_non_woven_recive_entries.bill_id')
            ->join('processing_subcats', 'nonwoven_bills.plantname_id', '=', 'processing_subcats.id')
            ->whereBetween('nonwoven_bills.bill_date_en', [
                date('Y-m-01', strtotime($request->given_date)),
                $request->given_date
            ])
            ->whereIn('processing_subcats.id', [7])
            ->selectRaw('processing_subcats.id as plantName_id,
                        processing_subcats.name as plant_name,
                        MONTH(nonwoven_bills.bill_date_en) as month,
                        SUM(nonwoven_bills.total_waste) as monthly_total_waste,
                        SUM(fabric_non_woven_recive_entries.net_weight) as monthly_total_net_weight')
            ->groupBy('processing_subcats.id', 'processing_subcats.name', 'month')
            ->get()
            ->toArray();
        
            // dd($result1);
            
            //yearly
            $result2 = DB::table('nonwoven_bills')
            ->join('fabric_non_woven_recive_entries', 'nonwoven_bills.id', '=', 'fabric_non_woven_recive_entries.bill_id')
            ->join('processing_subcats', 'nonwoven_bills.plantname_id', '=', 'processing_subcats.id')
            ->whereBetween('nonwoven_bills.bill_date_en', [
                date('Y-01-01', strtotime($request->given_date)),
                $request->given_date
            ])
            ->whereIn('processing_subcats.id', [7])
            ->selectRaw('processing_subcats.id as plantName_id,
                        processing_subcats.name as plant_name,
                        YEAR(nonwoven_bills.bill_date_en) as year,
                        SUM(nonwoven_bills.total_waste) as yearly_total_waste,
                        SUM(fabric_non_woven_recive_entries.net_weight) as yearly_total_net_weight')
            ->groupBy('processing_subcats.id', 'processing_subcats.name', 'year')
            ->get()
            ->toArray();
        
            // dd($result2);
   
         $mergeData=[];       
            foreach( $plantName as $plant_id){
                $processingSubcat =ProcessingSubcat::find($plant_id);
                $mergeData[$processingSubcat->name]['plant_name'] =  $processingSubcat->name;
                
                foreach($result as $todayData){
                    if( $todayData->plantName_id == $plant_id){
                        $mergeData[$processingSubcat->name]['today_total_waste'] =  $todayData->today_total_waste;
                        $mergeData[$processingSubcat->name]['today_total_net_weight'] =  $todayData->today_total_net_weight;
                    }                 
                }

                foreach($result1 as $monthly){
                    if( $monthly->plantName_id == $plant_id){
                    $mergeData[$processingSubcat->name]['monthly_total_waste'] =  $monthly->monthly_total_waste;
                    $mergeData[$processingSubcat->name]['monthly_total_net_weight'] =  $monthly->monthly_total_net_weight;
                }   
                }

                foreach($result2 as $yearly){
                    if( $yearly->plantName_id == $plant_id){
                    $mergeData[$processingSubcat->name]['yearly_total_waste'] =  $yearly->yearly_total_waste;
                    $mergeData[$processingSubcat->name]['yearly_total_net_weight'] =  $yearly->yearly_total_net_weight;
                }   
                }

            }
        // dd($mergeData);
        
        $resultData=[];
        foreach($mergeData as $data){
            $resultData[$data['plant_name']]['plant_name'] = $data['plant_name'];
            $resultData[$data['plant_name']]['today_total_waste'] = isset($data['today_total_waste']) ? $data['today_total_waste']:0;
            $resultData[$data['plant_name']]['today_total_net_weight'] = isset($data['today_total_net_weight']) ? $data['today_total_net_weight']:0;
            $resultData[$data['plant_name']]['monthly_total_waste'] = isset($data['monthly_total_waste']) ? $data['monthly_total_waste']:0;
            $resultData[$data['plant_name']]['monthly_total_net_weight'] = isset($data['monthly_total_net_weight']) ? $data['monthly_total_net_weight']:0;
            $resultData[$data['plant_name']]['yearly_total_waste'] = isset($data['yearly_total_waste']) ? $data['yearly_total_waste']:0;
            $resultData[$data['plant_name']]['yearly_total_net_weight'] = isset($data['yearly_total_net_weight']) ? $data['yearly_total_net_weight']:0;

        }
        // dd( $resultData);
        $tapePlantData=[];
        foreach($resultData as $data){
            $tapePlantData[$data['plant_name']]['plant_name'] = $data['plant_name'];
            $tapePlantData[$data['plant_name']]['today_total_net_weight'] = isset($data['today_total_net_weight']) ? $data['today_total_net_weight']:0;
            $tapePlantData[$data['plant_name']]['today_total_waste'] = isset($data['today_total_waste']) ? $data['today_total_waste']:0;

            $today_total_waste = isset($data['today_total_waste']) ? $data['today_total_waste'] : 0;
            $today_total_net_weight = isset($data['today_total_net_weight']) ? $data['today_total_net_weight'] : 1; // Use 1 as a default value to avoid division by zero
            $tapePlantData[$data['plant_name']]['today_wastage_perc'] = $today_total_net_weight != 0
                ? ($today_total_waste / $today_total_net_weight * 100)
                : 0;
            
            $tapePlantData[$data['plant_name']]['monthly_total_net_weight'] = isset($data['monthly_total_net_weight']) ? $data['monthly_total_net_weight']:0;
            $tapePlantData[$data['plant_name']]['monthly_total_waste'] = isset($data['monthly_total_waste']) ? $data['monthly_total_waste']:0;
            $monthly_total_waste = isset($data['monthly_total_waste']) ? $data['monthly_total_waste'] : 0;
            $monthly_total_net_weight = isset($data['monthly_total_net_weight']) ? $data['monthly_total_net_weight'] : 1; // Use 1 as a default value to avoid division by zero
            
            $tapePlantData[$data['plant_name']]['monthly_wastage_perc'] = $monthly_total_net_weight != 0
                ? ($monthly_total_waste / $monthly_total_net_weight * 100)
                : 0;
            
            $tapePlantData[$data['plant_name']]['yearly_total_net_weight'] = isset($data['yearly_total_net_weight']) ? $data['yearly_total_net_weight']:0;
            $tapePlantData[$data['plant_name']]['yearly_total_waste'] = isset($data['yearly_total_waste']) ? $data['yearly_total_waste']:0;
            $yearly_total_waste = isset($data['yearly_total_waste']) ? $data['yearly_total_waste'] : 0;
            $yearly_total_net_weight = isset($data['yearly_total_net_weight']) ? $data['yearly_total_net_weight'] : 1; // Use 1 as a default value to avoid division by zero
            
            $tapePlantData[$data['plant_name']]['yearly_wastage_perc'] = $yearly_total_net_weight != 0
                ? ($yearly_total_waste / $yearly_total_net_weight * 100)
                : 0;
            
        }
        // dd($tapePlantData);
        return $tapePlantData;

    }

    public function data5(Request $request){
        $todayWaste = PrintedAndCuttedRollsEntry::join('printing_and_cutting_bag_items', 'printed_and_cutted_rolls_entry.id', '=', 'printing_and_cutting_bag_items.printAndCutEntry_id')
        ->where('printed_and_cutted_rolls_entry.date', '=', $request->given_date)
        ->selectRaw('SUM(printing_and_cutting_bag_items.wastage) as today_total_wastage, printed_and_cutted_rolls_entry.date as date')
        ->groupBy('printed_and_cutted_rolls_entry.date')
        ->get()
        ->toArray();
       
        $todayKgspcs = BagBundelEntry::join('bag_bundel_items', 'bag_bundel_entries.id', '=', 'bag_bundel_items.bag_bundel_entry_id')
        ->where('bag_bundel_entries.receipt_date', '=', $request->given_date)
        ->selectRaw('bag_bundel_entries.receipt_date,
         SUM(bag_bundel_items.qty_in_kg) as today_total_qty_in_kg,
          SUM(bag_bundel_items.qty_pcs) as today_total_qty_pcs')
        ->groupBy('bag_bundel_entries.receipt_date')
        ->get()
        ->toArray();
        $today_waste_perc = [];

        // Iterate through each entry in $todayWaste
        foreach ($todayWaste as $wasteEntry) {
            $date = $wasteEntry['date'];
        
            // Find the corresponding entry in $todayKgspcs based on the date
            $correspondingKgspcsEntry = current(array_filter($todayKgspcs, function ($kgspcsEntry) use ($date) {
                return $kgspcsEntry['receipt_date'] === $date;
            }));
        
            // Initialize an array for the current iteration
            if ($correspondingKgspcsEntry) {
                $todayTotalWastage = $wasteEntry['today_total_wastage'];
                $todayTotalQtyPcs = $correspondingKgspcsEntry['today_total_qty_in_kg'];
        
                // Avoid division by zero
                if ($todayTotalQtyPcs != 0) {
                    $today_waste_perc['today_waste_perc'] = ($todayTotalWastage / $todayTotalQtyPcs) * 100;
                } else {
                    $today_waste_perc['today_waste_perc'] = null; // Handle division by zero, set to null or any other desired value
                }
            }
        }
        
        // If no data is found, set a default value
        if (empty($today_waste_perc)) {
            $today_waste_perc['today_waste_perc'] = 0;
        }
        
        // dd($today_waste_perc);  
            $monthlyWaste = PrintedAndCuttedRollsEntry::join('printing_and_cutting_bag_items', 'printed_and_cutted_rolls_entry.id', '=', 'printing_and_cutting_bag_items.printAndCutEntry_id')
            ->whereBetween('printed_and_cutted_rolls_entry.date', [date('Y-m-01', strtotime($request->given_date)), $request->given_date])
            ->selectRaw('SUM(printing_and_cutting_bag_items.wastage) as monthly_total_wastage,
            MONTH(printed_and_cutted_rolls_entry.date) as month')
            ->groupBy( 'month')
            ->get()
            ->toArray();

            $monthlyKgspcs = BagBundelEntry::join('bag_bundel_items', 'bag_bundel_entries.id', '=', 'bag_bundel_items.bag_bundel_entry_id')
            ->whereBetween('bag_bundel_entries.receipt_date', [date('Y-m-01', strtotime($request->given_date)), $request->given_date])
            ->selectRaw('        
            SUM(bag_bundel_items.qty_in_kg) as monthly_total_qty_in_kg,
            SUM(bag_bundel_items.qty_pcs) as monthly_total_qty_pcs,
            MONTH(bag_bundel_entries.receipt_date) as month'
            )
            ->groupBy('month')
            ->get()
            ->toArray();

            // $monthly_waste_perc = [];

            // // Iterate through each entry in $todayWaste
            // foreach ($monthlyWaste as $wasteEntry) {
            //     $date = $wasteEntry['month'];

            //     // Find the corresponding entry in $todayKgspcs based on the date
            //     $correspondingKgspcsEntry = current(array_filter($monthlyKgspcs, function ($kgspcsEntry) use ($date) {
            //         return $kgspcsEntry['month'] === $date;
            //     }));

            //     // Calculate the waste percentage if a corresponding entry is found
            //     if ($correspondingKgspcsEntry) {
            //         $monthlyTotalWastage = $wasteEntry['monthly_total_wastage'];
            //         $monthlyTotalQtyPcs = $correspondingKgspcsEntry['monthly_total_qty_in_kg'];

            //         // Avoid division by zero
            //         if ($monthlyTotalQtyPcs != 0) {
            //             $monthly_waste_perc['monthly_waste_perc'] = ( $monthlyTotalWastage / $monthlyTotalQtyPcs) * 100;
            //         } else {
            //             $monthly_waste_perc['monthly_waste_perc'] = null; // Handle division by zero, set to null or any other desired value
            //         }
            //     } else {
            //         $monthly_waste_perc['monthly_waste_perc'] = null; // No corresponding entry found in $todayKgspcs
            //     }
            // }
            $monthly_waste_perc = [];

            // Iterate through each entry in $monthlyWaste
            foreach ($monthlyWaste as $wasteEntry) {
                $date = $wasteEntry['month'];

                // Find the corresponding entry in $monthlyKgspcs based on the date
                $correspondingKgspcsEntry = current(array_filter($monthlyKgspcs, function ($kgspcsEntry) use ($date) {
                    return $kgspcsEntry['month'] === $date;
                }));

                // Calculate the waste percentage if a corresponding entry is found
                if ($correspondingKgspcsEntry) {
                    $monthlyTotalWastage = $wasteEntry['monthly_total_wastage'];
                    $monthlyTotalQtyPcs = $correspondingKgspcsEntry['monthly_total_qty_in_kg'];

                    // Avoid division by zero
                    if ($monthlyTotalQtyPcs != 0) {
                        $monthly_waste_perc["monthly_waste_perc"] = ($monthlyTotalWastage / $monthlyTotalQtyPcs) * 100;
                    } else {
                        $monthly_waste_perc["monthly_waste_perc"] = null; // Handle division by zero, set to null or any other desired value
                    }
                } else {
                    $monthly_waste_perc["monthly_waste_perc"] = null; // No corresponding entry found in $monthlyKgspcs
                }
            }

            // If no data is found, set a default value
            if (empty($monthly_waste_perc)) {
                $monthly_waste_perc["monthly_waste_perc"] = 0;
            }

            // dd($monthly_waste_perc );

            $yearlyWaste = PrintedAndCuttedRollsEntry::join('printing_and_cutting_bag_items', 'printed_and_cutted_rolls_entry.id', '=', 'printing_and_cutting_bag_items.printAndCutEntry_id')
            ->whereBetween('printed_and_cutted_rolls_entry.date', [date('Y-01-01', strtotime($request->given_date)), $request->given_date])
            ->selectRaw('SUM(printing_and_cutting_bag_items.wastage) as yearly_total_wastage,
            YEAR(printed_and_cutted_rolls_entry.date) as year')
            ->groupBy('year')
            ->get()
            ->toArray();
            // dd($yearlyWaste);

            $yearlyKgspcs = BagBundelEntry::join('bag_bundel_items', 'bag_bundel_entries.id', '=', 'bag_bundel_items.bag_bundel_entry_id')
            ->whereBetween('bag_bundel_entries.receipt_date', [date('Y-01-01', strtotime($request->given_date)), $request->given_date])
            ->selectRaw('        
            SUM(bag_bundel_items.qty_in_kg) as yearly_total_qty_in_kg,
            SUM(bag_bundel_items.qty_pcs) as yearly_total_qty_pcs,
            YEAR(bag_bundel_entries.receipt_date) as year'
            )
            ->groupBy('year')
            ->get()
            ->toArray();
            // dd($yearlyKgspcs);

            $yearly_waste_perc = [];

            // Iterate through each entry in $todayWaste
            foreach ($yearlyWaste as $wasteEntry) {
                $date = $wasteEntry['year'];

                // Find the corresponding entry in $todayKgspcs based on the date
                $correspondingKgspcsEntry = current(array_filter($yearlyKgspcs, function ($kgspcsEntry) use ($date) {
                    return $kgspcsEntry['year'] === $date;
                }));

                // Calculate the waste percentage if a corresponding entry is found
                if ($correspondingKgspcsEntry) {
                    $yearlyTotalWastage = $wasteEntry['yearly_total_wastage'];
                    $yearlyTotalQtyPcs = $correspondingKgspcsEntry['yearly_total_qty_in_kg'];

                    // Avoid division by zero
                    if ($yearlyTotalQtyPcs != 0) {
                        $yearly_waste_perc['yearly_waste_perc'] = ( $yearlyTotalWastage/ $yearlyTotalQtyPcs) * 100;
                    } else {
                        $yearly_waste_perc['yearly_waste_perc'] = null; // Handle division by zero, set to null or any other desired value
                    }
                } else {
                    $yearly_waste_perc['yearly_waste_perc'] = null; // No corresponding entry found in $todayKgspcs
                }
            }
            if (empty($yearly_waste_perc)) {
                $yearly_waste_perc["yearly_waste_perc"] = 0;
            }

         
       
        // dd($yearly_waste_perc );
        $kgsPcs = ['kg', 'pcs'];
        // $mergeData = [];
        
        // foreach ($kgsPcs as $key) {
        //     $mergeData[$key] = [];
        
        //     if ($key === 'kg') {
        //         $mergeData[$key]['today_total_qty_in_kg'] = null;
        //         $mergeData[$key]['monthly_total_qty_in_kg'] = null;
        //         $mergeData[$key]['yearly_total_qty_in_kg'] = null;
        //     } elseif ($key === 'pcs') {
        //         $mergeData[$key]['today_total_qty_pcs'] = null;
        //         $mergeData[$key]['monthly_total_qty_pcs'] = null;
        //         $mergeData[$key]['yearly_total_qty_pcs'] = null;
        //     }
        
        //     foreach ($todayKgspcs as $todayData) {
        //         if ($key === 'kg') {
        //             $mergeData[$key]['today_total_qty_in_kg'] = $todayData['today_total_qty_in_kg'];
        //         } elseif ($key === 'pcs') {
        //             $mergeData[$key]['today_total_qty_pcs'] = $todayData['today_total_qty_pcs'];
        //         }
        //     }
        
        //     foreach ($monthlyKgspcs as $monthly) {
        //         if ($key === 'kg') {
        //             $mergeData[$key]['monthly_total_qty_in_kg'] = $monthly['monthly_total_qty_in_kg'];
        //         } elseif ($key === 'pcs') {
        //             $mergeData[$key]['monthly_total_qty_pcs'] = $monthly['monthly_total_qty_pcs'];
        //         }
        //     }
        
        //     foreach ($yearlyKgspcs as $yearly) {
        //         if ($key === 'kg') {
        //             $mergeData[$key]['yearly_total_qty_in_kg'] = $yearly['yearly_total_qty_in_kg'];
        //         } elseif ($key === 'pcs') {
        //             $mergeData[$key]['yearly_total_qty_pcs'] = $yearly['yearly_total_qty_pcs'];
        //         }
        //     }
        // }
        $mergeData = [];

foreach ($kgsPcs as $key) {
    $mergeData[$key] = ['type' => $key]; // Add the 'type' key to each sub-array

    if ($key === 'kg') {
        $mergeData[$key]['today_total_qty_in_kg'] = null;
        $mergeData[$key]['monthly_total_qty_in_kg'] = null;
        $mergeData[$key]['yearly_total_qty_in_kg'] = null;
    } elseif ($key === 'pcs') {
        $mergeData[$key]['today_total_qty_pcs'] = null;
        $mergeData[$key]['monthly_total_qty_pcs'] = null;
        $mergeData[$key]['yearly_total_qty_pcs'] = null;
    }

    foreach ($todayKgspcs as $todayData) {
        if ($key === 'kg') {
            $mergeData[$key]['today_total_qty_in_kg'] = $todayData['today_total_qty_in_kg'];
        } elseif ($key === 'pcs') {
            $mergeData[$key]['today_total_qty_pcs'] = $todayData['today_total_qty_pcs'];
        }
    }

    foreach ($monthlyKgspcs as $monthly) {
        if ($key === 'kg') {
            $mergeData[$key]['monthly_total_qty_in_kg'] = $monthly['monthly_total_qty_in_kg'];
        } elseif ($key === 'pcs') {
            $mergeData[$key]['monthly_total_qty_pcs'] = $monthly['monthly_total_qty_pcs'];
        }
    }

    foreach ($yearlyKgspcs as $yearly) {
        if ($key === 'kg') {
            $mergeData[$key]['yearly_total_qty_in_kg'] = $yearly['yearly_total_qty_in_kg'];
        } elseif ($key === 'pcs') {
            $mergeData[$key]['yearly_total_qty_pcs'] = $yearly['yearly_total_qty_pcs'];
        }
    }
}

// Output $mergeData

        // dd($yearlyWaste);
        return [
            'mergeData' => $mergeData,
            'todayWaste' => $todayWaste,
            'today_waste_per' => $today_waste_perc,
            'monthlyWaste' => $monthlyWaste,
            'monthly_waste_perc' => $monthly_waste_perc,
            'yearlyWaste' => $yearlyWaste,
            'yearly_waste_perc' => $yearly_waste_perc,
        ];
    } 
    private function data6($request){
        $result = DB::table('reprocess_wastes')
        ->whereDate('reprocess_wastes.date_en', '=', $request->given_date)
        ->join('wastage_danas', 'reprocess_wastes.id', '=', 'wastage_danas.reprocess_wastage_id')
        ->join('reprocess_wastage_details', 'reprocess_wastes.id', '=', 'reprocess_wastage_details.reprocess_waste_id')
        ->join('processing_subcats', 'wastage_danas.plantname_id', '=', 'processing_subcats.id')
        ->join('dana_names', 'wastage_danas.dana_id', '=', 'dana_names.id') // Add this join
        ->whereIn('wastage_danas.dana_id', [97, 59, 66, 67, 62, 60, 64, 57, 68, 56, 91, 89, 65, 63, 61, 96, 88, 55])
        ->select(
            'reprocess_wastes.date_en',
            'dana_names.name as dana_name', // Include dana_name
            'wastage_danas.dana_id as danaName_id',
            DB::raw('SUM(wastage_danas.quantity) as today_total_quantity'),
            DB::raw('SUM(reprocess_wastage_details.dye_quantity) as today_total_dye_quantity'),
            DB::raw('SUM(reprocess_wastage_details.cutter_quantity) as today_total_cutter_quantity'),
            DB::raw('SUM(reprocess_wastage_details.melt_quantity) as today_total_melt_quantity'),
            DB::raw('SUM(reprocess_wastage_details.dye_quantity + reprocess_wastage_details.cutter_quantity + reprocess_wastage_details.melt_quantity) as today_total_waste')
        )
        ->groupBy('reprocess_wastes.date_en', 'wastage_danas.dana_id', 'dana_names.name') // Group by dana_name
        ->get()
        ->toArray();
        // dd($result);

        $result1 = DB::table('reprocess_wastes')
        ->whereBetween('reprocess_wastes.date_en', [date('Y-m-01', strtotime($request->given_date)), $request->given_date])
        ->join('wastage_danas', 'reprocess_wastes.id', '=', 'wastage_danas.reprocess_wastage_id')
        ->join('reprocess_wastage_details', 'reprocess_wastes.id', '=', 'reprocess_wastage_details.reprocess_waste_id')
        ->join('processing_subcats', 'wastage_danas.plantname_id', '=', 'processing_subcats.id')
        ->join('dana_names', 'wastage_danas.dana_id', '=', 'dana_names.id') // Add this join
        ->whereIn('wastage_danas.dana_id', [97, 59, 66, 67, 62, 60, 64, 57, 68, 56, 91, 89, 65, 63, 61, 96, 88, 55])
        ->select(
            'dana_names.name as dana_name', // Include dana_name
            'wastage_danas.dana_id as danaName_id',
            DB::raw('MONTH(reprocess_wastes.date_en) as month'),
            DB::raw('SUM(wastage_danas.quantity) as monthly_total_quantity'),
            DB::raw('SUM(reprocess_wastage_details.dye_quantity) as monthly_total_dye_quantity'),
            DB::raw('SUM(reprocess_wastage_details.cutter_quantity) as monthly_total_cutter_quantity'),
            DB::raw('SUM(reprocess_wastage_details.melt_quantity) as monthly_total_melt_quantity'),
            DB::raw('SUM(reprocess_wastage_details.dye_quantity + reprocess_wastage_details.cutter_quantity + reprocess_wastage_details.melt_quantity) as monthly_total_waste')
        )
        ->groupBy('month', 'wastage_danas.dana_id', 'dana_names.name') // Group by dana_name
        ->get()
        ->toArray();
        // dd($result1);
        
        $result2 = DB::table('reprocess_wastes')
        ->whereBetween('reprocess_wastes.date_en', [date('Y-01-01', strtotime($request->given_date)), $request->given_date])
        ->join('wastage_danas', 'reprocess_wastes.id', '=', 'wastage_danas.reprocess_wastage_id')
        ->join('reprocess_wastage_details', 'reprocess_wastes.id', '=', 'reprocess_wastage_details.reprocess_waste_id')
        ->join('processing_subcats', 'wastage_danas.plantname_id', '=', 'processing_subcats.id')
        ->join('dana_names', 'wastage_danas.dana_id', '=', 'dana_names.id') // Add this join
        ->whereIn('wastage_danas.dana_id', [97, 59, 66, 67, 62, 60, 64, 57, 68, 56, 91, 89, 65, 63, 61, 96, 88, 55])
        ->select(
            'dana_names.name as dana_name', // Include dana_name
            'wastage_danas.dana_id as danaName_id',
            DB::raw('YEAR(reprocess_wastes.date_en) as year'),
            DB::raw('SUM(wastage_danas.quantity) as yearly_total_quantity'),
            DB::raw('SUM(reprocess_wastage_details.dye_quantity) as yearly_total_dye_quantity'),
            DB::raw('SUM(reprocess_wastage_details.cutter_quantity) as yearly_total_cutter_quantity'),
            DB::raw('SUM(reprocess_wastage_details.melt_quantity) as yearly_total_melt_quantity'),
            DB::raw('SUM(reprocess_wastage_details.dye_quantity + reprocess_wastage_details.cutter_quantity + reprocess_wastage_details.melt_quantity) as yearly_total_waste')
        )
        ->groupBy('year', 'wastage_danas.dana_id', 'dana_names.name') // Group by dana_name
        ->get()
        ->toArray();
        // dd($result2 );
        
        $wasteKeysMapping = [
            'rafia' => [97, 59, 66, 67, 62, 60, 64, 57, 68, 56],
            'nw' => [91, 89],
            'Ld' => [65, 63],
            'hm' => [61, 96],
            'tripal' => [88, 55],
        ];
        
        $mergeData = [];
        
        foreach ($wasteKeysMapping as $key => $processingStepIds) {
            $mergeData[$key] = [
                'wastage_key' => $key,
                'today_total_quantity' => null,
                'today_total_waste' => null,
                'monthly_total_quantity' => null,
                'monthly_total_waste' => null,
                'yearly_total_quantity' => null,
                'yearly_total_waste' => null,
            ];
        
            foreach ($result as $todayData) {
                if (in_array($todayData->danaName_id, $processingStepIds)) {
                    $mergeData[$key]['today_total_quantity'] += $todayData->today_total_quantity;
                    $mergeData[$key]['today_total_waste'] += $todayData->today_total_waste;
                }
            }
        
            foreach ($result1 as $monthly) {
                if (in_array($monthly->danaName_id, $processingStepIds)) {
                    $mergeData[$key]['monthly_total_quantity'] += $monthly->monthly_total_quantity;
                    $mergeData[$key]['monthly_total_waste'] += $monthly->monthly_total_waste;
                }
            }
        
            foreach ($result2 as $yearly) {
                if (in_array($yearly->danaName_id, $processingStepIds)) {
                    $mergeData[$key]['yearly_total_quantity'] += $yearly->yearly_total_quantity;
                    $mergeData[$key]['yearly_total_waste'] += $yearly->yearly_total_waste;
                }
            }
        }
        
        
        
        // dd($mergeData);
        
        // $resultData=[];
        // foreach($mergeData as $data){
        //     $resultData[$data['name']]['name'] = $data['name'];
        //     $resultData[$data['name']]['today_run_loom_sum'] = isset($data['today_run_loom_sum']) ? $data['today_run_loom_sum']:0;
        //     $resultData[$data['name']]['today_total_meter'] = isset($data['today_total_meter']) ? $data['today_total_meter']:0;
        //     $resultData[$data['name']]['monthly_run_loom_sum'] = isset($data['monthly_run_loom_sum']) ? $data['monthly_run_loom_sum']:0;
        //     $resultData[$data['name']]['monthly_total_meter_sum'] = isset($data['monthly_total_meter_sum']) ? $data['monthly_total_meter_sum']:0;
        //     $resultData[$data['name']]['yearly_run_loom_sum'] = isset($data['yearly_run_loom_sum']) ? $data['yearly_run_loom_sum']:0;
        //     $resultData[$data['name']]['yearly_total_meter_sum'] = isset($data['yearly_total_meter_sum']) ? $data['yearly_total_meter_sum']:0;

        // }
        // dd($resultData);
        $resultData = [];

        foreach ($mergeData as $key => $data) {
            $resultData[$key]['wastage_key'] = $data['wastage_key'];
            $resultData[$key]['today_total_quantity'] = isset($data['today_total_quantity']) ? $data['today_total_quantity'] : 0;
            $resultData[$key]['today_total_waste'] = isset($data['today_total_waste']) ? $data['today_total_waste'] : 0;
        
            $today_total_waste = isset($data['today_total_waste']) ? $data['today_total_waste'] : 0;
            $today_total_quantity = isset($data['today_total_quantity']) ? $data['today_total_quantity'] : 1;
            $resultData[$key]['today_waste_perc'] = $today_total_quantity != 0
                ? ($today_total_waste / $today_total_quantity) * 100
                : 0;
        
            $resultData[$key]['monthly_total_quantity'] = isset($data['monthly_total_quantity']) ? $data['monthly_total_quantity'] : 0;
            $resultData[$key]['monthly_total_waste'] = isset($data['monthly_total_waste']) ? $data['monthly_total_waste'] : 0;
        
            $monthly_total_waste = isset($data['monthly_total_waste']) ? $data['monthly_total_waste'] : 0;
            $monthly_total_quantity = isset($data['monthly_total_quantity']) ? $data['monthly_total_quantity'] : 1;
            $resultData[$key]['monthly_waste_perc'] = $monthly_total_quantity != 0
                ? ($monthly_total_waste / $monthly_total_quantity) * 100
                : 0;
        
            $resultData[$key]['yearly_total_quantity'] = isset($data['yearly_total_quantity']) ? $data['yearly_total_quantity'] : 0;
            $resultData[$key]['yearly_total_waste'] = isset($data['yearly_total_waste']) ? $data['yearly_total_waste'] : 0;
        
            $yearly_total_waste = isset($data['yearly_total_waste']) ? $data['yearly_total_waste'] : 0;
            $yearly_total_quantity = isset($data['yearly_total_quantity']) ? $data['yearly_total_quantity'] : 1;
            $resultData[$key]['yearly_waste_perc'] = $yearly_total_quantity != 0
                ? (($yearly_total_waste / $yearly_total_quantity)) * 100
                : 0;
        }
        
        // dd($resultData);
    return ($resultData);            
    } 

    private function data7(Request $request){

        $result = DB::table('ccplantentry')
        ->whereDate('ccplantentry.date', '=', $request->given_date)
        ->leftJoin('cc_plant_dana_creation', 'ccplantentry.id', '=', 'cc_plant_dana_creation.cc_plant_entry_id')
        ->leftJoin('cc_plant_wastages', 'ccplantentry.id', '=', 'cc_plant_wastages.ccplantentry_id')
        ->leftJoin('dana_groups', 'cc_plant_dana_creation.dana_group_id', '=', 'dana_groups.id')
        ->whereIn('cc_plant_dana_creation.dana_group_id', [2, 5, 6])
        ->select(
            'ccplantentry.date',
            'cc_plant_dana_creation.dana_group_id',
            'dana_groups.name as group_name', // Include the group name from dana_groups
            DB::raw('SUM(cc_plant_dana_creation.quantity) as today_total_quantity'),
            DB::raw('SUM(cc_plant_wastages.quantity) as today_total_wastages')
        )
        ->groupBy('ccplantentry.date', 'cc_plant_dana_creation.dana_group_id', 'dana_groups.name')
        ->get()
        ->toArray();
        // dd($result);

        $result1 = DB::table('ccplantentry')
        ->whereBetween('ccplantentry.date', [date('Y-m-01', strtotime($request->given_date)), $request->given_date])
        ->leftJoin('cc_plant_dana_creation', 'ccplantentry.id', '=', 'cc_plant_dana_creation.cc_plant_entry_id')
        ->leftJoin('cc_plant_wastages', 'ccplantentry.id', '=', 'cc_plant_wastages.ccplantentry_id')
        ->leftJoin('dana_groups', 'cc_plant_dana_creation.dana_group_id', '=', 'dana_groups.id')
        ->whereIn('cc_plant_dana_creation.dana_group_id', [2, 5, 6])
        ->select(
            'cc_plant_dana_creation.dana_group_id',
            'dana_groups.name as group_name', // Include the group name from dana_groups
            DB::raw('MONTH(ccplantentry.date) as month'),
            DB::raw('SUM(cc_plant_dana_creation.quantity) as monthly_total_quantity'),
            DB::raw('SUM(cc_plant_wastages.quantity) as monthly_total_wastages')
        )
        ->groupBy('month', 'cc_plant_dana_creation.dana_group_id', 'dana_groups.name')
        ->get()
        ->toArray();
        // dd($result1);

        $result2 = DB::table('ccplantentry')
        ->whereBetween('ccplantentry.date', [date('Y-01-01', strtotime($request->given_date)), $request->given_date])
        ->leftJoin('cc_plant_dana_creation', 'ccplantentry.id', '=', 'cc_plant_dana_creation.cc_plant_entry_id')
        ->leftJoin('cc_plant_wastages', 'ccplantentry.id', '=', 'cc_plant_wastages.ccplantentry_id')
        ->leftJoin('dana_groups', 'cc_plant_dana_creation.dana_group_id', '=', 'dana_groups.id')
        ->whereIn('cc_plant_dana_creation.dana_group_id', [2, 5, 6])
        ->select(
            'cc_plant_dana_creation.dana_group_id',
            'dana_groups.name as group_name', // Include the group name from dana_groups
            DB::raw('YEAR(ccplantentry.date) as year'),
            DB::raw('SUM(cc_plant_dana_creation.quantity) as yearly_total_quantity'),
            DB::raw('SUM(cc_plant_wastages.quantity) as yearly_total_wastages')
        )
        ->groupBy('year', 'cc_plant_dana_creation.dana_group_id', 'dana_groups.name')
        ->get()
        ->toArray();
        // dd($result2);
        $dana_groups=[2,5,6];
            $mergeData=[];       
            foreach ($dana_groups as $danaGroupId) {
                // dd($danaGroupId);
                $danaGroup = DanaGroup::find($danaGroupId);
                $mergeData[$danaGroup->name]['name'] = $danaGroup->name;
            
                foreach ($result as $todayData) {
                    if ($todayData->dana_group_id == $danaGroupId) {
                        $mergeData[$danaGroup->name]['today_total_quantity'] = $todayData->today_total_quantity;
                        $mergeData[$danaGroup->name]['today_total_wastages'] = $todayData->today_total_wastages;
                    }
                }
                
                foreach ($result1 as $monthly) {
                    if ($monthly->dana_group_id == $danaGroupId) {
                        $mergeData[$danaGroup->name]['monthly_total_quantity'] = $monthly->monthly_total_quantity;
                        $mergeData[$danaGroup->name]['monthly_total_wastages'] = $monthly->monthly_total_wastages;
                    }
                }
                
                foreach ($result2 as $yearly) {
                    if ($yearly->dana_group_id == $danaGroupId) {
                        $mergeData[$danaGroup->name]['yearly_total_quantity'] = $yearly->yearly_total_quantity;
                        $mergeData[$danaGroup->name]['yearly_total_wastages'] = $yearly->yearly_total_wastages;
                    }
                }
                
            }
        // dd($mergeData);
        $resultData=[];
        foreach($mergeData as $data){
            $resultData[$data['name']]['name'] = $data['name'];
            $resultData[$data['name']]['today_total_quantity'] = isset($data['today_total_quantity']) ? $data['today_total_quantity']:0;
            $resultData[$data['name']]['today_total_wastages'] = isset($data['today_total_wastages']) ? $data['today_total_wastages']:0;
            $resultData[$data['name']]['monthly_total_quantity'] = isset($data['monthly_total_quantity']) ? $data['monthly_total_quantity']:0;
            $resultData[$data['name']]['monthly_total_wastages'] = isset($data['monthly_total_wastages']) ? $data['monthly_total_wastages']:0;
            $resultData[$data['name']]['yearly_total_quantity'] = isset($data['yearly_total_quantity']) ? $data['yearly_total_quantity']:0;
            $resultData[$data['name']]['yearly_total_wastages'] = isset($data['yearly_total_wastages']) ? $data['yearly_total_wastages']:0;

        }
        // dd($resultData);
        $danaGroupData=[];
        foreach($resultData as $data){
            $danaGroupData[$data['name']]['name'] = $data['name'];
            $danaGroupData[$data['name']]['today_total_quantity'] = isset($data['today_total_quantity']) ? $data['today_total_quantity']:0;
            $danaGroupData[$data['name']]['today_total_wastages'] = isset($data['today_total_wastages']) ? $data['today_total_wastages']:0;

            $today_total_wastages = isset($data['today_total_wastages']) ? $data['today_total_wastages'] : 0;
            $today_total_quantity = isset($data['today_total_quantity']) ? $data['today_total_quantity'] : 1; // Use 1 as a default value to avoid division by zero
            $danaGroupData[$data['name']]['today_wastage_perc'] = $today_total_quantity != 0
                ? ($today_total_wastages / $today_total_quantity * 100)
                : 0;
            
            $danaGroupData[$data['name']]['monthly_total_quantity'] = isset($data['monthly_total_quantity']) ? $data['monthly_total_quantity']:0;
            $danaGroupData[$data['name']]['monthly_total_wastages'] = isset($data['monthly_total_wastages']) ? $data['monthly_total_wastages']:0;
            $monthly_total_wastages = isset($data['monthly_total_wastages']) ? $data['monthly_total_wastages'] : 0;
            $monthly_total_quantity = isset($data['monthly_total_quantity']) ? $data['monthly_total_quantity'] : 1; // Use 1 as a default value to avoid division by zero
            
            $danaGroupData[$data['name']]['monthly_wastage_perc'] = $monthly_total_quantity != 0
                ? ($monthly_total_wastages / $monthly_total_quantity * 100)
                : 0;
            
            $danaGroupData[$data['name']]['yearly_total_quantity'] = isset($data['yearly_total_quantity']) ? $data['yearly_total_quantity']:0;
            $danaGroupData[$data['name']]['yearly_total_wastages'] = isset($data['yearly_total_wastages']) ? $data['yearly_total_wastages']:0;
            $yearly_total_wastages = isset($data['yearly_total_wastages']) ? $data['yearly_total_wastages'] : 0;
            $yearly_total_quantity = isset($data['yearly_total_quantity']) ? $data['yearly_total_quantity'] : 1; // Use 1 as a default value to avoid division by zero
            
            $danaGroupData[$data['name']]['yearly_wastage_perc'] = $yearly_total_quantity != 0
                ? ($yearly_total_wastages / $yearly_total_quantity * 100)
                : 0;
            
        }
        // dd($danaGroupData);
        return  $danaGroupData;
    }
}
