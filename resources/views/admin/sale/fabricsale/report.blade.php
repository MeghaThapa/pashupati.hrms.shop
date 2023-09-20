@extends('layouts.admin')
@section('extra-style')
    <link href="{{ asset('css/nepaliDatePicker/nepali.datepicker.v4.0.1.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/nepaliDatePicker/nepali.datepicker.v4.0.1.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Fabric Sale Report') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Fabric Sale Report') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3>Fabric Sale Report</h3>
                </div>
                <div class="row container">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="start_date">Start Date:</label>
                            <input type="text" class="form-control ndp-nepali-calendar" id="start_date" name="start_date" value="">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="end_date">End Date:</label>
                            <input type="text" class="form-control ndp-nepali-calendar" id="end_date" name="end_date" value="">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label for="godamID">Bill For</label>
                        <select class="form-control" id="billFor">
                            <option value="" selected disabled>{{ __('Select Bill For') }}</option>
                            <option value="local">Local</option>
                            <option value="export">Export</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group mt-4">
                            <button id="generateReport" class="btn btn-primary">Generate</button>
                        </div>
                    </div>
                </div>
                <div class="table-custom card-body table-responsive" id="reportView">
                </div>
                <div class="table-custom card-body table-responsive" id="summaryView">
                </div>
            </div>
        </div>
    </div>
@endsection
@section('extra-script')
    <script src="{{ asset('js/nepaliDatePicker/nepali.datepicker.v4.0.1.min.js') }}"></script>
    <script>
        $(function(){

            var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");

            $('#start_date').val(currentDate);
            $('#start_date').nepaliDatePicker({
                ndpYear: true,
                ndpMonth: true,
                disableAfter: currentDate,
            });

            $('#end_date').val(currentDate);
            $('#end_date').nepaliDatePicker({
                ndpYear: true,
                ndpMonth: true,
                disableAfter: currentDate,
            });

            $("#generateReport").click(function(e) {
                e.preventDefault()
                $.ajax({
                    url: "{{ route('fabric.sale.report.view') }}",
                    method: "post",
                    data: {
                        "_token": $("meta[name='csrf-token']").attr("content"),
                        "bill_for": $('#billFor').val(),
                        "start_date":$('#start_date').val(),
                        "end_date":$('#end_date').val(),
                    },
                    success: function(response) {
                        $('#reportView').empty();
                        $('#summaryView').empty();
                        if(response.status==false){
                            alert(response.message);
                            return;
                        }
                        $('#reportView').append(response.data);
                        $('#summaryView').append(response.summary);
                        alert('Report Fetched');
                    },
                    error: function(error) {
                        console.log(error)
                    }
                })
            })

        });
    </script>
@endsection
