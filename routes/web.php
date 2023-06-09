<?php

use App\Http\Controllers\FabricTransferEntryForBagController;
use App\Http\Controllers\InstallHelperController;
use App\Http\Controllers\PrintedAndCuttedRollsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ThemeSettingsContoller;
use App\Http\Controllers\SetupstoreinController;
use App\Http\Controllers\SetupstoreoutController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\StoreinController;
use App\Http\Controllers\StoreoutController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\StoreinDepartmentController;
use App\Http\Controllers\PlacementController;
use App\Http\Controllers\StockImportController;
use App\Http\Controllers\ProcessingCategoryAndSubsController;
use App\Http\Controllers\ProcessingSubcatController;
use App\Http\Controllers\RawMaterialStockController;
use App\Http\Controllers\AutoloadController;
use App\Http\Controllers\TapeEntryController;
use App\Http\Controllers\TapeEntryStockController;
use App\Http\Controllers\AutoloadItemsController;
use App\Http\Controllers\AutoLoadStockController;
use App\Http\Controllers\StoreinTypeController;

use App\Http\Controllers\FabricSendReceiveController;
use App\Http\Controllers\StoreinCategoryController;
use App\Http\Controllers\ItemsOfStoreinController;

use App\Http\Controllers\FabricNonWovenController;
use App\Http\Controllers\NonWovenStockController;

use App\Http\Controllers\FabricStockController;

use App\Http\Controllers\WastageController;
use App\Http\Controllers\WastageStockController;
use App\Http\Controllers\GodamController;
use App\Http\Controllers\StoreoutDepartmentController;
use App\Http\Controllers\Tripal\TripalController;
use App\Http\Controllers\Tripal\SingleTripalStockController;
use App\Http\Controllers\Tripal\DoubleTripalStockController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\BagBrandController;
use App\Http\Controllers\PrintingAndCuttingBagItemController;
use App\Http\Controllers\PrintsAndCutsDanaConsumptionController;
// brandBag.store
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// installer routes
Route::group(['prefix' => 'install',  'middleware' => ['web', 'install', 'isVerified']], function () {
    Route::get('/', [InstallHelperController::class, 'getPurchaseCodeVerifyPage'])->name('verify');
    Route::post('verify', [InstallHelperController::class, 'verifyPurchaseCode'])->name('verifyPurchaseCode');
});

// redirect to login page
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('artisandone',function(){
   // return bcrypt('matinsoftech');
    //$2y$10$sGQ5cB84msZ71W88Nf1Ji.nnPT2oF9ZQUZThLFRS7GFuNQG1sCddi
    // \Artisan::call("make:controller AutoloadItemsController");
    // \Artisan::call("make:model AutoloadItems");
    // \Artisan::call('make:migration create_autoload_items_stock_table');
    // \Artisan::call('make:migration add_department_id_to_processing_steps_table --table=processing_steps');


    // \Artisan::call("m:migrationcreate_processing_categories_and_subcategories_table");
    // if($artisan){
    //     return "done";
    // }else{
    //     return "not done";
    // }
    // \Artisan::call('migrate:refresh --path=/database/migrations/2023_05_25_094556_add_department_id_to_processing_steps_table.php');
});

