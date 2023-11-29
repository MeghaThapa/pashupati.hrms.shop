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
                <h1 class="m-0 text-dark">{{ __('Performence Report Acc Date') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Performence Report Acc Date') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3>Performence Report Acc Date</h3>
                </div>
                <div class="row container">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="start_date">Start Date:</label>
                            <input type="date" class="form-control" id="given_date" name="given_date" value="">
                        </div>
                    </div>
                    {{-- <div class="col-sm-3">
                        <div class="form-group">
                            <label for="end_date">End Date:</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="">
                        </div>
                    </div> --}}
                    <div class="col-sm-3">
                        <div class="form-group mt-4">
                            <button id="generateReport" class="btn btn-primary">Generate</button>
                        </div>
                    </div>
                </div>
                <div class="table-custom card-body table-responsive" id="reportView">
                </div>

                <div class="table-custom card-body table-responsive" id="reportViewone">
                </div>
                <div class="table-custom card-body table-responsive" id="reportViewtwo">
                </div>
            </div>
        </div>
    </div>
@endsection
@section('extra-script')
    <script>
        $(function() {

            $("#generateReport").click(function(e) {
                e.preventDefault()
                $.ajax({
                    url: "{{ route('performence.report') }}",
                    method: "GET",
                    data: {
                        "given_date": $('#given_date').val(),
                    },
                    success: function(response) {
                        $('#reportView').empty();
                        $('#reportViewone').empty();
                        if (response.status == false) {
                            alert(response.message);
                            return;
                        }
                        $('#reportView').append(response.data);
                        $('#reportViewone').append(response.loomRollDown);
                        $('#reportViewtwo').append(response.loomAvgMeter);  
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
