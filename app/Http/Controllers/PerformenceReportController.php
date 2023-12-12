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
use App\Models\AutoloadItems;
use App\Services\NepaliConverter;
use App\Helpers\AppHelper;
use DB;

class PerformenceReportController extends Controller
{
    protected $npDate;
 
    public function __construct(NepaliConverter $npDate){
        $this->npDate = $npDate;
    }
    public function __invoke(Request $request)
    {
        if ($request->ajax()) {

           $currentDate=AppHelper::convertNepaliToEnglishDate($request->given_date);
           $startOfYear = $this->npDate->getnepaliShrawanYear($request->given_date);
           $startOfMonth =$this->npDate->getnepaliYearMonth($request->given_date);
           $engStartOfYear =AppHelper::convertNepaliToEnglishDate($startOfYear);
           $engStartOfMonth =AppHelper::convertNepaliToEnglishDate($startOfMonth);
            // dd($currentDate);

            $datas = $this->data($currentDate, $engStartOfYear,$engStartOfMonth);
            $loomRollDown =$this->data1($currentDate, $engStartOfYear,$engStartOfMonth);
            $loomAvgMeter =$this->data2($currentDate, $engStartOfYear,$engStartOfMonth);
            $laminationProdReport= $this->data3($currentDate, $engStartOfYear,$engStartOfMonth);  
            $nonWovenProduction = $this->data4($currentDate, $engStartOfYear,$engStartOfMonth);
          
            $ppBags=$this->data5($currentDate, $engStartOfYear,$engStartOfMonth);

            $erimaPlantProd =$this->data6($currentDate, $engStartOfYear,$engStartOfMonth);
            $ccplant = $this->data7($currentDate, $engStartOfYear,$engStartOfMonth);

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

    private function data($currentDate, $engStartOfYear,$engStartOfMonth)
        {
            $plantName= [1, 2, 3, 4,9,13];
            $result = DB::table('tape_entry_items')
            ->join('tape_entry', 'tape_entry_items.tape_entry_id', '=', 'tape_entry.id')
            ->join('processing_subcats', 'tape_entry_items.plantName_id', '=', 'processing_subcats.id')
            ->where('tape_entry.tape_entry_date', '=', $currentDate)
            ->whereIn('processing_subcats.id', [1, 2, 3, 4,9,13])
            ->groupBy('tape_entry_items.plantName_id', 'tape_entry.tape_entry_date')
            ->select(
                'tape_entry_items.plantName_id',
                'tape_entry.tape_entry_date',
                DB::raw('SUM(tape_entry_items.tape_qty_in_kg) as today_total_tape_qty'),
                DB::raw('SUM(tape_entry_items.bypass_wast + tape_entry_items.loading + tape_entry_items.running) as today_total_waste')
            )
            ->get()
            ->toArray();
        
            // dd($result);
            $result1 = DB::table('tape_entry_items')
            ->join('tape_entry', 'tape_entry_items.tape_entry_id', '=', 'tape_entry.id')
            ->join('processing_subcats', 'tape_entry_items.plantName_id', '=', 'processing_subcats.id')
            ->whereBetween('tape_entry.tape_entry_date', [$engStartOfMonth,$currentDate])
            ->whereIn('processing_subcats.id', [1, 2, 3, 4,9,13])
            ->groupBy('processing_subcats.id','processing_subcats.name')
            ->select(
                'processing_subcats.id as plantName_id',
                'processing_subcats.name', // Include the plant name in the select clause
                // DB::raw('MONTH(tape_entry.tape_entry_date) as month'),
                DB::raw('SUM(tape_entry_items.tape_qty_in_kg) as monthly_total_tape_qty'),
                DB::raw('SUM(tape_entry_items.bypass_wast + tape_entry_items.loading + tape_entry_items.running) as monthly_total_waste')
            )
            ->get()
            ->toArray();
            $result2 = DB::table('tape_entry_items')
            ->join('tape_entry', 'tape_entry_items.tape_entry_id', '=', 'tape_entry.id')
            ->join('processing_subcats', 'tape_entry_items.plantName_id', '=', 'processing_subcats.id')
            ->whereBetween('tape_entry.tape_entry_date', [$engStartOfYear, $currentDate])
            ->whereIn('processing_subcats.id', [1, 2, 3, 4, 9, 13])
            ->groupBy('processing_subcats.id', 'processing_subcats.name')
            ->select(
                'processing_subcats.id as plantName_id',
                'processing_subcats.name',
                DB::raw('SUM(tape_entry_items.tape_qty_in_kg) as yearly_total_tape_qty'),
                DB::raw('SUM(tape_entry_items.bypass_wast + tape_entry_items.loading + tape_entry_items.running) as yearly_total_waste')
            )
            ->get();
        
        

            // dd($result2);
            $mergeData=[];       
            foreach( $plantName as $plant_id){
                $processingSubcat =ProcessingSubcat::find($plant_id);
                $mergeData[$processingSubcat->name]['plant_name'] =  $processingSubcat->name;
                
                foreach($result as $todayData){
                    if( $todayData->plantName_id == $plant_id){
                        $mergeData[$processingSubcat->name]['today_tape_quantity'] =  $todayData->today_total_tape_qty;
                        $mergeData[$processingSubcat->name]['today_waste'] =  $todayData->today_total_waste;
                    }                 
                }

                foreach($result1 as $monthly){
                    if( $monthly->plantName_id == $plant_id){
                    $mergeData[$processingSubcat->name]['monthly_tape_quantity'] =  $monthly->monthly_total_tape_qty;
                    $mergeData[$processingSubcat->name]['monthly_total_wastages'] =  $monthly->monthly_total_waste;
                 }   
                }

                foreach($result2 as $yearly){
                    if( $yearly->plantName_id == $plant_id){
                    $mergeData[$processingSubcat->name]['yearly_tape_quantity'] =  $yearly->yearly_total_tape_qty;
                    $mergeData[$processingSubcat->name]['yearly_total_waste'] =  $yearly->yearly_total_waste;
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
                $resultData[$data['plant_name']]['monthly_total_wastages'] = isset($data['monthly_total_wastages']) ? $data['monthly_total_wastages']:0;
                $resultData[$data['plant_name']]['yearly_tape_quantity'] = isset($data['yearly_tape_quantity']) ? $data['yearly_tape_quantity']:0;
                $resultData[$data['plant_name']]['yearly_total_waste'] = isset($data['yearly_total_waste']) ? $data['yearly_total_waste']:0;

            }
            // dd($resultData);
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
                $tapePlantData[$data['plant_name']]['monthly_total_wastages'] = isset($data['monthly_total_wastages']) ? $data['monthly_total_wastages']:0;
                $monthly_total_wastages = isset($data['monthly_total_wastages']) ? $data['monthly_total_wastages'] : 0;
                $monthly_tape_quantity = isset($data['monthly_tape_quantity']) ? $data['monthly_tape_quantity'] : 1; // Use 1 as a default value to avoid division by zero
                
                $tapePlantData[$data['plant_name']]['monthly_wastage_perc'] = $monthly_tape_quantity != 0
                    ? ($monthly_total_wastages / $monthly_tape_quantity * 100)
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
    
    private function data1($currentDate, $engStartOfYear,$engStartOfMonth){

            $todayResult = FabricDetail::whereDate('bill_date_en', '=', $currentDate)
            ->join('godam', 'fabric_details.godam_id', '=', 'godam.id')
            ->select('godam_id',
            'fabric_details.bill_date_en', 
            'godam.name',
              DB::raw('SUM(total_netweight) as today_netweight_sum'),
              DB::raw('SUM(total_wastage) as today_wastage_sum'))
            ->groupBy('godam_id', 'godam.name','fabric_details.bill_date_en')
            ->get()
            ->toArray();
            // dd($todayResult);

            $monthlyResult = FabricDetail::whereBetween('bill_date_en', [$engStartOfMonth,$currentDate])
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

            $yearlyResult = FabricDetail::whereBetween('bill_date_en', [$engStartOfYear,$currentDate])
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
    private function data2($currentDate, $engStartOfYear,$engStartOfMonth){

        $todayResult = FabricDetail::whereDate('bill_date_en', '=', $currentDate)
        ->join('godam', 'fabric_details.godam_id', '=', 'godam.id')
        ->select('godam_id','fabric_details.bill_date_en', 'godam.name',
         DB::raw('SUM(run_loom) as today_run_loom_sum'),
          DB::raw('SUM(total_meter) as today_total_meter'))
        ->groupBy('godam_id', 'godam.name','fabric_details.bill_date_en')
        ->get()
        ->toArray();
        // dd($todayResult);

        $monthlyResult = FabricDetail::whereBetween('bill_date_en', [$engStartOfMonth, $currentDate])
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

        $yearlyResult = FabricDetail::whereBetween('bill_date_en', [$engStartOfYear, $currentDate])
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
    private function data3($currentDate, $engStartOfYear,$engStartOfMonth)
        {
                $plantName= [6, 5, 10, 14];  
                $result = FabricSendAndReceiveEntry::
                join('processing_subcats', 'fabric_send_and_receive_entry.plantname_id', '=', 'processing_subcats.id')
                ->where('fabric_send_and_receive_entry.bill_date', '=', $currentDate)
                ->selectRaw('processing_subcats.id as plantName_id, 
                            fabric_send_and_receive_entry.bill_date,
                            SUM(fabric_send_and_receive_entry.polo_waste) as today_total_polo_waste'
                            )
                ->whereIn('processing_subcats.id', [6, 5, 10, 14])
                ->groupBy('processing_subcats.id', 'fabric_send_and_receive_entry.bill_date')
                ->get()
                ->toArray();
                // dd($result);

                $date =$currentDate; // replace this with your actual date variable
                $plantIds = [6, 5, 10, 14]; // replace this with your desired plant IDs

                    $quantityResult = AutoloadItems::join('auto_loads', 'autoload_items.autoload_id', '=', 'auto_loads.id')
                    ->join('processing_subcats', 'autoload_items.plant_name_id', '=', 'processing_subcats.id')
                    ->where('auto_loads.transfer_date', '=',  $currentDate)
                    ->whereIn('processing_subcats.id', [6, 5, 10, 14])
                    ->selectRaw('
                        auto_loads.transfer_date,
                        processing_subcats.id as plantName_id,
                        SUM(autoload_items.quantity) as today_total_quantity
                    ')
                    ->groupBy('plantName_id', 'auto_loads.transfer_date') // Remove 'auto_loads.transfer_date'
                    ->get()
                    ->toArray();
                    // dd($quantityResult);
                    $combinedArray = [];

                    // Merge data from the first array
                    foreach ($result as $item) {
                        $key = $item['bill_date'] . '_' . $item['plantName_id'];
                        $combinedArray[$key] = $item + ($combinedArray[$key] ?? []);
                        $combinedArray[$key] += ['transfer_date' => null, 'today_total_quantity' => null];
                    }
                    
                    // Merge data from the second array
                    foreach ($quantityResult as $item) {
                        $key = $item['transfer_date'] . '_' . $item['plantName_id'];
                        $combinedArray[$key] = $item + ($combinedArray[$key] ?? []);
                        $combinedArray[$key] += ['bill_date' => null, 'today_total_polo_waste' => null];
                    }
                    $mergedResult = array_values($combinedArray);
                // dd($mergedResult);
                $result1 = FabricSendAndReceiveEntry::
                join('processing_subcats', 'fabric_send_and_receive_entry.plantname_id', '=', 'processing_subcats.id')
                ->whereBetween('fabric_send_and_receive_entry.bill_date', [$engStartOfMonth, $currentDate])
                ->selectRaw('processing_subcats.id as plantName_id, 
                            MONTH(fabric_send_and_receive_entry.bill_date) as month, 
                            SUM(fabric_send_and_receive_entry.polo_waste) as monthly_total_polo_waste'
                            )
                ->whereIn('processing_subcats.id', [6, 5, 10, 14])
                ->groupBy('processing_subcats.id','month')
                ->get()
                ->toArray();
                // dd($result1);

                $quantityResult1 = AutoloadItems::join('auto_loads', 'autoload_items.autoload_id', '=', 'auto_loads.id')
                ->join('processing_subcats', 'autoload_items.plant_name_id', '=', 'processing_subcats.id')
                ->whereBetween('auto_loads.transfer_date', [$engStartOfMonth, $currentDate])
                ->whereIn('processing_subcats.id', [6, 5, 10, 14])
                ->selectRaw('
                MONTH(auto_loads.transfer_date) as month, 
                    processing_subcats.id as plantName_id,
                    SUM(autoload_items.quantity) as monthly_total_quantity
                ')
                ->groupBy('plantName_id', 'month') // Remove 'auto_loads.transfer_date'
                ->get()
                ->toArray();
                // dd($quantityResult1);
                $combinedArray1 = [];

                // Merge data from the first array
                foreach ($result1 as $item) {
                    $key = $item['month'] . '_' . $item['plantName_id'];
                    $combinedArray1[$key] = $item + ($combinedArray1[$key] ?? []);
                    $combinedArray1[$key] += ['month' => null, 'monthly_total_quantity' => null];
                }
                
                // Merge data from the second array
                foreach ($quantityResult1 as $item) {
                    $key = $item['month'] . '_' . $item['plantName_id'];
                    $combinedArray1[$key] = $item + ($combinedArray1[$key] ?? []);
                    $combinedArray1[$key] += ['month' => null, 'monthly_total_polo_waste' => null];
                }
                $mergedResult1 = array_values($combinedArray1);

                // dd($mergedResult1);
                //yearly
                $result2 = FabricSendAndReceiveEntry::
                join('processing_subcats', 'fabric_send_and_receive_entry.plantname_id', '=', 'processing_subcats.id')
                ->whereBetween('fabric_send_and_receive_entry.bill_date', [$engStartOfYear, $currentDate])
                ->selectRaw('processing_subcats.id as plantName_id, 
                            YEAR(fabric_send_and_receive_entry.bill_date) as year, 
                            SUM(fabric_send_and_receive_entry.polo_waste) as yearly_total_polo_waste'
                            )
                ->whereIn('processing_subcats.id', [6, 5, 10, 14])
                ->groupBy('processing_subcats.id','year')
                ->get()
                ->toArray();
                // dd($result2);

                $quantityResult2 = AutoloadItems::join('auto_loads', 'autoload_items.autoload_id', '=', 'auto_loads.id')
                ->join('processing_subcats', 'autoload_items.plant_name_id', '=', 'processing_subcats.id')
                ->whereBetween('auto_loads.transfer_date', [$engStartOfYear, $currentDate])
                ->whereIn('processing_subcats.id', [6, 5, 10, 14])
                ->selectRaw('
                    YEAR(auto_loads.transfer_date) as year, 
                    processing_subcats.id as plantName_id,
                    SUM(autoload_items.quantity) as yearly_total_quantity
                ')
                ->groupBy('plantName_id', 'year') // Remove 'auto_loads.transfer_date'
                ->get()
                ->toArray();
                $combinedArray2 = [];
                // Merge data from the first array
                foreach ($result2 as $item) {
                    $key = $item['year'] . '_' . $item['plantName_id'];
                    $combinedArray2[$key] = $item + ($combinedArray2[$key] ?? []);
                    $combinedArray2[$key] += ['year' => null, 'yearly_total_quantity' => null];
                }
                
                // Merge data from the second array
                foreach ($quantityResult2 as $item) {
                    $key = $item['year'] . '_' . $item['plantName_id'];
                    $combinedArray2[$key] = $item + ($combinedArray2[$key] ?? []);
                    $combinedArray2[$key] += ['year' => null, 'yearly_total_polo_waste' => null];
                }
                $mergedResult2 = array_values($combinedArray2);

                // dd($mergedResult2);
               
       
             $mergeData=[];       
                foreach( $plantName as $plant_id){
                    $processingSubcat =ProcessingSubcat::find($plant_id);
                    $mergeData[$processingSubcat->name]['plant_name'] =  $processingSubcat->name;
                    
                    foreach($mergedResult as $todayData){
                        if( $todayData['plantName_id'] == $plant_id){
                            $mergeData[$processingSubcat->name]['today_total_polo_waste'] =  $todayData['today_total_polo_waste'];
                            $mergeData[$processingSubcat->name]['today_total_consumption_quantity'] =  $todayData['today_total_quantity'];
                        }                 
                    }

                    foreach($mergedResult1 as $monthly){
                        if( $monthly['plantName_id'] == $plant_id){
                        $mergeData[$processingSubcat->name]['monthly_total_polo_waste'] =  $monthly['monthly_total_polo_waste'];
                        $mergeData[$processingSubcat->name]['montly_total_consumption_quantity'] =  $monthly['monthly_total_quantity'];
                    }   
                    }

                    foreach($mergedResult2 as $yearly){
                        if( $yearly['plantName_id'] == $plant_id){
                        $mergeData[$processingSubcat->name]['yearly_total_polo_waste'] =  $yearly['yearly_total_polo_waste'];
                        $mergeData[$processingSubcat->name]['yearly_total_consumption_quantity'] =  $yearly['yearly_total_quantity'];
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
  
        private function data4($currentDate, $engStartOfYear,$engStartOfMonth) {
            $plantName= [7];   
            //today   
            $result = DB::table('nonwoven_bills')
            ->join(
                DB::raw('(SELECT bill_id, SUM(net_weight) as total_net_weight FROM fabric_non_woven_recive_entries GROUP BY bill_id) as fabric_recive'),
                'nonwoven_bills.id', '=', 'fabric_recive.bill_id'
            )
            ->join('processing_subcats', 'nonwoven_bills.plantname_id', '=', 'processing_subcats.id')
            ->where('nonwoven_bills.bill_date_en', '=', $currentDate)
            ->whereIn('processing_subcats.id', [7])
            ->selectRaw('processing_subcats.id as plantName_id,
                        processing_subcats.name as plant_name,
                        nonwoven_bills.bill_date_en as date,
                        SUM(nonwoven_bills.total_waste) as today_total_waste,
                        COALESCE(SUM(fabric_recive.total_net_weight), 0) as today_total_net_weight')
            ->groupBy('processing_subcats.id', 'processing_subcats.name', 'nonwoven_bills.bill_date_en')
            ->get()
            ->toArray();

            $result1 = DB::table('nonwoven_bills')
            ->join(
                DB::raw('(SELECT bill_id, SUM(net_weight) as total_net_weight FROM fabric_non_woven_recive_entries GROUP BY bill_id) as fabric_recive'),
                'nonwoven_bills.id', '=', 'fabric_recive.bill_id'
            )
            ->join('processing_subcats', 'nonwoven_bills.plantname_id', '=', 'processing_subcats.id')
            ->whereBetween('nonwoven_bills.bill_date_en', [
                $engStartOfMonth,
                $currentDate // This should be the end of the date range, e.g., '2023-11-29'
            ])
            ->whereIn('processing_subcats.id', [7])
            ->selectRaw('processing_subcats.id as plantName_id,
                        processing_subcats.name as plant_name,
                        MONTH(nonwoven_bills.bill_date_en) as month,
                        SUM(nonwoven_bills.total_waste) as monthly_total_waste,
                        COALESCE(SUM(fabric_recive.total_net_weight), 0) as monthly_total_net_weight')
            ->groupBy('processing_subcats.id', 'processing_subcats.name', 'month')
            ->get()
            ->toArray();

            $result2 = DB::table('nonwoven_bills')
            ->join(
                DB::raw('(SELECT bill_id, SUM(net_weight) as total_net_weight FROM fabric_non_woven_recive_entries GROUP BY bill_id) as fabric_recive'),
                'nonwoven_bills.id', '=', 'fabric_recive.bill_id'
            )
            ->join('processing_subcats', 'nonwoven_bills.plantname_id', '=', 'processing_subcats.id')
            ->whereBetween('nonwoven_bills.bill_date_en', [
               $engStartOfYear,
               $currentDate
            ])
            ->whereIn('processing_subcats.id', [7])
            ->selectRaw('processing_subcats.id as plantName_id,
                        processing_subcats.name as plant_name,
                        YEAR(nonwoven_bills.bill_date_en) as year,
                        SUM(nonwoven_bills.total_waste) as yearly_total_waste,
                        COALESCE(SUM(fabric_recive.total_net_weight), 0) as yearly_total_net_weight')
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

    public function data5($currentDate, $engStartOfYear,$engStartOfMonth){
        $todayWaste = PrintedAndCuttedRollsEntry::join('printing_and_cutting_bag_items', 'printed_and_cutted_rolls_entry.id', '=', 'printing_and_cutting_bag_items.printAndCutEntry_id')
        ->where('printed_and_cutted_rolls_entry.date', '=',$currentDate)
        ->selectRaw('SUM(printing_and_cutting_bag_items.wastage) as today_total_wastage, printed_and_cutted_rolls_entry.date as date')
        ->groupBy('printed_and_cutted_rolls_entry.date')
        ->get()
        ->toArray();
       
        $todayKgspcs = BagBundelEntry::join('bag_bundel_items', 'bag_bundel_entries.id', '=', 'bag_bundel_items.bag_bundel_entry_id')
        ->where('bag_bundel_entries.receipt_date', '=',$currentDate)
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
            ->whereBetween('printed_and_cutted_rolls_entry.date', [$engStartOfMonth, $currentDate])
            ->selectRaw('SUM(printing_and_cutting_bag_items.wastage) as monthly_total_wastage,
            MONTH(printed_and_cutted_rolls_entry.date) as month')
            ->groupBy( 'month')
            ->get()
            ->toArray();

            $monthlyKgspcs = BagBundelEntry::join('bag_bundel_items', 'bag_bundel_entries.id', '=', 'bag_bundel_items.bag_bundel_entry_id')
            ->whereBetween('bag_bundel_entries.receipt_date', [$engStartOfMonth, $currentDate])
            ->selectRaw('        
            SUM(bag_bundel_items.qty_in_kg) as monthly_total_qty_in_kg,
            SUM(bag_bundel_items.qty_pcs) as monthly_total_qty_pcs,
            MONTH(bag_bundel_entries.receipt_date) as month'
            )
            ->groupBy('month')
            ->get()
            ->toArray();

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
            ->whereBetween('printed_and_cutted_rolls_entry.date', [$engStartOfYear, $currentDate])
            ->selectRaw('SUM(printing_and_cutting_bag_items.wastage) as yearly_total_wastage,
            YEAR(printed_and_cutted_rolls_entry.date) as year')
            ->groupBy('year')
            ->get()
            ->toArray();
            // dd($yearlyWaste);

            $yearlyKgspcs = BagBundelEntry::join('bag_bundel_items', 'bag_bundel_entries.id', '=', 'bag_bundel_items.bag_bundel_entry_id')
            ->whereBetween('bag_bundel_entries.receipt_date', [$engStartOfYear, $currentDate])
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
        $kgsPcs = ['kg', 'pcs'];
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
    private function data6($currentDate, $engStartOfYear,$engStartOfMonth){
       
        $result = DB::table('dana_names')
        ->leftJoin('wastage_danas', 'dana_names.id', '=', 'wastage_danas.dana_id')
        ->join('reprocess_wastes', 'wastage_danas.reprocess_wastage_id', '=', 'reprocess_wastes.id')
        ->leftJoin('reprocess_wastage_details', 'reprocess_wastes.id', '=', 'reprocess_wastage_details.reprocess_waste_id')
        ->whereIn('wastage_danas.dana_id', [97, 59, 66, 67, 62, 60, 64, 57, 68, 56, 91, 89, 65, 63, 61, 96, 88, 55])
        ->whereDate('reprocess_wastes.date_en', '=',$currentDate)
        ->groupBy('reprocess_wastes.date_en','wastage_danas.dana_id', 'dana_names.name')
        ->select(
            'reprocess_wastes.date_en',
            'dana_names.name as dana_name',
            'wastage_danas.dana_id as danaName_id',
            DB::raw('SUM(COALESCE(wastage_danas.quantity, 0)) as today_total_quantity'),
            DB::raw('SUM(COALESCE(reprocess_wastage_details.dye_quantity, 0)) as today_total_dye_quantity'),
            DB::raw('SUM(COALESCE(reprocess_wastage_details.cutter_quantity, 0)) as today_total_cutter_quantity'),
            DB::raw('SUM(COALESCE(reprocess_wastage_details.melt_quantity, 0)) as today_total_melt_quantity'),
            DB::raw('SUM(COALESCE(reprocess_wastage_details.dye_quantity, 0) + COALESCE(reprocess_wastage_details.cutter_quantity, 0) + COALESCE(reprocess_wastage_details.melt_quantity, 0)) as today_total_waste')
        )
        ->get()
        ->toArray();
        // dd($result);
        
        $result1 = DB::table('dana_names')
        ->leftJoin('wastage_danas', 'dana_names.id', '=', 'wastage_danas.dana_id')
        ->join('reprocess_wastes', 'wastage_danas.reprocess_wastage_id', '=', 'reprocess_wastes.id')
        ->leftJoin('reprocess_wastage_details', 'reprocess_wastes.id', '=', 'reprocess_wastage_details.reprocess_waste_id')
        ->whereIn('wastage_danas.dana_id', [97, 59, 66, 67, 62, 60, 64, 57, 68, 56, 91, 89, 65, 63, 61, 96, 88, 55])
        ->whereBetween('reprocess_wastes.date_en', [$engStartOfMonth ,$currentDate])
        ->groupBy('month','wastage_danas.dana_id', 'dana_names.name')
        ->select(
            'dana_names.name as dana_name',
            'wastage_danas.dana_id as danaName_id',
            DB::raw('MONTH(reprocess_wastes.date_en) as month'),
            DB::raw('SUM(COALESCE(wastage_danas.quantity, 0)) as monthly_total_quantity'),
            DB::raw('SUM(COALESCE(reprocess_wastage_details.dye_quantity, 0)) as monthly_total_dye_quantity'),
            DB::raw('SUM(COALESCE(reprocess_wastage_details.cutter_quantity, 0)) as monthly_total_cutter_quantity'),
            DB::raw('SUM(COALESCE(reprocess_wastage_details.melt_quantity, 0)) as monthly_total_melt_quantity'),
            DB::raw('SUM(COALESCE(reprocess_wastage_details.dye_quantity, 0) + COALESCE(reprocess_wastage_details.cutter_quantity, 0) + COALESCE(reprocess_wastage_details.melt_quantity, 0)) as monthly_total_waste')
        )
        ->get()
        ->toArray();

        $result2 = DB::table('dana_names')
        ->leftJoin('wastage_danas', 'dana_names.id', '=', 'wastage_danas.dana_id')
        ->join('reprocess_wastes', 'wastage_danas.reprocess_wastage_id', '=', 'reprocess_wastes.id')
        ->leftJoin('reprocess_wastage_details', 'reprocess_wastes.id', '=', 'reprocess_wastage_details.reprocess_waste_id')
        ->whereIn('wastage_danas.dana_id', [97, 59, 66, 67, 62, 60, 64, 57, 68, 56, 91, 89, 65, 63, 61, 96, 88, 55])
        ->whereBetween('reprocess_wastes.date_en', [$engStartOfYear, $currentDate])
        ->groupBy('year','wastage_danas.dana_id', 'dana_names.name')
        ->select(
            'dana_names.name as dana_name',
            'wastage_danas.dana_id as danaName_id',
            DB::raw('YEAR(reprocess_wastes.date_en) as year'),
            DB::raw('SUM(COALESCE(wastage_danas.quantity, 0)) as yearly_total_quantity'),
            DB::raw('SUM(COALESCE(reprocess_wastage_details.dye_quantity, 0)) as yearly_total_dye_quantity'),
            DB::raw('SUM(COALESCE(reprocess_wastage_details.cutter_quantity, 0)) as yearly_total_cutter_quantity'),
            DB::raw('SUM(COALESCE(reprocess_wastage_details.melt_quantity, 0)) as yearly_total_melt_quantity'),
            DB::raw('SUM(COALESCE(reprocess_wastage_details.dye_quantity, 0) + COALESCE(reprocess_wastage_details.cutter_quantity, 0) + COALESCE(reprocess_wastage_details.melt_quantity, 0)) as yearly_total_waste')
        )
        ->get()
        ->toArray();
       
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
                'today_total_quantity' => 0,
                'today_total_waste' => 0,
                'monthly_total_quantity' => 0,
                'monthly_total_waste' => 0,
                'yearly_total_quantity' => 0,
                'yearly_total_waste' => 0,
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

    private function data7($currentDate, $engStartOfYear,$engStartOfMonth){

        $result = DB::table('ccplantentry')
        ->whereDate('ccplantentry.date', '=', $currentDate)
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
        ->whereBetween('ccplantentry.date', [$engStartOfMonth,$currentDate])
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
        ->whereBetween('ccplantentry.date', [$engStartOfYear, $currentDate])
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