// admin auth routes
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'admin',  'middleware' => ['auth']], function () {
    // admin dashbaord route
    Route::get('dashboard', 'AdminController@index')->name('dashboard');

    // admin profile route
    Route::get('profile', 'AdminController@profilePage')->name('admin.profile');

    // admin profile update route
    Route::put('profile/{email}', 'AdminController@profileUpdate')->name('admin.profile.update');

    // setup route
    Route::get('setup', 'AdminController@setupPage')->name('admin.setup');

    // general settings routes
    Route::get('general-settings', 'AdminController@generalSettings')->name('admin.setup.general');
    Route::post('general-settings', 'AdminController@updateGeneralSettings')->name('admin.setup.general.update');

    // units routes
    Route::resource('units', 'UnitController', [
        'names' => [
            'index' => 'units.index',
            'create' => 'units.create',
            'store' => 'units.store',
            'edit' => 'units.edit',
            'update' => 'units.update'
        ]
    ]);
    Route::get('units/{slug}/staus', 'UnitController@changeStatus')->name('units.status');
    Route::get('units/{slug}/delete', 'UnitController@destroy')->name('units.delete');

    // shift routes
    Route::get('shift/index', 'ShiftController@index')->name('shift.index');
    Route::get('shift/create', 'ShiftController@create')->name('shift.create');
    Route::post('shift/store', 'ShiftController@store')->name('shift.store');
    Route::post('shift/update', 'ShiftController@update')->name('shift.update');
    Route::get('/shift/getShiftData/{id}', 'ShiftController@getShiftData')->name('shift.getShiftData');
    Route::delete('/shift/delete/{id}', 'ShiftController@delete')->name('shift.delete');

    //prpocessing categories
    Route::get('processing/categories',[ProcessingCategoryAndSubsController::class,"categoryindex"])->name('processing.categories'); //index for p-cat
    Route::get('processing/categories/create',[ProcessingCategoryAndSubsController::class,"categorycreate"])->name('processing.categories.create');
    Route::post('processing/categories/store',[ProcessingCategoryAndSubsController::class,"categorystore"])->name('processing.categories.store');

    //Tax Route
    Route::resource('tax', 'TaxController', [
        'names' => [
            'index' => 'tax.index',
            'create' => 'tax.create',
            'store' => 'tax.store',
            'edit' => 'tax.edit',
            'update' => 'tax.update'
        ]
    ]);
    Route::get('tax/{slug}/staus', 'TaxController@changeStatus')->name('tax.status');
    Route::get('tax/{slug}/delete', 'TaxController@destroy')->name('tax.delete');

    //StoreinDepartment Route
     Route::post('storeinDepartment/store', 'StoreinDepartmentController@store')->name('storeinDepartment.store');

    //Department Route

    Route::post('storeDepartmentFromModel', 'DepartmentController@storeDepartmentFromModel')->name('department.storeDepartmentFromModel');
    Route::resource('department', 'DepartmentController', [
        'names' => [
            'index' => 'department.index',
            'create' => 'department.create',
            'store' => 'department.store',
            'edit' => 'department.edit',
            'update' => 'department.update'
        ]
    ]);
    Route::get('department/{slug}/staus', 'DepartmentController@changeStatus')->name('department.status');
    Route::get('department/{slug}/delete', 'DepartmentController@destroy')->name('department.delete');

    // payment methods routes
    Route::resource('payment-methods', 'PaymentMethodController', [
        'names' => [
            'index' => 'payments.index',
            'create' => 'payments.create',
            'store' => 'payments.store',
            'edit' => 'payments.edit',
            'update' => 'payments.update'
        ]
    ]);
    Route::get('payment-methods/{slug}/staus', 'PaymentMethodController@changeStatus')->name('payments.status');
    Route::get('payment-methods/{slug}/delete', 'PaymentMethodController@destroy')->name('payments.delete');

    // sizes routes
    Route::resource('sizes', 'SizeController', [
        'names' => [
            'index' => 'sizes.index',
            'create' => 'sizes.create',
            'store' => 'sizes.store',
            'edit' => 'sizes.edit',
            'update' => 'sizes.update'
        ]
    ]);
    Route::post('sizes/create', 'SizeController@store')->name('sizes.store');
    Route::get('sizes/{slug}/staus', 'SizeController@changeStatus')->name('sizes.status');
    Route::get('sizes/{slug}/delete', 'SizeController@destroy')->name('sizes.delete');

    // Setup Storein routes
    Route::resource('storeinsetup', 'SetupstoreinController', [
        'names' => [
            'index' => 'storeinsetup.index',
            'create' => 'storeinsetup.create',
            'store' => 'storeinsetup.store',
            'edit' => 'storeinsetup.edit',
            'update' => 'storeinsetup.update'
        ]
    ]);


    Route::post('storeinsetup/create', 'SetupstoreinController@store')->name('storeinsetup.store');
    Route::get('storeinsetup/{slug}/staus', 'SetupstoreinController@changeStatus')->name('storeinsetup.status');
    Route::get('storeinsetup/{slug}/delete', 'SetupstoreinController@destroy')->name('storeinsetup.delete');

    //autoloadStock
    Route::get('autoloadStock/index', 'AutoLoadStockController@index')->name('autoloadStock.index');
    Route::post('autoloadStock/filterAccGodam', 'AutoLoadStockController@filterAccGodam')->name('autoloadStock.filterAccGodam');

    //RawMaterial
    //recent
    Route::delete('rawMaterial/delete/{rawMaterial_id}', 'RawMaterialController@delete')->name('rawMaterial.delete');
    Route::get('rawMaterial/saveEntireRawMaterial/{rawMaterial_id}', 'RawMaterialController@saveEntireRawMaterial')->name('rawMaterial.saveEntireRawMaterial');
    Route::get('rawMaterial/godamTransferDetail', 'RawMaterialController@godamTransferDetail')->name('rawMaterial.godamTransferDetail');

    Route::post('rawMaterial/filterGodamTransferAccGodam', 'RawMaterialController@filterGodamTransferAccGodam')->name('rawMaterial.filterGodamTransferAccGodam');


    //for gana name from rawmaterial stock
    Route::get('rawMaterial/getStock', 'RawMaterialController@getStock')->name('rawMaterial.getStock');

    Route::get('rawMaterial/getDanaGroupDanaNameFromRawMStock/{danaGroup_id}/{godam_id}', 'RawMaterialController@getDanaGroupDanaNameFromRawMStock')->name('rawMaterial.getDanaGroupDanaNameFromRawMStock');

    Route::get('rawMaterial/index', 'RawMaterialController@index')->name('rawMaterial.index');
    Route::get('rawMaterial/create', 'RawMaterialController@create')->name('rawMaterial.create');
    Route::post('rawMaterial/store', 'RawMaterialController@store')->name('rawMaterial.store');
    Route::get('rawMaterial/edit/{rawMaterial_id}', 'RawMaterialController@edit')->name('rawMaterial.edit');
    Route::post('rawMaterial/update/{rawMaterial_id}', 'RawMaterialController@update')->name('rawMaterial.update');
    Route::get('rawMaterial/createRawMaterialItems/{rawMaterial_id}', 'RawMaterialController@createRawMaterialItems')->name('rawMaterial.createRawMaterialItems');
    Route::get('rawMaterial/dataTable', 'RawMaterialController@dataTable')->name('rawMaterial.dataTable');
    //Rawmaterial stock
    Route::get('rawMaterial/stock/index',[RawMaterialStockController::class,'index'])->name('rawMaterialStock.index');
    Route::get('rawMaterial/stock/danaGroupFilter',[RawMaterialStockController::class,'danaGroupFilter'])->name('rawMaterialStock.danaGroupFilter');
    Route::get('rawMaterial/stock/danaNameFilter',[RawMaterialStockController::class,'danaNameFilter'])->name('rawMaterialStock.danaNameFilter');
    Route::get('rawMaterialStock/filterAccDanaName/{danaName_id}',[RawMaterialStockController::class,'filterAccDanaName'])->name('rawMaterialStock.filterAccDanaName');
    Route::get('rawMaterialStock/filterAccDanaGroup/{danaGroup_id}',[RawMaterialStockController::class,'filterAccDanaGroup'])->name('rawMaterialStock.filterAccDanaGroup');
    Route::post('rawMaterialStock/filterStocks',[RawMaterialStockController::class,'filterStocks'])->name('rawmaterial.filterStocks');


    //Godam
    Route::get('godam/index',[GodamController::class,'index'])->name('godam.index');
    Route::get('godam/create',[GodamController::class,'create'])->name('godam.create');
    Route::post('godam/store',[GodamController::class,'store'])->name('godam.store');
    Route::get('godam/edit/{godam_id}',[GodamController::class,'edit'])->name('godam.edit');
    Route::post('godam/update/{godam_id}',[GodamController::class,'update'])->name('godam.update');
    Route::get('godam/delete/{godam_id}',[GodamController::class,'delete'])->name('godam.delete');


    //RawMaterial Items
     Route::post('rawMaterialItem/store', 'RawMaterialItemController@store')->name('rawMaterialItem.store');
    Route::get('rawMaterialItem/getRawMaterialItemsData/{rawMaterial_id}', 'RawMaterialItemController@getRawMaterialItemsData')->name('rawMaterialItem.getRawMaterialItemsData');
    Route::get('rawMaterialItem/getEditRawMaterialItemData/{rawMaterialItem_id}', 'RawMaterialItemController@getEditRawMaterialItemData')->name('rawMaterialItem.getEditRawMaterialItemData');
    Route::post('rawMaterialItem/update', 'RawMaterialItemController@update')->name('rawMaterialItem.update');
    Route::delete('rawMaterialItem/delete/{id}', 'RawMaterialItemController@delete')->name('rawMaterialItem.delete');
    Route::get('rawMaterial/getDanaGroupDanaName/{danaGroup_id}', 'RawMaterialController@getDanaGroupDanaName')->name('rawMaterial.getDanaGroupDanaName');
    // Dana Group
    Route::post('danaGroup/store', 'DanaGroupController@store')->name('danaGroup.store');

    //Dana Name
    Route::post('danaName/store', 'DanaNameController@store')->name('danaName.store');


    // Setup Storeout routes
    Route::resource('storeoutsetup', 'SetupstoreoutController', [
        'names' => [
            'index' => 'storeoutsetup.index',
            'create' => 'storeoutsetup.create',
            'store' => 'storeoutsetup.store',
            'edit' => 'storeoutsetup.edit',
            'update' => 'storeoutsetup.update'
        ]
    ]);

    Route::post('storeoutsetup/create', 'SetupstoreoutController@store')->name('storeoutsetup.store');
    Route::get('storeoutsetup/{slug}/staus', 'SetupstoreoutController@changeStatus')->name('storeoutsetup.status');
    Route::get('storeoutsetup/{slug}/delete', 'SetupstoreoutController@destroy')->name('storeoutsetup.delete');

    // processing steps routes
    Route::resource('processing-steps', 'ProcessingStepController', [
        'names' => [
            'index' => 'processing-steps.index',
            'create' => 'processing-steps.create',
            'store' => 'processing-steps.store',
            'edit' => 'processing-steps.edit',
            'update' => 'processing-steps.update'
        ]
    ]);
    Route::get('processing-steps/{slug}/staus', 'ProcessingStepController@changeStatus')->name('processing-steps.status');
    Route::get('processing-steps/{slug}/delete', 'ProcessingStepController@destroy')->name('processing-steps.delete');

     //Autoloader
    Route::get('autoload/index',[AutoloadController::class,'index'])->name('autoload.index');
    Route::get('autoload/create',[AutoloadController::class,'create'])->name('autoload.create');
    Route::get('autoload/getReceiptNo',[AutoloadController::class,'getReceiptNo'])->name('autoLoad.getReceiptNo');
    Route::get('autoload/createAutoloadItem/{autoload_id}',[AutoloadController::class,'createAutoloadItem'])->name('autoLoad.createAutoloadItem');
    Route::post('autoload/store',[AutoloadController::class,'store'])->name('autoLoad.store');
    Route::get('autoload/getPlantTypePlantName/{plantType_id}',[AutoloadController::class,'getPlantTypePlantName'])->name('autoload.getPlantTypePlantName');
    Route::get('autoload/getDanaGroupDanaName/{danaGroup_id}/{fromGodam_id}',[AutoloadController::class,'getDanaGroupDanaName'])->name('autoload.getDanaGroupDanaName');
    Route::get('autoload/getPlantTypeAccGodam/{godam_id}',[AutoloadController::class,'getPlantTypeAccGodam'])->name('autoload.getPlantTypeAccGodam');

    Route::get('autoload/saveEntireAutoload/{autoload_id}',[AutoloadController::class,'saveEntireAutoload'])->name('autoLoad.saveEntireAutoload');


    Route::get('autoload/getEditDanaGroupAccToGodam/{department_id}',[AutoloadController::class,'getEditDanaGroupAccToGodam'])->name('autoLoad.getEditDanaGroupAccToGodam');
    Route::get('autoload/getEditDanaGroupDanaName/{danaGroup_id}/{fromGodam_id}',[AutoloadController::class,'getEditDanaGroupDanaName'])->name('autoLoad.getEditDanaGroupDanaName');
    Route::get('autoload/dataTable',[AutoloadController::class,'dataTable'])->name('autoLoad.dataTable');
    Route::get('autoload/getEditItemData/{autoLoad_id}',[AutoloadController::class,'getEditItemData'])->name('autoLoad.getEditItemData');
    Route::post('autoload/update',[AutoloadController::class,'update'])->name('autoload.update');
    Route::delete('autoload/delete/{autoload_id}',[AutoloadController::class,'delete'])->name('autoload.delete');



     //AutoloadItemF
   Route::post('autoloadItem/store',[AutoloadItemsController::class,'store'])->name('autoloadItem.store');
    Route::get('autoloadItem/getAutoloadItemsData/{autoload_id}',[AutoloadItemsController::class,'getAutoloadItemsData'])->name('autoloadItem.getAutoloadItemsData');
    Route::get('autoloadItem/getEditAutoloadItemData/{autoloadItem_id}',[AutoloadItemsController::class,'getEditAutoloadItemData'])->name('autoLoadItem.getEditAutoloadItemData');
    Route::post('autoloadItem/update',[AutoloadItemsController::class,'update'])->name('autoloadItem.update');
    Route::delete('autoloadItem/delete/{autoloadItem_id}',[AutoloadItemsController::class,'delete'])->name('autoLoadItem.delete');



    Route::get('autoloadItem/getDanaGroupAccToGodam/{godam_id}',[AutoloadController::class,'getDanaGroupAccToGodam'])->name('autoload.getDanaGroupAccToGodam');



    //Processing sub-category route

    Route::resource('processing-subcat', 'ProcessingSubcatController', [
        'names' => [
            'index' => 'processing-subcat.index',
            'create' => 'processing-subcat.create',
            'store' => 'processing-subcat.store',
        ]
    ]);
   Route::get('processing-subcat/edit/{processingSubCatId}','ProcessingSubcatController@edit')->name('processing-subcat.edit');
    Route::get('processing-subcat/{slug}/staus', 'ProcessingSubcatController@changeStatus')->name('processing-subcat.status');
    Route::get('processing-subcat/{slug}/delete', 'ProcessingSubcatController@destroy')->name('processing-subcat.delete');
    Route::get('processing-subcat/getProcessingStepsAccDept/{godam_id}', 'ProcessingSubcatController@getProcessingStepsAccDept')->name('processing-subcat.getProcessingStepsAccDept');
    Route::post('processing-subcat/update/{processingSubCatId}', 'ProcessingSubcatController@update')->name('processing-subcat.update');

    // showrooms routes
    Route::resource('showrooms', 'ShowroomController', [
        'names' => [
            'index' => 'showrooms.index',
            'create' => 'showrooms.create',
            'store' => 'showrooms.store',
            'show' => 'showrooms.show',
            'edit' => 'showrooms.edit',
            'update' => 'showrooms.update'
        ]
    ]);
    Route::get('showrooms/{slug}/staus', 'ShowroomController@changeStatus')->name('showrooms.status');
    Route::get('showrooms/{slug}/delete', 'ShowroomController@destroy')->name('showrooms.delete');

    // users routes
    Route::get('/users/pdf', 'UserController@createPDF')->name('users.pdf');
    Route::resource('users', 'UserController', [
        'names' => [
            'index' => 'users.index',
            'create' => 'users.create',
            'store' => 'users.store',
            'show' => 'users.show',
            'edit' => 'users.edit',
            'update' => 'users.update',
        ]
    ]);
    Route::get('users/{slug}/staus', 'UserController@changeStatus')->name('users.status');
    Route::get('users/{id}/delete', 'UserController@destroy')->name('users.delete');


    // expenses categories routes
    Route::get('/expense-categories/pdf', 'ExpenseCategoryController@createPDF')->name('expCategories.pdf');
    Route::resource('expense-categories', 'ExpenseCategoryController', [
        'names' => [
            'index' => 'expCategories.index',
            'create' => 'expCategories.create',
            'store' => 'expCategories.store',
            'show' => 'expCategories.show',
            'edit' => 'expCategories.edit',
            'update' => 'expCategories.update',
        ]
    ]);
    Route::get('expense-categories/{slug}/staus', 'ExpenseCategoryController@changeStatus')->name('expCategories.status');
    Route::get('expense-categories/{slug}/delete', 'ExpenseCategoryController@destroy')->name('expCategories.delete');

    // expenses routes
    Route::get('/expenses/pdf', 'ExpenseController@createPDF')->name('expenses.pdf');
    Route::resource('expenses', 'ExpenseController', [
        'names' => [
            'index' => 'expenses.index',
            'create' => 'expenses.create',
            'store' => 'expenses.store',
            'show' => 'expenses.show',
            'edit' => 'expenses.edit',
            'update' => 'expenses.update',
        ]
    ]);
    Route::get('expenses/{slug}/staus', 'ExpenseController@changeStatus')->name('expenses.status');
    Route::get('expenses/{slug}/delete', 'ExpenseController@destroy')->name('expenses.delete');


    // staff routes
    Route::get('/staff/pdf', 'StaffController@createPDF')->name('staff.pdf');
    Route::resource('staff', 'StaffController', [
        'names' => [
            'index' => 'staff.index',
            'create' => 'staff.create',
            'store' => 'staff.store',
            'show' => 'staff.show',
            'edit' => 'staff.edit',
            'update' => 'staff.update',
        ]
    ]);
    Route::get('staff/{slug}/staus', 'StaffController@changeStatus')->name('staff.status');
    Route::get('staff/{id}/delete', 'StaffController@destroy')->name('staff.delete');

    // supplier routes
    Route::get('/suppliers/pdf', 'SupplierController@createPDF')->name('suppliers.pdf');
    Route::resource('suppliers', 'SupplierController', [
        'names' => [
            'index' => 'suppliers.index',
            'create' => 'suppliers.create',
            'store' => 'suppliers.store',
            'show' => 'suppliers.show',
            'edit' => 'suppliers.edit',
            'update' => 'suppliers.update',
        ]
    ]);
    Route::get('suppliers/{id}/status', 'SupplierController@changeStatus')->name('suppliers.status');
    Route::get('suppliers/{id}/delete', 'SupplierController@destroy')->name('suppliers.delete');
    //Storein Category Routes
     Route::post('/storeinCategory/store', 'StoreinCategoryController@store')->name('storeinCategory.store');
    // categories route
    Route::get('/categories/pdf', 'CategoryController@createPDF')->name('categories.pdf');
    Route::resource('categories', 'CategoryController', [
        'names' => [
            'index' => 'categories.index',
            'create' => 'categories.create',
            'store' => 'categories.store',
            'edit' => 'categories.edit',
            'update' => 'categories.update',
        ]
    ]);
    Route::get('categories/{id}/status', 'CategoryController@changeStatus')->name('categories.status');
    Route::get('categories/{id}/delete', 'CategoryController@destroy')->name('categories.delete');

    // sub categories route
    Route::get('/sub-categories/pdf', 'SubCategoryController@createPDF')->name('subCategories.pdf');
    Route::resource('sub-categories', 'SubCategoryController', [
        'names' => [
            'index' => 'subCategories.index',
            'create' => 'subCategories.create',
            'store' => 'subCategories.store',
            'edit' => 'subCategories.edit',
            'update' => 'subCategories.update',
        ]
    ]);
    Route::get('sub-categories/{id}/status', 'SubCategoryController@changeStatus')->name('subCategories.status');
    Route::get('sub-categories/{id}/delete', 'SubCategoryController@destroy')->name('subCategories.delete');

    //fabric send receive contoller
    /************* aile baki xa **************/
        Route::get('/fabricSendReceive/create', 'FabricSendReceiveController@create')->name('fabricSendReceive.create');
        Route::post('/fabricSendReceive/store', 'FabricSendReceiveController@store')->name('fabricSendReceive.store');
    /************* aile baki xa **************/
    Route::get('/fabricSendReceive/index', 'FabricSendReceiveController@index')->name('fabricSendReceive.index');
    Route::get('/fabricSendReceive/ajax/get/planttype/{id}', 'FabricSendReceiveController@getplanttype')->name('fabricSendReceive.get.planttype');
    Route::get('/fabricSendReceive/ajax/get/plantname/{id}', 'FabricSendReceiveController@getplantname')->name('fabricSendReceive.get.plantname');
    Route::get('/fabricSendReceive/ajax/get/fabrics', 'FabricSendReceiveController@getfabrics')->name('fabricSendReceive.get.fabrics');
    Route::post("fabric/send/unlaminated/store",[FabricSendReceiveController::class,'sendunlaminated'])->name("fabricSendReceive.send.unlaminated");
    Route::get('fabric/send/unlaminated/delete/{id}',[FabricSendReceiveController::class,'sendunlaminateddelete'])->name('fabricSendReceive.send.unlaminated.delete');
    //getting unlaminated into form
    Route::get('fabric/get/unlaminated',[FabricSendReceiveController::class,'getunlaminated'])->name('fabricSendReceive.get.unlaminated');
    Route::post('fabric/store/laminated',[FabricSendReceiveController::class,'storelaminated'])->name('fabricSendReceive.store.laminated');
    //sending for lamination
    // Route::post('fabric/store/laminated',[FabricSendReceiveController::class,'storelaminated'])->name('fabricSendReceive.store.laminated');

    Route::get('discard',[FabricSendReceiveController::class,'discard'])->name('discard');

    Route::get('fabricSendReceive/compare/lamandunlam',[FabricSendReceiveController::class,'comparelamandunlam'])->name('fabricSendReceive.compare.lamandunlam');
    Route::post('subtract/dana/from/autoloader',[FabricSendReceiveController::class,'subtractdanafromautoloder'])->name("subtract.dana.from.autoloder");
    Route::post('final/submit/fsr',[FabricSendReceiveController::class,'finalsubmitfsr'])->name("final.submit.fsr");

    // fabric_group route
    Route::get('/fabrics/pdf', 'FabricController@createPDF')->name('fabrics.pdf');
    Route::post('/fabrics/discard', 'FabricController@discard')->name('fabrics.discard');
    Route::resource('fabrics', 'FabricController', [
        'names' => [
            'index' => 'fabrics.index',
            'create' => 'fabrics.create',
            'store' => 'fabrics.store',
            'edit' => 'fabrics.edit',
            'update' => 'fabrics.update',
        ]
    ]);
    Route::get('fabrics/{id}/status', 'FabricController@changeStatus')->name('fabrics.status');
    Route::get('fabrics/{id}/delete', 'FabricController@destroy')->name('fabrics.delete');

    Route::get('/fabrics/pdf', 'FabricController@createPDF')->name('fabrics.detailStore');

    Route::post('fabric/detail','FabricController@fabricDetail')->name("fabricDetail");

    //fabric stock

    Route::get('fabrics/getstock/index',[FabricStockController::class,'index'])->name('fabric-stock.index');

    //tripal
    Route::resource('tripal', 'Tripal\TripalController', [
        'names' => [
            'index' => 'tripal.index',
            'create' => 'tripal.create',
            'store' => 'tripal.store',
            'edit' => 'tripal.edit',
            'update' => 'tripal.update',
        ]
    ]);
    Route::get('tripals/{id}/status', 'Tripal\TripalController@changeStatus')->name('tripal.status');
    Route::get('tripals/{id}/delete', 'Tripal\TripalController@destroy')->name('tripal.delete');

    //get fabricdata in tripal

    Route::get('tripal/getFabric/List', 'Tripal\TripalController@getfabrics')->name('tripal.getFabric');

    Route::post('tripal/store', 'Tripal\TripalController@store')->name('tripal.store');

    Route::get('tripal/getUnlamSingleLam/List','Tripal\TripalController@getUnlamSingleLam')->name('tripal.getUnlamSingleLam');

    Route::post('tripal/wastage/submit','Tripal\TripalController@getWastageStore')->name("tripal.wastage.submit");

    //singletripal stock
    Route::get('single-tripal/getstock/index',[SingleTripalStockController::class,'index'])->name('singletripal-stock.index');

    //doubletripal stock
    Route::get('double-tripal/getstock/index',[DoubleTripalStockController::class,'index'])->name('doubletripal-stock.index');



    //double laminated tripal

    Route::resource('doubletripal', 'Tripal\DoubleTripalController', [
        'names' => [
            'index' => 'doubletripal.index',
            'create' => 'doubletripal.create',
            'store' => 'doubletripal.store',
            'edit' => 'doubletripal.edit',
            'update' => 'doubletripal.update',
        ]
    ]);


    Route::get('doubletripal/getSingleLaminatedFabric/List','Tripal\DoubleTripalController@getSingleLamFabric')->name('doubletripal.getSingleLaminatedFabric');

    Route::get('doubletripals/{id}/status', 'Tripal\DoubleTripalController@changeStatus')->name('doubletripal.status');
    Route::get('doubletripals/{id}/delete', 'Tripal\DoubleTripalController@destroy')->name('doubletripal.delete');

    Route::get('doubletripals/getUnlamSingleDoubleLam/List', 'Tripal\DoubleTripalController@getUnlamSingleDoubleLam')->name('doubletripal.getUnlamSingleDoubleLam');


    Route::post('doubletripal/wastage/submit','Tripal\DoubleTripalController@getWastageStore')->name("doubletripal.wastage.submit");
    //for checking the quantity of data

    Route::post('dana/autoload/checkQuantity', 'Tripal\TripalController@checkAutoloadQuantity')->name('dana.autoload.checkAutoloadQuantity');



    //fabric send and receive



     // fabric_group route

    Route::resource('nonwovenfabrics', 'FabricNonWovenController', [
        'names' => [
            'index' => 'nonwovenfabrics.index',
            'create' => 'nonwovenfabrics.create',
            'store' => 'nonwovenfabrics.store',
            'edit' => 'nonwovenfabrics.edit',
            'update' => 'nonwovenfabrics.update',
        ]
    ]);

    Route::get('nonwovenfabrics/{id}/status', 'FabricNonWovenController@changeStatus')->name('nonwovenfabrics.status');
    Route::get('nonwovenfabrics/{id}/delete', 'FabricNonWovenController@destroy')->name('nonwovenfabrics.delete');

    Route::resource('nonwovenfabrics-receiveentry', 'FabricNonWovenReceiveEntryController', [
        'names' => [
            'index' => 'nonwovenfabrics-receiveentry.index',
            'create' => 'nonwovenfabrics-receiveentry.create',
            'store' => 'nonwovenfabrics-receiveentry.store',
            'edit' => 'nonwovenfabrics-receiveentry.edit',
            'update' => 'nonwovenfabrics-receiveentry.update',
        ]
    ]);

    Route::post('nonwovenfabrics-receiveentry/store', 'FabricNonWovenReceiveEntryController@storeWaste')->name('storeWastage');

    //getnonwovenreceiveentries
    Route::get('nonwovenfabrics-receiveentry/getReceiveEntries/list', 'FabricNonWovenReceiveEntryController@getnonwovenentries')->name('nonwovenfabric.getReceiveEntryData');

    Route::get('nonwovenfabrics/{id}/status', 'FabricNonWovenController@changeStatus')->name('nonwovenfabrics.status');

    Route::get('nonwovenfabrics-receiveentry/{id}/status', 'FabricNonWovenReceiveEntryController@changeStatus')->name('nonwovenfabrics-receiveentry.status');
    Route::get('nonwovenfabrics-receiveentry/{id}/delete', 'FabricNonWovenReceiveEntryController@destroy')->name('nonwovenfabrics-receiveentry.delete');
    Route::post('nonwovenfabrics-receiveentry/getDataList', 'FabricNonWovenReceiveEntryController@getDataList')->name('getDataList');

    Route::post('nonwovenfabric/getFabricNameList', 'FabricNonWovenReceiveEntryController@getFabricNameList')->name('getFabricNameList');
    Route::post('nonwovenfabric/getFabricNameColorList', 'FabricNonWovenReceiveEntryController@getFabricNameColorList')->name('getFabricNameColorList');

    Route::post('nonwovenfabrics-receiveentry/getDanaList', 'FabricNonWovenReceiveEntryController@getDanaList')->name('getDanaList');

    //nonwoven stock

    Route::get('nonwovenfabrics-receiveentry/getstock/index',[NonWovenStockController::class,'index'])->name('nonwovenfabrics-receiveentrystock.index');
     // Route::post('nonwovenfabrics/getstock/filterStocks',[NonWovenStockController::class,'filterStock'])->name('tapeentry-stock.filterStock');


    Route::post('department/getPlantTypeList', 'FabricNonWovenReceiveEntryController@getPlantTypeList')->name('getPlantTypeList');
     Route::post('department/getPlantNameList', 'FabricNonWovenReceiveEntryController@getPlantNameList')->name('getPlantNameList');

    // fabric_group route
    Route::get('/fabric-groups/pdf', 'FabricGroupController@createPDF')->name('fabric-groups.pdf');
    Route::resource('fabric-groups', 'FabricGroupController', [
        'names' => [
            'index' => 'fabric-groups.index',
            'create' => 'fabric-groups.create',
            'store' => 'fabric-groups.store',
            'edit' => 'fabric-groups.edit',
            'update' => 'fabric-groups.update',
        ]
    ]);
    Route::get('fabric-groups/{id}/status', 'FabricGroupController@changeStatus')->name('fabric-groups.status');
    Route::get('fabric-groups/{id}/delete', 'FabricGroupController@destroy')->name('fabric-groups.delete');
//Storein Item route
    Route::post('storeinItems/store', 'ItemsOfStoreinController@store')->name('storeinItems.store');

    // Items route
    Route::resource('items', 'ItemController', [
        'names' => [
            'index' => 'items.index',
            'create' => 'items.create',
            'store' => 'items.store',
            'edit' => 'items.edit',
            'update' => 'items.update',
        ]
    ]);
    Route::get('items/{id}/status', 'ItemController@changeStatus')->name('items.status');
    Route::get('items/{id}/delete', 'ItemController@destroy')->name('items.delete');

    // purchases route
    Route::get('/purchases/pdf', 'PurchaseController@createPDF')->name('purchases.pdf');
    Route::resource('purchases', 'PurchaseController', [
        'names' => [
            'index' => 'purchases.index',
            'create' => 'purchases.create',
            'store' => 'purchases.store',
            'show' => 'purchases.show',
            'edit' => 'purchases.edit',
            'update' => 'purchases.update',
        ]
    ]);
    Route::get('purchases/{code}/invoice', 'PurchaseController@getInvoice')->name('purchases.invoice');
    Route::get('purchases/{code}/status', 'PurchaseController@changeStatus')->name('purchases.status');
    Route::post('/purchase-products', 'PurchaseController@purchaseProducts')->name('purchase.purchaseProducts');
    Route::get('purchases/{code}/delete', 'PurchaseController@destroy')->name('purchases.delete');


    // return purchases route
    Route::get('/return-purchases/pdf', 'PurchaseReturnController@createPDF')->name('purchaseReturn.pdf');
    Route::resource('return-purchases', 'PurchaseReturnController', [
        'names' => [
            'index' => 'purchaseReturn.index',
            'create' => 'purchaseReturn.create',
            'store' => 'purchaseReturn.store',
            'show' => 'purchaseReturn.show',
            'edit' => 'purchaseReturn.edit',
            'update' => 'purchaseReturn.update',
        ]
    ]);
    Route::get('return-purchases/{code}/status', 'PurchaseReturnController@changeStatus')->name('purchaseReturn.status');
    Route::get('return-purchases/{code}/delete', 'PurchaseReturnController@destroy')->name('purchaseReturn.delete');


    // damage purchases route
    Route::get('/damage-purchases/pdf', 'PurchaseDamageController@createPDF')->name('purchaseDamage.pdf');
    Route::resource('damage-purchases', 'PurchaseDamageController', [
        'names' => [
            'index' => 'purchaseDamage.index',
            'create' => 'purchaseDamage.create',
            'store' => 'purchaseDamage.store',
            'show' => 'purchaseDamage.show',
            'edit' => 'purchaseDamage.edit',
            'update' => 'purchaseDamage.update',
        ]
    ]);
    Route::get('damage-purchases/{code}/status', 'PurchaseDamageController@changeStatus')->name('purchaseDamage.status');
    Route::get('damage-purchases/{code}/delete', 'PurchaseDamageController@destroy')->name('purchaseDamage.delete');

    // purchase inventory route
    Route::get('/purchase-inventory/pdf', 'PurchaseInventoryController@createPDF')->name('purchaseInventory.pdf');
    Route::resource('purchase-inventory', 'PurchaseInventoryController', [
        'names' => [
            'index' => 'purchaseInventory.index',
        ]
    ]);

    // processing products route
    Route::get('/processing-products/pdf', 'ProcessingProductController@createPDF')->name('processing.pdf');
    Route::resource('processing-products', 'ProcessingProductController', [
        'names' => [
            'index' => 'processing.index',
            'create' => 'processing.create',
            'store' => 'processing.store',
            'show' => 'processing.show',
            'edit' => 'processing.edit',
            'update' => 'processing.update',
        ]
    ]);
    Route::get('processing-products/{slug}/status', 'ProcessingProductController@changeStatus')->name('processing.status');
    Route::get('processing-products/{slug}/delete', 'ProcessingProductController@destroy')->name('processing.delete');

    // finished products route
    Route::get('/finished-products/pdf', 'FinishedProductController@createPDF')->name('finished.pdf');
    Route::post('/sizes', 'FinishedProductController@productSizes')->name('finished.sizes');
    Route::post('/finished-purchase-products', 'FinishedProductController@finishedPurchaseProducts')->name('finished.purchase.products');
    Route::resource('finished-products', 'FinishedProductController', [
        'names' => [
            'index' => 'finished.index',
            'create' => 'finished.create',
            'store' => 'finished.store',
            'show' => 'finished.show',
            'edit' => 'finished.edit',
            'update' => 'finished.update',
        ]
    ]);
    Route::get('finished-products/{id}/status', 'FinishedProductController@changeStatus')->name('finished.status');
    Route::get('finished-products/{id}/delete', 'FinishedProductController@destroy')->name('finished.delete');

    // transferred products route
    Route::get('/transferred-products/pdf', 'TransferredProductController@createPDF')->name('transferred.pdf');
    Route::post('/finished-product-sizes', 'TransferredProductController@finishedProductSizes')->name('transferred.finished.sizes');
    Route::resource('transferred-products', 'TransferredProductController', [
        'names' => [
            'index' => 'transferred.index',
            'create' => 'transferred.create',
            'store' => 'transferred.store',
            'show' => 'transferred.show',
            'edit' => 'transferred.edit',
            'update' => 'transferred.update',
        ]
    ]);
    Route::get('transferred-products/{id}/status', 'TransferredProductController@changeStatus')->name('transferred.status');
    Route::get('transferred-products/{id}/delete', 'TransferredProductController@destroy')->name('transferred.delete');

    // purchase report
    Route::get('purchase-report', 'PurchaseReport@purchaseReport')->name('purchase.report');
    Route::post('purchase-report', 'PurchaseReport@postPurchaseReport')->name('purchase.report.post');

    // processing report
    Route::get('processing-report', 'ProductReport@processingReport')->name('processing.report');
    Route::post('processing-report', 'ProductReport@filterProcessingReport')->name('processing.report.filter');

    // finished report
    Route::get('finished-report', 'ProductReport@finishedReport')->name('finished.report');
    Route::post('finished-report', 'ProductReport@filterFinishedReport')->name('finished.report.filter');

    // transferred report
    Route::get('transferred-report', 'ProductReport@transferredReport')->name('transferred.report');
    Route::post('transferred-report', 'ProductReport@filterTransferredReport')->name('transferred.report.filter');

    // lang change
    Route::get('lang/change', [LanguageController::class, 'change'])->name('changeLang');
});
// charges route
Route::post('charge/store', 'ChargesController@store')->name('charge.store');

