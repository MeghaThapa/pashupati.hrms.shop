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
    <!-- Content Header (Page header) -->
    <div id="error_msg" class="alert alert-danger mt-2" hidden>

    </div>
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Rawmaterial Opening') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">

                        {{ __('Bag Bundelling') }}</li>
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
        {{-- <div class="row" style="float:right">
            <a class='btn btn-primary go-back float-right'>Go back</a>
        </div>
        <br> --}}
        <div class=" p-0 m-0">
            <form action="" id="openingRawmaterialItemStore">
                <div class="row ">
                    <input type="text" id="bagSellingEntryId" name="bag_selling_entry_id" hidden
                        value="{{ $rawmaterialOpeningEntry->id }}">
                    <div class="col-md-3">
                        <label for="receipt_number">Opening Date</label>
                        <input type="text" class="form-control" id='challanNumber'
                            name='challan_number'value="{{ $rawmaterialOpeningEntry->opening_date }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="Date NP">Receipt No</label>
                        <input type="text" class="form-control" id='date'
                            name='date'value="{{ $rawmaterialOpeningEntry->receipt_no }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="Date NP">To Godam</label>
                        <input type="text" class="form-control" id='nepaliDate'
                            name='nepali_date'value="{{ $rawmaterialOpeningEntry->godam->name }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="gross_weight">Dana Group</label>
                        <select class="advance-select-box form-control" id="danaGroupId" name="dana_group_id" required>
                            <option value=" " selected disabled>{{ __('Select a group') }}</option>
                            @foreach ($danaGroups as $danaGroup)
                                <option value="{{ $danaGroup->id }}">{{ $danaGroup->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="Date NP">Dana Name</label>
                        <select class="advance-select-box form-control" id="danaNameId" name="dana_name_id" required>
                            <option value=" " selected disabled>{{ __('Select a group') }}</option>
                            {{-- @foreach ($danaNames as $danaName)
                                <option value="{{ $danaName->id }}">{{ $danaNames->name }}
                                </option>
                            @endforeach --}}
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="Date NP">Qty In kg</label>
                        <input type="text" class="form-control" id='qtyInKg' name='qty_in_kg'>
                    </div>
                    <div class="col-md-3 mt-4">
                        <button class="btn btn-primary">Add</button>
                    </div>
                </div>

        </div>
        </form>
        <div class="table-custom table-responsive">
            <table class="table table-hover table-bordered" id="rawmaterialOpeningTable">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Dana Group</th>
                        <th>Dana Name</th>
                        <th>Quantity In Kg</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="rawmaterialOpeningTbody">
                </tbody>
            </table>
        </div>

        <div class="row mx-1 mt-3">
            <button class="btn btn-success" id="updateOpeningRawmaterialentry">Add</button>
        </div>
    </div>

    </div>
@endsection
@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            refresh();
            async function refresh() {
                await openingRawmaterialItemData();
            }

            function openingRawmaterialItemData() {
                let rawmaterial_opening_entry_id = {!! json_encode($rawmaterialOpeningEntry->id) !!}
                return new Promise(function(resolve, reject) {
                    $.ajax({
                        url: "{{ route('openingRawmaterialEntry.getRawmaterialItem') }}",
                        method: 'get',
                        data: {
                            _token: "{{ csrf_token() }}",
                            rawmaterial_opening_entry_id: rawmaterial_opening_entry_id,
                        },
                        success: function(response) {
                            response.forEach(function($item) {
                                setIntoTable($item)
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

            function setErrorMsg(errorMessage) {
                let errorContainer = document.getElementById('error_msg');
                errorContainer.hidden = false;
                errorContainer.innerHTML = errorMessage;
                setTimeout(function() {
                    errorContainer.hidden = true;
                }, 5000);
            }

            let sn = 1;
            $('#danaGroupId').on('select2:select', function(e) {
                let danaGroup_id = e.target.value;
                getDanaName(danaGroup_id);
            });
            document.getElementById('updateOpeningRawmaterialentry').addEventListener('click', function(e) {
                let rawmaterial_opening_entry_id = {!! json_encode($rawmaterialOpeningEntry->id) !!}
                //  console.log('ghjgfvb', rawmaterial_opening_entry_id);
                $.ajax({
                    url: "{{ route('openingRawmaterialEntry.saveEntire') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        rawmaterial_opening_entry_id: rawmaterial_opening_entry_id,

                    },
                    success: function(response) {
                        //console.log('megha fjytrdghj', response);
                        window.location.href = "{{ route('openingRawmaterialEntry.index') }}";
                        //setIntoTable(response);

                    },
                    error: function(xhr) {
                        setMessage('RawMaterialItemsError', xhr.responseJSON.message)

                        //console.log(xhr.responseJSON.message);
                    }
                });
            });
            document.getElementById('openingRawmaterialItemStore').addEventListener('submit',
                function(e) {
                    e.preventDefault();
                    const form = e.target;
                    let rawmaterial_opening_entry_id = form.elements['bag_selling_entry_id'];
                    let dana_group_id = form.elements['dana_group_id'];
                    let dana_name_id = form.elements['dana_name_id'];
                    let qty_in_kg = form.elements['qty_in_kg'];
                    $.ajax({
                        url: "{{ route('openingRawmaterialItem.store') }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            rawmaterial_opening_entry_id: rawmaterial_opening_entry_id.value,
                            dana_group_id: dana_group_id.value,
                            dana_name_id: dana_name_id.value,
                            qty_in_kg: qty_in_kg.value
                        },
                        success: function(response) {
                            console.log('megha fjytrdghj', response);
                            setIntoTable(response);

                        },
                        error: function(xhr) {
                            setMessage('RawMaterialItemsError', xhr.responseJSON.message)

                            //console.log(xhr.responseJSON.message);
                        }
                    });
                });

            function setIntoTable(res) {

                var html = "";

                html = "<tr  id=editRow-" + res.id + "><td>" + sn +
                    "</td><td class='rowDanaGroupName'>" + res.dana_group.name +
                    "</td><td class='rowDanaName'>" + res.dana_name.name +
                    "</td><td class='rowQuantity'>" + res.qty_in_kg +
                    "</td><td>" +
                    "<button class='btn btn-danger dltOpeningRawMaterialItem' data-id=" +
                    res.id + " ><i class='fas fa-trash-alt'></i> </button>" + "</td ></tr>";

                document.getElementById('rawmaterialOpeningTbody').innerHTML += html;
                sn++;
                // Clearing the input fields
                // clearInputFields();
                deleteEventBtn();
            }

            function deleteEventBtn() {
                let dltButtons = document.getElementsByClassName('dltOpeningRawMaterialItem');

                for (var i = 0; i < dltButtons.length; i++) {
                    dltButtons[i].addEventListener('click', function(event) {
                        let openingRawMaterialItemId = this.getAttribute('data-id');
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
                                    url: '{{ route('openingRawmaterialItem.delete', ['id' => ':lol']) }}'
                                        .replace(':lol', openingRawMaterialItemId),
                                    type: "DELETE",
                                    data: {
                                        "_method": "DELETE",
                                        "_token": "{{ csrf_token() }}",

                                    },
                                    success: function(result) {
                                        console.log('delete :', result);
                                        new swal
                                            ({
                                                text: "Poof! Your data has been deleted!",
                                                title: "Deleted",
                                                icon: "success",
                                            });
                                        location.reload();

                                        refresh();

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

            function setSuccessMessage(message) {
                let successContainer = document.getElementById('success_msg');
                //console.log(successContainer);
                successContainer.hidden = false;
                successContainer.innerHTML = message;
                setTimeout(function() {
                    successContainer.hidden = true;
                }, 2000); // 5000 milliseconds = 5 seconds
            }

            function getDanaName(danaGroup_id) {
                return new Promise(function(resolve, reject) {
                    let url =
                        "{{ route('openingRawmaterialEntry.getDanaGroupDanaName', ['danaGroup_id' => ':Replaced']) }}"
                        .replace(':Replaced', danaGroup_id);
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
                            $('#danaNameId').html(selectOptions);
                            resolve(response);
                        },
                        error: function(xhr, status, error) {
                            reject(error);
                        }
                    });
                });
            }
        });
    </script>
@endsection
