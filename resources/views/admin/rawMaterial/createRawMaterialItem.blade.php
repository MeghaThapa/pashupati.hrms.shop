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

        */
    </style>
@endsection
@section('content')
    {{-- message for success --}}
    <div id="success_msg" class="alert alert-success mt-2" hidden>
    </div>
    <div id="error_msg" class="alert alert-danger mt-2" hidden>
    </div>
    <div id="RawMaterialItemsError" class="alert alert-danger" hidden></div>
    <div style="display: flex; flex-direction:column;margin:10px;">
        <div style="display: flex; justify-content:space-between;">
            <p>Date : {{ $rawMaterial->date }}</p>
            <p>Storein Type: {{ $rawMaterial->storein_type ? $rawMaterial->storein_type->name : 'EMPTY' }}</p>
            <p>Supplier : {{ $rawMaterial->supplier ? $rawMaterial->supplier->name : 'EMPTY' }}</p>
            <p>From Godam : {{ $rawMaterial->fromGodam ? $rawMaterial->fromGodam->name : 'EMPTY' }}</p>
            <p>To Godam : {{ $rawMaterial->toGodam ? $rawMaterial->toGodam->name : 'EMPTY' }}</p>

        </div>
        <div style="display: flex; justify-content:space-between;">
            <p>GP No : {{ $rawMaterial->gp_no ?? 'EMPTY' }}</p>
            <p>Bill : {{ $rawMaterial->bill_no ?? 'EMPTY' }}</p>
            <p>PP No : {{ $rawMaterial->pp_no ?? 'EMPTY' }}</p>
            <p>Challan No : {{ $rawMaterial->challan_no ?? 'EMPTY' }}</p>
            <p>Receipt No : {{ $rawMaterial->receipt_no }}</p>

        </div>
    </div>

    <div class="card-body p-0 m-0">
        <form id="createRawMaterial">
            @csrf
            <div class="row">
                <div class="col-md-2 form-group">
                    <input type="text" step="any" min="0" class="form-control calculator" id="rawMaterialId"
                        data-number="1" name="rawMaterial_id" value="{{ $rawMaterial->id }}" min="1" required
                        hidden>
                    <label for="size" class="col-form-label">{{ __('Lorry No') }}<span class="required-field">*</span>
                    </label>
                    <input type="text" step="any" min="0" class="form-control calculator" id="lorryNo"
                        data-number="1" name="lorry_no" placeholder="{{ __('Lorry No') }}" min="1" required>
                </div>

                <div class="col-md-3 form-group">
                    <label for="size" class="col-form-label">{{ __('Dana Group') }}
                    </label>
                    <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                        data-target="#addDanaGroupModel" style="margin-top:0 !important; top:0;float:right;">
                        <i class="fas fa-plus" style="display:flex;align-items: center;justify-content: center;"></i>
                    </a>
                    <select class="advance-select-box form-control" id="danaGroup" name="dana_group" required>
                        <option value="" selected disabled>{{ __('Select dana Group') }}</option>
                        @foreach ($danaGroups as $danaGroup)
                            <option value="{{ $danaGroup->id }}">{{ $danaGroup->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label for="size" class="col-form-label">{{ __('Dana Name') }}
                    </label>
                    <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                        data-target="#addDanaNameModel" style="margin-top:0 !important; top:0;float:right;">
                        <i class="fas fa-plus" style="display:flex;align-items: center;justify-content: center;"></i>
                    </a>

                    <select class="advance-select-box form-control" id="danaName" name="dana_name_id" required>
                        <option value="" selected disabled>{{ __('Select dana Name') }}</option>
                    </select>
                </div>
                @if ($rawMaterial && $rawMaterial->from_godam_id)
                    <div class="col-md-2 form-group">
                        <label for="size" class="col-form-label">{{ __('Stock Qty') }}
                        </label>
                        <input type="text" step="any" min="0" class="form-control calculator" id="stockQty"
                            data-number="1" name="stock_qty" placeholder="{{ __('Stock QTY') }}" min="1" readonly>
                    </div>
                @endif
                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('Qty in Kg') }}
                    </label>
                    <input type="text" step="any" min="0" class="form-control calculator" id="quantityInKg"
                        data-number="1" name="quantity_in_kg" placeholder="{{ __('Remarks') }}" min="1" required>
                </div>
                <div>
                    <button type="submit" class="btn btn-sm btn-primary" style="margin-top:35px;">
                        Add
                    </button>
                </div>

            </div>
        </form>
    </div>
    {{-- <button class="btn btn-primary" data-toggle="modal" data-target="#tryModel">Open Modal</button> --}}

    <div class="row">
        <div class="Ajaxdata col-md-12">
            <div class="p-0 table-responsive table-custom my-3">
                <table class="table" id="rawMaterialItemTable">
                    <thead>
                        <tr>
                            <th>{{ __('S.No') }}</th>
                            <th>{{ __('Dana Group') }}</th>
                            <th>{{ __('Dana Name') }}</th>
                            <th>{{ __('Quantity In Kg') }} (<span id="totalQty"></span>)
                            </th>
                            <th>{{ __('Lorry No') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>

                    <tbody id="rawMaterialItemTbody">
                    </tbody>

                </table>

                {{-- for sub total --}}
                {{-- <input type="text" id="subTotal"> --}}
            </div>

        </div>
    </div>
    <div class="row">
        <a href="{{ route('rawMaterial.index') }}">
            <button class="btn btn-primary">Back</button>
        </a>
    </div>
    <!--Dana Name popup-->
    <div class="modal fade" id="addDanaNameModel" tabindex="-1" role="dialog" aria-labelledby="exampleModaltax"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModaltax">Add Dana Name</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="modelFormDanaName">
                        @csrf
                        <div class="card-body">
                            <div id="danaName-create-form-error" class="alert alert-danger" hidden>

                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="status" class="col-form-label">{{ __('Dana Name') }}
                                        <span class="required-field">*</span>
                                    </label>
                                    {{-- <select class="advance-select-box form-control" id="danaNameModel"
                                        name="dana_name_model_id" required>
                                        <option value="" selected disabled>{{ __('Select dana Name') }}</option>
                                        @foreach ($danaNames as $danaName)
                                            <option value="{{ $danaName->id }}">{{ $danaName->name }}
                                            </option>
                                        @endforeach
                                    </select> --}}
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="{{ __('Dana Name') }}" value="{{ old('placement') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="status" class="col-form-label">{{ __('Dana Group Name') }}
                                        <span class="required-field">*</span>
                                    </label>
                                    <select class="advance-select-box form-control" id="danaGroupId_model"
                                        name="dana_group_id" required>
                                        <option value="" selected disabled>{{ __('Select dana Group') }}
                                        </option>
                                        @foreach ($danaGroups as $danaGroup)
                                            <option value="{{ $danaGroup->id }}">{{ $danaGroup->name }}
                                            </option>
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
                        </div>
                        <!-- /.card-body -->

                </div>
                <div class="modal-footer">
                    <div class="row" style="gap:10px;">
                        <div>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                                {{ __('Save Dana Name') }}</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Close
                        </button>
                    </div>
                    <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                </div>
                </form>
            </div>
        </div>
    </div>
    <!--Dana Name Popup End-->

    {{-- Dana Group model popup --}}
    <div class="modal fade" id="addDanaGroupModel" tabindex="-1" role="dialog" aria-labelledby="addDepartmentModel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="createDanaGroup">
                    @csrf
                    <div id="edit-form-error" class="alert alert-danger" hidden>

                    </div>
                    <div class="modal-header">
                        <h5 class="modal-title" id="addDepartmentModel">Add Dana Group</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>


                    <div class="modal-body">

                        <div class="card-body">
                            <div class="row form-group">
                                <label for="email" class="col-form-label">{{ __('Dana Group Name') }}</label>
                                <input type="text" class="form-control @error('email') is-invalid @enderror"
                                    id="DanaGroupName" name="dana_group_name" placeholder="{{ __('Dana Group Name') }}"
                                    value="{{ old('dana_group_name') }}">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="row form-group">
                                <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="active">{{ __('Active') }}</option>
                                    <option value="inactive">{{ __('Inactive') }}</option>
                                </select>
                            </div>

                            <div class="row mt-2">
                                <div class="col-sm-7 ">

                                </div>
                                <div class="col-sm-5 ">

                                    <button type="submit" class="btn btn-primary float-right"><i
                                            class="fas fa-save"></i>
                                        {{ __('Save') }}</button>


                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                        Close
                                    </button>

                                </div>
                            </div>
                            <!-- /.card-body -->

                        </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Dana Group model popup end --}}
    {{-- edit RawMaterialItem modal start --}}
    <div class="modal fade" id="rawMaterialModel" tabindex="-1" role="dialog" aria-labelledby="exampleModaltax"
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

                </div>

            </div>
        </div>
    </div>
    {{-- RawMaterialItem modal end --}}