//Storein route
// Route::resource('storein',StoreinController::class);
Route::get('storein/getItemsDepartment/{items_of_storein_name}', 'StoreinController@getItemsDepartment')->name('storein.getItemsDepartment');
Route::get('storein/getUnitOfItems/{items_of_storein_name}', 'StoreinController@getUnitOfItems')->name('storein.getUnitOfItems');

Route::get('storein/getDepartentAccCat/{category_id}', 'StoreinController@getDepartentAccCat')->name('storein.getDepartentAccCat');

Route::get('storein/createStorein', 'StoreinController@createStorein')->name('storein.createStoreins');
Route::get('/storein/pdf', 'StoreinController@createPDF')->name('storein.pdf');
Route::get('storein/storeinIndex', 'StoreinController@storeinIndex')->name('storein.storeinIndex');
Route::post('storein/saveStorein', 'StoreinController@saveStorein')->name('storein.saveStorein');
Route::get('storein/createItems/{id}', 'StoreinController@createItems')->name('storein.createItems');
Route::post('storein/saveStoreinItems/{id}', 'StoreinController@saveStoreinItems')->name('storein.saveStoreinItems');
//recent here m
Route::get('storein/getcategoryItems', 'StoreinController@getcategoryItems')->name('storein.getcategoryItems');
Route::get('storein/storeInItemsRetrive/{storein_id}', 'StoreinController@storeInItemsRetrive')->name('storein.storeInItemsRetrive');
Route::get('storein/getEditItemData/{storeinItem_id}', 'StoreinController@getEditItemData')->name('storein.getEditItemData');
Route::post('storein/EditItemStoreData', 'StoreinController@EditItemStoreData')->name('storein.EditItemStoreData');
Route::post('storein/saveEntireStorein/{storein_id}', 'StoreinController@saveEntireStorein')->name('storein.saveEntireStorein');
Route::get('storein/invoiceView/{storein_id}', 'StoreinController@invoiceView')->name('storein.invoiceView');
Route::get('storein/storeinItemCreate/{id}', 'StoreinController@storeinItemCreate')->name('storein.storeinItemCreate');
Route::delete('/storein/storeinItemDelete/{id}', 'StoreinController@storeinItemDelete')->name('storein.storeinItemDelete');
Route::delete('/storein/delete/{id}', 'StoreinController@storeinDelete')->name('storein.delete');
Route::get('/storein/storinYajraDatabales', 'StoreinController@storinYajraDatabales')->name('storein.storinYajraDatabales');
Route::get('/storein/getSizeOfItems/{items_of_storein_id}', 'StoreinController@getSizeOfItems')->name('storein.getSizeOfItems');
Route::get('/storein/getDepartmentSizeUnit/{items_of_storein_name}/{category_id}', 'StoreinController@getDepartmentSizeUnit')->name('storein.getDepartmentSizeUnit');



