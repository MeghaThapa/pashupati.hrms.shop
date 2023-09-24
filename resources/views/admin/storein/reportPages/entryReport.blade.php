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
                <h1 class="m-0 text-dark">{{ __('Storein Entry Report') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Storein Entry Report') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3>Storein Entry Report</h3>
                </div>
                <div class="row p-2">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="start_date">Start Date:</label>
                            <input type="date" class="form-control ndp-nepali-calendar" id="start_date" name="start_date"
                                value="">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="end_date">End Date:</label>
                            <input type="date" class="form-control ndp-nepali-calendar" id="end_date" name="end_date"
                                value="">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label for="godamID">Storein Department</label>
                        <select class="form-control" id="storeinDeptId" name="storein_dept_id">
                            <option value="" selected disabled>{{ __('Select storein Department') }}</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- <div class="col-sm-3">
                        <label for="godamID">Storein category</label>
                        <select class="form-control" id="storeinCategoryId">
                            <option value="" selected disabled>{{ __('Select storein Category') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>  --}}
                    {{-- <div class="col-sm-3">
                        <label for="godamID">Items of storein </label>
                        <select class="form-control" id="itemId">
                            <option value="" selected disabled>{{ __('Select itemsOf Storein') }}</option>
                            @foreach ($itemNames as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div> --}}
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
    <script src="{{ asset('js/nepaliDatePicker/nepali.datepicker.v4.0.1.min.js') }}"></script>
    <script>
        $(function() {

            var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");

            // $('#start_date').val(currentDate);
            // $('#start_date').nepaliDatePicker({
            //     ndpYear: true,
            //     ndpMonth: true,
            //     disableAfter: currentDate,
            // });

            // $('#end_date').val(currentDate);
            // $('#end_date').nepaliDatePicker({
            //     ndpYear: true,
            //     ndpMonth: true,
            //     disableAfter: currentDate,
            // });

            $("#generateReport").click(function(e) {
                e.preventDefault()
                $.ajax({
                    url: "{{ route('storein.entry.report.view') }}",
                    method: "post",
                    data: {
                        "_token": $("meta[name='csrf-token']").attr("content"),
                        // "godam_id": $('#godamID').val(),
                        "start_date": $('#start_date').val(),
                        "end_date": $('#end_date').val(),
                        "storein_dept_id": $('#storeinDeptId').val(),

                    },
                    success: function(response) {
                        console.log('megha', response);
                        $('#reportView').empty();
                        if (response.status == false) {
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
