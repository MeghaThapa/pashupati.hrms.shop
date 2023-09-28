@extends('layouts.admin')

@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"
        integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ asset('css/nepaliDatePicker/nepali.datepicker.v4.0.1.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .update_status{
            cursor: pointer;
        }
        .invalid{
            border-color: red;
        }
        .valid{
            border-color: green;
        }
    </style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6 mt-2">
                <h4><strong>Sauda Item Entry Report</strong></h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Sauda Item Entry Report') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            <div class="card mt-3">
                <div class="card-header">
                    <h4>Sauda Item Entry Report Datewise and Order For </h4>
                </div>
                <div class="card-body">
                    <form id="generate" method="POST" action="{{ route('sauda.entry.datewise.generate.view') }}">
                        @csrf
                    <div class="row">
                        <div class="col-sm-3">
                            <label>Start Date</label>
                            <input class="form-control" type="date" name="start_date" />
                        </div>
                        <div class="col-sm-3">
                            <label>End Date</label>
                            <input class="form-control" type="date" name="end_date" />
                        </div>
                        <div class="col-sm-3">
                            <button style="margin-top: 25px;" type="submit" class="btn btn-primary">Generate Report</button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="row" style="width: 100%;">
                        <div class="col-sm-3">
                            <a class="btn btn-success" href="{{ route('sauda-item.index') }}">Sauda Item List and entry</a>
                        </div>
                        <div class="col-sm-6">
                            <h4> Sauda Item Entry Report Datewise and Order For</h4>
                        </div>
                        <div class="col-sm-3">
                            <a class="btn btn-success" href="{{ route('sauda.item.datewise.filter') }}"> Datewise
                                Sauda Item Pending Report Datewise and Order For</a>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive" id="generatedView">

                </div>

                <div class="card-body table-responsive" id="generatedSummaryView">

                </div>


            </div>
        </div>
    </div>


@endsection

@section('extra-script')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/nepaliDatePicker/nepali.datepicker.v4.0.1.min.js') }}"></script>
    <script>
        $(".select2").select2()
    </script>
    @if (session()->has('message'))
        <script>
            toastr.success('{{ session()->get('message') }}');
        </script>
    @endif
    <script>

        $('#generate').on('submit', function(e) {
                e.preventDefault();
                let url = $(this).attr('action');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function() {
                        console.log('ajax fired');
                    },
                    success: function(data) {
                        if (data.status == true) {
                            $('#generatedView').html(data.data);
                            $('#generatedSummaryView').html(data.summary);
                            swal.fire("Generated!", "Report Generated Successfully!", "success");
                        } else {
                            swal.fire("Failed!", "Report Generation failed!", "error");
                        }
                    },
                    error: function(xhr) {
                        var i = 0;
                        $('.help-block').remove();
                        $('.has-error').removeClass('has-error');
                        for (var error in xhr.responseJSON.errors) {
                            $('#add_' + error).removeClass('has-error');
                            $('#add_' + error).addClass('has-error');
                            $('#error_' + error).html(
                                '<span class="help-block ' + error + '">*' + xhr
                                .responseJSON.errors[
                                    error] + '</span>');
                            i++;
                        }
                    }
                });
            });


    </script>

@endsection