Route::resource('storein', 'StoreinController', [
    'names' => [
        'index' => 'storein.index',
        'create' => 'storein.create',
        'store' => 'storein.store',
        'show' => 'storein.show',
        'edit' => 'storein.edit',
        'update' => 'storein.update',
    ]
]);
//StoreinType Controller
Route::post('/storeinType/store', 'StoreinTypeController@store')->name('storeinType.store');

// storein edit routes
Route::get('storein/editStorein/{storein_id}', 'StoreinController@editStorein')->name('storein.editStorein');
Route::post('storein/updateStorein/{storein_id}', 'StoreinController@updateStorein')->name('storein.updateStorein');
Route::get('storein/editStoreinItems', 'StoreinController@editStoreinItems')->name('storein.editStoreinItems');


// Tax Controller

Route::get('tax/getPercentageBySlug/{slug}', 'TaxController@getPercentageBySlug')->name('tax.getPercentageBySlug');

Route::get('storein/{code}/invoice', 'StoreinController@getInvoice')->name('storein.invoice');
Route::get('storein/{code}/status', 'StoreinController@changeStatus')->name('storein.status');
Route::post('/purchase-products', 'StoreinController@purchaseProducts')->name('storein.purchaseProducts');
Route::get('storein/{code}/delete', 'StoreinController@destroy')->name('storein.delete');