@endsection
@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/storein.js') }}"></script>

    <script>
        $(document).ready(function() {
            let sn = 1;
            //get Data in table when page refreshed
            getRawMaterialItemsData();
            clearInputFields();


            //for model type
            let from_godam_model = document.getElementById('fromGodamModel');
            let challan_no_model = document.getElementById('challanNoModel');
            let gp_no_model = document.getElementById('gpNoModel');
            $('#TypeIdModel').on('select2:select', function(e) {
                let selectedName = e.params.data.text.replace(/\s/g, "");
                if (selectedName == "Godam") {
                    from_godam_model.disabled = false;
                    challan_no_model.disabled = false;
                    gp_no_model.disabled = false;

                    from_godam_model.required = true;
                    challan_no_model.required = true;
                    gp_no_model.required = true;
                } else {
                    from_godam_model.disabled = true;
                    challan_no_model.disabled = true;
                    gp_no_model.disabled = true;

                    from_godam_model.required = false;
                    challan_no_model.required = false;
                    gp_no_model.required = false;

                    $('#fromGodamModel').val($('#from_godam_id_model option:first').val()).change();
                    $('#challanNoModel').val('');
                    $('#gpNoModel').val('');

                }


            });


            function setIntoTable(res) {

                var html = "";

                html = "<tr  id=editRow-" + res.id + "><td>" + sn +
                    "</td><td class='rowDanaGroupName'>" + res.dana_group.name +
                    "</td><td class='rowDanaName'>" + res.dana_name.name +
                    "</td><td class='rowQuantity'>" + res.quantity +
                    "</td><td class='rowLorryNo'>" + res.lorry_no +
                    "</td><td>" +
                    "<button class='btn btn-danger dltRawMaterialItem' data-id=" +
                    res.id + " ><i class='fas fa-trash-alt'></i> </button>" + "</td ></tr>";

                document.getElementById('rawMaterialItemTbody').innerHTML += html;
                sn++;
                // Clearing the input fields
                clearInputFields();
                deleteEventBtn();
            }


            // Updating Table tr td value when something changed or updated
            function updateTableRow(response, raw_material_item_id) {
                // triggering table tr by storeinItem_id
                let row = document.getElementById('editRow-' + raw_material_item_id);

                //Updating tds
                row.querySelector('td.rowDanaGroupName').innerHTML = response.dana_group.name;
                row.querySelector('td.rowDanaName').innerHTML = response.dana_name.name;
                row.querySelector('td.rowQuantity').innerHTML = response.quantity;
                row.querySelector('td.rowLorryNo').innerHTML = response.lorry_no;
                //row.querySelector('td.rowPrice').innerHTML = response.price;
                //row.querySelector('td.rowTotalAmount').innerHTML = response.total_amount;

            }

            function deleteEventBtn() {
                let dltButtons = document.getElementsByClassName('dltRawMaterialItem');
                let fromRawMaterialStock = JSON.parse(`{!! json_encode($fromRawMaterialStock) !!}`);
                let rawMaterial = JSON.parse(`{!! json_encode($rawMaterial) !!}`);
                for (var i = 0; i < dltButtons.length; i++) {
                    dltButtons[i].addEventListener('click', function(event) {
                        let RawMaterialItemId = this.getAttribute('data-id');
                        new swal({
                            title: "Are you sure?",
                            text: "Do you want to delete Item.",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                            closeOnClickOutside: false,
                        }).then((willDelete) => {
                            if (willDelete) {
                                $.ajax({
                                    url: '{{ route('rawMaterialItem.delete', ['id' => ':lol']) }}'
                                        .replace(':lol', RawMaterialItemId),
                                    type: "DELETE",
                                    data: {
                                        "_method": "DELETE",
                                        "_token": "{{ csrf_token() }}",
                                        'fromRawMaterialStock': fromRawMaterialStock,
                                        "rawMaterial_id": rawMaterial.id
                                    },
                                    success: function(result) {
                                        console.log('delete :', result);
                                        getRawMaterialItemsData();
                                        calculateTotalQuantity();
                                    },
                                    error: function(xhr, status, error) {
                                        setErrorMessage('error_msg', xhr.responseJSON
                                            .message)
                                    }

                                });
                            };
                        });
                    })
                }
            }

            function removeAllTableRows() {
                // Reseting SN
                sn = 1;
                let tbody = document.querySelector("#rawMaterialItemTable tbody");
                for (var i = tbody.rows.length - 1; i >= 0; i--) {
                    tbody.deleteRow(i);
                }
            }


            //Set raMaterialItem data when page refreshed
            function getRawMaterialItemsData() {
                removeAllTableRows();
                $.ajax({
                    url: '{{ route('rawMaterialItem.getRawMaterialItemsData', ['rawMaterial_id' => $rawMaterial->id]) }}',
                    method: 'GET',
                    success: function(response) {
                        if (response.count <= 0) {
                            return false;
                        }
                        response.rawMaterialItemDatas.forEach(function(itemsRow) {
                            setIntoTable(itemsRow);
                        });
                        calculateTotalQuantity();
                    },
                    error: function(xhr, status, error) {
                        reject(error);
                    }
                });
            }

            //create raw material
            document.getElementById('createRawMaterial').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = event.target;
                let rawMaterial_id = form.elements['rawMaterial_id'];
                let lorry_no = form.elements['lorry_no'];
                let dana_group_id = form.elements['dana_group'];
                let dana_name_id = form.elements['dana_name_id'];
                let quantity_in_kg = form.elements['quantity_in_kg'];
                let fromStockBool = JSON.parse(`{!! json_encode($fromRawMaterialStock) !!}`);
                console.log('js :', fromStockBool);
                $.ajax({
                    url: "{{ route('rawMaterialItem.store') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        lorry_no: lorry_no.value,
                        dana_group_id: dana_group_id.value,
                        dana_name_id: dana_name_id.value,
                        quantity_in_kg: quantity_in_kg.value,
                        rawMaterial_id: rawMaterial_id.value,
                        fromStockBool: fromStockBool
                    },
                    success: function(response) {
                        console.log(response);
                        setSuccessMessage(response.message);
                        clearInputFields();
                        setIntoTable(response.rawMaterialItem);
                        calculateTotalQuantity();
                    },
                    error: function(xhr) {
                        setMessage('RawMaterialItemsError', xhr.responseJSON.message)

                        //console.log(xhr.responseJSON.message);
                    }
                });
            });

            function calculateTotalQuantity() {
                const myTable = document.getElementById("rawMaterialItemTable");

                // Get the tbody element of the table
                const tbody = myTable.querySelector("tbody");

                // Get all the tr elements in the tbody
                const rows = tbody.querySelectorAll("tr");

                // Loop through the rows and do something with them
                let eachTotalQuantity = 0;

                rows.forEach(row => {
                    eachTotalQuantity += parseInt(row.querySelector('td.rowQuantity').innerHTML);
                });
                document.getElementById('totalQty').innerHTML = eachTotalQuantity;


            }

            function clearInputFields() {
                // document.getElementById('lorryNo').value = "";
                document.getElementById('quantityInKg').value = "";
                //clear select 2
                // $('#fromGodam').val($('#fromGodam option:first').val()).change();
                //$('#toGodam').val($('#toGodam option:first').val()).change();
                $('#danaGroup').val($('#danaGroup option:first').val()).change();
                $('#danaName').val($('#danaName option:first').val()).change();

            }
            document.getElementById('modelFormDanaName').addEventListener('submit', function(
                e) {
                e.preventDefault();
                const form = event.target;
                let name = form.elements['name'];
                let dana_group_id = form.elements['danaGroupId_model'];
                let status = form.elements['status'];

                $.ajax({
                    url: "{{ route('danaName.store') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: name.value,
                        dana_group_id: dana_group_id.value,
                        status: status.value,
                    },
                    success: function(response) {
                        $('#addDanaNameModel').modal('hide');
                        setSuccessMessage(response.message);
                        console.log(response.danaName.name)
                        setOptionInSelect(
                            'danaName',
                            response.danaName.id,
                            response.danaName.name);
                    },
                    error: function(xhr, status, error) {
                        // console.log();
                        // console.log(xhr.responseText.message);
                        let errorMsg = xhr.responseJSON.message;
                        console.log(errorMsg);
                        if (errorMsg) {
                            setErrorMessage('danaName-create-form-error',
                                errorMsg);
                        } else {

                            setErrorMessage('danaName-create-form-error',
                                'Something went wrong');
                        }

                    }
                });
            });
            document.getElementById('createDanaGroup').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = event.target;
                let name = form.elements['dana_group_name'];
                let status = form.elements['status'];
                $.ajax({
                    url: "{{ route('danaGroup.store') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        dana_group_name: name.value,
                        status: status.value,
                    },
                    success: function(response) {
                        $('#addDanaGroupModel').modal('hide');
                        setSuccessMessage(response.message);
                        console.log(response.danaGroup.name)
                        setOptionInSelect(
                            'danaGroupId_model',
                            response.danaGroup.id,
                            response.danaGroup.name);
                        // console.log(response);
                        setOptionInSelect(
                            'danaGroup',
                            response.danaGroup.id,
                            response.danaGroup.name);



                    },
                    error: function(xhr, status, error) {
                        setErrorMessage('edit-form-error',
                            'Please Fill out all fields')
                    }
                });
            });
            //success message
            function setSuccessMessage(message) {
                let successContainer = document.getElementById('success_msg');
                //console.log(successContainer);
                successContainer.hidden = false;
                successContainer.innerHTML = message;
                setTimeout(function() {
                    successContainer.hidden = true;
                }, 2000); // 5000 milliseconds = 5 seconds
            }
            // errror message inside of model
            function setErrorMessage(element_id, message) {
                let errorContainer = document.getElementById(element_id);
                errorContainer.hidden = false;
                errorContainer.innerHTML = message;
                setTimeout(function() {
                    errorContainer.hidden = true;
                }, 2000);
            }

            //to get dana name according to dana group
            $('#danaGroup').on('select2:select', function(e) {
                let danaGroup_id = e.target.value;
                let godam_id = JSON.parse(`{!! json_encode($rawMaterial->from_godam_id) !!}`);
                let fromStockBool = JSON.parse(`{!! json_encode($fromRawMaterialStock) !!}`);
                getDanaName(danaGroup_id, fromStockBool, godam_id);
            });

            $('#danaName').on('select2:select', function(e) {
                let fromStockBool = JSON.parse(`{!! json_encode($fromRawMaterialStock) !!}`);
                if (!fromStockBool) {
                    return false;
                }
                let danaName_id = e.target.value;
                let danaGroup_id = $('#danaGroup').val();
                let godam_id = JSON.parse(`{!! json_encode($rawMaterial->from_godam_id) !!}`);
                if (danaName_id && danaGroup_id && godam_id) {
                    getStockDanaName(danaGroup_id, godam_id, danaName_id);
                }

            });

            function getStockDanaName(danaGroup_id, godam_id, danaName_id) {
                $.ajax({
                    url: "{{ route('rawMaterial.getStock') }}",
                    data: {
                        'dana_group_id': danaGroup_id,
                        'godam_id': godam_id,
                        'danaName_id': danaName_id
                    },
                    method: 'GET',
                    success: function(quantity) {
                        $('#stockQty').val(quantity);
                    },
                    error: function(xhr, status, error) {
                        setErrorMessage('error_msg', error);
                    }
                });
            }

            function getDanaName(danaGroup_id, fromStockBool, godam_id = null) {
                return new Promise(function(resolve, reject) {
                    let url = '';
                    if (fromStockBool && godam_id) {
                        url =
                            "{{ route('rawMaterial.getDanaGroupDanaNameFromRawMStock', ['danaGroup_id' => ':danaGroup', 'godam_id' => ':godam_id']) }}"
                            .replace(':danaGroup', danaGroup_id).replace(':godam_id', godam_id);
                    } else {
                        url =
                            "{{ route('rawMaterial.getDanaGroupDanaName', ['danaGroup_id' => ':Replaced']) }}"
                            .replace(':Replaced', danaGroup_id);
                    }

                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function(response) {
                            let selectOptions = '';
                            if (response.length == 0) {
                                selectOptions +=
                                    '<option disabled selected>' +
                                    'no items found' + '</option>';
                            } else {
                                selectOptions +=
                                    '<option disabled selected>' +
                                    'select an item' + '</option>';
                                for (var i = 0; i < response.length; i++) {
                                    selectOptions += '<option value="' +
                                        response[i].id +
                                        '">' +
                                        response[i].name + '</option>';
                                }
                            }
                            $('#danaName').html(selectOptions);
                            resolve(response);
                        },
                        error: function(xhr, status, error) {
                            reject(error);
                        }
                    });
                });
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

            function setMessage(element_id, message) {
                let errorContainer = document.getElementById(element_id);
                errorContainer.hidden = false;
                errorContainer.innerHTML = message;
                setTimeout(function() {
                    errorContainer.hidden = true;
                }, 2000);
            }

        });
    </script>
@endsection
