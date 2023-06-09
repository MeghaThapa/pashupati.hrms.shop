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
                <input type="text" class="form-control" value="{{ $storeOut->id }}" id="storeOut_id" data-number="1"
                    name="store_out_id" placeholder="{{ __('store_out_id') }}" hidden>
                <div class="col-md-6">
                    <label for="item_name" style="width:80px !important;"
                        class="col-form-label">{{ __('Item Name') }}</label>
                    <select class="advance-select-box form-control" id="items" name="item_id" required>
                        <option value="" selected disabled>{{ __('Select an item') }}</option>
                        @foreach ($items as $key => $stock)
                         @if ($stock && $stock->item && $stock->sizes)
                            <option value="{{ $stock->item_id }}">{{ $stock->item->name }} /ppNo: {{$stock->item->pnumber}} /size: {{$stock->sizes->name}}
                            </option>
                        @endif
                        @endforeach
                    </select>
                    @error('item_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-2">
                    <label for="stock_quantity" class="col-form-label">{{ __('Stock Quantity') }}</label>
                    <input type="number" class="form-control " id="stock_quantity" name="stock_quantity" data-ignore
                        placeholder="{{ __('Stock Quantity') }}" readonly tabindex="-1">
                    @error('stock_quantity')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <input type="text" class="form-control" id="size_id" name="size_id" data-ignore
                         readonly tabindex="-1" hidden>
                <div class="col-md-2">
                    <label for="unit" class="col-form-label">{{ __('Unit') }}</label>
                    <input type="text" step="any" min="0" max="99" class="form-control " id="item_unit" data-ignore
                        data-number="1" name="unit" placeholder="{{ __('Unit') }}" readonly tabindex="-1">
                    @error('unit')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
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

            </div>
            <div class="row mt-1">
                <div class="col-md-3 gap-2">
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
                <div class="col-md-3" style="gap:3px;">
                    <label for="size" class="col-form-label">{{ __('Department') }}</label>
                    <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal" tabindex="-1"
                        data-target="#addDepartmentModel" style="margin-top:0 !important; top:8px;float:right;">
                        <i class="fas fa-plus" style="display:flex;align-items: center;justify-content: center;"></i>
                    </a>
                    <select class="advance-select-box form-control" id="departments" name="department" required>
                        <option value="" selected disabled>{{ __('Select a department') }}</option>
                        @foreach ($storeinDepartment as $department)
                             @if ($department && $department->name)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('department')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
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
                                    <label for="name">{{ __('Placement name') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('placement') is-invalid @enderror"
                                        id="placement" name="placement" placeholder="{{ __('Placement') }}"
                                        value="{{ old('placement') }}" required>
                                    @error('placement')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="department">{{ __('Department') }}<span
                                            class="required-field">*</span></label>
                                    <select class="advance-select-box form-control @error('supplier') is-invalid @enderror"
                                        id="model_department" name="department">
                                        <option value="" selected disabled>{{ __('Select a Department') }}</option>
                                        @foreach ($storeinDepartment as $department)
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
    <div class="modal fade" id="addDepartmentModel" tabindex="-1" role="dialog" aria-labelledby="addDepartmentModel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="modalFormDepartment">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addDepartmentModel">Add Department</h5>
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
                                        id="department" name="department" placeholder="{{ __('Department Name') }}"
                                        value="{{ old('department') }}" required>
                                    @error('company')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                    <select class="form-control" id="status" name="status">
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
                                        @foreach ($items as $key => $stock)
                                         @if ($stock && $stock->item)
                                            <option value="{{ $stock->item_id }}">{{ $stock->item->name }}
                                            </option>
                                        @endif
                                        @endforeach
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
                                        @foreach ($storeinDepartment as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
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
            $('#items').focus();
              $(document).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });

            //for user accessibility
            let formDiv = document.getElementById("formdiv");
            let focusableElements = Array.from(formDiv.querySelectorAll("input, select,button")).filter(function(element) {
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

            $('#departments').on('select2:select', function(e) {
                let department_id = e.params.data.id;

                getDepartmentPlacement(department_id, 'blade');

            });
            $('#departmentIdModel').on('select2:select', function(e) {
                let department_id = e.params.data.id;

                getDepartmentPlacement(department_id, 'model');

            });


            $('#items').on('select2:select', function(e) {
                //select item so as to get size unit and rate

                let itemUnit = document.getElementById('item_unit');
                let size = document.getElementById('size_id');
                let itemRate = document.getElementById('rate');
                let stockQuantity = document.getElementById('stock_quantity');
                let item_id = e.params.data.id;
                $.ajax({
                    url: "{{ route('stock.getDetailsAccItem', ['item_id' => ':Replaced']) }}"
                        .replace(
                            ':Replaced',
                            item_id),

                    method: 'GET',
                    success: function(response) {

                        var selectOptions = '';
                        stockQuantity.value = response.quantity;
                        size.value= response.size;
                        itemUnit.value = response.unit;
                        itemRate.value = response.avg_price;
                        $('#placementSelect').html(selectOptions);
                    },
                    error: function(xhr, status, error) {
                        reject(error);
                    }
                });
            });

        });

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
                    console.log(response);
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

        function getDepartmentPlacement(department_id, selectFrom) {
            return new Promise(function(resolve, reject) {

                $.ajax({
                    url: "{{ route('storeout.getDepartmentPlacements', ['dept_id' => ':Replaced']) }}"
                        .replace(
                            ':Replaced',
                            department_id),

                    method: 'GET',
                    success: function(response) {
                        //console.log(response);
                        let selectOptions = '';
                        if (response.length == 0) {
                            selectOptions += '<option disabled selected>' +
                                'no items found' + '</option>';
                        } else {
                            selectOptions += '<option disabled selected>' +
                                'select an item' + '</option>';
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
            let item_name = form.elements['item_id'].value;
            let size = form.elements['size_id'].value;

            let unit = form.elements['unit'].value;
            let rate = form.elements['rate'].value;
            let quantity = form.elements['quantity'].value;
            let department = form.elements['department'].value;
            let placement = form.elements['placement_id'].value;
            let through = form.elements['through'].value;
            // let remark = form.elements['remark'].value;
            $.ajax({
                url: "{{ route('storeout.saveStoreoutItems') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    storeout_id: storeout_id,
                    item_id: item_name,
                    size: size,
                    unit: unit,
                    rate: rate,
                    quantity: quantity,
                    department_id: department,
                    placement_id: placement,
                    through: through,
                    //remark: remark,
                    // Goes Into Request
                },
                success: function(response) {
                    console.log(response);
                    setIntoTable(response.storeOutItem);
                    $('#items').focus();

                    if (response.stock.quantity <= 0) {
                        let itemSelect = $('#items').find('option[value="' + response.stock.item_id +
                            '"]');
                        itemSelect.remove().trigger('change.select2');

                    }
                    editStoreOutEvent();
                    deleteEventBtn();

                    totalAmountCalculation();
                    currentIndex = -1;
                      $('#items').focus();
                    //   checkIfTableHasData();
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
            console.log(res);
            var html = "";

            html = "<tr  id=editRow-" + res.id + "><td>" + sn +
                "</td><td class='rowItemName'>" + res.items_of_storein.name +
                "</td><td class='rowsize_id'>" + res.size +
                "</td><td class='rowQuantity'>" + res.quantity +
                "</td><td class='rowUnitName'>" + res.unit +
                "</td><td class='rowPrice'>" + res.rate +
                "</td><td class='rowTotalAmount'>" + res.total +
                "</td> <td>" +
                // "<button class='btn btn-success editStoreoutBtn' data-id=" +
                // res.id + "><i class='fas fa-edit'></i></button>" +
                // "  " +
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
                                success: function(result) {
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
                                    //  checkRowInTable();
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

        function clearInputFields() {
            document.getElementById('size_id').value = "";
            document.getElementById('item_unit').value = "";
            document.getElementById('rate').value = "";
            document.getElementById('quantity').value = "";
            document.getElementById('through').value = "";
            //document.getElementById('remark').value = "";
            document.getElementById('stock_quantity').value = "";

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
                },
                error: function(xhr, status, error) {
                    reject(error);
                }
            });
        }

        //department save
        document.getElementById('modalFormDepartment').addEventListener('submit', function(e) {
            e.preventDefault();
            let form = e.target;
            let departmentName = form.elements['department'];
            let status = form.elements['status'];
            $.ajax({
                url: "{{ route('department.storeDepartmentFromModel') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    department: departmentName.value,
                    status: status.value
                    // Goes Into Request
                },
                success: function(response) {
                    $('#addDepartmentModel').modal('hide');
                    departmentName.value = "";
                    status = "";
                    setSuccessMessage(response.message);

                    //recent by megha
                    setOptionInSelect(
                        'departments',
                        response.department.id,
                        response.department.department);
                    setOptionInSelect(
                        'model_department',
                        response.department.id,
                        response.department.department);
                },
                error: function(xhr, status, error) {

                    let errorMessage = xhr.responseJSON.message;

                    setTimeout(function() {
                        errorContainer.hidden = true;
                    }, 2000); // 5000 milliseconds = 5 seconds
                    //return error;
                }
            });

        });

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
            let placementName = form.elements['placement'];
            let department_id = form.elements['department'];
            let status = form.elements['status'];
            $.ajax({
                url: "{{ route('placement.save') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    placement: placementName.value,
                    department_id: department_id.value,
                    status: status.value
                    // Goes Into Request
                },
                success: function(response) {
                    setSuccessMessage(response.message);
                    $('#placementCreateModel').modal('hide');
                    placementName.value = "";
                    department_id.value = "";
                    status = "";

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