// stockController
Route::post('stock/createUpdate/{storein_item_id}', 'StockController@createUpdate')->name('stock.createUpdate');
Route::get('stock/index', 'StockController@index')->name('stock.index');
Route::get('stock/filterStockAccCategory/{category_id}', 'StockController@filterStockAccCategory')->name('stock.filterStockAccCategory');
Route::get('stock/filterStockAccDepartment/{department_id}', 'StockController@filterStockAccDepartment')->name('stock.filterStockAccDepartment');
//recent megha
Route::get('storeinStock/filter', 'StockController@filter')->name('storeinStock.filter');
//filter department acc category
Route::get('storeinStock/getCategoryDepartment/{category_id}', 'StockController@getCategoryDepartment')->name('storeinStock.getCategoryDepartment');
//import stock
Route::post('import/stock',[StockImportController::class,"import"])->name('import.stock');
//import fabric
Route::post('import/fabric', 'FabricController@import')->name('import.fabric');

/*****************tape entry**************/
Route::get('tape-entry',[TapeEntryController::class,"index"])->name('tape.entry');
Route::post('tape-entry/store',[TapeEntryController::class,"tapeentrystore"])->name("tape.entry.store");
Route::get('tape-entry/receive/create/{id}',[TapeEntryController::class,"create"])->name("tape.entry.receive.create");
Route::get('tape-entry/receive/view/{id}',[TapeEntryController::class,"view"])->name("tape.entry.receive.view");
Route::post('tape-entry/receive/delete/{id}',[TapeEntryController::class,"deleteTape"])->name("tape.entry.receive.delete");
    //reteieve planttype
