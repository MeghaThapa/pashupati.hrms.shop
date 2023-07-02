@extends('layouts.admin')

@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
    <style>
        .col-form-label {
            font-size: 12px !important;

        }

        .dynamic-btn {
            height: 18px;
            width: 4px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #storeinSubmitBtn {
            height: 25px;
            width: 70px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 38px !important;
        }

        .fa-plus {
            font-size: 10px;
        }

        .form-control {
            font-size: 12px !important;

        }

        .select2-selection__rendered,
        .select2-container--bootstrap4 .select2-selection {
            font-size: 12px !important;
            display: flex !important;
            align-items: center !important;
            height: calc(1.6em + 0.75rem + 2px) !important;
        }

        .select2-container {
            height: calc(1.6em + 0.75rem + 2px) !important;
        }

        .taxStyle .select2-selection {
            width: 200px !important;
        }

        .form-group {
            margin-bottom: 0px !important;
        }

        .content-wrapper {
            padding-top: 0px !important;
        }

        /* .select2-selection {
                                                                                                                                                                                                                                    width:150px !important;
                                                                                                                                                                                                                                } */
    </style>
@endsection

@section('content')
    {{-- @if (session('message'))
        <div id="alert-message" class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif --}}
    @if (session('success'))
        <div id="error-container" class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div id="error-container" class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div id="storeinItemError" class="alert alert-danger" hidden></div>
    <div class="card-body p-0 m-0">
        <div id="formdiv">
            <form id="createStoreInItem">
                @csrf

                <div class="row">

                    <div class="col-md-2 form-group">
                        <label for="Category" class="col-form-label">{{ __('Category Name') }}<span
                                class="required-field">*</span>
                        </label>
                        <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal" tabindex="-1"
                            data-target="#storeinCategoryModel" style="margin-top:0 !important; top:8px;float:right;">
                            <i class="fas fa-plus" style="display:flex;align-items: center;justify-content: center;"></i>
                        </a>
                        <select class="advance-select-box form-control  @error('Category') is-invalid @enderror"
                            id="categorySelect" name="categoryName" required>
                            <option value=" " selected disabled>{{ __('Select a Category') }}</option>
                            @foreach ($categories as $key => $category)
                                <option value="{{ $category->id }}"
                                    {{ old('categoryName') == $category->id ? 'selected' : '' }}>{{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('categoryName')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    {{-- item --}}
                    <div class="col-md-6 form-group">
                        <label for="products" class="col-form-label">{{ __('Storein Item Name') }}<span
                                class="required-field">*</span>
                        </label>
                        <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal" tabindex="-1"
                            data-target="#createStoreinItemModelPopup"
                            style="margin-top:0 !important; top:8px;float:right;">
                            <i class="fas fa-plus" style="display:flex;align-items: center;justify-content: center;"></i>
                        </a>
                        <select class="form-control  @error('ProductName') is-invalid @enderror" id="ProductName"
                            name="ProductName" required>
                            <option value="" selected disabled>{{ __('Select a Product') }}</option>
                        </select>
                        @error('ProductName')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>


                    {{-- Quantity --}}

                    <div class="col-md-2">
                        <label for="qunatities" class="col-form-label">{{ __('Quantity') }}<span
                                class="required-field">*</span></label>
                        <input type="number" step="any" min="0"
                            class="form-control @error('qunatities') is-invalid @enderror calculator" id="qunatities"
                            data-number="1" name="quantities" placeholder="{{ __('Quantity') }}" min="1" required>
                        @error('quantities')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    {{-- unit price --}}

                    <div class="col-md-2">
                        <label for="unitPrices[]" class="col-form-label">{{ __('Unit Price') }}<span
                                class="required-field">*</span></label>
                        <input type="number" step="any" min="0"
                            class="form-control @error('unitPrices') is-invalid @enderror calculator" id="unitPrices"
                            data-number="1" name="unitPrices" placeholder="{{ __('Unit Price') }}" min="1" required>
                        @error('unitPrices')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row" style="gap:10px">
                    {{-- size --}}
                    <div class="col-md-2">
                        <label for="qunatities" class="col-form-label">{{ __('Size') }}<span
                                class="required-field">*</span></label>
                        <select class="advance-select-box form-control  @error('ProductName') is-invalid @enderror"
                            id="SizeId" name="size_id" required>
                            <option value="" selected disabled>{{ __('Select a Size') }}</option>
                        </select>
                        @error('quantities')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    {{-- unit --}}
                    <div class="col-md-2">
                        <label for="qunatities" class="col-form-label">{{ __('Unit') }}<span
                                class="required-field">*</span></label>
                        <select class="advance-select-box form-control  @error('ProductName') is-invalid @enderror"
                            id="unitId" name="unit_id" required>
                            <option value="" selected disabled>{{ __('Select a Unit') }}</option>
                        </select>
                        @error('quantities')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    {{-- Department --}}
                    <div class="col-md-4" style="gap:10px">
                        <div>
                            <label for="singleTotal[]" class="col-form-label">{{ __('Department') }}</label>
                        </div>
                        <div>
                            <select class="advance-select-box form-control" id="departmentId" name="department_id"
                                required>
                                <option value="" selected disabled>{{ __('Select a department') }}</option>
                            </select>
                            {{-- <input type="text" step="any" min="0" style="width:200px; "
                                class="form-control @error('singleTotal') is-invalid @enderror" id="department_id" --}}
                            {{-- name="department_id" placeholder="{{ __('Department name') }}" data-ignore readonly tabindex="-1"> --}}
                        </div>
                    </div>
                    <div class="d-flex" style="gap:10px;margin-top:30px;">
                        <div>
                            <label for="singleTotal[]" class="col-form-label">{{ __('Total') }}</label>
                        </div>
                        <div class="input-group">
                            {{-- <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Rs.</span>
                            </div> --}}
                            {{-- <input type="text" id="singleTotal" tabindex="-1" name="singleTotal"
                                class="form-control" placeholder="{{ __('Product Total') }}" data-ignore readonly
                                aria-label="total amount" aria-describedby="basic-addon1" /> --}}
                            <div class="input-group mb-3">
                                <div class="input-group-prepend" style="height: 37px!important">
                                    <span class="input-group-text" id="basic-addon1">Rs.</span>
                                </div>
                                <input type="text" id="singleTotal" tabindex="-1" name="singleTotal"
                                    style="height: 37px!important;padding:5px;" class="form-control" data-ignore readonly
                                    placeholder="Total" aria-label="Total" aria-describedby="basic-addon1">
                            </div>
                        </div>

                    </div>
                    <div>
                        <button type="submit" id="storeinSubmitBtn" class="btn btn-primary m-4"
                            style=" padding:14px!important; margin-m:40px;margin-left:10px!important;">
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
        {{-- table --}}
        <div class="row">
            <div class="Ajaxdata col-md-12">
                <div class="p-0 table-responsive table-custom my-3">
                    <table class="table" id="storeInItemTable">
                        <thead>
                            <tr>
                                <th>{{ __('S.No') }}</th>
                                {{-- <th>{{ __('Category Name') }}</th> --}}
                                <th>{{ __('Item') }}</th>
                                <th>{{ __('Department') }}</th>
                                <th>{{ __('Size') }}</th>
                                <th>{{ __('Quantity') }}</th>
                                <th>{{ __('Unit') }}</th>
                                <th>{{ __('Rate') }}</th>
                                {{-- <th>{{ __('Discount %') }}</th>
                                <th>{{ __('Discount') }}</th> --}}

                                <th>{{ __('Total') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>

                        <tbody id="result">
                        </tbody>

                    </table>

                    {{-- for sub total --}}
                    {{-- <input type="text" id="subTotal"> --}}
                </div>

            </div>
        </div>
        <form action="{{ route('storein.saveEntireStorein', ['storein_id' => $storein->id]) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="d-flex" style="margin:0px; padding:0px;justify-content:space-between; ">
                <div class="d-flex" style="margin-right:8px;gap:5px">
                    <label for="purchaseImage" class="col-form-label">{{ __('Total') }}</label>
                    <input type="text" step="any" min="0" style="width:150px; "
                        class="form-control form-control-sm @error('total') is-invalid @enderror" id="totalStoreIn"
                        name="total" placeholder="{{ __('Total') }}" disabled>
                </div>

                <div class="d-flex" style="margin-right:8px;gap:5px">
                    <label for="purchaseImage" class="col-form-label">{{ __('Discount %') }}</label>
                    <input type="text" step="any" min="0" style="width:150px; "
                        class="form-control form-control-sm @error('singleTotal') is-invalid @enderror"
                        @if ($storein->discount_percent) value="{{ $storein->discount_percent }}" @endif
                        id="discount_percent" name="discount_percent" placeholder="{{ __('Discount %') }}">
                </div>

                <div class="d-flex" style="margin-right:8px;gap:5px">
                    <label for="purchaseImage" class="col-form-label">{{ __('Discount Amount') }}</label>
                    <input type="text" step="any" min="0" style="width:150px; "
                        class="form-control form-control-sm @error('discount_amount') is-invalid @enderror"
                        @if (!$storein->discount_percent && $storein->total_discount) value="{{ $storein->total_discount }}" @endif
                        id="discount_amount" name="discount_amount" placeholder="{{ __('Discount Amount') }}">
                </div>
                <div class="d-flex" style="margin-right:8px;gap:5px">
                    <label for="purchaseImage" class="col-form-label">{{ __('Net Total') }}</label>
                    <input type="text" step="any" min="0" style="width:150px; "
                        class="form-control form-control-sm @error('netTotal') is-invalid @enderror" id="net_total"
                        name="netTotal" placeholder="{{ __('Total') }}" disabled>
                </div>
            </div>

            <div class="d-flex" style="margin-right:8px; gap:10px">
                <label for="" class="col-form-label">{{ __('Charges') }}</label>
                <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                    data-target="#createCharge" style="margin-top:0 !important; top:8px;float:right;">
                    <i class="fas fa-plus" style="display:flex;align-items: center;justify-content: center;"></i>
                </a>
            </div>
            <div id="chargesContainer" class="row">
            </div>
            {{-- <label for="Category" class="col-form-label">{{ __('Category Name') }}<span class="required-field">*</span>
            </label> --}}
            <div class="row">
                <div class="form-group col-md-12">
                    <label for="note" class="col-form-label">{{ __('Storein Note') }}</label>
                    <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note"
                        placeholder="{{ __('Purchase Note') }}">
                        @if ($storein->note)
{{ $storein->note }}
@endif
                    </textarea>
                    @error('note')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="purchaseImage" class="col-form-label">{{ __('Storein Image') }}</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input @error('purchaseImage') is-invalid @enderror"
                            id="attached-image" name="purchaseImage">
                        <label class="custom-file-label" for="customFile">{{ __('Choose file') }}</label>
                        @error('purchaseImage')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="image-preview">
                        <img src="" id="attached-preview-img" class="mt-3" />
                    </div>
                </div>
                <div class="col-md-6 form-group">
                    <label for="status" class="col-form-label">{{ __('Status') }}</label>
                    <select class="form-control" id="status" name="status">
                        <option {{ $storein->status == 'active' ? 'selected' : '' }} value="active">{{ __('Active') }}
                        </option>
                        <option {{ $storein->status == 'inactive' ? 'selected' : '' }} value="inactive">
                            {{ __('Inactive') }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-10">
                    <button id="saveStoreInBtn" type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                        {{ __('Save Store In') }}</button>
                </div>
            </div>
        </form>
    </div>
    {{-- editItemsMOdal --}}
    <div class="modal fade" id="editItemModal" tabindex="-1" role="dialog" aria-labelledby="exampleModaltax"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModaltax">Edit StoreinItem</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editStoreinItem">
                        {{-- action="{{ route('storein.EditItemStoreData') }}" method="POST"
                        enctype="multipart/form-data" --}}
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <input type="text" id="storeinItem_id" name="storeInItem_id" hidden>
                                <input type="text" id="storein_id" name="storeIn_id" hidden>
                                {{-- storein_id --}}
                                <div class="form-group col-md-12">
                                    <label for="categorySelect" class="form-label" style="font-weight: bold;">Category
                                        Name</label>
                                    <select class="advance-select-box form-control" name="category_id"
                                        id="categoryitem_id">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">
                                                {{ $category->name }}</option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="itemSelect" class="col-form-label" style="font-weight: bold;">Item
                                        Name</label>
                                    <select class="advance-select-box form-control" name="item_id" id="item_id">


                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="quantity" class="form-label" style="font-weight: bold;">Quantity</label>
                                    <input type="number" id="quantity" name="quantity" required="true" min="1"
                                        class="form-control" />
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="iUnit" class="form-label" style="font-weight: bold;">Unit</label>

                                </div>
                                <div class="form-group col-md-12">
                                    <label for="iUnitPrice" class="form-label" style="font-weight: bold;">Unit
                                        Price</label>
                                    <input type="number" id="price" name="unitPrice" required="true"
                                        min="1" class="form-control" />
                                </div>

                                <div class="form-group col-md-12">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>

                </div> --}}
            </div>
        </div>
    </div>
    {{-- edit item modal end --}}
    {{-- create charges pop up --}}
    <div class="modal fade" id="createCharge" tabindex="-1" role="dialog" aria-labelledby="exampleModaltax"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModaltax">Edit StoreinItem</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('charge.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="categorySelect" class="form-label" style="font-weight: bold;">Charge
                                        Name</label>
                                    <input type="text" id="charge" name="charge" required="true"
                                        class="form-control" />
                                </div>
                                <div class="form-group col-md-12">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- create charges pop up end --}}
    <!--Category Model popup-->
    <div class="modal fade" id="storeinCategoryModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalcat"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title" id="exampleModalcat">Add Storein Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form class="form-horizontal" id="createCategoryModel">
                    <div class="modal-body">
                        <div id="categoryModelError" class="alert alert-danger" hidden></div>
                        <div class="card-body">
                            <div class="row">

                                <div class="form-group col-md-2">
                                    <label style="width:400px !important;" for="name">{{ __('Category Name') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="catNameModel" name="cat_name_model" style="width:430px !important; "
                                        placeholder="{{ __('Category Name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="note" class="col-form-label">{{ __('Category Note') }}</label>
                                    <textarea class="form-control @error('note') is-invalid @enderror" id="catNoteModel" name="cat_note_model"
                                        placeholder="{{ __('Category Note') }}">{{ old('note') }}</textarea>
                                    @error('note')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                    <select class="form-control" id="catStatus" name="cat_status">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Inactive') }}</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <!-- /.card-body -->

                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                            {{ __('Save Category') }}</button>
                        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--Category  Model Popup End-->
    <!--Item Model popup-->

    <div class="modal fade" id="createStoreinItemModelPopup" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalitem" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalitem">Add Storein Items</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal" id="createStoreinItemModel">

                    <div class="modal-body">
                        <div id="itemModelError" class="alert alert-danger" hidden></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="item">{{ __('Items Name') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="itemNameModel" name="item_name_model" placeholder="{{ __('Items Name') }}"
                                        value="{{ old('item') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="item">{{ __('Product Number') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('pnumber') is-invalid @enderror"
                                        id="pnumberModel" name="pnumber_model" placeholder="{{ __('Product No') }}"
                                        value="{{ old('pnumber') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group mt-3">
                                    <label for="categoryName">{{ __('Category Name') }}<span
                                            class="required-field">*</span></label>
                                    <select
                                        class="advance-select-box form-control @error('categoryName') is-invalid @enderror"
                                        id="categoryNameModel" name="categoryName_model" required>
                                        <option value="" selected disabled>{{ __('Select a category') }}</option>
                                        @foreach ($categories as $key => $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group mt-3">
                                    <label for="supplier" class="col-form-label">
                                        {{ __('Department') }}
                                    </label>
                                    <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                                        data-target="#addStoreinDepartmentModel"
                                        style="margin-top:-5px !important; top:0;float:right;">
                                        <i class="fas fa-plus"
                                            style="display:flex;align-items: center;justify-content: center;"></i>
                                    </a>
                                    <select
                                        class="advance-select-box form-control @error('department') is-invalid @enderror"
                                        id="departmentModel" name="department_id_model">
                                        <option value="" selected disabled>{{ __('No department data') }}</option>

                                    </select>
                                    @error('department_id_model')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group mt-3">
                                    <label for="categoryName">{{ __('Unit Name') }}<span
                                            class="required-field">*</span></label>
                                    <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                                        tabindex="-1" data-target="#exampleModalunitModel"
                                        style="margin-top:0 !important; top:8px;float:right;">
                                        <i class="fas fa-plus"
                                            style="display:flex;align-items: center;justify-content: center;"></i>
                                    </a>
                                    <select
                                        class="advance-select-box form-control @error('unitName') is-invalid @enderror"
                                        id="unitNameModel" name="unit_name_model" required>
                                        <option value="" selected disabled>{{ __('Select a unit') }}</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group mt-3">
                                    <label for="supplier" class="col-form-label">
                                        {{ __('Size') }}
                                    </label>
                                    <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                                        tabindex="-1" data-target="#exampleModalsizeCreate"
                                        style="margin-top:0 !important; top:8px;float:right;">
                                        <i class="fas fa-plus"
                                            style="display:flex;align-items: center;justify-content: center;"></i>
                                    </a>
                                    <select class="advance-select-box form-control @error('size') is-invalid @enderror"
                                        id="sizeModel" name="size_model">
                                        <option value="" selected disabled>{{ __('Select a size') }}</option>
                                        @foreach ($sizes as $size)
                                            <option value="{{ $size->id }}">{{ $size->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department_id_model')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                    <select class="form-control" id="itemStatusModel" name="item_status_model">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Inactive') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                            {{ __('Save Items') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--Item Model Popup End-->
    {{-- department model popup --}}
    <div class="modal fade" id="addStoreinDepartmentModel" tabindex="-1" role="dialog"
        aria-labelledby="addDepartmentModel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="storeinDepartmentCreate">

                    <div class="modal-header">
                        <h5 class="modal-title" id="addDepartmentModel">Add Storein Department</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="card-body">
                            <div id="departmentModelError" class="alert alert-danger" hidden></div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="department" class="col-form-label">{{ __('Department Name') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('company') is-invalid @enderror"
                                        id="department" name="department" placeholder="{{ __('Department Name') }}"
                                        value="{{ old('department') }}" required>
                                    @error('company')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group mt-3">
                                    <label for="supplier" class="col-form-label">
                                        {{ __('Category') }}
                                    </label>
                                    <select class="advance-select-box form-control" id="deptCatModel"
                                        name="dept_cat_model">
                                        <option value="" selected disabled>{{ __('Select a category') }}</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department_id_model')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Inactive') }}</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <!-- /.card-body -->

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                            {{ __('Save Department') }}</button>

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Close
                        </button>
                        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- department model popup end --}}
    <!--Unit Model popup-->

    <div class="modal fade" id="exampleModalunitModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalunit"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalunit">Add Unit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="unitStoreModelCreate" {{-- {{ route('units.store') }} --}}>
                        <div class="card-body">
                            <div id="unitModelError" class="alert alert-danger" hidden></div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="name">{{ __('Unit Name') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" placeholder="{{ __('Unit Name') }}"
                                        value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="unitCode">{{ __('Unit Code') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('unitCode') is-invalid @enderror"
                                        id="unitCodeModel" name="unit_code_model" placeholder="{{ __('Unit Code') }}"
                                        value="{{ old('unitCode') }}" required>
                                    @error('unitCode')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="note" class="col-form-label">{{ __('Unit Note') }}</label>
                                    <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note"
                                        placeholder="{{ __('Unit Note') }}">{{ old('note') }}</textarea>
                                    @error('note')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Inactive') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                                        {{ __('Save Unit') }}</button>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                </div>
            </div>
        </div>
    </div>

    <!--Unit  Model Popup End-->
    <!--Size Model popup-->

    <div class="modal fade" id="exampleModalsizeCreate" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalsize" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalsize">Add Size</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="sizeCreateModel" {{-- {{ route('sizes.store')}} --}}>
                        <div class="card-body">
                            <div id="sizeModelError" class="alert alert-danger" hidden></div>
                            <div class="row">

                                <div class="col-md-6 form-group">
                                    <label for="name">{{ __('Size Name') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" placeholder="{{ __('Size Name') }}"
                                        value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="sizeCode">{{ __('Size Code') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('sizeCode') is-invalid @enderror"
                                        id="sizeCode" name="size_code" placeholder="{{ __('Size Code') }}"
                                        value="{{ old('sizeCode') }}" required>
                                    @error('sizeCode')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="note" class="col-form-label">{{ __('Size Note') }}</label>
                                    <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note"
                                        placeholder="{{ __('Size Note') }}">{{ old('note') }}</textarea>
                                    @error('note')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Inactive') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                                        {{ __('Save Size') }}</button>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                </div>
            </div>
        </div>
    </div>

    <!--Size  Model Popup End-->

    <!--Tax Model popup-->

    <div class="modal fade" id="exampleModaltax" tabindex="-1" role="dialog" aria-labelledby="exampleModaltax"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModaltax">Add Size</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="{{ route('tax.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="name">{{ __('Tax Type') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" placeholder="{{ __('Tax Type') }}"
                                        value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="unitCode">{{ __('Tax %') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('unitCode') is-invalid @enderror"
                                        id="tax" name="tax" placeholder="{{ __('Tax %') }}"
                                        value="{{ old('tax') }}" required>
                                    @error('unitCode')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Inactive') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                                        {{ __('Save Unit') }}</button>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                </div>
            </div>
        </div>
    </div>




    <!--Tax  Model Popup End-->
@endsection

@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/storein.js') }}"></script>
    <script>
        $(document).ready(function() {

            $('#categorySelect').focus();
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });

            checkRowInTable();


            function checkRowInTable() {
                let tableTbody = document.querySelector("#storeInItemTable tbody");
                let saveStoreInBtn = document.getElementById('saveStoreInBtn');
                if (tableTbody.rows.length <= 0) {
                    saveStoreInBtn.disabled = true;
                } else {
                    saveStoreInBtn.disabled = false;
                }
            }
            //calculate total
            document.getElementById("qunatities").addEventListener('input', function(e) {
                let qty = e.target.value.trim();
                let unitPrice = document.getElementById('unitPrices').value.trim();
                if (qty && unitPrice) {
                    calculateTotalAmount(qty, unitPrice);
                }
            })
            document.getElementById("unitPrices").addEventListener('input', function(e) {
                let unitPrice = e.target.value.trim();
                let qty = document.getElementById('qunatities').value.trim();
                if (unitPrice && qty) {
                    calculateTotalAmount(qty, unitPrice);
                }
            })

            function calculateTotalAmount(qty, unitPrice) {
                document.getElementById("singleTotal").value = parseFloat(qty) * parseFloat(unitPrice);
            }

            let formDiv = document.getElementById("formdiv");
            let focusableElements = Array.from(formDiv.querySelectorAll("input, select,button")).filter(function(
                element) {
                return !element.hasAttribute("data-ignore");
            });

            let currentIndex = -1;

            formDiv.addEventListener("keydown", function(event) {
                // Check if the pressed key is the tab key (key code 9)
                if (event.keyCode === 9) {
                    event.preventDefault(); // Prevent the default tab behavior
                    var nextIndex = (currentIndex + 1) % focusableElements.length;
                    focusableElements[nextIndex].focus();
                    currentIndex = nextIndex;
                }
            });
            //for size create
            document.getElementById("sizeCreateModel").addEventListener('submit', function(e) {
                e.preventDefault();
                const form = e.target;
                let name = form.elements['name'];
                let code = form.elements['size_code'];
                let note = form.elements['note'];
                let status = form.elements['status'];
                if (!name.value && !code.value && !note.value && !status.value) {
                    setMessage('sideModelError', 'Please Fill out all fields')
                    return false;
                }
                // {{ route('sizes.store') }}
                $.ajax({
                    url: "{{ route('sizes.store') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: name.value,
                        code: code.value,
                        note: note.value,
                        status: status.value,
                    },
                    success: function(response) {
                        $('#exampleModalsizeCreate').modal('hide');

                        name.value = '';
                        code.value = '';
                        note.value = '';

                        let selectElement = document.getElementById('sizeModel');
                        let optionElement = document.createElement('option');

                        optionElement.value = response.size.id;
                        optionElement.text = response.size.name;
                        selectElement.appendChild(optionElement);
                        optionElement.selected = true;
                    },
                    error: function(xhr, status, error) {

                        setMessage('sizeModelError', xhr.responseJSON.message)
                    }
                })
            })
            //for unit create
            document.getElementById('unitStoreModelCreate').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = e.target;
                let name = form.elements['name'];
                let code = form.elements['unit_code_model'];
                let note = form.elements['note'];
                let status = form.elements['status'];
                if (!name.value && !code.value && !note.value && !status.value) {
                    setMessage('unitModelError', 'Please Fill out all fields')
                    return false;
                }
                $.ajax({

                    url: "{{ route('units.store') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: name.value,
                        code: code.value,
                        note: note.value,
                        status: status.value,
                    },
                    success: function(response) {
                        console.log(response);
                        $('#exampleModalunitModel').modal('hide');
                        name.value = '';
                        code.value = '';
                        note.value = '';
                        status.value = '';

                        let selectElement = document.getElementById('unitNameModel');
                        let option = document.createElement('option');
                        option.value = response.unit.id;
                        option.text = response.unit.name;
                        selectElement.append(option);

                        // console.log(response);
                    },
                    error: function(xhr, status, error) {
                        setMessage('unitModelError', xhr.responseJSON.message);
                    }

                });
            })

            document.getElementById('storeinDepartmentCreate').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = e.target;
                let name = form.elements['department'];
                let category_id = form.elements['dept_cat_model'];
                let status = form.elements['status'];
                if (!name.value &&
                    !status.value) {
                    setMessage('departmentModelError', 'Please Fill out all fields')
                    return false;
                }
                $.ajax({
                    url: "{{ route('storeinDepartment.store') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: name.value,
                        category_id: category_id.value,
                        status: status.value,
                    },
                    success: function(response) {
                        $('#addStoreinDepartmentModel').modal('hide');

                        name.value = '';
                        category_id.value = '';
                        let selectElement = document.getElementById('departmentModel');
                        let option = document.createElement('option');
                        option.value = response.storeinDepartment.id;
                        option.text = response.storeinDepartment.name;
                        selectElement.append(option);

                        // console.log(response);
                    },
                    error: function(xhr, status, error) {
                        setMessage('departmentModelError', xhr.responseJSON.message);
                    }

                });
            });

            function setMessage(element_id, message) {
                let errorContainer = document.getElementById(element_id);
                errorContainer.hidden = false;
                errorContainer.innerHTML = message;
                setTimeout(function() {
                    errorContainer.hidden = true;
                }, 2000);
            }


            document.getElementById('createCategoryModel').addEventListener('submit', function(e) {
                e.preventDefault();

                const form = e.target;
                let name = form.elements['cat_name_model'];
                let note = form.elements['cat_note_model'];
                let status = form.elements['cat_status'];
                if (!name.value && !note.value &&
                    !status.value) {
                    //megha recent
                    setMessage('categoryModelError', 'Please Fill out all fields')
                    return false;
                }
                $.ajax({
                    url: "{{ route('storeinCategory.store') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: name.value,
                        note: note.value,
                        status: status.value,
                    },
                    success: function(response) {
                        $('#storeinCategoryModel').modal('hide');

                        name.value = '';
                        note.value = '';

                        console.log(response.category.name);
                        let selectElement = document.getElementById('categorySelect');
                        let optionElement = document.createElement('option');

                        optionElement.value = response.category.id;
                        optionElement.text = response.category.name;
                        selectElement.appendChild(optionElement);
                        optionElement.selected = true;

                        let selectElementModel = document.getElementById('categoryNameModel');

                        let optionElementModel = document.createElement('option');

                        optionElementModel.value = response.category.id;
                        optionElementModel.text = response.category.name;
                        selectElementModel.appendChild(optionElementModel);
                        selectElementModel.selected = true;

                        let selectElementDepartmentModel = document.getElementById(
                            'deptCatModel');
                        let optionElementDeptCatModel = document.createElement('option');

                        optionElementDeptCatModel.value = response.category.id;
                        optionElementDeptCatModel.text = response.category.name;
                        selectElementDepartmentModel.appendChild(optionElementDeptCatModel);


                    },
                    error: function(xhr, status, error) {
                        setMessage('categoryModelError', xhr.responseJSON.message)
                    }
                })

            })

            document.getElementById('createStoreinItemModel').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = e.target;
                let name = form.elements['item_name_model'];
                let product_no = form.elements['pnumber_model'];
                let category_id = form.elements['categoryName_model'];
                let department_id = form.elements['department_id_model'];
                //    console.log('dpt',department_id.value);
                let unit_id = form.elements['unit_name_model'];
                let size_id = form.elements['size_model'];

                let status = form.elements['item_status_model'];
                if (!name.value && !product_no.value &&
                    !category_id.value && !department_id.value && !status.value) {
                    setMessage('categoryModelError', 'Please Fill out all fields')
                    return false;
                }
                $.ajax({
                    url: "{{ route('storeinItems.store') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: name.value,
                        pnumber: product_no.value,
                        category_id: category_id.value,
                        department_id: department_id.value,
                        unit_id: unit_id.value,
                        size_id: size_id.value,
                        status: status.value,
                    },
                    success: function(response) {

                        $('#createStoreinItemModelPopup').modal('hide');
                        let selectItemElement = document.getElementById('ProductName');
                        let optionElement = document.createElement('option');
                        optionElement.value = response.item.name;
                        optionElement.text = response.item.name;
                        selectItemElement.appendChild(optionElement);
                        $('#categorySelect').focus();

                    },
                    error: function(xhr, status, error) {
                        setMessage('itemModelError', xhr.responseJSON.message)
                    }
                })


            })

            function setMessage(element_id, message) {
                let errorContainer = document.getElementById(element_id);
                errorContainer.hidden = false;
                errorContainer.innerHTML = message;
                setTimeout(function() {
                    errorContainer.hidden = true;
                }, 2000);
            }
            setChargesFields();

            var chargeFieldsIndex = 1;
            let netTotal = 0;

            document.getElementById('discount_percent').addEventListener('change', function(event) {
                let discountValue = event.target.value;
                updateDiscountPercent(discountValue)
                updateTaxAmount();

            });

            function updateDiscountPercent(discountValue) {
                let discountAmt = document.getElementById('discount_amount');
                if (discountValue) {
                    discountAmt.disabled = true;
                    let total = document.getElementById('totalStoreIn').value;
                    let discountAmount = (parseInt(discountValue) * parseInt(total)) / 100;
                    netTotal = parseInt(total) - parseInt(discountAmount);
                    document.getElementById('net_total').value = netTotal;

                } else {
                    discountAmt.disabled = false;
                    document.getElementById('net_total').value = document.getElementById('totalStoreIn').value;
                }
            }
            document.getElementById('discount_amount').addEventListener('change', function(event) {
                let discountValue = event.target.value;
                updateDiscountAmount(discountValue);
                updateTaxAmount();
            });

            function updateDiscountAmount(discountValue) {
                let discountPercentage = document.getElementById('discount_percent');
                if (discountValue) {
                    discountPercentage.disabled = true;
                    let total = document.getElementById('totalStoreIn').value;
                    let netTotal = parseInt(total) - parseInt(discountValue);
                    document.getElementById('net_total').value = netTotal;
                } else {
                    discountPercentage.disabled = false;
                    document.getElementById('net_total').value = document.getElementById('totalStoreIn').value;
                }
            }

            function getChargeChange() {
                let chargeContainer = document.getElementById('chargesContainer');
                let ChargeChanges = chargeContainer.querySelectorAll('[id^="chargeSelect-"]');
                ChargeChanges.forEach(ChargeChange => {
                    $('#' + ChargeChange.id).on('select2:select', function(e) {
                        var data = e.params.data;
                        // let fieldNum =document.getElementById('chargeNum-'+extractIdFromID(chargeSelect.id));
                        if (data.text === 'VAT') {

                            let operator = document.getElementById('operationSign-' +
                                extractIdFromID(ChargeChange.id));
                            operator.value = "%";
                        }
                    });
                });
            }
            //charge.addEventListener('change',function(event){
            // let id= extractIdFromID(event.target.id);
            //let chargeValue= event.target.value;})

            function updateTaxAmount() {
                let chargeContainer = document.getElementById('chargesContainer');
                let chargeSelects = chargeContainer.querySelectorAll('[id^="chargeSelect-"]');
                chargeSelects.forEach(chargeSelect => {
                    let value = $('#' + chargeSelect.id).val();
                    let vatChargeNum = document.getElementById('chargeNum-' + extractIdFromID(chargeSelect
                        .id)).value;

                    if ($('#' + chargeSelect.id + " option[value='" + value + "']").text() == 'VAT') {

                        let fieldNum = document.getElementById('chargeNum-' + extractIdFromID(chargeSelect
                            .id));
                        if (fieldNum.value === null || fieldNum.value == 0 || fieldNum.value == "0") {
                            return false;
                        }
                        let net_amount = document.getElementById('net_total').value;
                        let vat_amount = (parseInt(net_amount) * parseInt(fieldNum.value)) / 100;
                        console.log(vat_amount);
                        document.getElementById('chargeTotal-' + extractIdFromID(chargeSelect.id)).value =
                            vat_amount;
                    }

                });


            }

            // Only run once when page load
            function setChargesFields() {
                let chargeIndex = 0;
                let chargesContainer = document.getElementById('chargesContainer');
                for (chargeFieldsIndex = 1; chargeFieldsIndex <= 3; chargeFieldsIndex++) {

                    let addedCharge = [];
                    let charges = [];
                    addedCharge = JSON.parse(`{!! json_encode($addedCharges) !!}`);
                    charges = JSON.parse(`{!! json_encode($charges) !!}`);

                    let options = charges
                        .filter(charge => charge.name !== 'Discount')
                        .map(charge =>
                            `<option ${addedCharge[chargeIndex] && addedCharge[chargeIndex].charge_id == charge.id ? 'selected' : ''}
                value="${charge.id}-${charge.name}">${charge.name}</option>`
                        ).join('');

                    chargesContainer.innerHTML += `<div class="col-md-6">
                            <div class="row" style="gap:4px;">
                            <div class="taxStyle">
                                <select id="chargeSelect-${chargeFieldsIndex}" class="advance-select-box  form-control @error('tax') is-invalid @enderror"
                                    name="chargeName[]" style="width:300px !important;height:30px !important;">
                                    <option value="" selected disabled>{{ __('-----Select a Charge----- ') }}</option>
                                    ${options}
                                </select>
                            </div>
                            <div>
                                <input type="number" value="${addedCharge[chargeIndex]?addedCharge[chargeIndex].charge_amount:'' }" id="chargeNum-${chargeFieldsIndex}" class="form-control"  name="chargeAmount[]" style="width: 100px" >
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="chargeOperator[]" id="operationSign-` +
                        chargeFieldsIndex + `">
                                    <option ${addedCharge[chargeIndex] && addedCharge[chargeIndex].charge_operator == "+" ?'selected':'' } value="+">+</option>
                                    <option ${addedCharge[chargeIndex] && addedCharge[chargeIndex].charge_operator == "-" ?'selected':'' }  value="-">-</option>
                                    <option ${addedCharge[chargeIndex] && addedCharge[chargeIndex].charge_operator == "%" ?'selected':'' }  value="%">%</option>
                                </select>
                            </div>
                            <input type="number" value="${addedCharge[chargeIndex]?addedCharge[chargeIndex].charge_total:'' }" id="chargeTotal-${chargeFieldsIndex}" class="form-control" name="chargeTotal[]" style="width: 100px" readonly>
                        </div>
                    </div>`;
                    chargeIndex++;
                }

                setEventListenerChargeField();
                setEventListenerOperatorField();
                //megha
                getChargeChange();
                // setEventListenerChargeSelect();
            }

            function setEventListenerChargeField() {
                let chargeContainer = document.getElementById('chargesContainer');
                let chargeNums = chargeContainer.querySelectorAll('[id^="chargeNum-"]');
                chargeNums.forEach(charge => {
                    charge.addEventListener('change', function(event) {
                        let id = extractIdFromID(event.target.id);
                        let chargeValue = event.target.value;

                        let operator = document.getElementById('operationSign-' + id);
                        let select = document.getElementById('chargeSelect-' + id);

                        if (chargeValue) {
                            if (operator.value == '%') {
                                let net_total = document.getElementById('net_total').value;
                                let percentValue = (parseInt(net_total) * parseInt(chargeValue)) /
                                    100;
                                document.getElementById('chargeTotal-' + id).value = percentValue;
                            } else {
                                document.getElementById('chargeTotal-' + id).value = chargeValue;
                            }
                            select.required = true;

                        } else {
                            select.required = false;
                            document.getElementById('chargeTotal-' + id).value = '';
                        }

                    })
                });
            }


            //check if the charge option is selected or not
            function setEventListenerChargeSelect() {

                let chargeContainer = document.getElementById('chargesContainer');
                let chargeSelects = chargeContainer.querySelectorAll('[id^="chargeSelect-"]');

                chargeSelects.forEach(chargeSelect => {
                    $('#' + chargeSelect.id).on('select2:select', function(e) {
                        var data = e.params.data;
                        let fieldNum = document.getElementById('chargeNum-' + extractIdFromID(
                            chargeSelect.id));
                        if (fieldNum.value === null || fieldNum.value == 0 || fieldNum.value ==
                            "0") {
                            return false;
                        }
                        if (data.text == 'Discount') {
                            let chargeFields = chargeContainer.querySelectorAll(
                                '[id^="chargeSelect-"]');
                            chargeFields.forEach(field => {
                                let value = $('#' + field.id).val();
                                let vatChargeNum = document.getElementById('chargeNum-' +
                                    extractIdFromID(field.id)).value;
                                if ($('#' + field.id + " option[value='" + value + "']")
                                    .text() == 'VAT') {
                                    if (vatChargeNum === null || vatChargeNum == 0 ||
                                        vatChargeNum == "0") {
                                        return false;
                                    }
                                    let chargeSelectedId = extractIdFromID(chargeSelect.id);
                                    let vatSelectedId = extractIdFromID(field.id);

                                    let totalValue = document.getElementById('totalStoreIn')
                                        .value;
                                    // Disount Fields
                                    let discountValue = fieldNum;
                                    let discountOperator = document.getElementById(
                                        'operationSign-' + chargeSelectedId).value;

                                    // Vat Fields
                                    let vatCharge = vatChargeNum;
                                    let vatOperator = document.getElementById(
                                        'operationSign-' + vatSelectedId).value;
                                    let discountedVatAmount = 0;
                                    let TotalAfterDiscount = 0;

                                    if (discountOperator == '%') {
                                        let discountedAmount = (parseInt(discountValue) *
                                            parseInt(totalValue)) / 100;
                                        TotalAfterDiscount = parseInt(totalValue) -
                                            parseInt(discountedAmount);
                                    } else {
                                        TotalAfterDiscount = parseInt(totalValue) -
                                            parseInt(discountValue);
                                    }
                                    if (vatOperator == '%') {
                                        let discountedVatAmount = (parseInt(vatCharge) *
                                            parseInt(TotalAfterDiscount)) / 100;
                                    } else {
                                        discountedVatAmount = parseInt(vatCharge);

                                    }
                                    console.log()
                                    document.getElementById('chargeTotal-' + vatSelectedId)
                                        .value = discountedVatAmount;



                                    // console.log(discountValue);
                                }
                            });

                        }
                    });
                });
            }

            function setEventListenerOperatorField() {
                let chargeContainer = document.getElementById('chargesContainer');
                let operatorOptions = chargeContainer.querySelectorAll('[id^="operationSign-"]');
                operatorOptions.forEach(operatorOption => {
                    operatorOption.addEventListener('change', function(e) {
                        let id = extractIdFromID(e.target.id);
                        let chargeNum = document.getElementById('chargeNum-' + id);
                        if (chargeNum == 0 && chargeNum == null) {
                            return false;
                        }
                        let net_totalValue = document.getElementById('net_total').value;
                        let chargeValue = chargeNum.value;
                        if (e.target.value == '%') {

                            let percentAmount = parseInt(net_totalValue) * parseInt(chargeValue) /
                                100;
                            document.getElementById('chargeTotal-' + id).value = percentAmount;
                        } else {
                            document.getElementById('chargeTotal-' + id).value = chargeValue;
                        };
                    })
                })
            }

            function extractIdFromID(ids) {
                const splitIdAndString = ids.split('-');
                return splitIdAndString[1];
            }

            setTimeout(function() {
                var alertMessage = document.getElementById('alert-message');
                if (alertMessage) {
                    alertMessage.remove();
                }
            }, 3000);


            $('#categoryNameModel').on('select2:select', function(e) {
                let category_id = e.params.data.id;
                // let click_by=blade;
                getDepartentAccCat(category_id);
            });

            $('#categoryitem_id').on('select2:select', function(e) {
                let category_id = e.params.data.id;
                // let click_by=blade;
                getCategoryItems(category_id, 'model');
            });

            $('#item_id').on('select2:select', function(e) {
                let product_id = e.params.data.id;
                console.log(product_id);
                getItemsDepartment(product_id);

            });

            $('#ProductName').select2({
                theme: 'bootstrap4',
                ajax: {
                    method: 'GET',
                    url: "{{ route('storein.getcategoryItems') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        let category_id = $('#categorySelect').val();
                        return {
                            category_id: category_id,
                            query: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.data,
                            pagination: {
                                more: data.next_page_url ? true : false
                            }
                        };
                    }
                },
                placeholder: 'Select a user',
                minimumInputLength: 0
            });



            $('#categorySelect').on('select2:select', function(e) {
                let category_id = e.params.data.id;
                getCategoryItems(category_id, 'blade');
            });

            $('#ProductName').on('select2:select', async function(e) {
                let item_name = e.params.data.id;
                let category_id = $('#categorySelect').val();

                let object = await getDepartmentSizeUnit(item_name, category_id);
                fillOptionInSelect(object.department, '#departmentId');
                fillOptionInSelect(object.size, '#SizeId');
                fillOptionInSelect(object.units, '#unitId');
            });

            function getDepartentAccCat(category_id) {
                return new Promise(function(resolve, reject) {
                    $.ajax({
                        url: "{{ route('storein.getDepartentAccCat', ['category_id' => ':Replaced']) }}"
                            .replace(
                                ':Replaced',
                                category_id),
                        method: 'GET',
                        success: function(response) {
                            let selectOptions = '';
                            if (response.length == 0) {
                                selectOptions += '<option disabled selected>' +
                                    'no department found' + '</option>';
                            } else {
                                selectOptions += '<option disabled selected>' +
                                    'select department' + '</option>';

                                for (let i = 0; i < response.length; i++) {
                                    let optionText = response[i].name;
                                    let optionValue = response[i].id;
                                    let option = new Option(optionText, optionValue);
                                    selectOptions += option.outerHTML;
                                }
                            }
                            $('#departmentModel').html(selectOptions);
                            resolve(response);
                        },
                        error: function(xhr, status, error) {
                            reject(error);
                        }
                    });
                });
            }

            function getDepartmentSizeUnit(item_name, category_id) {
                return new Promise(function(resolve, reject) {
                    $.ajax({
                        url: "{{ route('storein.getDepartmentSizeUnit', ['items_of_storein_name' => ':Replaced', 'category_id' => ':category']) }}"
                            .replace(':Replaced', item_name)
                            .replace(':category', category_id),
                        method: 'GET',
                        success: function(response) {
                            resolve(response);
                        },
                        error: function(xhr, status, error) {
                            reject(error);
                        }
                    });
                });
            }

            function fillOptionInSelect(obj, element_id) {
                let selectOptions = '';
                if (obj.length == 0) {
                    selectOptions += "<option value='' disabled selected>No Data found</option>";
                } else {
                    selectOptions += "<option value='' disabled selected> select required data </option>";
                    for (let i = 0; i < obj.length; i++) {
                        let optionText = obj[i].name;
                        let optionValue = obj[i].id;
                        let option = new Option(optionText, optionValue);
                        selectOptions += option.outerHTML;
                    }
                }
                $(element_id).html(selectOptions);
            }

            function getUnitOfItemsOfStorin(item_name) {
                let myPromise = new Promise(function(resolve, reject) {
                    $.ajax({
                        url: "{{ route('storein.getUnitOfItems', ['items_of_storein_name' => ':Replaced']) }}"
                            .replace(
                                ':Replaced',
                                item_name),

                        method: 'GET',
                        success: function(response) {
                            //only unit found
                            let selectOptions = '';

                            if (response.length == 0) {
                                selectOptions += '<option disabled selected>' +
                                    'no size found' + '</option>';
                            } else {
                                selectOptions += '<option disabled selected>' +
                                    'select Size item' + '</option>';

                                for (let i = 0; i < response.length; i++) {
                                    let optionText = response[i].size.name;
                                    let optionValue = response[i].size.id;
                                    let option = new Option(optionText, optionValue);
                                    selectOptions += option.outerHTML;
                                }

                            }
                            $('#SizeId').html(selectOptions);
                            resolve(response);

                        },
                        error: function(xhr, status, error) {
                            reject(error);
                        }
                    });
                });
            }

            function getItemsDepartment(items_of_storein_name) {
                let myPromise = new Promise(function(resolve, reject) {
                    $.ajax({
                        url: "{{ route('storein.getItemsDepartment', ['items_of_storein_name' => ':Replaced']) }}"
                            .replace(
                                ':Replaced',
                                items_of_storein_name),

                        method: 'GET',
                        success: function(response) {
                            let selectOptions = '';

                            if (response.length == 0) {
                                selectOptions += '<option disabled selected>' +
                                    'no department found' + '</option>';
                            } else {
                                selectOptions += '<option disabled selected>' +
                                    'select department' + '</option>';

                                for (let i = 0; i < response.length; i++) {
                                    let optionText = response[i].storein_department.name;
                                    let optionValue = response[i].storein_department.id;
                                    let option = new Option(optionText, optionValue);
                                    selectOptions += option.outerHTML;
                                }

                            }
                            $('#departmentId').html(selectOptions);
                            resolve(response);

                        },
                        error: function(xhr, status, error) {
                            reject(error);
                        }
                    });
                });
            }

            function getCategoryItems(category_id, click_by = 'blade') {
                return new Promise(function(resolve, reject) {

                    $.ajax({
                        method: 'GET',
                        url: "{{ route('storein.getcategoryItems') }}",
                        data: {
                            category_id: category_id,
                        },
                        success: function(response) {
                            let selectOptions = '';
                            if (response.data.length == 0) {
                                selectOptions += '<option disabled selected>' +
                                    'no items found' + '</option>';
                            } else {
                                selectOptions += '<option disabled selected>' +
                                    'select an item' + '</option>';

                                for (let i = 0; i < response.data.length; i++) {
                                    let optionText = response.data[i].name;
                                    let optionValue = response.data[i].name;
                                    let option = new Option(optionText, optionValue);
                                    selectOptions += option.outerHTML;
                                }

                            }
                            if (click_by == 'blade') {
                                $('#ProductName').html(selectOptions);
                                resolve(response);

                            } else {
                                $('#item_id').html(selectOptions);
                                resolve(response);
                            }

                        },
                        error: function(xhr, status, error) {
                            reject(error);
                        }
                    });
                });
            }

            let sn = 1;
            let selectedPercentage = 0;
            // setTimeout(loadWhenPageLoad, 2000);

            function loadWhenPageLoad() {
                let transport_cost = document.getElementById('transport_cost').value;
                // check whether transport cost is empty or not
                triggerTransport(transport_cost);
            }


            //Edit
            document.getElementById('editStoreinItem').addEventListener('submit', function(event) {
                event.preventDefault();
                const form = event.target;
                let storeinItem_id = form.elements['storeInItem_id'].value;
                let category_id = form.elements['category_id'].value;
                let product_id = form.elements['item_id'].value;
                let quantities = form.elements['quantity'].value;
                let unit_id = form.elements['unit_id'].value;
                let unit_price = form.elements['unitPrice'].value;

                $.ajax({
                    url: "{{ route('storein.EditItemStoreData') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        storein_item_id: storeinItem_id,
                        category_id: category_id,
                        product_id: product_id,
                        quantity: quantities,
                        unit_id: unit_id,
                        unit_price: unit_price,
                        // Goes Into Request
                    },
                    success: function(response) {

                        $('#editItemModal').modal('hide');
                        // Updating changes into table row
                        updateTableRow(response, storeinItem_id);
                        // after data is updated calculation of total amt
                        totalAmountCalculation();
                    },
                    error: function(xhr, status, error) {
                        // handle error response here
                    }
                });
            });
            // trigger transport cost input field
            $('#transport_cost').on('blur', function(event) {

                let transport_cost = $(this).val();
                triggerTransport(transport_cost);
            });

            function triggerTransport(transport_cost) {
                if (transport_cost == null || transport_cost == '') {
                    calculateVatAmount();
                    return false;
                }
                let selectTax = document.getElementById("tax");
                let grandTotal = document.getElementById('grand_total').value;
                let discountedTotal = document.getElementById('discountedTotal').value;
                let dueAmount = document.getElementById('due_amount');


                // when vat and transport cost is selected
                if (selectTax.selectedIndex > 0 && transport_cost) {
                    calculateVatAmount();
                }
                //tax not selected and transport cost is selected
                else if (selectTax.selectedIndex == 0 && transport_cost) {
                    let total_amount = parseInt(discountedTotal) + parseInt(transport_cost);
                    document.getElementById('grand_total').value = total_amount;
                    dueAmount.value = total_amount;
                }
            }

            // trigger event when total paid is there
            $('#totalPayment').on('blur', function(event) {
                let total_Paid = $(this).val();
                calculateTotalPayment(total_Paid);
            });

            function calculateTotalPayment(total_Paid) {
                let grand_total = document.getElementById('grand_total').value;
                let due_amount = parseInt(grand_total) - parseInt(total_Paid);
                if (due_amount < 0) {
                    alert('You Cannot pay more than grand total amount');
                    return false;
                }
                document.getElementById('due_amount').value = due_amount;
            }


            // recent
            $('#tax').on('change', async function() {
                calculateVatAmount();
            });

            async function calculateVatAmount() {
                slugVal = document.getElementById('tax').value;

                // when $slugVal is not assign
                if (slugVal == null || !slugVal) {
                    $discount_total = document.getElementById('discountedTotal').value;
                    document.getElementById('grand_total').value = $discount_total;
                    return false;
                }
                let taxPercent = await getVatPercentage(slugVal);
                let discountTotal = document.getElementById('discountedTotal').value;
                let taxAmount = (parseInt(discountTotal) * parseInt(taxPercent)) / 100;
                let dueAmount = document.getElementById('due_amount');

                let transport_cost = document.getElementById('transport_cost').value;
                if (transport_cost == '' || transport_cost == null) {
                    let amount = parseInt(discountTotal) + parseInt(taxAmount);
                    document.getElementById('grand_total').value = amount;
                    dueAmount.value = amount;
                } else {
                    let amount = parseInt(discountTotal) + parseInt(taxAmount) + parseInt(
                        transport_cost);
                    document.getElementById('grand_total').value = amount;
                    dueAmount.value = amount;
                }

                let totalPayment = document.getElementById('totalPayment').value;
                calculateTotalPayment(totalPayment);
            }

            async function getVatPercentage(slug) {
                return new Promise(function(resolve, reject) {
                    $.ajax({
                        url: "{{ route('tax.getPercentageBySlug', ['slug' => ':toBeReplaced']) }}"
                            .replace(
                                ':toBeReplaced',
                                slug),
                        method: 'GET',
                        success: function(response) {
                            resolve(response.percentage);
                        },
                        error: function(xhr, status, error) {
                            reject(error);
                        }
                    });
                });

            }

            function totalAmountCalculation() {
                const myTable = document.getElementById("storeInItemTable");

                // Get the tbody element of the table
                const tbody = myTable.querySelector("tbody");

                // Get all the tr elements in the tbody
                const rows = tbody.querySelectorAll("tr");

                // Loop through the rows and do something with them
                let eachTotalAmount = 0;

                rows.forEach(row => {
                    eachTotalAmount += parseInt(row.querySelector('td.rowTotalAmount')
                        .innerHTML);
                });



                document.getElementById('totalStoreIn').value = eachTotalAmount;
            }


            // Updating Table tr td value when something changed or updated
            function updateTableRow(response, storeinItem_id) {
                // triggering table tr by storeinItem_id
                let row = document.getElementById('editRow-' + storeinItem_id);

                //Updating tds
                row.querySelector('td.rowQuantity').innerHTML = response.quantity;
                row.querySelector('td.rowsize_id').innerHTML = response.size_id;
                row.querySelector('td.rowItemName').innerHTML = response.item.item;
                row.querySelector('td.rowUnitName').innerHTML = response.unit.name;
                row.querySelector('td.rowPrice').innerHTML = response.price;
                row.querySelector('td.rowTotalAmount').innerHTML = response.total_amount;

            }

            dataRetrive();

            //Validation start

            function validateForm(formElement, validationRules) {
                let isValid = true;

                validationRules.forEach((rule) => {
                    const {
                        fieldName,
                        required,
                        validationFunction,
                        errorMessage
                    } = rule;
                    const fieldValue = formElement.elements[fieldName].value.trim();

                    if (required && fieldValue === '') {
                        console.error(errorMessage);
                        isValid = false;
                    }

                    if (validationFunction && !validationFunction(fieldValue)) {
                        console.error(errorMessage);
                        isValid = false;
                    }
                });

                return isValid;
            }

            // Usage example
            const validationRules = [{
                    fieldName: 'size_id',
                    required: true,
                    errorMessage: 'Size ID is required.',
                },
                {
                    fieldName: 'categoryName',
                    required: true,
                    errorMessage: 'Category ID is required.',
                },
                {
                    fieldName: 'ProductName',
                    required: true,
                    errorMessage: 'Product ID is required.',
                },
                {
                    fieldName: 'quantities',
                    required: true,
                    validationFunction: (value) => !isNaN(value) && parseFloat(value) > 0,
                    errorMessage: 'Quantities must be a positive number.',
                },
                {
                    fieldName: 'unit_id',
                    required: true,
                    errorMessage: 'Unit ID is required.',
                },
                {
                    fieldName: 'unitPrices',
                    required: true,
                    validationFunction: (value) => !isNaN(value) && parseFloat(value) > 0,
                    errorMessage: 'Unit price must be a positive number.',
                },
                {
                    fieldName: 'department_id',
                    required: true,
                    errorMessage: 'Department ID is required.',
                },
            ];


            //Validation end




            //Creating New Storein Item and Adding into Table
            document.getElementById('createStoreInItem').addEventListener('submit', function(event) {
                event.preventDefault();
                const form = event.target;
                let size_id = form.elements['size_id'].value;
                let category_id = form.elements['categoryName'].value;
                let product_id = form.elements['ProductName'].value;
                let quantities = form.elements['quantities'].value;
                let unit_id = form.elements['unit_id'].value;
                let unit_price = form.elements['unitPrices'].value;
                let department_id = form.elements['department_id'].value;
                // validation js
                const isFormValid = validateForm(form, validationRules);
                if (!isFormValid) {
                    setMessage('storeinItemError', 'Please Fill out all fields')
                    return false;
                }
                $.ajax({
                    url: '{{ route('storein.saveStoreinItems', ['id' => $storein->id]) }}',
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        category_id: category_id,
                        item_id: product_id,
                        quantity: quantities,
                        unit_id: unit_id,
                        unit_price: unit_price,
                        size_id: size_id,
                        department_id: department_id

                        // Goes Into Request
                    },
                    success: function(response) {
                        console.log('table item', response);
                        setIntoTable(response);
                        // calculate total amount
                        totalAmountCalculation();

                        document.getElementById('net_total').value = document
                            .getElementById('totalStoreIn').value;
                        // Add Edit Event Listener
                        editEventBtn();
                        // Add Delete Event Listener
                        deleteEventBtn();
                        checkRowInTable();




                    },
                    error: function(xhr, status, error) {
                        // handle error response here
                    }
                });
            });

            // function for creating a new row in table
            function setIntoTable(res) {

                var html = "";

                html = "<tr id=editRow-" + res.id + "><td>" + sn +
                    "</td><td class='rowItemName'>" + res.items_of_storein.name +
                    "</td><td class='rowDepartmentName'>" + res.storein_department.name +
                    "</td><td class='rowsize_id'>" + res.size.name +
                    "</td><td class='rowQuantity'>" + res.quantity +
                    "</td><td class='rowUnitName'>" + res.unit.name +
                    "</td><td class='rowPrice'>" + res.price +
                    "</td><td class='rowTotalAmount'>" + res.total_amount +
                    "</td> <td>" +
                    // "<button class='btn btn-success editItemBtn' data-id=" +
                    // res.id + "><i class='fas fa-edit'></i></button>" +
                    // "  " +
                    "<button class='btn btn-danger dltstoreinItem' data-id=" +
                    res.id + " ><i class='fas fa-trash-alt'></i> </button>" + "</td ></tr>";

                document.getElementById('result').innerHTML += html;
                sn++;
                // Clearing the input fields
                clearInputFields();
            }

            function clearInputFields() {
                document.getElementById('categorySelect').value = "";
                document.getElementById('ProductName').value = "";
                document.getElementById('qunatities').value = "";
                document.getElementById('unitPrices').value = "";
                document.getElementById('singleTotal').value = "";
            }

            function removeAllTableRows() {
                // Reseting SN
                sn = 1;
                let tbody = document.querySelector("#storeInItemTable tbody");
                for (var i = tbody.rows.length - 1; i >= 0; i--) {
                    tbody.deleteRow(i);
                }
            }

            // when page is refressed
            function dataRetrive() {
                removeAllTableRows();
                $.ajax({
                    url: '{{ route('storein.storeInItemsRetrive', ['storein_id' => $storein->id]) }}',
                    method: 'GET',
                    success: function(response) {
                        console.log('table item', response);
                        if (response.storein_items.length <= 0) {
                            return false;
                        }
                        response.storein_items.forEach(itemsRow => {
                            setIntoTable(itemsRow);
                        });
                        if (response.storein_items.length > 0) {
                            // alert('fghjk');
                            editEventBtn();
                            deleteEventBtn();

                        }
                        totalAmountCalculation();

                        document.getElementById('net_total').value = document
                            .getElementById('totalStoreIn').value;
                        //Megha
                        let discountAmt = document.getElementById('discount_amount');
                        let discountPercentage = document.getElementById('discount_percent');
                        if (discountAmt.value) {
                            updateDiscountAmount(discountAmt.value);
                        } else if (discountPercentage.value) {
                            updateDiscountPercent(discountPercentage.value);
                        }
                        updateTaxAmount();
                        checkRowInTable();



                        // console.log(response);
                    },
                    error: function(xhr, status, error) {
                        // handle error response here
                    }
                });
            }

            function deleteEventBtn() {
                let deleteButtons = document.getElementsByClassName('dltstoreinItem');
                //console.log(deleteButtons);
                for (var i = 0; i < deleteButtons.length; i++) {
                    deleteButtons[i].addEventListener('click', function(event) {
                        let itemId = this.getAttribute('data-id');
                        // console.log(itemId);
                        new swal({
                                title: "Are you sure?",
                                text: "Do you want to delete Item.",
                                icon: "warning",
                                buttons: true,
                                dangerMode: true,
                                closeOnClickOutside: false,
                            })
                            .then((willDelete) => {
                                if (willDelete) {
                                    $.ajax({
                                        url: '{{ route('storein.storeinItemDelete', ['id' => ':lol']) }}'
                                            .replace(':lol', itemId),
                                        type: "DELETE",
                                        data: {
                                            "_method": "DELETE",
                                            "_token": "{{ csrf_token() }}",
                                        },
                                        success: function(result) {


                                            dataRetrive();
                                            totalAmountCalculation();
                                            new swal({
                                                title: "Success",
                                                text: "Data deleted",
                                                type: 'success',
                                                timer: '1500'
                                            });
                                            checkRowInTable();
                                        },
                                        error: function(result) {
                                            new swal({
                                                title: "Error",
                                                text: "something went wrong",
                                                type: 'error',
                                                timer: '1500'
                                            });
                                        }
                                    });
                                }
                            });

                    });
                }
            }

            function editEventBtn() {
                // Assign event listener to buttons with class 'editItemBtn'
                let editButtons = document.getElementsByClassName('editItemBtn');
                for (var i = 0; i < editButtons.length; i++) {
                    editButtons[i].addEventListener('click', function(event) {
                        let itemId = this.getAttribute('data-id');
                        //console.log('select id item =',itemId);
                        $.ajax({
                            url: '{{ route('storein.getEditItemData', ['storeinItem_id' => ':lol']) }}'
                                .replace(':lol', itemId),
                            method: 'GET',
                            success: async function(response) {

                                $('#storein_id').val(response.storein_id);
                                $('#storeinItem_id').val(response.id);
                                $('#quantity').val(response.quantity);
                                $('#unit_id').val(response.unit_id);
                                $('#price').val(response.price);
                                //console.log('category',response.category_id)
                                $('#categoryitem_id').val(response
                                    .storein_category_id).trigger('change');
                                let category = await getCategoryItems(response
                                    .storein_category_id, 'model');

                                $('#item_id').val(response.storein_item_id).trigger(
                                    'change');
                                $('#editItemModal').modal('show');

                            },
                            error: function(xhr, status, error) {
                                console.error('Error:', error);
                            }
                        });


                    });
                }
            }

            setTimeout(function() {
                $('#error-container').fadeOut('fast');
            }, 3000); // 5000 milliseconds = 5 seconds
        });
    </script>
@endsection
