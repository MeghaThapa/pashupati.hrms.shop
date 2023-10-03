<?php
namespace App\Http\Controllers;
use App\Models\PrintedAndCuttedRolls;
use App\Models\PrintedAndCuttedRollsEntry;
use App\Models\BagFabricReceiveItemSentStock;
use App\Models\PrintingAndCuttingBagItem;
use App\Models\AutoLoadItemStock;
use App\Models\Godam;
use App\Models\Wastages;
use App\Models\BagBrand;
use App\Models\Group;
use Illuminate\Http\Request;
use DB;

class PrintedAndCuttedRollsController extends Controller

{

    /*************** For Entry *********/

    public function datafixes(){

        // try{
        //     DB::beginTransaction();
        // $tests=PrintingAndCuttingBagItem::with('brandBag:id,name')->get();
        //     foreach( $tests as $test ){
        //         // return $test->brandBag->name;

        //         $brandbagid=BagBrand::where('name',$test->brandBag->name)->first()->id;
        //         // return $brandbagid;
        //         $test->update([
        //             'bag_brand_id'=>$brandbagid,
        //         ]);
        //     }
        //     DB::commit();
        //     return 'done';
        //     }catch(Exception $ex){
        //         DB::rollback();
        //     }


        // try{
        //     DB::beginTransaction();
        //     $duplicateNames = BagBrand::groupBy('name')
        //     ->havingRaw('COUNT(*) > 1')
        //     ->get('name');
        //     // return $duplicateNames;
        //     foreach ($duplicateNames as $name) {
        //         $recordsToDelete = BagBrand::where('name', $name->name)->skip(1)->take(PHP_INT_MAX)->get();

        //         foreach ($recordsToDelete as $record) {
        //             $record->delete();
        //         }
        //     }
        //     DB::commit();
        //     return 'done';
        //     }catch(Exception $ex){
        //         DB::rollback();
        //     }

    }
    public function index()
    {
        
        $data = PrintedAndCuttedRollsEntry::orderBy('created_at', 'desc')->get();
        return view("admin.bag.printsandcuts.index", compact("data"));
    }

    public function view($id){
         $printedAndCuttedRollsEntryData = PrintedAndCuttedRollsEntry::with(['printingAndCuttingBagItems','printingAndCuttingBagItems.fabric:id,name','printingAndCuttingBagItems.brandBag:id,name'])
         ->find($id);
        //   return  $printedAndCuttedRollsEntryData->printingAndCuttingBagItems[10];
         return view("admin.bag.printsandcuts.view", compact("printedAndCuttedRollsEntryData"));
    }

    public function createEntry()
    {

        $id = PrintedAndCuttedRollsEntry::latest()->value('id');

        $nepaliDate = getNepaliDate(date('Y-m-d'));

        $date = date('Y-m-d');

        $receipt_no = "PCR" . "-" . getNepaliDate(date('Y-m-d')) . "-" . $id + 1;

        return view("admin.bag.printsandcuts.createEntry", compact('nepaliDate', 'date', 'receipt_no'));
    }



    public function storeEntry(Request $request)
    {

        $request->validate([

            "receipt_number" => "required",

            "date" => "required",

            "date_np" => "required"

        ]);
        PrintedAndCuttedRollsEntry::create([

            "receipt_number" => $request->receipt_number,

            "date" => $request->date,

            "date_np" => $request->date_np

        ]);
        return $this->index();
    }

    /*************** For Entry *********/

    public function createPrintedRolls($id)
    {

        $data = PrintedAndCuttedRollsEntry::where('id', $id)->first();

        // return $data;
        $fabrics = DB::table('bag_fabric_receive_item_sent_stock as stock')

            ->join('fabrics', 'fabrics.id', '=', 'stock.fabric_id')

            ->select('fabrics.id', 'fabrics.name')

            ->get();

        $groups = Group::where('status', 'active')->get();

        $godams = AutoLoadItemStock::with(['fromGodam' => function ($query) {

            $query->select('id', 'name');
        }])

            ->select('from_godam_id')

            ->distinct()

            ->get();



        $wasteGodams = Godam::where('status', 'active')->get();

        $wastageTypes = Wastages::where('status', 'active')->get();

        return view("admin.bag.printsandcuts.create")->with([

            "data" => $data,

            "fabrics" => $fabrics,

            "groups" => $groups,

            "godams" => $godams,

            "wasteGodams" => $wasteGodams,

            "wastageTypes" => $wastageTypes,

        ]);
    }

    public function getFabric(Request $request)
    {
        $fabric =  BagFabricReceiveItemSentStock::where('roll_no', $request->roll_no)
            ->where('status','Stock')
            ->with(['fabric' => function ($query) {

                $query->select('id', 'name');
            }])
            ->select('fabric_id as fabric_id', 'net_wt', 'gross_wt', 'average','meter')
            ->first();
             if (!$fabric) {
                return response()->json([
                                'error'=>'roll no not in stock or have already been used'
                            ],404);
            }else{
                 return  $fabric;
            }

    }

    public function getDanaGroup(Request $request)
    {

        $danaGroups = AutoLoadItemStock::with(['danaGroup' => function ($query) {

            $query->select('id', 'name');
        }])

            ->select('dana_group_id')

            ->where('from_godam_id', $request->godam_id)

            ->distinct()

            ->get();



        return response()->json([

            'danaGroups' => $danaGroups

        ]);
    }

    public function getDanaName(Request $request)
    {

        $danaNames = AutoLoadItemStock::with(['danaName' => function ($query) {

            $query->select('id', 'name');
        }])
            ->select('dana_name_id')
            //->where('dana_group_id',$request->dana_group_id)
            ->where('from_godam_id', $request->godam_id)
            ->distinct()
            ->get();

        return $danaNames;
    }

    public function getStockQuantity(Request $request)
    {

        $stockQty = AutoLoadItemStock::select('quantity')

            ->where('from_godam_id', $request->godam_id)

            ->where('dana_name_id', $request->dana_name_id)

            ->first();

        return $stockQty;
    }
}
