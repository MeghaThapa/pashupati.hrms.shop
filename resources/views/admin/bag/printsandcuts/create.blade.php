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

        label {
            font-weight: lighter;
            color: rgba(0, 0, 0, 0.8);
        }

        /* .select2-selection {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                width:150px !important;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            } */
    </style>
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div id="error_msg" class="alert alert-danger mt-2" hidden>

    </div>
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Prints and Cuts') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Prints and cuts') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    {{-- <div id="successMsgDisplayContainer" class="alert alert-success"> --}}
    <div id="success_msg" class="alert alert-success mt-2" hidden>

    </div>
    {{-- <input type="text" value="{{ $data->id }}"> --}}
    {{-- </div> --}}
    <div class="content">
        <div class="container-fluid">
            <a class='btn btn-primary go-back float-right'>Go back</a>
            <br><br>
            <div class="form">
                <form action="" id="savePrintingAndCuttingBagItem">
                    <input type="text" value="{{ $data->id }}" class="form-control" id='printAndCutEntryId'
                        name='printAndCutEntry_id' hidden>
                    <div class="row">
                        {{-- @foreach ($data as $d) --}}
                        <div class="col-md-3">
                            <label for="receipt_number">Receipt Number</label>
                            <input type="text" value="{{ $data->receipt_number }}" class="form-control"
                                id='receipt_number' name='receipt_number' readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="Date NP">Date</label>
                            <input type="text" value="{{ $data->date_np }}" class="form-control" id='date_np'
                                name='date_np' readonly>
                        </div>
                        {{-- @endforeach --}}
                        <div class="col-md-3">
                            <label for="Roll number">Roll Number</label>
                            <input type="text" class="form-control" id='rollNumber' name='roll_number'>
                        </div>
                        <div class="col-md-3">
                            <label for="Fabric">Fabric </label>
                            <select name="fabric_id" class="form-control advance-select-box" id="fabricId" disabled>
                                <option value="">Fabric Hre</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="net_weight">Net Weight </label>
                            <input type="text" class="form-control" id="netWeight" name="net_weight" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="gross_weight">Gross Weight </label>
                            <input type="text" class="form-control" id="grossWeight" name="gross_weight" readonly>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="meter">Meter</label>
                            <input type="text" class="form-control" id="meter" name="meter" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="average">Average</label>
                            <input type="text" class="form-control" id="average" name="average" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="cut_length">Cut Length</label>
                            <input type="text" class="form-control" id="cutLength" name="cut_length">
                        </div>
                        <div class="col-md-3">
                            <label for="req_length">Req Bag</label>
                            <input type="text" class="form-control" id="reqBag" name="req_bag">
                        </div>
                    </div>
                    <hr>
                    <div class="row">

                        <div class="col-md-3 form-group">
                            <label for="Category" class="col-form-label">{{ __('Group') }}<span
                                    class="required-field">*</span>
                            </label>
                            <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                                tabindex="-1" data-target="#groupModel"
                                style="margin-top:0 !important; top:8px;float:right;">
                                <i class="fas fa-plus"
                                    style="display:flex;align-items: center;justify-content: center;"></i>
                            </a>
                            <select class="advance-select-box form-control  @error('group') is-invalid @enderror"
                                id="group" name="group_id" required>
                                <option value=" " selected disabled>{{ __('Select a Group') }}</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}"
                                        {{ old('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('group_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="Category" class="col-form-label">{{ __('Bag Brand') }}<span
                                    class="required-field">*</span>
                            </label>
                            <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                                tabindex="-1" data-target="#bagBrandModel"
                                style="margin-top:0 !important; top:8px;float:right;">
                                <i class="fas fa-plus"
                                    style="display:flex;align-items: center;justify-content: center;"></i>
                            </a>
                            <select class="advance-select-box form-control  @error('group') is-invalid @enderror"
                                id="bagBrand" name="bag_brand_id" required>
                                {{-- <option value=" " selected disabled>{{ __('Select a bag brand') }}</option> --}}
                                {{-- @foreach ($categories as $key => $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('categoryName') == $category->id ? 'selected' : '' }}>{{ $category->name }}
                                    </option>
                                @endforeach --}}
                            </select>
                            @error('bag_brand_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="cut_length">Quantity Piece</label>
                            <input type="text" class="form-control" id="quantity_piece" name="quantity_piece">
                        </div>

                        <div class="col-md-3">
                            <label for="cut_length">AVG</label>
                            <input type="text" class="form-control" id="avg" name="avg">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="cut_length">wastage</label>
                            <input type="text" class="form-control" id="wastage" name="wastage">
                        </div>
                        <div class="col-md-3">
                            <label for="cut_length">Godam</label>
                            <select class="advance-select-box form-control  @error('godam_id') is-invalid @enderror"
                                id="wasteGodamId" name="waste_godam_id" required>
                                <option value=" " selected disabled>{{ __('Select Godam') }}</option>
                                @foreach ($wasteGodams as $wasteGodam)
                                    <option value="{{ $wasteGodam->id }}">
                                        {{ $wasteGodam->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="cut_length">Waste Type</label>
                            <select class="advance-select-box form-control  @error('godam_id') is-invalid @enderror"
                                id="wasteTypeId" name="waste_type_id" required>
                                <option value=" " selected disabled>{{ __('Select waste type') }}</option>
                                @foreach ($wastageTypes as $wastageType)
                                    <option value="{{ $wastageType->id }}">
                                        {{ $wastageType->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-primary">Add</button>
                        </div>

                    </div>
                </form>
                <hr>
                <div class="table-custom table-responsive ">
                    <table class="table table-hover table-bordered" id="printsAndCutsItem">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Brand Group</th>
                                <th>Bag Brand</th>
                                <th>Quantity Piece</th>
                                <th>Average</th>
                                <th>Wastage</th>
                                <th>Roll No</th>
                                <th>Fabric Name</th>
                                <th>NW</th>
                                <th>GW</th>
                                <th>Meter</th>
                                <th>Avg</th>
                                <th>Req Bag</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="result">
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-5">
                        <form id="addPrintsAndCutsDanaConsumption">
                            <div class="card p-2">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="cut_length">Godam</label>
                                        <select
                                            class="advance-select-box form-control  @error('godam_id') is-invalid @enderror"
                                            id="godamId" name="godam_id" required>
                                            <option value=" " selected disabled>{{ __('Select Godam') }}</option>
                                            @foreach ($godams as $godam)
                                                <option value="{{ $godam->fromGodam->id }}">
                                                    {{ $godam->fromGodam->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- <div class="col-md-4">
                                        <label for="cut_length">Dana Group</label>
                                        <select
                                            class="advance-select-box form-control  @error('dana_group_id') is-invalid @enderror"
                                            id="danaGroupId" name="dana_group_id" required>
                                            <option value=" " selected disabled>{{ __('Select dana group') }}
                                            </option>
                                        </select>
                                    </div> --}}

                                    <div class="col-md-4">
                                        <label for="cut_length">Dana Name</label>
                                        <select
                                            class="advance-select-box form-control  @error('dana_name_id') is-invalid @enderror"
                                            id="danaNameId" name="dana_name_id" required>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="cut_length">Available</label>
                                        <input type="text" class="form-control" id="avilableStock"
                                            name="avilable_stock" readonly>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-4">
                                        <label for="cut_length">Quantity</label>
                                        <input type="text" class="form-control" id="quantity" name="quantity">
                                    </div>
                                    <div class="col-md-2 mt-4">
                                        <button class="btn btn-primary ">Add</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-7">
                        <table class="table table-bordered table-hover" id="danaConsumption">
                            <thead>
                                <tr>
                                    <th style="width:30px;">SN</th>
                                    <th>Godam</th>
                                    {{-- <th>Dana Group</th> --}}
                                    <th>Dana Name</th>
                                    <th>Quantity</th>

                                </tr>
                            </thead>
                            <tbody id="printsCutsDanaConsumpt">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mx-1">
                    <button class="btn btn-success" id="updateStocks">Update</button>
                </div>
            </div>
        </div>
    </div>
    <!--Group Model popup-->
    <div class="modal fade" id="groupModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalcat"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="exampleModalcat">Add Group</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="error_msg" class="alert alert-danger mt-2" hidden>

                </div>
                <form class="form-horizontal" id="createGroupModel">
                    <div class="modal-body">
                        <div id="categoryModelError" class="alert alert-danger" hidden></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label style="width:400px !important;" for="name">{{ __('Group Name') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="catNameModel" name="cat_name_model" style="width:430px !important; "
                                        placeholder="{{ __('Group Name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                    <select class="form-control" id="groupStatus" name="group_status">
                                        <option value="active">{{ __('Active') }}</option>
                                        <option value="inactive">{{ __('Inactive') }}</option>
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
                            {{ __('Save Group') }}</button>
                        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--Group Model Popup End-->
    <!--Bag Brand Model popup-->
    <div class="modal fade" id="bagBrandModel" tabindex="-1" role="dialog" aria-labelledby="exampleBagBrand"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title" id="exampleModalcat">Add Bag Brand</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal" id="createBrandBagModel">
                    <div class="modal-body">
                        <div id="categoryModelError" class="alert alert-danger" hidden></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label style="width:400px !important;" for="name">{{ __('Bag Brand Name') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="brandNameModel" name="brand_name_model" style="width:430px !important; "
                                        placeholder="{{ __('Bag Brand Name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label style="width:400px !important;" for="name">{{ __('Group') }}<span
                                            class="required-field">*</span></label>
                                    <select class="advance-select-box form-control  @error('group') is-invalid @enderror"
                                        id="groupNameModel" name="group_name_model" required>
                                        <option value=" " selected disabled>{{ __('Select a Group') }}</option>
                                        @foreach ($groups as $group)
                                            <option value="{{ $group->id }}"
                                                {{ old('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('group_name_model')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="bagBrandStatus" class="col-form-label">{{ __('Status') }}</label>
                                    <select class="form-control" id="bagBrandStatus" name="bag_brand_status">
                                        <option value="active">{{ __('Active') }}</option>
                                        <option value="inactive">{{ __('Inactive') }}</option>
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
                            {{ __('Save Bag Brand') }}</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--Bag Brand Model Popup End-->
@endsection
@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            pageRefresh();

            async function pageRefresh() {
                await getdanaConsumptionData();
                deleteDanaConsumptionData();
            }
            //When Page Refresh
            getPrintsAndCutsBagItems();

            //when page refresh
            function deleteDanaConsumptionData() {

                let deleteButtons = document.getElementsByClassName('dltPrintingAndCutDanaConsumption');
                //console.log(deleteButtons);
                for (let i = 0; i < deleteButtons.length; i++) {
                    deleteButtons[i].addEventListener('click', function(event) {
                        // console.log('hello');
                        let prints_and_cuts_dana_consumption_id = this.getAttribute('data-id');
                        // console.log(storeout_item_id);
                        let receipt_no = document.getElementById(receipt_number).value;
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
                                    url: '{{ route('printingAndCuttingBagItem.deleteConsumedDana') }}',
                                    type: "POST",
                                    data: {
                                        "_method": "POST",
                                        'receipt_no': receipt_no,
                                        "prints_and_cuts_dana_consumption_id": prints_and_cuts_dana_consumption_id,
                                        "_token": "{{ csrf_token() }}",
                                    },
                                    success: function(response) {
                                        removeAllTableRows(
                                            'printsAndCutsItem');
                                        //   checkRowInTable();
                                        getPrintsAndCutsBagItems();
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

            function getdanaConsumptionData() {
                return new Promise(function(resolve, reject) {
                    let printAndCutEntry_id_data = {!! json_encode($data->id) !!};
                    //console.log('printAndCutEntry_id_data', printAndCutEntry_id_data);
                    $.ajax({
                        url: '{{ route('printingAndCuttingBagItem.getPrintsAndCutsDanaConsumption') }}',
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            printAndCutEntry_id: printAndCutEntry_id_data
                        },
                        success: function(response) {
                            console.log('consumption data hgftyuhb', response);
                            response.forEach(function(response) {
                                console.log('qty', response.quantity)
                                setIntoConsumptionTable(response)
                            })
                            resolve();
                        },
                        error: function(xhr, status, error) {
                            setErrorMsg(xhr.responseJSON.message);
                            reject();
                        }
                    });
                });
            }

            function getPrintsAndCutsBagItems() {
                let printAndCutEntry_id_data = {!! json_encode($data->id) !!};
                $.ajax({
                    url: '{{ route('printingAndCuttingBagItem.getPrintsAndCutsBagItems') }}',
                    method: 'GET',
                    data: {
                        printAndCutEntry_id: printAndCutEntry_id_data
                    },
                    success: function(response) {
                        if (response.count <= 0) {
                            return false;
                        }
                        response.printingAndCuttingBagItem.forEach(itemsRow => {
                            setIntoTable(itemsRow);
                        });
                        deleteEventBtn();
                        //  checkRowInTable('printsAndCutsItem');
                    },
                    error: function(xhr, status, error) {
                        reject(error);
                    }
                });
            }

            //function to disable update button
            // function checkRowInTable(tableId) {
            //     tableTbody = document.querySelector("#" + tableId + " tbody");
            //     let updateStocksBtn = document.getElementById('updateStocks');

            //     if (tableTbody && tableTbody.rows.length <= 0) {
            //         updateStocksBtn.disabled = true;
            //     } else {
            //         updateStocksBtn.disabled = false;
            //     }
            // }




            //meghamm
            document.getElementById('updateStocks').addEventListener('click', function(event) {
                let printAndCutEntry_id = {!! json_encode($data->id) !!};
                $.ajax({
                    url: "{{ route('printingAndCuttingBagItem.updateStock') }}",
                    method: 'post',
                    data: {
                        _token: "{{ csrf_token() }}",
                        printAndCutEntry_id: printAndCutEntry_id,
                    },
                    success: function(response) {
                        //console.log(response);
                        setSuccessMessage(response.message);
                        window.location.href = "{{ route('prints.and.cuts.index') }}";
                        // setOptionInSelect('fabricId', response.fabric.id, response.fabric.name);
                        // $('#netWeight').val(response.net_wt);
                        // $('#grossWeight').val(response.gross_wt);
                        // $('#average').val(response.average);
                    },
                    error: function(xhr, status, error) {
                        alert(xhr.responseJSON.message);
                        setErrorMsg(xhr.responseJSON.message);
                    }
                });
                // console.log('hello');
            });


            $(".go-back").click(function() {
                history.back();
            });

            $('#fabricId').on('select2:select', function(e) {
                let fabric_id = e.params.data.id;
                console.log('fabric_id', fabric_id);
            });

            $('#godamId').on('select2:select', function(e) {
                let godam_id = e.params.data.id;
                $('#danaNameId').empty();
                getDanaName(godam_id);
            });



            // $('#danaGroupId').on('select2:select', function(e) {
            //     // console.log('df');
            //     let dana_group_id = e.params.data.id;
            //     $('#danaNameId').empty();
            //     getDanaName(dana_group_id);
            // });

            $('#danaNameId').on('select2:select', function(e) {
                // console.log('df');
                let godam_id = document.getElementById('godamId').value;
                let dana_name_id = e.params.data.id;
                // $('#avilableStock').empty();
                getStockQuantity(godam_id, dana_name_id);
            });
            // printsAndCuts.getStockQuantity
            function getStockQuantity(godam_id, dana_name_id) {
                $.ajax({
                    url: "{{ route('printsAndCuts.getStockQuantity') }}",
                    method: 'post',
                    data: {
                        _token: "{{ csrf_token() }}",
                        godam_id: godam_id,
                        dana_name_id: dana_name_id
                    },
                    success: function(response) {
                        console.log('stockQty', response);
                        document.getElementById('avilableStock').value = response.quantity;
                        // response.forEach(function(item) {
                        //     setOptionInSelect('danaNameId', item.dana_name.id, item.dana_name
                        //         .name);
                        // });
                    },
                    error: function(xhr, status, error) {
                        setErrorMsg(xhr.responseJSON.message);
                    }
                });
            }

            function getDanaName(dana_group_id) {
                let godam_id = document.getElementById('godamId').value;
                $.ajax({
                    url: "{{ route('printsAndCuts.getDanaName') }}",
                    method: 'post',
                    data: {
                        _token: "{{ csrf_token() }}",
                        //dana_group_id: dana_group_id,
                        godam_id: godam_id

                    },
                    success: function(response) {

                        $('#danaNameId').prepend(
                            "<option value='' disabled selected>Select required</option>"
                        );
                        response.forEach(function(item) {
                            setOptionInSelect('danaNameId', item.dana_name.id, item
                                .dana_name
                                .name);
                        });
                    },
                    error: function(xhr, status, error) {
                        setErrorMsg(xhr.responseJSON.message);
                    }
                });
            }

            function getDanaGroup(godam_id) {
                $.ajax({
                    url: "{{ route('printsAndCuts.getDanaGroup') }}",
                    method: 'post',
                    data: {
                        _token: "{{ csrf_token() }}",
                        godam_id: godam_id,
                    },
                    success: function(response) {
                        // $('#danaGroupId').prepend(
                        //     "<option value='' disabled selected>Select required data</option>"
                        // );
                        response.danaGroups.forEach(function(item) {
                            setOptionInSelect('danaGroupId', item.dana_group.id,
                                item.dana_group
                                .name);
                        });
                    },
                    error: function(xhr, status, error) {
                        setErrorMsg(xhr.responseJSON.message);
                    }
                });
            }

            //to calculate avg
            let cutLengthInput = document.getElementById("cutLength");

            // Add the event listener to the input field
            cutLengthInput.addEventListener("input", handleAvgCalculation);

            function handleAvgCalculation() {
                let cutLengthValue = cutLengthInput.value;
                let average = document.getElementById("average").value;
                if (cutLengthValue && average) {
                    calculation(cutLengthValue, average);
                }
            }

            function calculation(cutLengthValue, average) {
                let avg = parseFloat(average) * parseFloat(cutLengthValue) / 39.37;
                avg = avg.toFixed(2);
                document.getElementById('avg').value = parseFloat(avg);
            }
            //  adding event listenersfor calculating QTY in kg
            // document.getElementById('average').addEventListener('input', checkInputsToCalQtyInKg);
            // document.getElementById('reqBag').addEventListener('input', checkInputsToCalQtyInKg);

            // function checkInputsToCalQtyInKg() {
            //     var average = document.getElementById('average').value;
            //     var reqBag = document.getElementById('reqBag').value;
            //     // Check if both fields have values
            //     if (meter && cutLength) {
            //         calculateQtyInKg(average, reqBag);
            //     }
            // }

            // function calculateQtyInKg(average, reqBag) {
            //     let qtyInKg = parseFloat(average) * parseFloat(reqBag);
            //     document.getElementById('qtyInKg').value = qtyInKg;
            // }

            let timeoutId;

            document.getElementById('rollNumber').addEventListener('blur', getFabric);


            function getFabric() {
                let roll_no = document.getElementById('rollNumber').value;
                $.ajax({
                    url: "{{ route('printsAndCuts.getFabric') }}",
                    method: 'post',
                    data: {
                        _token: "{{ csrf_token() }}",
                        roll_no: roll_no,
                    },
                    success: function(response) {
                        console.log(response.error);
                        clearAutoFilledInput();
                        setOptionInFabSelect('fabricId', response.fabric.id, response.fabric
                            .name);

                        $('#netWeight').val(response.net_wt);
                        $('#grossWeight').val(response.gross_wt);
                        $('#average').val(response.average);
                        $('#meter').val(response.meter);
                    },
                    error: function(xhr, status, error) {
                        clearAutoFilledInput();
                        if (xhr.status === 404) {
                            alert("Error: " + xhr.responseJSON.error);
                        }
                    }
                });
            }

            function clearAutoFilledInput() {
                console.log('here');
                setOptionInFabSelect('fabricId', '', ''); // Clear 'fabricId'
                $('#netWeight').val('');
                $('#grossWeight').val('');
                $('#average').val('');
                $('#meter').val('');
            }
            // Adding event listeners
            document.getElementById('meter').addEventListener('input', checkInputs);
            document
                .getElementById('cutLength').addEventListener('input', checkInputs);

            // Checking if both input fields have a value
            function checkInputs() {
                let meter = document.getElementById('meter').value;
                let cutLength = document.getElementById('cutLength').value;

                // Check if both fields have values
                if (meter && cutLength) {
                    calculateReqBagAndDisplay(meter, cutLength);
                }
            }

            function calculateReqBagAndDisplay(meter, cutLength) {
                let reqBag = parseFloat(meter) / parseFloat(cutLength);
                // Update the value of the 'reqBag' element
                document.getElementById('reqBag').value = reqBag;
            }
            //save addPrintsAndDanaConsumption
            document.getElementById('addPrintsAndCutsDanaConsumption').addEventListener('submit',
                function(e) {
                    //let printCutEntry_id = {!! json_encode($data->id) !!};
                    e.preventDefault();
                    const form = e.target;
                    let printCutEntry_id = {!! json_encode($data->id) !!};
                    //console.log('testing', printCutEntry_id);
                    let godam_id = form.elements['godam_id'];
                    let dana_name_id = form.elements['dana_name_id'];
                    let quantity = form.elements['quantity'];
                    $.ajax({
                        url: "{{ route('printingAndCuttingDanaConsumption.store') }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            printCutEntry_id: printCutEntry_id,
                            godam_id: godam_id.value,
                            dana_name_id: dana_name_id.value,
                            quantity: quantity.value
                        },
                        success: function(response) {
                            removeAllTableRows('danaConsumption');
                            getdanaConsumptionData();
                            // setIntoConsumptionTable(response);
                            // getdanaConsumptionData();
                            updateStockQuantity();
                        },
                        error: function(xhr, status, error) {
                            setErrorMsg(xhr.responseJSON.message);
                        }
                    });
                });
            //current
            function updateStockQuantity() {
                let godam_id = $('#godamId').val();
                // let dana_group_id = $('#danaGroupId').val();
                let dana_name_id = $('#danaNameId').val();
                getStockQuantity(godam_id, dana_name_id);
            }

            //Save group
            document.getElementById('createGroupModel').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = e.target;
                let name = form.elements['cat_name_model'];
                let status = form.elements['group_status'];
                $.ajax({
                    url: "{{ route('group.store') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: name.value,
                        status: status.value,
                    },
                    success: function(response) {

                        $('#groupModel').modal('hide');
                        console.log('group', response);
                        setSuccessMessage(response.message);
                        setOptionInSelect('groupNameModel', response.group.id,
                            response.group.name);
                        setOptionInSelect('group', response.group.id,
                            response.group.name);
                    },
                    error: function(xhr, status, error) {
                        setErrorMsg(xhr.responseJSON.message);
                    }
                });
            });
            checkRollNumber()

            function checkRollNumber($roll_no) {
                let tbody = document.querySelector("#printsAndCutsItem tbody");
                let rollNumbers = tbody.querySelector('.rowRollno');

            }
            //save printing and cutting bag items
            document.getElementById('savePrintingAndCuttingBagItem').addEventListener('submit',
                function(e) {
                    e.preventDefault();
                    const form = e.target;
                    let printAndCutEntry_id = form.elements['printAndCutEntry_id'];
                    let roll_no = form.elements['roll_number'];
                    let fabric_id = form.elements['fabricId'];
                    let netWeight = form.elements['netWeight'];
                    let grossWeight = form.elements['grossWeight'];
                    let meter = form.elements['meter'];
                    let average = form.elements['average'];
                    let cutLength = form.elements['cutLength'];
                    let reqBag = form.elements['reqBag'];
                    let group_id = form.elements['group'];
                    let bagBrand_id = form.elements['bagBrand'];
                    let quantity_piece = form.elements['quantity_piece'];
                    let wastage = form.elements['wastage'];
                    let avg = form.elements['avg'];
                    let godam_id = form.elements['waste_godam_id'];
                    let waste_type_id = form.elements['waste_type_id'];
                    //  checkIfRollAlreadyEntered(roll_no);
                    $.ajax({
                        url: "{{ route('printingAndCuttingBagItem.store') }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            printAndCutEntry_id: printAndCutEntry_id.value,
                            group_id: group_id.value,
                            bag_brand_id: bagBrand_id.value,
                            quantity_piece: quantity_piece.value,
                            average: average.value,
                            wastage: wastage.value,
                            roll_no: roll_no.value,
                            fabric_id: fabric_id.value,
                            net_weight: netWeight.value,
                            cut_length: cutLength.value,
                            gross_weight: grossWeight.value,
                            meter: meter.value,
                            avg: avg.value,
                            req_bag: reqBag.value,
                            godam_id: godam_id.value,
                            waste_type_id: waste_type_id.value
                        },
                        success: function(response) {
                            console.log('megha',response);
                            setSuccessMessage(response.message);
                            setIntoTable(response.printingAndCuttingBagItem);
                            deleteEventBtn();
                        },
                        error: function(xhr, status, error) {

                            alert(xhr.responseJSON.error);

                        }
                    });
                });

            function checkIfRollAlreadyEntered() {
                try {
                    let bundles = Math.floor(quantity_Pcs_cal / bundle_pcs_cal);
                    let remainingPieces = quantity_Pcs_cal % bundle_pcs_cal;
                    if (remainingPieces > 0) {
                        throw new Error(
                            `Cannot create complete bundles. Remaining pieces: ${remainingPieces}`);
                    }
                    return true;
                } catch (error) {
                    setErrorMsg(error.message);
                    return false;
                }
            }

            let sn = 1;
            //set data in the table
            function setIntoTable(res) {

                var html = "";

                html = "<tr id=editRow-" + res.id + "><td>" + sn +
                    "</td><td class='rowGroupName'>" + res.group.name +
                    "</td><td class='rowBrandBagName'>" + res.brand_bag.name +
                    "</td><td class='rowQuantity_piece'>" + res.quantity_piece +
                    "</td><td class='rowAverage'>" + res.average +
                    "</td><td class='rowWastage'>" + res.wastage +
                    "</td><td class='rowRollno'>" + res.roll_no +
                    "</td><td class='rowFabricName'>" + res.fabric.name +
                    "</td><td class='rowNetWeight'>" + res.net_weight +
                    "</td><td class='rowGrossWeight'>" + res.gross_weight +
                    "</td><td class='rowMeter'>" + res.meter +
                    "</td><td class='rowAvg'>" + res.avg +
                    "</td><td class='rowReqBag'>" + res.req_bag +
                    "</td> <td>" +
                    // "<button class='btn btn-success editItemBtn' data-id=" +
                    // res.id + "><i class='fas fa-edit'></i></button>" +
                    // "  " +
                    "<button class='btn btn-danger dltPrintingAndCuttingBagItems' data-id=" +
                    res.id + " ><i class='fas fa-trash-alt'></i> </button>" + "</td ></tr>";

                document.getElementById('result').innerHTML += html;
                sn++;
                // Clearing the input fields
                clearInputFields();
            }
            //set data into dana consumption table
            function setIntoConsumptionTable(res) {
                var html = "";
                html = "<tr id=editConsumptRow-" + res.id + "><td>" + sn +
                    "</td><td class='rowGodamName'>" + res.godam.name +
                    "</td><td class='rowDanaName'>" + res.dana_name.name +
                    "</td><td class='rowQuantity'>" + res.quantity +
                    "</td></tr>";


                document.getElementById('printsCutsDanaConsumpt').innerHTML += html;
                sn++;
                // Clearing the input fields
                clearInputFields();
            }

            function deleteEventBtn() {
                let deleteButtons = document.getElementsByClassName('dltPrintingAndCuttingBagItems');
                //console.log(deleteButtons);
                for (let i = 0; i < deleteButtons.length; i++) {
                    deleteButtons[i].addEventListener('click', function(event) {
                        // console.log('hello');
                        let printingAndCuttingBagItem_id = this.getAttribute('data-id');
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
                                    url: '{{ route('printingAndCuttingBagItem.itemDelete', ['printingAndCuttingBagItem_id' => ':lol']) }}'
                                        .replace(':lol',
                                            printingAndCuttingBagItem_id),
                                    type: "DELETE",
                                    data: {
                                        "_method": "DELETE",
                                        "_token": "{{ csrf_token() }}",
                                    },
                                    success: function(response) {
                                        removeAllTableRows(
                                            'printsAndCutsItem');
                                        //   checkRowInTable();
                                        getPrintsAndCutsBagItems();
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
            //remove all table data
            function removeAllTableRows(tableId) {
                // Resetting SN
                sn = 1;
                let tableTbody = document.querySelector("#" + tableId + " tbody");
                //let tableTbody = tableId.querySelector("tbody");
                if (tableTbody) {
                    for (var i = tableTbody.rows.length - 1; i >= 0; i--) {
                        tableTbody.deleteRow(i);
                    }
                }
            }

            //clear the data from input fields
            function clearInputFields() {
                document.getElementById('rollNumber').value = "";
                document.getElementById('netWeight').value = "";
                document.getElementById('grossWeight').value = "";
                document.getElementById('meter').value = "";
                document.getElementById('average').value = "";
                document.getElementById('cutLength').value = "";
                document.getElementById('reqBag').value = "";
                document.getElementById('quantity_piece').value = "";
                document.getElementById('wastage').value = "";
                document.getElementById('avg').value = "";
                $('#fabricId').val($('#fabricId option:first').val()).change();
                $('#group').val($('#group option:first').val()).change();
                $('#bagBrand').val($('#bagBrand option:first').val()).change();
            }

            //save brand bag
            document.getElementById('createBrandBagModel').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = e.target;
                let name = form.elements['brand_name_model'];
                let group_id = form.elements['group_name_model'];
                let status = form.elements['bag_brand_status'];
                $.ajax({
                    url: "{{ route('bagBrand.store') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: name.value,
                        group_id: group_id.value,
                        status: status.value,
                    },
                    success: function(response) {
                        console.log('bag brand', response);
                        $('#bagBrandModel').modal('hide');
                        setSuccessMessage(response.message);
                        setOptionInSelect('bagBrand', response.bagBrand.id,
                            response.bagBrand.name);
                    },
                    error: function(xhr, status, error) {
                        setErrorMsg(xhr.responseJSON.message);
                    }
                });

            });
            //get bag brand when group slected
            // document.getElementById('group').addEventListener('change', function(e) {
            $('#group').on('select2:select', function(e) {
                //console.log('helo');
                let group_id = e.params.data.id;
                $.ajax({
                    url: "{{ route('bagBrand.getBagBrandFromGroup', ['group_id' => ':Replaced']) }}"
                        .replace(':Replaced', group_id),
                    method: 'GET',
                    success: function(response) {
                        // console.log('bag name from group', response);
                        fillOptionInSelect(response.bagBrands, '#bagBrand');

                    },
                    error: function(xhr, status, error) {
                        reject(error);
                    }
                });
            });

            function fillOptionInSelect(obj, element_id) {
                let selectOptions = '';
                if (obj.length == 0) {
                    selectOptions += "<option value='' disabled selected>No Data found</option>";
                } else {
                    selectOptions +=
                        "<option value='' disabled selected> select required data </option>";
                    for (let i = 0; i < obj.length; i++) {
                        let optionText = obj[i].name;
                        let optionValue = obj[i].id;
                        let option = new Option(optionText, optionValue);
                        selectOptions += option.outerHTML;
                    }
                }
                $(element_id).html(selectOptions);
            }

            function setOptionInFabSelect(elementId, optionId, optionText) {
                let selectElement = $('#' + elementId);

                selectElement.empty();
                // create a new option element
                let newOption = $('<option>');

                // set the value and text of the new option element
                newOption.val(optionId).text(optionText);

                // append the new option element to the select element
                selectElement.append(newOption);

                newOption.prop('selected', true);

                // refresh the select2 element to update the UI
                selectElement.trigger('change.select2');
                // $('#' + elementId).val(optionId).trigger('change.select2');
            }

            function setOptionInSelect(elementId, optionId, optionText) {
                let selectElement = $('#' + elementId);
                let newOption = $('<option>');
                newOption.val(optionId).text(optionText);
                selectElement.append(newOption);
                selectElement.trigger('change.select2');

            }

            function setErrorMsg(errorMessage) {
                let errorContainer = document.getElementById('error_msg');
                errorContainer.hidden = false;
                errorContainer.innerHTML = errorMessage;
                setTimeout(function() {
                    errorContainer.hidden = true;
                }, 2000);
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


        });
    </script>
    <script>
        @if (session()->has('message'))
            toastr.success("{{ session()->get('message') }}");
        @elseif (session()->has('message_err'))

            toastr.error("{{ session()->get('message_err') }}");
        @endif
    </script>
@endsection