Route::get('tape-entry/ajax-request/{godam_id}',[TapeEntryController::class,"ajaxrequestplanttype"])->name('tape.entry.ajax.planttype');
    //reteieve plantname
Route::get('tape-entry/ajax-request/plantname/{planttype_id}/{godam_id}',[TapeEntryController::class,"ajaxrequestplantname"])->name('tape.entry.ajax.plantname');
    //retrieve shift
Route::get('tape-entry/ajax-request/shift/{plantname_id}/{godam_id}/{plantType_id}',[TapeEntryController::class,"ajaxrequestshift"])->name('tape.entry.ajax.shift');
    //get dana info
Route::post('tape-entry/ajax-request/danainfo',[TapeEntryController::class,"ajaxrequestdanainfo"])->name('tape.entry.ajax.get.danainfo');
    // tapeentrystock
Route::post('tape-entry/stock/store',[TapeEntryController::class,'tapeentrystockstore'])->name('tape.entry.stock.store');
/**************tape entry end*************/

//tapeentry stock
Route::get('tape-entry/getstock/index',[TapeEntryStockController::class,'index'])->name('tapeentry-stock.index');
 Route::post('tape-entry/getstock/filterStocks',[TapeEntryStockController::class,'filterStock'])->name('tapeentry-stock.filterStock');

