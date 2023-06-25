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
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6 mt-2">
                <h4><strong>Tape Receive Entry</strong></h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Tape Entry') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            {{-- <div class="card"> --}}

            {{-- <div class="card-body"> --}}
            <form id="tape_entry_form" action="{{ route('tape.entry.stock.store') }}" method="post">
                @csrf
                @foreach ($tapeentries as $data)
                    <div class='row mt-2'>
                        <div class="col-md-4">
                            <label for="tape_receive_date">Tape Date</label>
                            <input type="date" name="tape_receive_date" class="form-control"
                                value="{{ $data->tape_entry_date }}" readonly required />
                        </div>
                        <div class="col-md-4">
                            <label for="receipt_no">Receipt Number</label>
                            <input type="text" value="{{ $data->receipt_number }}" id="receipt_number_1_repeat"
                                name="receipt_number" class="form-control" readonly required />
                        </div>
                        <input type="hidden" name='tape_entry_id' value="{{ $data->id }}">
                    </div>
                @endforeach
                <hr>
                <div class='row mt-2'>
                    <div class="col-md-12">
                        <label for="receipt_no">To Godam</label>
                        <select class="form-control select2 advance-select-box" name="togodam" id="togodam" required>
                            {{-- select2 advance-select-box --}}
                            <option>Select Godam/Department</option>
                            {{-- @php
                                    $previousDepartment = null;
                                @endphp
                                @foreach ($department as $data)
                                    @php
                                        $currentDepartment = $data->fromGodam->department;
                                    @endphp
                                    @if ($currentDepartment !== $previousDepartment)
                                        <option value="{{ $data->from_godam_id }}">{{ $currentDepartment }}</option>
                                    @endif
                                    @php
                                        $previousDepartment = $currentDepartment;
                                    @endphp
                                @endforeach --}}

                            @foreach ($department as $data)
                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class='row mt-2'>
                    <div class="col-md-4">
                        <label for="receipt_no">Plant Type</label>
                        <select class="form-control select2 advance-select-box" name="planttype" id="planttype" required>
                            {{-- advance-select-box --}}
                            {{-- @foreach ($planttype as $data)
                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                @endforeach --}}
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="receipt_no">Plant Name</label>
                        <select class="form-control select2 advance-select-box" name="plantname" id="plantname" required>
                            {{-- advance-select-box --}}
                            {{-- @foreach ($plantname as $data)
                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                @endforeach --}}
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="receipt_no">Plant Shift</label>
                        <select class="form-control select2 advance-select-box" name="shift" id="shift" required>
                            {{-- select2 advance-select-box --}}
                            @foreach ($shift as $data)
                                @php
                                    $currentDepartment = $data->shift->name;
                                @endphp

                                @if ($loop->first || $currentDepartment !== $previousDepartment)
                                    <option value="{{ $data->shift_id }}">{{ $currentDepartment }}</option>
                                @endif

                                @php
                                    $previousDepartment = $currentDepartment;
                                @endphp
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class='row mt-2'>
                    <div class="col-md-3">
                        <label for="receipt_no">Total In Kgs</label>
                        <input type="text" class="form-control" name="total_in_kg" id="total_in_kg" disabled required />
                    </div>
                    <div class="col-md-3">
                        <label for="receipt_no">Loading</label>
                        <input type="text" class="form-control" name="loading" id="loading" disabled required />
                    </div>
                    <div class="col-md-3">
                        <label for="receipt_no">Running</label>
                        <input type="text" class="form-control" name="running" id="running" disabled required />
                    </div>
                    <div class="col-md-3">
                        <label for="receipt_no">Bypass wast</label>
                        <input type="text" class="form-control" name="bypass_wast" id="bypass_wast" disabled required />
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label for="tape type">Tape Type</label>
                        <select name="tapetype" id="tapetype" name="tapetype" class='advance-select-box select2' disabled
                            required>
                            <option> select type</option>
                            <option value="tape1">Tape1</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="">Tape Quantity In Kg</label>
                        <input type="text" name='tape_qty_in_kg' id='tape_qty_in_kg' class="form-control"
                            placeholder="Enter Amount In Kg" disabled required>
                    </div>
                </div>
        </div>
        <hr>
        <div class='row mt-3'>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <label for="receipt_no">Dana Name</label>
                            <select class="form-control select2 advance-select-box" id="dana"> {{-- select2
                                    advance-select-box --}}
                                {{-- @foreach ($dananame as $data)
                                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                                    @endforeach --}}
                            </select>
                        </div>
                        <div class="mt-2">
                            <label for="receipt_no">Quantity in Kg</label>
                            <input type="text" class="form-control" name="qty_in_kg" id='dana_quantity' />
                            <hr>
                            <button class="btn btn-primary float-right" id="add_new_dana_to_autoloader_stock"
                                disabled>Add</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <table class="table table-bordered" id="tape_entry_dana_table"
                        style="background:rgba(241, 214, 147,0.2)">
                        <thead class="table-warning">
                            <tr>
                                <th style="width:100px">Sr No</th>
                                <th>Dana Name</th>
                                <th>Dana Qty</th>
                            </tr>
                        </thead>
                        {{-- <tbody>
                                <tr>
                                    <th>1</th>
                                    <th>dana name</th>
                                    <th>dana qty</th>
                                </tr>
                            </tbody> --}}
                    </table>
                </div>
            </div>
        </div>
        <hr>
        <div class="row d-flex justify-content-center">
            <div class="col-md-3" title='This is enabled if wastage ooccurs'>
                <div class="card">
                    <div class="card-body">
                        <label for="">Waste Type</label>
                        <select name="wastetype" id="wastetype" class="advance-select-box select2" disabled>
                            <option value="">select waste type</option>
                            @foreach ($wastage as $data)
                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label>Total Row/Dana in Kg</label>
                <input type="text" class="form-control" name="dana_in_kg" id="dana_in_kg" disabled required />
                <button type="submit" id="btn-update" class="btn btn-info mt-3" disabled>Update</button>
            </div>
        </div>
        <hr>
        </form>
        {{--
        </div> --}}
        <!-- card-body -->
        {{--
    </div> --}}
        <!-- card -->
    </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function() {
                // let receipt_number = $('#receipt_number_1').val();
                // let receipt_number_repeat = $('#receipt_number_1_repeat').val(receipt_number);
            });
        </script>
        <script>
            $(document).ready(function() {
                $(document).on('change click', '#togodam', function() {
                    // $("#dana_in_kg").val('');
                    getgodam();
                    emptystuffs();
                    revertdisables();
                });

                /***************** AJAX *******************************/
                //for plant name
                $(document).on("change click", '#planttype', function(e) {
                    // $("#planttype").on("select2:select",function(e){
                    let godam_id = $('#togodam').val();
                    $.ajax({
                        url: "{{ route('tape.entry.ajax.plantname', ['planttype_id' => ':id', 'godam_id' => ':godam_id']) }}"
                            .replace(':id', e.target.value)
                            .replace(':godam_id', godam_id),
                        beforeSend: function() {
                            console.log("Getting Plant Name");
                        },
                        success: function(response) {
                            console.log(response);
                            plantname(response);
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                });

                $(document).on('change click', '#plantname', function(e) {
                    let godam_id = $('#togodam').val();
                    let plantType_id = $('#planttype').val();
                    $.ajax({
                        url: "{{ route('tape.entry.ajax.shift', ['plantname_id' => ':plantName_id', 'godam_id' => ':godam_id', 'plantType_id' => ':plantType_id']) }}"
                            .replace(':plantName_id', e.target.value)
                            .replace(':godam_id', godam_id)
                            .replace(':plantType_id', plantType_id),

                        beforeSend: function() {
                            console.log("Getting Plant Name");
                        },
                        success: function(response) {
                            console.log('shift name response', response);
                            // plantname(response);
                            $('#shift').empty();
                            shift(response);
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                });


                $(document).on("change click", '#shift', function(e) {
                    $("#dana").empty();
                    let department = $('#togodam').val();
                    let planttype = $('#planttype').val();
                    let plantname = $('#plantname').val();
                    let shift = $('#shift').val();
                    let csrftoken = $('meta[name="csrf-token"]').attr('content');
                    console.log(department, planttype, plantname, shift);
                    $.ajax({
                        url: "{{ route('tape.entry.ajax.get.danainfo') }}",
                        method: 'POST',
                        data: {
                            '_token': csrftoken,
                            'shift': shift,
                            'department': department,
                            'plantname': plantname,
                            'planttype': planttype
                        },
                        beforeSend: function() {
                            console.log('getting dana info');
                        },
                        success: function(response) {
                            console.log(response);
                            danaandquantity(response);
                            disabledstuffs();
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                });

                /***************** AJAX *******************************/

                /********************* ajax functions *********************/
                //for godam and planttype
                function getgodam() {
                    let geturl = "{{ route('tape.entry.ajax.planttype', ['godam_id' => ':godam_id']) }}";
                    finalurl = geturl.replace(':godam_id', $('#togodam').val());
                    $.ajax({
                        url: finalurl,
                        method: "GET",
                        beforeSend: function() {
                            console.log("ajax fired");
                        },
                        success: function(response) {
                            //  console.log('received response', response);
                            planttype(response);
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                }

                //planttype
                function planttype(response) {
                    console.log('planttype', response);
                    $('#planttype').empty();
                    $('<option></option>').text("Select Plant Type").appendTo('#planttype');
                    response.planttype.forEach(data => {
                        if (data.name == "tape plant") {
                            $('<option></option>').attr('value', `${data.id}`).text(`${data.name}`).appendTo(
                                '#planttype');
                        }
                    });
                }

                //for plant name
                function plantname(data) {
                    $('#plantname').empty();
                    $('<option></option>').text("Select Plant Name").appendTo('#plantname');
                    data.plantname.forEach(resp => {
                        $('<option></option>').attr('value', `${resp.id}`).text(
                            `${resp.name}`).appendTo(
                            '#plantname');
                    });
                }

                //for shift
                function shift(data) {
                    console.log(data);
                    $('<option></option>').text("Select Type").appendTo('#shift');
                    data.shifts.forEach(response => {
                        $('<option></option>').attr('value', `${response.id}`).text(`${response.name}`)
                            .appendTo('#shift');
                    });
                }

                //for dana and quantity
                function danaandquantity(response) {
                    let tbody = $('<tbody></tbody>').appendTo('#tape_entry_dana_table');
                    tbody.attr({
                        'id': 'tape_dana_tbody',
                    })
                    let a = 1;
                    response.data.forEach(d => {
                        let tr = $('<tr></tr>').appendTo(tbody);
                        $('<option></option>').attr('value', `${d.id}`).text(`${d.dana_name.name}`).appendTo(
                            '#dana');
                        $('<td></td>').text(a++).appendTo(tr);
                        $('<td></td>').text(`${d.dana_name.name}`).appendTo(tr);
                        $('<td></td>').text(`${d.quantity}`).appendTo(tr);
                    });
                    $('#dana_in_kg').attr({
                        'value': response.total_quantity
                    })
                }

                /***************** Additional Functionalities ****************************/
                function emptystuffs() {
                    $('#plantname').empty();
                    $('#planttype').empty();
                    $('#shift').empty();
                    $('#tape_dana_tbody').empty();
                    $('#dana').empty();
                    $('#dana_in_kg').attr({
                        'value': null
                    })
                }

                function disabledstuffs() {
                    $('#total_in_kg').prop('disabled', false);
                    $('#btn-update').prop('disabled', false);
                    $('#dana_in_kg').prop('disabled', false);
                    $("#btn-view").prop('disabled', false);
                    $("#add_new_dana_to_autoloader_stock").prop('disabled', false);

                }

                function revertdisables() {
                    $('#total_in_kg').prop('disabled', true);
                    $('#loading').prop('disabled', true);
                    $('#running').prop('disabled', true);
                    $('#bypass_wast').prop('disabled', true);
                    $('#dana_in_kg').prop('disabled', true);
                    $("#btn-view").prop('disabled', true);
                    $("#btn-update").prop('disabled', true);
                    $("#add_new_dana_to_autoloader_stock").prop('disabled', true);
                }

                $(document).on("keyup", "#total_in_kg", function(e) {
                    $('#loading').prop('disabled', false);
                });
                $(document).on("keyup", "#loading", function(e) {
                    $('#running').prop('disabled', false);
                });
                $(document).on("keyup", "#running", function(e) {
                    $('#bypass_wast').prop('disabled', false);
                });
                /***************** Additional Functionalities End ****************************/

                /********************* ajax functions *********************/

                $("#add_new_dana_to_autoloader_stock").click(function(e) {
                    e.preventDefault();
                });
                $('#bypass_wast').keyup(function(e) {
                    $('#tape_qty_in_kg').prop('disabled', false);
                    $('#tapetype').prop('disabled', false);
                    let total = parseFloat($('#total_in_kg').val());
                    let loading = parseFloat($('#loading').val());
                    let running = parseFloat($('#running').val());
                    let bypass = parseFloat($('#bypass_wast').val());
                    let tapetypeval = total - loading - running - bypass;
                    $("#tape_qty_in_kg").val(tapetypeval);

                    let tape_qty_in_kg = $("#tape_qty_in_kg").val();
                    let dana_in_kg = $("#dana_in_kg").val()
                    if (tape_qty_in_kg != dana_in_kg) {
                        $("#wastetype").prop('disabled', false);
                    } else if (tape_qty_in_kg == dana_in_kg) {
                        $("#wastetype").prop('disabled', true);
                    }
                });

                /*********************** form condition checks  **********************/
                $('#tape_entry_form').submit(function(e) {
                    let total = parseFloat($('#total_in_kg').val());
                    let loading = parseFloat($('#loading').val());
                    let running = parseFloat($('#running').val());
                    let bypass = parseFloat($('#bypass_wast').val());
                    let dana_in_kg = parseFloat($('#dana_in_kg').val());

                    $('#btn-update').prop('disabled', false);

                    let final = total + loading + running + bypass;
                    if (final != dana_in_kg) {
                        e.preventDefault();
                        alert('not equal');
                    } else {
                        $('#tape_entry_form').submit();
                    }
                });
                /*********************** form condition checks end **********************/
            });
        </script>
    @endpush
@endsection

@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
@endsection
