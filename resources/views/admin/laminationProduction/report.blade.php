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
                <h1 class="m-0 text-dark">{{ __('Lamination Production Report') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Lamination Production Report') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3>Lamination Production Report</h3>
                </div>
                <div class="row container">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="start_date">Start Date:</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="end_date">End Date:</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="end_date">Godam:</label>
                            <select class="advance-select-box form-control @error('type') is-invalid @enderror"
                            id="godamID" name="godam_id" required focus>
                            <option value="" selected disabled>{{ __('Select a godam') }}
                            </option>
                            @foreach ($godams as $godam)
                                <option
                                    value="{{ $godam->id }}">{{ $godam->name }}</option>
                            @endforeach
                        </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group mt-4">
                            <button id="generateReport" class="btn btn-primary">Generate</button>
                        </div>
                    </div>
                </div>
                <div class="table-custom card-body table-responsive" id="reportView">
                </div>
            </div>
        </div>
    </div>
@endsection
@section('extra-script')
    <script>
        $(function(){

            $("#generateReport").click(function(e) {
                e.preventDefault()
                $.ajax({
                    url: "{{ route('lamination.production.report') }}",
                    method: "GET",
                    data: {
                        "godam_id": $('#godamID').val(),
                        "start_date":$('#start_date').val(),
                        "end_date":$('#end_date').val(),
                    },
                    success: function(response) {
                        $('#reportView').empty();
                        if(response.status==false){
                            alert(response.message);
                            return;
                        }
                        $('#reportView').append(response.data);
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
