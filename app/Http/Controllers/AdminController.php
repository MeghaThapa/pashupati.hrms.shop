<?php

namespace App\Http\Controllers;

use App\Charts\ExpenseChart;
use App\Charts\FinishedQtyChart;
use App\Charts\PurchaseChart;
use App\Charts\TransferredQtyChart;
use App\Models\Category;
use App\Models\Expense;
use App\Models\FinishedProduct;
use App\Models\GeneralSetting;
use App\Models\ProcessingProduct;
use App\Models\Purchase;
use App\Models\Staff;
use App\Models\SubCategory;
use App\Models\Supplier;
use App\Models\TransferredProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    // return admin dashboard page
    public function index()
    {
        $stats = (object) ([
            'staff' => Staff::count(),
            'suppliers' => Supplier::count(),
            'categories' => Category::count(),
            'subCats' => SubCategory::count(),
            'purchases' => Purchase::count(),
            'processing' => ProcessingProduct::count(),
            'finished' => FinishedProduct::count(),
            'transferred' => TransferredProduct::count(),
            'expenses' => Expense::count()
        ]);

        // prepare  dataset for charts
        $purchasesByMonth = array();
        $finishedQtyByMonths = array();
        $transferredQtyByMonths = array();
        $expensesByMonth = array();
        for ($i = 1; $i <= 12; $i++) {
            // dataset for purchases
            $monthPurchase = Purchase::where('status', 1)->whereYear('purchase_date', '=', date("Y"))->whereMonth('purchase_date', '=', $i)->sum('total');
            $monthPurchase > 0 ? array_push($purchasesByMonth, $monthPurchase) : array_push($purchasesByMonth, 0);

            // dataset for finished products
            $finishedProducts = FinishedProduct::where('status', 1)->whereYear('finished_date', '=', date("Y"))->whereMonth('finished_date', '=', $i)->get();
            $total = 0;
            foreach ($finishedProducts as $key => $qty) {
                $arrayQuantities = explode(', ', $qty->quantities);
                foreach ($arrayQuantities as $singleQty) {
                    $total += (int)$singleQty;
                }
            }
            $total > 0 ? array_push($finishedQtyByMonths, $total) : array_push($finishedQtyByMonths, 0);

            // dataset for transferred products
            $transferredProducts = TransferredProduct::where('status', 1)->whereYear('transferred_date', '=', date("Y"))->whereMonth('transferred_date', '=', $i)->get();
            $transTotal = 0;
            foreach ($transferredProducts as $key => $transQty) {
                $arrayTransQuantities = explode(', ', $transQty->transferred_quantities);
                foreach ($arrayTransQuantities as $transSingleQty) {
                    $transTotal += (int)$transSingleQty;
                }
            }
            $transTotal > 0 ? array_push($transferredQtyByMonths, $transTotal) : array_push($transferredQtyByMonths, 0);

            // dataset for expenses
            $monthlyExpense = Expense::where('status', 1)->whereYear('expense_date', '=', date("Y"))->whereMonth('expense_date', '=', $i)->sum('amount');
            $monthlyExpense > 0 ? array_push($expensesByMonth, $monthlyExpense) : array_push($expensesByMonth, 0);
        }

        // purchases chart
        $purchasesChart = new PurchaseChart;
        // $purchasesChart->title('Purchases amount by Months', 20,"rgb(0, 0, 0)", true, 'Sen', 'sans-serif');
        $purchasesChart->barwidth(0.0);
        $purchasesChart->displaylegend(false);
        $purchasesChart->labels(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        $purchasesChart->dataset('Purchases by trimester', 'line', $purchasesByMonth)
        ->color('rgba(99,102,241, 1.0)')->backgroundcolor('rgba(99,102,241, 0.2)');

        //finished products chart
        $finishedQtyChart = new FinishedQtyChart;
        $finishedQtyChart->displaylegend(false);
        // $finishedQtyChart->title('Finished quantities by Months', 20, "rgb(0, 0, 0)", true, 'Sen', 'sans-serif');
        $finishedQtyChart->labels(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        $finishedQtyChart->dataset('Finished quantities by trimester', 'bar', $finishedQtyByMonths)
                        ->color('rgba(99,102,241, 1.0)')->backgroundcolor('rgba(99,102,241, 0.2)');

        //transferred products chart
        $transferredQtyChart = new TransferredQtyChart;
        $transferredQtyChart->displaylegend(false);
        // $transferredQtyChart->title('Transferred quantities by Months', 20, "rgb(0, 0, 0)", true, 'Sen', 'sans-serif');
        $transferredQtyChart->labels(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        $transferredQtyChart->dataset('Transferred quantities by trimester', 'bar', $transferredQtyByMonths)
            ->color('rgba(99,102,241, 1.0)')->backgroundcolor('rgba(99,102,241, 0.2)');


        // expense chart
        $expenseChart = new ExpenseChart;
        // $expenseChart->title('Expenses amount by Months', 20, "rgb(0, 0, 0)", true, 'Sen', 'sans-serif');
        $expenseChart->barwidth(0.0);
        $expenseChart->displaylegend(false);
        $expenseChart->labels(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        $expenseChart->dataset('Expenses by trimester', 'line', $expensesByMonth)->color('rgba(99,102,241, 1.0)')->backgroundcolor('rgba(99,102,241, 0.2)');



        return view('admin.dashboard', compact('stats',  'purchasesChart', 'finishedQtyChart', 'transferredQtyChart', 'expenseChart'));
    }

    // return admin profile page
    public function profilePage()
    {
        return view('admin.profile');
    }

    // update admin profile
    public function profileUpdate(Request $request, $email)
    {
        $user = User::where('email', $email)->first();
        $validator = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|max:80|unique:users,name,' . $user->id,
            'password' => $request->password ? 'nullable|string|min:8|max:255' : '',
            'profilePic' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);


        $request->password ? $password = Hash::make($request->password) : $password = $user->password;

        $imageName = $user->profile_picture;
        if (!empty($request->profilePic)) {
            $this->deleteImage('img/profile/' . $user->profile_picture);
            $imageName = $this->uploadImage('img/profile/', $request->profilePic);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $password,
            'profile_picture' => $imageName
        ]);

        return redirect()->route('admin.profile')->withSuccess('Profile updated successfully!');
    }


    // return setup page
    public function setupPage()
    {
        return view('admin.setup.index');
    }

    // return genereal settings page
    public function generalSettings()
    {
        $settings =
            [
                'compnayName' => GeneralSetting::where('key', 'company_name')->firstOrFail()->value,
                'compnayTagline' => GeneralSetting::where('key', 'compnay_tagline')->firstOrFail()->value,
                'email' => GeneralSetting::where('key', 'email_address')->firstOrFail()->value,
                'phone' => GeneralSetting::where('key', 'phone_number')->firstOrFail()->value,
                'address' => GeneralSetting::where('key', 'address')->firstOrFail()->value,
                'currencyName' => GeneralSetting::where('key', 'currency_name')->firstOrFail()->value,
                'currencySymbol' => GeneralSetting::where('key', 'currency_symbol')->firstOrFail()->value,
                'currencyPosition' => GeneralSetting::where('key', 'currency_position')->firstOrFail()->value,
                'timezone' => GeneralSetting::where('key', 'timezone')->firstOrFail()->value,
                'codePefix' => GeneralSetting::where('key', 'purchase_code_prefix')->firstOrFail()->value,


                'processingCodePefix' => GeneralSetting::where('key', 'processing_code_prefix')->firstOrFail()->value,

                'finishedCodePefix' => GeneralSetting::where('key', 'finished_code_prefix')->firstOrFail()->value,

                'transferredCodePefix' => GeneralSetting::where('key', 'transferred_code_prefix')->firstOrFail()->value,

                'startingCode' => GeneralSetting::where('key', 'starting_purchase_code')->firstOrFail()->value,
                'logo' => GeneralSetting::where('key', 'logo')->firstOrFail()->value,
                'smallLogo' => GeneralSetting::where('key', 'small_logo')->firstOrFail()->value,

                'darkLogo' =>GeneralSetting::where('key', 'dark_logo')->first()->value? asset('img') . '/' . GeneralSetting::where('key', 'dark_logo')->first()->value : asset('img/logo-white.svg'),

                'smallDarkLogo' =>GeneralSetting::where('key', 'small_dark_logo')->first()->value ? asset('img') . '/' . GeneralSetting::where('key', 'small_dark_logo')->first()->value : asset('img/small-dark-logo.png'),

                'favicon' => GeneralSetting::where('key', 'favicon')->firstOrFail()->value,
                'copyright' => GeneralSetting::where('key', 'copyright')->firstOrFail()->value,
            ];
        $settings = (object) $settings;
        return view('admin.setup.general', compact('settings'));
    }

    // update general settings
    public function updateGeneralSettings(Request $request)
    {


        $validator = $request->validate([
            'companyName' => 'required|string|max:30',
            'compnayTagline' => 'required|string|max:255|min:3',
            'email' => 'required|string|email|max:80',
            'phone' => 'nullable|numeric',
            'address' => 'required|string|max:255',
            'currencyName' => 'required|string|max:30',
            'currencySymbol' => 'required|string|max:10',
            'currencyPosition' => 'required|string',
            'timezone' => 'nullable|string',
            'purchaseCodePrefix' => 'required|string|max:20',
            'processingCodePrefix' => 'required|string|max:20',
            'finishedCodePrefix' => 'required|string|max:20',
            'transferredCodePefix' => 'required|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'smallLogo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'dark_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'small_dark_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'favicon' => 'nullable|mimes:jpeg,png,jpg,gif,svg,webp,ico|max:1024',
            'copyright' => 'nullable|string|max:255',
        ]);

        // current logo
        $logo = GeneralSetting::where('key', 'logo')->firstOrFail()->value;

        // delete old logo and upload new logo
        if (isset($request->logo)) {
            $imagePath = 'img/';
            if (isset($logo)) {
                $this->deleteImage('img/' . $logo);
            }
            $logo = $this->uploadImage($imagePath, $request->logo);
        }
        // current dark logo
        $dark_logo = GeneralSetting::where('key', 'dark_logo')->firstOrFail()->value;

        // delete old dark logo and upload new dark logo
        if (isset($request->dark_logo)) {
            $imagePath = 'img/';
            if (isset($dark_logo)) {
                $this->deleteImage('img/' . $dark_logo);
            }
            $dark_logo = $this->uploadImage($imagePath, $request->dark_logo);
        }

        // current small logo
        $smallLogo = GeneralSetting::where('key', 'small_logo')->firstOrFail()->value;

        // delete old samll logo and upload new logo
        if (isset($request->smallLogo)) {
            $imagePath = 'img/';
            if (isset($smallLogo)) {
                $this->deleteImage('img/' . $smallLogo);
            }
            $smallLogo = $this->uploadImage($imagePath, $request->smallLogo);
        }

        // current dark small dark logo
        $small_dark_logo = GeneralSetting::where('key', 'small_dark_logo')->firstOrFail()->value;



        // delete  small dark  logo and upload new dark small logo
        if (isset($request->small_dark_logo)) {
            $imagePath = 'img/';
            if (isset($small_dark_logo)) {
                $this->deleteImage('img/' . $small_dark_logo);
            }
            $small_dark_logo = $this->uploadImage($imagePath, $request->small_dark_logo);
        }

        // current favicon
        $favicon = GeneralSetting::where('key', 'favicon')->firstOrFail()->value;
        // delete favicon and upload new icon
        if (isset($request->favicon)) {
            $imagePath = '';
            if (isset($favicon)) {
                $this->deleteImage($favicon);
            }
            $favicon = $this->uploadImage($imagePath, $request->favicon);
        }

        // update general settings
        GeneralSetting::where('key', 'company_name')->firstOrFail()->update(['value' => clean($request->companyName)]);
        GeneralSetting::where('key', 'compnay_tagline')->firstOrFail()->update(['value' => clean($request->compnayTagline)]);
        GeneralSetting::where('key', 'email_address')->firstOrFail()->update(['value' => $request->email]);
        GeneralSetting::where('key', 'phone_number')->firstOrFail()->update(['value' => $request->phone]);
        GeneralSetting::where('key', 'address')->firstOrFail()->update(['value' => clean($request->address)]);
        GeneralSetting::where('key', 'currency_name')->firstOrFail()->update(['value' => $request->currencyName]);
        GeneralSetting::where('key', 'currency_symbol')->firstOrFail()->update(['value' => $request->currencySymbol]);
        GeneralSetting::where('key', 'currency_position')->firstOrFail()->update(['value' => $request->currencyPosition]);
        GeneralSetting::where('key', 'timezone')->firstOrFail()->update(['value' => $request->timezone]);
        GeneralSetting::where('key', 'purchase_code_prefix')->firstOrFail()->update(['value' => $request->purchaseCodePrefix]);
        GeneralSetting::where('key', 'processing_code_prefix')->firstOrFail()->update(['value' => $request->processingCodePrefix]);
        GeneralSetting::where('key', 'finished_code_prefix')->firstOrFail()->update(['value' => $request->finishedCodePrefix]);

        GeneralSetting::where('key', 'transferred_code_prefix')->firstOrFail()->update(['value' => $request->transferredCodePefix]);

        GeneralSetting::where('key', 'logo')->firstOrFail()->update(['value' => $logo]);
        GeneralSetting::where('key', 'small_logo')->firstOrFail()->update(['value' => $smallLogo]);

        // Dark Logo
        GeneralSetting::where('key', 'dark_logo')->firstOrFail()->update(['value' => $dark_logo]);
        GeneralSetting::where('key', 'small_dark_logo')->firstOrFail()->update(['value' => $small_dark_logo]);


        GeneralSetting::where('key', 'favicon')->firstOrFail()->update(['value' => $favicon]);
        GeneralSetting::where('key', 'copyright')->firstOrFail()->update(['value' => clean($request->copyright)]);

        return redirect()->back()->withSuccess('Settings updated successfully!');
    }
}