/******************** wastages *****************************/
Route::get('setup/wastage/index',[WastageController::class,'index'])->name('setup.wastage.index');
Route::get('setup/wastage/create',[WastageController::class,'create'])->name('setup.wastage.create');
Route::post('setup/wastage/store',[WastageController::class,'store'])->name('setup.wastage.store');
/******************** wastages  end *****************************/
//storeoutDepartment
Route::post('storeoutDepartment/store', [StoreoutDepartmentController::class, 'store'])->name('storeoutDepartment.store');
//Storeout route
Route::get('storeout/index', [StoreoutController::class, 'index'])->name('storeout.index');
Route::get('storeout/create', [StoreoutController::class, 'create'])->name('storeout.create');
Route::post('storeout/saveStoreout', [StoreoutController::class, 'saveStoreout'])->name('storeout.saveStoreout');
Route::get('storeout/storeOutItems/{store_out_id}', [StoreoutController::class, 'storeOutItems'])->name('storeout.storeOutItems');
Route::get('storeout/getStoreOutItemData/{storeout_id}', [StoreoutController::class, 'getStoreOutItemData'])->name('storeout.getStoreOutItemData');
Route::get('storeout/getEditItemData/{storeoutItem_id}', [StoreoutController::class, 'getEditItemData'])->name('storeout.getEditItemData');
Route::post('storeout/updateStoreOutItems', [StoreoutController::class, 'updateStoreOutItems'])->name('storeout.updateStoreOutItems');
Route::post('storeout/saveEntireStoreOut/{storeout_id}', [StoreoutController::class, 'saveEntireStoreOut'])->name('storeout.saveEntireStoreOut');
//get item acc cat
//recent by m
Route::get('storeout/getStoreinItemAccCat', [StoreoutController::class, 'getStoreinItemAccCat'])->name('storeout.getStoreinItemAccCat');
//getDepartmentSizeUnit
Route::get('/storeout/getDepartmentSizeUnit/{items_of_storein_name}/{category_id}', 'StoreoutController@getDepartmentSizeUnit')->name('storeout.getDepartmentSizeUnit');
//getStockQtyRate
Route::post('/storeout/getStockQtyRate', 'StoreoutController@getStockQtyRate')->name('storeout.getStockQtyRate');

