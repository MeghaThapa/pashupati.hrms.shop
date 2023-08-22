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

    </style>
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('fabric transfer for bag') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('fabric transfer for bag') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            <form method="post" action="{{ route('fabric.transfer.entry.for.bag.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="receipt_number"> Receipt Number</label>
                            <input type="text" step="any" min="0" value="{{ $data->receipt_number }}"
                                class="form-control" name="receipt_number" id="receipt-number" readonly required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="receipt_date"> Receipt Date</label>
                            <input type="date" value="{{ $data->receipt_date }}" step="any" min="0"
                                class="form-control" name="receipt_date" id="receipt_date" readonly required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="receipt_date">Date NP</label>
                            <input type="text" value="{{ $data->receipt_date_np }}" step="any" min="0"
                                class="form-control" name="date_np" id="receipt_date" readonly required>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="from_godam">From Godam</label>
                            <select class="form-control select2 advance-select-box" name="from_godam" id="from_godam"
                                required>
                                <option>--Select Godam--</option>
                                @foreach ($godam as $data)
                                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="remarks">Remarks</label>
                            <input type="text" class="form-control" name="remarks" id="remarks" />
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="Fabric Name">Fabric Name</label>
                            <select class="form-control select2 advance-select-box" name="fabric_name" id="fabric_name">
                                <option disabled>--Select Fabric--</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="" style="opacity:0">HH</label>
                        <center>
                            <p>OR</p>
                        </center>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="Fabric Name">Roll No</label>
                            <input type="text" class="form-control" name="roll_no" id="roll_no" readonly/>
                            <input type="hidden" id="entry_id" value="{{ $id }}"
                                data-id='{{ $id }}'>
                        </div>
                    </div>
                    {{-- <div class="col-md-2">
                    <div class="form-group">
                        <label for="" style="opacity:0">HH</label>
                        <input type="submit" class="form-control btn btn-primary" name="submit" value="submit" />
                    </div>
                </div> --}}
                </div>
            </form>
           <div class="card">
            <div class="card-body">
                <div class="p-0 table-responsive my-3 table-hover">
                    <table class="table table-striped table-hover table-bordered" id="DataTable">
                        <thead>
                            <tr>
                                <th>{{ __('SN') }}</th>
                                <th scope="10">{{ __('Fabric Name') }}</th>
                                <th>{{ __('Roll No') }}</th>
                                <th>{{ __('Gross Weight') }}</th>
                                <th>{{ __('Net Weight') }}</th>
                                <th>{{ __('Meter') }}</th>
                                <th>{{ __('Average') }}</th>
                                <th>{{ __('Gram') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                        </tbody>
                    </table>
                </div>
            </div>
           </div>

           <div class="card">
            <div class="card-body">
                <div class="p-0 table-responsive table-custom my-3 table-hover" style="min-height:600px;max-height:900px;overflow-y:scroll">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered" id="lower_table">
                            <thead>
                                <tr>
                                    <th>{{ __('SN') }}</th>
                                    <th scope="10">{{ __('Fabric Name') }}</th>
                                    <th>{{ __('Roll No') }}</th>
                                    <th>{{ __('Gross Weight') }}</th>
                                    <th>{{ __('Net Weight') }}</th>
                                    <th>{{ __('Meter') }}</th>
                                    <th>{{ __('Average') }}</th>
                                    <th>{{ __('Gram') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_1">
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-none text-center" id="tfoot">
                        <button class="btn btn-danger discard">discard</button>
                        <button class="btn btn-primary finalsave">Save</button>
                    </div>
                </div>
            </div>
           </div>
        </div>
        </div>
    </div>
@endsection

@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    @if (session()->has('message'))
        <script>
            toastr.success("{{ session()->get('message') }}");
        </script>
    @elseif(session()->has('message_err'))
        <script>
            toastr.error("{{ session()->get('message_err') }}");
        </script>
    @endif
    <script>
        $(document).ready(function() {
            let DataTable = null;
            calldetailstolowerfabrictable();

            $("#from_godam").on("change", function(e) {
                let godam_id = $(this).val();

                $.ajax({
                    url: "{{ route('get.fabrics.according.godams', ['id' => ':id']) }}".replace(
                        ':id', godam_id),
                    method: "get",
                    success: function(response) {
                        putfabricsonplace(response);
                    },
                    error: function(error) {
                        console.log("error = " + error);
                    }
                });
            });

            $(document).on("change", "#fabric_name", function(e) {

                if(DataTable != null){
                    DataTable.destroy()
                }
                let fabric_id = $(this).val();

                DataTable = $("#DataTable").DataTable({
                    serverside : true,
                    processing : true,
                    lengthMenu : [
                        [15,30,60,150,300,500,-1],
                        [15,30,60,150,300,500,"All"]
                    ],
                    ajax : "{{ route('get.specific.fabric.details', ['id' => ':id']) }}".replace(":id",fabric_id),
                    columns : [
                        { data : "DT_RowIndex" , name : "DT_RowIndex" },
                        { data : "name" , name : "name" },
                        { data : "roll_no" , name : "roll_no" },
                        { data : "gross_wt" , name : "gross_wt" },
                        { data : "net_wt" , name : "net_wt" },
                        { data : "meter" , name : "meter" },
                        { data : "average_wt" , name : "average_wt" },
                        { data : "gram_wt" , name : "gram_wt" },
                        { data : "action" , name : "action" },
                    ]
                })
            });

            $(document).on("click", ".sendFabLower", function(e) {
                e.preventDefault();
                let id = $(this).data("id");
                let fabric_bag_entry_id = $("#entry_id").val();
                $.ajax({
                    url: `{{ route('send.fabric.to.lower', ['id' => ':id']) }}`.replace(":id", id),
                    method: "get",
                    data: {
                        "fabric_bag_entry_id": fabric_bag_entry_id
                    },
                    beforeSend: function() {
                        console.log("ajax fired");
                    },
                    success: function(response) {
                        console.log(response);
                        sendFabToLower(response);
                    },
                    error: function(error) {
                        console.log("error:" + error);
                    }
                });
            });

        });

        $(document).on('click', ".discard", function(e) {
            alert("clicked");
            $.ajax({
                url: "{{ route('discard.temporary.table') }}",
                method: "post",
                data: {
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    location.reload();
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });

        function putfabricsonplace(data) {
            console.log(data);
            $("#fabric_name").empty();
            $("<option disbled>--Select Fabric--</option>").appendTo("#fabric_name")
            data.data.forEach(d => {
                let option = $(`<option value=${d.fabric_id}>${d.name}(${d.fabricgroup.name})</option>`).appendTo("#fabric_name");
            });
        }

        function sendFabToLower(data) {
            console.log(data);
            if (data.status == '200') {
                console.log(data);
            } else {
                alert(data);
            }

            calldetailstolowerfabrictable();
        }

        function calldetailstolowerfabrictable() {
            $("#tbody_1").empty();

            $.ajax({
                url: "{{ route('call.details.to.lower.fabric.table') }}",
                method: "get",
                beforeSend: function() {
                    console.log('ajax fired');
                },
                success: function(response) {
                    console.log(response);
                    putdatatolowertable(response);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function putdatatolowertable(data) {
            if (data.data.length > 0) {
                $("#tfoot").removeClass("d-none");
            }

            data.data.forEach(d => {

                let average = parseFloat(d.average)
                let average_fixed = average.toFixed(2)
                let size = parseFloat(d.fabric.name)
                let gram = (average_fixed/size).toFixed(2)

                let tr = $("<tr></tr>").appendTo("#tbody_1");
                tr.append(`<td>#</td>`);
                tr.append(`<td>${d.fabric.name}</td>`);
                tr.append(`<td>${d.roll_no}</td>`);
                tr.append(`<td>${d.gross_wt}</td>`);
                tr.append(`<td>${d.net_wt}</td>`);
                tr.append(`<td>${d.meter}</td>`);
                tr.append(`<td>${average_fixed}</td>`);
                tr.append(`<td>${gram}</td>`);
                tr.append(`<a class="btn btn-danger lowerData" href="${d.id}" data-id="${d.id}">delete</a>`);
            });
        }

        $(document).on("click", ".lowerData", function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            $.ajax({
                url: "{{ route('delete.from.lower.table') }} ",
                method: "post",
                data: {
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                    "id": id
                },
                beforeSend: function() {
                    console.log('Ajax Fired');
                },
                success: function(response) {
                    console.log(response);
                    if (response.message == "ok") {
                        calldetailstolowerfabrictable();
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });


        $(document).on("click", ".finalsave", function(e) {
            e.preventDefault();

            let fabric_entry_id = $("#entry_id").val();

            $.ajax({
                url: "{{ route('final.save') }}",
                method: "post",
                data: {
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                    "fabric_id": fabric_entry_id
                },
                beforeSend: function() {
                    console.log("ajax fired");
                },
                success: function(response) {
                    console.log(response);
                    if (response.message == "ok") {
                        location.href = "{{ route('fabric.transfer.entry.for.bag') }}";
                    } else {
                        alert("Something went wrong! check console");
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        })
    </script>
@endsection
