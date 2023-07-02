@extends('layouts.admin')
@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />

    {{-- <style>
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
    </style> --}}
@endsection
@section('content')
    <!-- Content Header (Page header) -->
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
    {{-- </div> --}}
    <div class="content">
        <div class="container-fluid">
            <a class='btn btn-primary go-back float-right'>Go back</a>
            <br><br>
            <div class="form">
                <form action="">
                    <div class="row">
                        @foreach ($data as $d)
                            <div class="col-md-3">
                                <label for="receipt_number">Receipt Number</label>
                                <input type="text" value="{{ $d->receipt_number }}" class="form-control"
                                    id='receipt_number' name='receipt_number' readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="Date NP">Date</label>
                                <input type="text" value="{{ $d->date }}" class="form-control" id='date_np'
                                    name='date_np' readonly>
                            </div>
                        @endforeach
                        <div class="col-md-3">
                            <label for="Roll number">Roll Number</label>
                            <input type="text" class="form-control" id='roll_number' name='roll_number' disabled>
                        </div>
                        <div class="col-md-3">
                            <label for="Fabric">Fabric </label>
                            <select name="fabric_id" class="form-control advance-select-box" id="fabricId">
                                <option value="">Fabric Hre</option>
                                @foreach ($fabrics as $fabricData)
                                    <option value="{{ $fabricData->id }}">{{ $fabricData->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="net_weight">Net Weight </label>
                            <input type="text" class="form-control" id="netWeight" name="net_weight">
                        </div>
                        <div class="col-md-3">
                            <label for="gross_weight">Gross Weight </label>
                            <input type="text" class="form-control" id="grossWeight" name="gross_weight">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="meter">Meter</label>
                            <input type="text" class="form-control" id="meter" name="meter">
                        </div>
                        <div class="col-md-3">
                            <label for="average">Average</label>
                            <input type="text" class="form-control" id="average" name="average">
                        </div>
                        <div class="col-md-2">
                            <label for="cut_length">Cut Length</label>
                            <input type="text" class="form-control" id="cutLength" name="cut_length">
                        </div>
                        <div class="col-md-2">
                            <label for="req_length">Req Bag</label>
                            <input type="text" class="form-control" id="reqBag" name="req_bag">
                        </div>
                        <div class="col-md-2">
                            <label for="req_length">QTY in kg</label>
                            <input type="text" class="form-control" id="qtyInKg" name="qty_in_kg">
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
                </form>
                <hr>
                <div class="table-custom table-responsive ">
                    <table class="table table-hover table-bordered">
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
                        <tbody>
                            <tr>
                                <td>SN</td>
                                <td>Brand Group</td>
                                <td>Bag Brand</td>
                                <td>Quantity Piece</td>
                                <td>Average</td>
                                <td>Wastage</td>
                                <td>Roll No</td>
                                <td>Fabric Name</td>
                                <td>NW</td>
                                <td>GW</td>
                                <td>Meter</td>
                                <td>Avg</td>
                                <td>Req Bag</td>
                                <td>
                                    <a class="btn btn-danger" href="javascript:void(0)"><i class="fa fa-trash"
                                            aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                Hello
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Hello</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        Hwello
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mx-1">
                    <button class="btn btn-success">Update</button>
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
            $(".go-back").click(function() {
                history.back();
            });


            $(".add-group").click(function(e) {
                alert("hello");
            });


            $('#fabricId').on('select2:select', function(e) {
                // console.log('helo');
                let fabric_id = e.params.data.id;
                // let click_by=blade;
                getNetWeightGrossWeight(fabric_id);
            });



            //  adding event listenersfor calculating QTY in kg
            document.getElementById('average').addEventListener('input', checkInputsToCalQtyInKg);
            document.getElementById('reqBag').addEventListener('input', checkInputsToCalQtyInKg);

            function checkInputsToCalQtyInKg() {
                var average = document.getElementById('average').value;
                var reqBag = document.getElementById('reqBag').value;
                // Check if both fields have values
                if (meter && cutLength) {
                    calculateQtyInKg(average, reqBag);
                }
            }

            function calculateQtyInKg(average, reqBag) {
                let qtyInKg = parseFloat(average) * parseFloat(reqBag);
                document.getElementById('qtyInKg').value = qtyInKg;
            }

            // Adding event listeners
            document.getElementById('meter').addEventListener('input', checkInputs);
            document.getElementById('cutLength').addEventListener('input', checkInputs);

            // Checking if both input fields have a value
            function checkInputs() {
                var meter = document.getElementById('meter').value;
                var cutLength = document.getElementById('cutLength').value;

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
                        console.log('bag name from group', response);
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

            function getNetWeightGrossWeight(fabric_id) {
                $.ajax({
                    url: "{{ route('printsAndCuts.getNetWeightGrossWeight', ['fabric_id' => ':Replaced']) }}"
                        .replace(
                            ':Replaced',
                            fabric_id),
                    method: 'GET',
                    success: function(response) {
                        console.log(response);
                        document.getElementById('netWeight').value = response.net_wt;
                        document.getElementById('grossWeight').value = response.gross_wt;
                    },
                    error: function(xhr, status, error) {
                        reject(error);
                    }
                });
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
