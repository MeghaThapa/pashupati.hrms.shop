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
            margin-top: 5px !important;
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

    {{-- message for success --}}
    <div id="success_msg" class="alert alert-success mt-2" hidden>

    </div>
    {{-- message for error --}}
    <div id="error_msg" class="alert alert-danger mt-2" hidden>

    </div>

    @if ($errors->any())
        <div id="error-container" class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card-body p-0 m-0">
        <div id="formdiv">
            <form id="createStoreOutItem">
                @csrf
                {{-- Item --}}
                <div class="row">
                    {{-- storeout it passed from here --}}
                    <input type="text" class="form-control" value="{{ $storeOut->id }}" id="storeOut_id" data-number="1"
                        name="store_out_id" placeholder="{{ __('store_out_id') }}" hidden>
                    <div class="col-md-2 form-group">
                        <label for="Category" class="col-form-label">{{ __('Category Name') }}<span
                                class="required-field">*</span>
                        </label>
                        <select class="advance-select-box form-control  @error('Category') is-invalid @enderror"
                            id="categorySelect" name="categoryName" required>
                            <option value="" selected disabled>{{ __('Select a Category') }}</option>
                            @foreach ($stockCategory as $category)
                                <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                        @error('categoryName')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="item_name" style="width:80px !important;"
                            class="col-form-label">{{ __('Item Name') }}</label>
                        <select class="form-control" id="items" name="item_id" required>
                            <option value="" selected disabled>{{ __('Select an item') }}</option>

                        </select>
                        @error('item_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-2" style="gap:3px;">
                        <label for="size" class="col-form-label">{{ __('Size') }}</label>

                        <select class="advance-select-box form-control" id="size" name="size" required>
                            <option value="" selected disabled>{{ __('Select a size') }}</option>
                        </select>
                        @error('size')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-2" style="gap:3px;">
                        <label for="size" class="col-form-label">{{ __('Unit') }}</label>

                        <select class="advance-select-box form-control" id="unit" name="unit" required>
                            <option value="" selected disabled>{{ __('Select a unit') }}</option>
                        </select>
                        @error('unit')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-2" style="gap:3px;">
                        <label for="size" class="col-form-label">{{ __('Department') }}</label>
                        <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal" tabindex="-1"
                            data-target="#storeoutDepartmentModel" style="margin-top:0 !important; top:8px;float:right;">
                            <i class="fas fa-plus" style="display:flex;align-items: center;justify-content: center;"></i>
                        </a>
                        {{-- megha --}}
                        <select class="advance-select-box form-control" id="storeoutDepartments" name="storeout_departments"
                            required>
                            <option value="" selected disabled>{{ __('Select a department') }}</option>
                            @foreach ($storeoutDepartment as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                        @error('department')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>


                </div>
                <div class="row mt-1">
                    {{-- placement --}}
                    <div class="col-md-3" style="gap:3px;">
                        <label for="category" class="col-form-label">{{ __('Placement') }}</label>
                        <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal" tabindex="-1"
                            data-target="#placementCreateModel" style="margin-top:0 !important; top:8px;float:right;">
                            <i class="fas fa-plus" style="display:flex;align-items: center;justify-content: center;"></i>
                        </a>
                        <select class="advance-select-box form-control" id="placementSelect" name="placement_id" required>
                            <option value="" selected disabled>{{ __('Select a Placement') }}</option>
                        </select>

                        @error('placment')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    {{-- stock quantity --}}
                    <div class="col-md-2">
                        <label for="stock_quantity" class="col-form-label">{{ __('Stock Quantity') }}</label>
                        <input type="number" class="form-control " id="stock_quantity" name="stock_quantity"
                            data-ignore placeholder="{{ __('Stock Quantity') }}" readonly tabindex="-1">
                        @error('stock_quantity')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    {{-- stock rate --}}
                    <div class="col-md-2">
                        <label for="rate" class="col-form-label">{{ __('Rate') }}</label>
                        <input type="number" class="form-control " id="rate" name="rate" data-ignore
                            placeholder="{{ __('Rate') }}" readonly tabindex="-1">
                        @error('rate')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    {{-- quantity --}}
                    <div class="col-md-2 gap-2">
                        <label for="item_name" style="width:80px !important;"
                            class="col-form-label">{{ __('Quantity') }}</label>
                        <input type="text" class="form-control " id="quantity" data-number="1" name="quantity"
                            placeholder="{{ __('quantity') }}">
                        @error('quantity')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-3 " style="gap:3px;">
                        <label for="category" class="col-form-label">{{ __('Through') }}</label>
                        <input type="text" class="form-control " id="through" name="through"
                            placeholder="{{ __('Through') }}" required>
                        @error('through')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>



                </div>
                <div class="col-md-4" style="padding:0px !important">
                    <button type="submit" id="storeoutSubmitBtn" style="width:150px;" class="btn btn-primary"
                        style=" padding:14px!important; margin-button:40px ;margin-left:10px!important;">
                        Add
                    </button>
                </div>
            </form>
        </div>
        {{-- table --}}
        <div class="row">
            <div class="Ajaxdata col-md-12">
                <div class="p-0 table-responsive table-custom my-3">
                    <table class="table" id="storeOutItemTable">
                        <thead>
                            <tr>
                                <th>{{ __('S.No') }}</th>
                                <th>{{ __('Item Name') }}</th>
                                <th>{{ __('Size') }}</th>
                                <th>{{ __('Quantity') }}</th>
                                <th>{{ __('Unit') }}</th>
                                <th>{{ __('Rate') }}</th>
                                <th>{{ __('Total') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>

                        <tbody id="storeOutTBody">
                        </tbody>

                    </table>

                    {{-- for sub total --}}
                    {{-- <input type="text" id="subTotal"> --}}
                </div>

            </div>
        </div>
        <form action="{{ route('storeout.saveEntireStoreOut', ['storeout_id' => $storeOut->id]) }}" method="POST">
            @csrf
            <div class=" row" style="margin:0px; padding:0px;justify-content:space-between; ">
                <div class="col-md-3 d-flex" style="margin-right:8px;gap:5px">
                    <label for="purchaseImage" class="col-form-label">{{ __('Total') }}</label>
                    <input type="number" step="any" min="0" style="width:150px; "
                        class="form-control form-control-sm @error('total') is-invalid @enderror" id="totalStoreOut"
                        name="storeOutTotal" placeholder="{{ __('Total') }}" readonly>
                </div>
                <div class="col-md-3 d-flex" style="margin-right:8px;gap:5px">
                    <label for="purchaseImage" class="col-form-label">{{ __('Remark') }}</label>
                    <input type="text" step="any" min="0" style="width:250px; "
                        @if ($storeOut->remark) value="{{ $storeOut->remark }}" @endif
                        class="form-control form-control-sm" id="storeoutRemark" name="store_out_remark"
                        placeholder="{{ __('remark') }}">
                </div>
            </div>


            <div class="row">
                <div class="col-sm-10">
                    <button id="saveStoreOutButton" type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                        {{ __('Save Store Out') }}
                    </button>
                </div>
            </div>
        </form>

    </div>

    <!--Placement Model popup-->
    <div class="modal fade" id="placementCreateModel" tabindex="-1" role="dialog" aria-labelledby="exampleModaltax"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModaltax">Add Placement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="modelFormPlacement">
                        @csrf
                        <div class="card-body">
                            <div id="form-error" class="alert alert-danger" hidden>

                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="department">{{ __('For') }}<span
                                            class="required-field">*</span></label>
                                    <select
                                        class="advance-select-box form-control @error('model_godam') is-invalid @enderror"
                                        id="modelGodam" name="model_godam_id">
                                        <option value="" selected disabled>{{ __('Select a godam') }}</option>
                                        @foreach ($godams as $godam)
                                            <option value="{{ $godam->id }}">{{ $godam->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="department">{{ __('Department') }}<span
                                            class="required-field">*</span></label>
                                    <select
                                        class="advance-select-box form-control @error('department') is-invalid @enderror"
                                        id="modelDepartment" name="model_dept_id">
                                        <option value="" selected disabled>{{ __('Select a Department') }}</option>
                                        @foreach ($storeoutDepartment as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="name">{{ __('Placement name') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('placement') is-invalid @enderror"
                                        id="modelPlacement" name="model_placement" placeholder="{{ __('Placement') }}"
                                        value="{{ old('placement') }}" required>
                                    @error('placement')
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


                        <div class="row">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                                    {{ __('Save Placement') }}</button>
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
    <!--Placement Model Popup End-->

    {{-- department model popup --}}
    <div class="modal fade" id="storeoutDepartmentModel" tabindex="-1" role="dialog"
        aria-labelledby="addDepartmentModel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="createStoreoutDepartment">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addDepartmentModel">Add Storeout Department</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="card-body">
                            <div id="form-dpt-error" class="alert alert-danger" hidden>

                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="department" class="col-form-label">{{ __('Department Name') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('company') is-invalid @enderror"
                                        id="storeoutDptName" name="storeout_dpt_name"
                                        placeholder="{{ __('Department Name') }}" value="{{ old('department') }}"
                                        required>
                                    @error('company')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                    <select class="form-control" id="storeoutDeptStatus" name="storeout_dept_status"
                                        required>
                                        <option value="active">{{ __('Active') }}</option>
                                        <option value="inactive">{{ __('Inactive') }}</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <!-- /.card-body -->

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                            {{ __('Save Storeout Department') }}</button>

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
    <!--edit storeout popup-->

    <div class="modal fade" id="editStoreoutModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalsize"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalsize">Add Size</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="updateStoreOutItem">
                        <div class="card-body">
                            <div id="edit-form-error" class="alert alert-danger" hidden>

                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="name">{{ __('Item Name') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="storeOutItemId" name="storeOut_item_id" value=""
                                        placeholder="{{ __('Item Name') }}" value="{{ old('nameModel') }}" required
                                        hidden>
                                    <select class="advance-select-box form-control" id="itemNameModel" name="name_model"
                                        required>
                                        <option value="" selected disabled>{{ __('Select an item') }}</option>
                                        {{-- @foreach ($items as $key => $stock)
                                         @if ($stock && $stock->item)
                                            <option value="{{ $stock->item_id }}">{{ $stock->item->name }}
                                            </option>
                                        @endif
                                        @endforeach --}}
                                    </select>
                                    @error('nameModel')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="sizeCode">{{ __('Quantity') }}<span
                                            class="required-field">*</span></label>
                                    <input type="number" class="form-control @error('sizeCode') is-invalid @enderror"
                                        id="itemQuantityModel" name="quantityModel" placeholder="{{ __('Quantity') }}"
                                        value="{{ old('quantityModel') }}" required>
                                    @error('quantityModel')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="departmentIdModel" class="col-form-label">{{ __('Department') }}</label>
                                    {{-- <input type="number" class="form-control @error('sizeCode') is-invalid @enderror"
                                        id="departmentIdModel" name="department_id_model"
                                        placeholder="{{ __('Department') }}" value="{{ old('department_id_model') }}"
                                        required> --}}
                                    <select class="advance-select-box form-control" id="departmentIdModel"
                                        name="department_id_model" required>
                                        <option value="" selected disabled>{{ __('Select a Department') }}</option>
                                        {{-- @foreach ($storeinDepartment as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach --}}
                                    </select>
                                    @error('department_id_model')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="placementIdModel" class="col-form-label">{{ __('Placement') }}</label>
                                    <select class="advance-select-box form-control" id="placementIdModel"
                                        name="placement_id_model" required>
                                        <option value="" selected disabled>{{ __('Select a Placement') }}</option>
                                    </select>
                                    @error('placement_id_model')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="throughModel" class="col-form-label">{{ __('Through') }}</label>
                                    <input type="text" class="form-control" id="throughModel" name="through_model"
                                        placeholder="{{ __('Through') }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                                        {{ __('Update StoreOut Item') }}</button>
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

    <!--edit storeout Popup End-->
@endsection

@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/storein.js') }}"></script>
    <script>
        $(document).ready(function() {
            //When Page Refresh
            getStoreOutItems();

            //  checkIfTableHasData();
            $('#categorySelect').focus();
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });

            $('#items').select2({
                theme: 'bootstrap4',
                ajax: {
                    method: 'GET',
                    url: "{{ route('storeout.getStoreinItemAccCat') }}",
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


            //for user accessibility
            let formDiv = document.getElementById("formdiv");
            let currentIndex = -1;
            let focusableElements = Array.from(formDiv.querySelectorAll("input, select,button")).filter(function(
                element) {
                return !element.hasAttribute("data-ignore");
            });



            formDiv.addEventListener("keydown", function(event) {
                // Check if the pressed key is the tab key (key code 9)
                if (event.keyCode === 9) {
                    event.preventDefault(); // Prevent the default tab behavior
                    var nextIndex = (currentIndex + 1) % focusableElements.length;
                    focusableElements[nextIndex].focus();
                    currentIndex = nextIndex;
                }
            });

            setTimeout(function() {
                var alertMessage = document.getElementById('alert-message');
                if (alertMessage) {
                    alertMessage.remove();
                }
            }, 3000);


            // Hide the error message after 5 seconds
            setTimeout(function() {
                $('#error-container').fadeOut('fast');
            }, 3000); // 5000 milliseconds = 5 seconds

            $('#departmentIdModel').on('select2:select', function(e) {
                let department_id = e.params.data.id;
                getDepartmentPlacement(department_id, 'model');
            });

            $('#categorySelect').on('select2:select', function(e) {
                let category_id = e.params.data.id;
                getStoreinItemAccCat(category_id);
            });

            $('#unit, #categorySelect, #items, #size').on('select2:select', function(e) {
                let unit_id = $('#unit').val();
                let category_id = $('#categorySelect').val();
                let item_name = $('#items').val();
                let side_id = $('#size').val();
                if (unit_id && category_id && item_name && side_id) {
                    getStockQtyRate(category_id, item_name, side_id, unit_id);
                }
            });


            $('#storeoutDepartments').on('select2:select', function(e) {
                let department_id = e.params.data.id;
                let storeout_id = JSON.parse(`{!! json_encode($storeOut->id) !!}`);
                getDepartmentPlacement(department_id, 'blade', storeout_id);
            });

            function getStockQtyRate(category_id, item_name, side_id, unit_id) {
                $.ajax({
                    url: "{{ route('storeout.getStockQtyRate') }}",
                    method: 'get',
                    data: {
                        cat_id: category_id,
                        item_name: item_name,
                        side_id: side_id,
                        unit_id: unit_id,
                    },
                    success: function(response) {
                        $('#stock_quantity').val(response.quantity);
                        $('#rate').val(response.avg_price);
                    },
                    error: function(xhr, status, error) {
                        setMessage('edit-form-error', 'Please Fill out all fields')
                    }
                })
            }

            function getStoreinItemAccCat(category_id) {
                return new Promise(function(resolve, reject) {

                    $.ajax({
                        url: "{{ route('storeout.getStoreinItemAccCat') }}",
                        method: 'GET',
                        data: {
                            category_id: category_id
                        },
                        success: function(response) {
                            let selectOptions = '';
                            if (response.data.length == 0) {
                                selectOptions += '<option disabled selected>' +
                                    'no items found' + '</option>';
                            } else {
                                selectOptions += '<option disabled selected>' +
                                    'select an item' + '</option>';
                                for (var i = 0; i < response.data.length; i++) {
                                    selectOptions += '<option value="' + response.data[i].text +
                                        '">' +
                                        response.data[i].text + '</option>';
                                }
                            }
                            $('#items').html(selectOptions);
                            resolve(response);

                        },
                        error: function(xhr, status, error) {
                            reject(error);
                        }
                    });
                });
            }


            $('#items').on('select2:select', function(e) {

                let item_id = e.params.data.id;
                let category_id = $('#categorySelect').val();
                $.ajax({
                    url: "{{ route('storeout.getDepartmentSizeUnit', ['items_of_storein_name' => ':Replaced', 'category_id' => ':categoryId']) }}"
                        .replace(
                            ':Replaced',
                            item_id)
                        .replace(
                            ':categoryId',
                            category_id),

                    method: 'GET',
                    success: function(object) {
                        // fillOptionInSelect(object.department,'#departments');
                        fillOptionInSelect(object.size, '#size');
                        fillOptionInSelect(object.units, '#unit');

                    },
                    error: function(xhr, status, error) {
                        reject(error);
                    }
                });
            });

        });

        function fillOptionInSelect(obj, element_id) {
            let selectOptions = '';
            if (obj.length == 0) {
                selectOptions += '<option disabled selected>' + 'no size found' + '</option>';
            } else {
                selectOptions += '<option disabled selected>' + 'select a item' + '</option>';
                for (let i = 0; i < obj.length; i++) {
                    let optionText = obj[i].name;
                    let optionValue = obj[i].id;
                    let option = new Option(optionText, optionValue);
                    selectOptions += option.outerHTML;
                }
            }
            $(element_id).html(selectOptions);
        }

        //save stoeout department
        document.getElementById('createStoreoutDepartment').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = e.target;
            let name = form.elements['storeout_dpt_name'];
            let status = form.elements['storeout_dept_status'];
            $.ajax({
                url: "{{ route('storeoutDepartment.store') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    name: name.value,
                    status: status.value,
                },
                success: function(response) {
                    $('#storeoutDepartmentModel').modal('hide');
                    setOptionInSelect(
                        'storeoutDepartments',
                        response.storeoutDepartment.id,
                        response.storeoutDepartment.name);
                    setOptionInSelect(
                        'modelDepartment',
                        response.storeoutDepartment.id,
                        response.storeoutDepartment.name);

                    //updateTableRow(response.storeOutItem, storeOut_item_id.value);

                    //totalAmountCalculation();

                    //editStoreOutEvent();
                    // deleteEventBtn();

                },
                error: function(xhr, status, error) {
                    setMessage('edit-form-error', 'Please Fill out all fields')
                }
            })
        })

        document.getElementById('updateStoreOutItem').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = e.target;
            //let storeout_id = form.elements['store_out_id'].value;
            let item_id = form.elements['name_model'];
            let quantity = form.elements['quantityModel'];
            let department_id = form.elements['department_id_model'];
            let placement_id = form.elements['placement_id_model'];
            let through = form.elements['through_model'];
            let storeOut_item_id = form.elements['storeOut_item_id'];

            if (!item_id && !quantity &&
                department_id.selectedIndex == 0 &&
                placement_id.selectedIndex == 0 &&
                !through && !storeOut_item_id) {
                setMessage('edit-form-error', 'Please Fill out all fields')
                return false;
            }

            $.ajax({
                url: "{{ route('storeout.updateStoreOutItems') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    storeout_item_id: storeOut_item_id.value,
                    item_id: item_id.value,
                    quantity: quantity.value,
                    department_id: department_id.value,
                    placement_id: placement_id.value,
                    through: through.value,
                },
                success: function(response) {
                    $('#editStoreoutModel').modal('hide');

                    updateTableRow(response.storeOutItem, storeOut_item_id.value);

                    totalAmountCalculation();

                    editStoreOutEvent();
                    deleteEventBtn();

                },
                error: function(xhr, status, error) {
                    setMessage('edit-form-error', 'Please Fill out all fields')
                }
            })


        })

        function totalAmountCalculation() {
            const myTable = document.getElementById("storeOutItemTable");

            // Get the tbody element of the table
            const tbody = myTable.querySelector("tbody");

            // Get all the tr elements in the tbody
            const rows = tbody.querySelectorAll("tr");

            // Loop through the rows and do something with them
            let eachTotalAmount = 0;

            rows.forEach(row => {
                eachTotalAmount += parseInt(row.querySelector('td.rowTotalAmount').innerHTML);
            });
            document.getElementById('totalStoreOut').value = eachTotalAmount;
        }

        // Updating Table tr td value when something changed or updated
        function updateTableRow(response, storeOutItem_id) {
            // triggering table tr by storeOutItem_id
            let row = document.getElementById('editRow-' + storeOutItem_id);
            console.log('response=', response);
            //Updating tds
            row.querySelector('td.rowQuantity').innerHTML = response.quantity;
            row.querySelector('td.rowsize_id').innerHTML = response.size;
            row.querySelector('td.rowItemName').innerHTML = response.item.item;
            row.querySelector('td.rowUnitName').innerHTML = response.unit;
            row.querySelector('td.rowPrice').innerHTML = response.rate;
            row.querySelector('td.rowTotalAmount').innerHTML = response.total;

        }

        function setMessage(element_id, message) {
            let errorContainer = document.getElementById(element_id);
            errorContainer.hidden = false;
            errorContainer.innerHTML = message;
            setTimeout(function() {
                errorContainer.hidden = true;
            }, 2000);
        }

        function getDepartmentPlacement(department_id, selectFrom, storeOut_id) {
            return new Promise(function(resolve, reject) {

                $.ajax({
                    url: "{{ route('storeout.getDepartmentPlacements', ['dept_id' => ':Replaced', 'storeout_id' => ':storeout_id']) }}"
                        .replace(
                            ':Replaced',
                            department_id)
                        .replace(
                            ':storeout_id',
                            storeOut_id
                        ),

                    method: 'GET',
                    success: function(response) {
                        //console.log(response);
                        let selectOptions = '';
                        if (response.length == 0) {
                            selectOptions += '<option disabled selected>' +
                                'no placement found' + '</option>';
                        } else {
                            selectOptions += '<option disabled selected>' +
                                'select a placement' + '</option>';
                            for (var i = 0; i < response.length; i++) {
                                selectOptions += '<option value="' + response[i].id + '">' +
                                    response[i].name + '</option>';
                            }
                        }
                        if (selectFrom == 'blade') {
                            $('#placementSelect').html(selectOptions);
                            resolve(response);
                        } else {
                            $('#placementIdModel').html(selectOptions);
                            resolve(response);
                        }
                    },
                    error: function(xhr, status, error) {
                        reject(error);
                    }
                });
            });
        }
        document.getElementById('createStoreOutItem').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = e.target;
            let storeout_id = form.elements['store_out_id'].value;
            let category_id = form.elements['categoryName'].value;
            let item_name = form.elements['item_id'].value;
            let size = form.elements['size'].value;
            let unit = form.elements['unit'].value;
            let quantity = form.elements['quantity'].value;
            let department = form.elements['storeout_departments'].value;
            let placement = form.elements['placement_id'].value;
            let through = form.elements['through'].value;

            $.ajax({
                url: "{{ route('storeout.saveStoreoutItems') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    category_id: category_id,
                    storeout_id: storeout_id,
                    item_name: item_name,
                    size: size,
                    unit: unit,
                    quantity: quantity,
                    department_id: department,
                    placement_id: placement,
                    through: through,
                },
                success: function(response) {
                    // getStoreOutItems();
                    totalAmountCalculation();
                    setIntoTable(response.storeOutItem);
                    $('#items').focus();
                    if (response.stock.quantity <= 0) {
                        let itemSelect = $('#items').find('option[value="' + response.stock.item_id +
                            '"]');
                        itemSelect.remove().trigger('change.select2');

                    }
                    deleteEventBtn();
                    totalAmountCalculation();
                    currentIndex = -1;
                    $('#items').focus();
                    checkRowInTable();
                },
                error: function(xhr, status, error) {
                    setErrorMessage(xhr.responseJSON.message);
                }
            })

            //console.log(form.elements['item_name'].value);
        });



        let sn = 1;
        //set Values to storein Items table
        function setIntoTable(res) {
            console.log('response:', res);
            var html = "";

            html = "<tr  id=editRow-" + res.id + "><td>" + sn +
                "</td><td class='rowItemName'>" + res.items_of_storein.name +
                "</td><td class='rowsize_id'>" + res.size.name +
                "</td><td class='rowQuantity'>" + res.quantity +
                "</td><td class='rowUnitName'>" + res.unit.name +
                "</td><td class='rowPrice'>" + res.rate +
                "</td><td class='rowTotalAmount'>" + res.total +
                "</td> <td>" +
                "<button class='btn btn-danger dltstoreoutItem' data-id=" +
                res.id + " ><i class='fas fa-trash-alt'></i> </button>" + "</td ></tr>";

            document.getElementById('storeOutTBody').innerHTML += html;
            sn++;
            // Clearing the input fields
            clearInputFields();

        }
        //for editing storeout items
        function editStoreOutEvent() {
            let editStoreOutButtons = document.getElementsByClassName('editStoreoutBtn');
            for (var i = 0; i < editStoreOutButtons.length; i++) {

                editStoreOutButtons[i].addEventListener('click', function(event) {
                    let outitemId = this.getAttribute('data-id');
                    console.log('select id item =', outitemId);
                    $.ajax({
                        url: '{{ route('storeout.getEditItemData', ['storeoutItem_id' => ':item_id']) }}'
                            .replace(':item_id', outitemId),
                        method: 'GET',
                        success: async function(response) {
                            $('#storeOutItemId').val(response.id);
                            $('#itemNameModel').val(response.item_id).trigger('change');
                            $('#itemQuantityModel').val(response.quantity);
                            $('#departmentIdModel').val(response.department_id).trigger('change');
                            $('#throughModel').val(response.through);
                            await getDepartmentPlacement(response.department_id, 'model');
                            $('#placementIdModel').val(response.placement_id).trigger('change');

                            // Model show
                            $('#editStoreoutModel').modal('show');
                        },
                        error: function(xhr, status, error) {
                            setErrorMessage('Something went wrong in Edit');
                        }

                    });


                });
            }
        }

        function deleteEventBtn() {
            let deleteButtons = document.getElementsByClassName('dltstoreoutItem');
            //console.log(deleteButtons);
            for (let i = 0; i < deleteButtons.length; i++) {
                deleteButtons[i].addEventListener('click', function(event) {
                    let storeout_item_id = this.getAttribute('data-id');
                    // console.log(storeout_item_id);
                    new swal({
                        title: "Are you sure?",
                        text: "Do you want to delete Item.",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                        showCancelButton: true,
                        cancelButtonText: 'Cancel',
                        closeOnClickOutside: false,
                    }).then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url: '{{ route('storeout.storeoutItemDelete', ['storeout_item_id' => ':lol']) }}'
                                    .replace(':lol', storeout_item_id),
                                type: "DELETE",
                                data: {
                                    "_method": "DELETE",
                                    "_token": "{{ csrf_token() }}",
                                },
                                success: function(response) {
                                    console.log(response);
                                    removeAllTableRows();
                                    getStoreOutItems();
                                    //console.log('i am here');
                                    totalAmountCalculation();
                                    //  checkIfTableHasData();
                                    clearInputFields();
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

        function checkRowInTable() {
            let tableTbody = document.querySelector("#storeOutItemTable tbody");
            let saveStoreOutBtn = document.getElementById('saveStoreOutButton');
            if (tableTbody.rows.length <= 0) {
                saveStoreOutBtn.disabled = true;
            } else {
                saveStoreOutBtn.disabled = false;
            }
        }

        function clearInputFields() {
            document.getElementById('size').value = "";
            // document.getElementById('item_id').value = "";
            // document.getElementById('rate').value = "";
            document.getElementById('quantity').value = "";
            document.getElementById('through').value = "";
            //document.getElementById('remark').value = "";
            document.getElementById('stock_quantity').value = "";
            $('#unit').val($('#unit option:first').val()).change();
            $('#items').val($('#items option:first').val()).change();
            $('#departments').val($('#items option:first').val()).change();
            $('#placementSelect').val($('#items option:first').val()).change();

        }

        function removeAllTableRows() {
            // Reseting SN
            sn = 1;
            let tbody = document.querySelector("#storeOutItemTable tbody");
            for (var i = tbody.rows.length - 1; i >= 0; i--) {
                tbody.deleteRow(i);
            }
        }

        //table data when the page refreshes
        function getStoreOutItems() {
            $.ajax({
                url: '{{ route('storeout.getStoreOutItemData', ['storeout_id' => $storeOut->id]) }}',
                method: 'GET',
                success: function(response) {
                    console.log('table', response);
                    if (response.count <= 0) {
                        return false;
                    }
                    response.storeOutItem.forEach(itemsRow => {
                        setIntoTable(itemsRow);
                    });

                    console.log('refresh')
                    editStoreOutEvent();
                    totalAmountCalculation();
                    deleteEventBtn();
                    checkRowInTable();
                },
                error: function(xhr, status, error) {
                    reject(error);
                }
            });
        }

        //department save
        // document.getElementById('modalFormDepartment').addEventListener('submit', function(e) {
        //     e.preventDefault();
        //     let form = e.target;
        //     let departmentName = form.elements['department'];
        //     let status = form.elements['status'];
        //     $.ajax({
        //         url: "{{ route('department.storeDepartmentFromModel') }}",
        //         method: 'POST',
        //         data: {
        //             _token: "{{ csrf_token() }}",
        //             department: departmentName.value,
        //             status: status.value
        //             // Goes Into Request
        //         },
        //         success: function(response) {
        //             $('#addDepartmentModel').modal('hide');
        //             departmentName.value = "";
        //             status = "";
        //             setSuccessMessage(response.message);

        //             //recent by megha
        //             setOptionInSelect(
        //                 'departments',
        //                 response.department.id,
        //                 response.department.department);
        //             setOptionInSelect(
        //                 'model_department',
        //                 response.department.id,
        //                 response.department.department);
        //         },
        //         error: function(xhr, status, error) {

        //             let errorMessage = xhr.responseJSON.message;

        //             setTimeout(function() {
        //                 errorContainer.hidden = true;
        //             }, 2000); // 5000 milliseconds = 5 seconds
        //             //return error;
        //         }
        //     });

        // });

        function setErrorMessage(message) {
            let errorContainer = document.getElementById('error_msg');
            errorContainer.hidden = false;
            errorContainer.innerHTML = message;
            setTimeout(function() {
                errorContainer.hidden = true;
            }, 2000); // 5000 milliseconds = 5 second
        }

        function setSuccessMessage(message) {
            let successContainer = document.getElementById('success_msg');
            //console.log(successContainer);
            successContainer.hidden = false;
            successContainer.innerHTML = message;
            setTimeout(function() {
                successContainer.hidden = true;
            }, 2000); // 5000 milliseconds = 5 seconds
        }

        function setOptionInSelect(elementId, optionId, optionText) {
            let selectElement = $('#' + elementId);
            // create a new option element
            let newOption = $('<option>');

            // set the value and text of the new option element
            newOption.val(optionId).text(optionText);

            // append the new option element to the select element
            selectElement.append(newOption);

            // refresh the select2 element to update the UI
            selectElement.trigger('change.select2');
            $('#' + elementId).val(optionId).trigger('change.select2');
        }

        document.getElementById('modelFormPlacement').addEventListener('submit', function(e) {
            e.preventDefault();
            let form = e.target;
            let placementName = form.elements['model_placement'];
            let godam_id = form.elements['model_godam_id'];
            let storeoutdpt_id = form.elements['model_dept_id'];

            let status = form.elements['status'];
            $.ajax({
                url: "{{ route('placement.save') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    name: placementName.value,
                    storeoutdpt_id: storeoutdpt_id.value,
                    godam_id: godam_id.value,
                    status: status.value
                    // Goes Into Request
                },
                success: function(response) {
                    setSuccessMessage(response.message);
                    $('#placementCreateModel').modal('hide');
                    setOptionInSelect(
                        'placementSelect',
                        response.placement.id,
                        response.placement.name);
                    $('#departments').val(response.placement.department_id).trigger(
                        'change.select2');
                },
                error: function(xhr, status, error) {
                    let errorContainer = document.getElementById('form-error');
                    let errorMessage = xhr.responseJSON.message;
                    errorContainer.hidden = false;
                    errorContainer.innerHTML = errorMessage;
                    setTimeout(function() {
                        errorContainer.hidden = true;
                    }, 2000); // 5000 milliseconds = 5 seconds
                    //return error;
                }
            });
        });
    </script>
@endsection