// delete storeoutItem
Route::delete('storeout/storeoutItemDelete/{storeout_item_id}', [StoreoutController::class, 'storeoutItemDelete'])->name('storeout.storeoutItemDelete');
//storeout edit
Route::get('storeout/edit/{storeout_id}', [StoreoutController::class, 'edit'])->name('storeout.edit');
Route::post('storeout/updateStoreOut/{storeout_id}', [StoreoutController::class, 'updateStoreOut'])->name('storeout.updateStoreOut');

//recent changes
Route::get('storeout/getDepartmentPlacements/{dept_id}/{storeout_id}', [StoreoutController::class, 'getDepartmentPlacements'])->name('storeout.getDepartmentPlacements');
// storeoutitem save
Route::post('storeout/saveStoreoutItems', [StoreoutController::class, 'saveStoreoutItems'])->name('storeout.saveStoreoutItems');
Route::get('storeout/storoutYajraDatabales', [StoreoutController::class, 'storoutYajraDatabales'])->name('storeout.storoutYajraDatabales');
Route::delete('storeout/deleteStoreout/{storeout_id}', [StoreoutController::class, 'deleteStoreout'])->name('storeout.deleteStoreout');



//placement route
Route::post('placement/save', [PlacementController::class, 'save'])->name('placement.save');



Route::post('theme-settings', [ThemeSettingsContoller::class, 'settings'])->name('theme-settings');

/******************** Bag  ************************/

        //for receipts
    Route::get('fabric/transfer/entry/for/bag/index',[FabricTransferEntryForBagController::class,"index"])->name('fabric.transfer.entry.for.bag');
    Route::get('fabric/transfer/entry/for/bag/create',[FabricTransferEntryForBagController::class,"create"])->name('fabric.transfer.entry.for.bag.create');
    Route::post('fabric/transfer/entry/for/bag/store',[FabricTransferEntryForBagController::class,"store"])->name('fabric.transfer.entry.for.bag.store');

        // for actual trasnfer
    Route::get('fabric/transfer/create/{id}',[FabricTransferEntryForBagController::class,"fabrictransferindex"])->name('fabric.transfer.create');
    Route::get('get/fabrics/according/godams/{id}',[FabricTransferEntryForBagController::class,"getfabricsaccordinggodams"])->name('get.fabrics.according.godams');
    Route::get('get/specific/fabric/details/{id}',[FabricTransferEntryForBagController::class,"getspecificfabricdetails"])->name('get.specific.fabric.details');

        // sending to lower
    Route::get('send/fabric/to/lower/{id}',[FabricTransferEntryForBagController::class,"sendfabrictolower"])->name('send.fabric.to.lower');
    Route::get("call/details/to/lower/fabric/table",[FabricTransferEntryForBagController::class,"gettemporaryfabricforbag"])->name('call.details.to.lower.fabric.table');
    Route::post("discard/temporary/table",[FabricTransferEntryForBagController::class,"discard"])->name('discard.temporary.table');
    Route::post("delete/from/lower/table",[FabricTransferEntryForBagController::class,"deletefromlowertable"])->name('delete.from.lower.table');
    Route::post("final/save",[FabricTransferEntryForBagController::class,"finalsave"])->name('final.save');
        //for report what was sent
    Route::get("view/sent/fabric/bag/{id}",[FabricTransferEntryForBagController::class,"viewSentItem"])->name('view.sent.fabric.bag');

        //prints and cuts starts
        //entry
    Route::get("prints/and/cuts/index",[PrintedAndCuttedRollsController::class,"index"])->name('prints.and.cuts.index');
    Route::get("prints/and/cuts/create/entry",[PrintedAndCuttedRollsController::class,"createEntry"])->name('prints.and.cuts.create.entry');
    Route::post("prints/and/cuts/store/entry",[PrintedAndCuttedRollsController::class,"storeEntry"])->name('prints.and.cuts.store.entry');

    Route::get("prints/and/cuts/createPrintedRolls/{id}",[PrintedAndCuttedRollsController::class,"createPrintedRolls"])->name('prints.and.cuts.createPrintedRolls');

    Route::post("printsAndCuts/getFabric",[PrintedAndCuttedRollsController::class,"getFabric"])->name('printsAndCuts.getFabric');
    Route::post("printsAndCuts/getDanaGroup",[PrintedAndCuttedRollsController::class,"getDanaGroup"])->name('printsAndCuts.getDanaGroup');

    Route::post("printsAndCuts/getDanaName",[PrintedAndCuttedRollsController::class,"getDanaName"])->name('printsAndCuts.getDanaName');

    Route::post("printsAndCuts/getStockQuantity",[PrintedAndCuttedRollsController::class,"getStockQuantity"])->name('printsAndCuts.getStockQuantity');


/******************** Bag  End ************************/
/****************Group Start********************/
    Route::post("group/store",[GroupController::class,"store"])->name('group.store');


/****************Group End********************/

/****************Bag Brand Start********************/
Route::post("bagBrand/store",[BagBrandController::class,"store"])->name('bagBrand.store');
Route::get("bagBrand/getBagBrandFromGroup/{group_id}",[BagBrandController::class,"getBagBrandFromGroup"])->name('bagBrand.getBagBrandFromGroup');

/****************Bag Brand End********************/

/***************printing and cutting bag item start*****************/
Route::post("printingAndCuttingBagItem/store",[PrintingAndCuttingBagItemController::class,"store"])->name('printingAndCuttingBagItem.store');
Route::get("printingAndCuttingBagItem",[PrintingAndCuttingBagItemController::class,"getPrintsAndCutsBagItems"])->name('printingAndCuttingBagItem.getPrintsAndCutsBagItems');
Route::delete("printingAndCuttingBagItem/{printingAndCuttingBagItem_id}",[PrintingAndCuttingBagItemController::class,"itemDelete"])->name('printingAndCuttingBagItem.itemDelete');
Route::post("printingAndCuttingBagItem/updateStock",[PrintingAndCuttingBagItemController::class,"updateStock"])->name('printingAndCuttingBagItem.updateStock');




/***************printing and cutting bag item end*****************/
/*************************PrintsAndCutsDanaConsumptionController start****************************/
Route::post("printingAndCuttingDanaConsumption/store",[PrintsAndCutsDanaConsumptionController::class,"store"])->name('printingAndCuttingDanaConsumption.store');

Route::post("printingAndCuttingDanaConsumption/getPrintsAndCutsDanaConsumption",[PrintsAndCutsDanaConsumptionController::class,"getPrintsAndCutsDanaConsumption"])->name('printingAndCuttingBagItem.getPrintsAndCutsDanaConsumption');

/*************************PrintsAndCutsDanaConsumptionController end****************************/
