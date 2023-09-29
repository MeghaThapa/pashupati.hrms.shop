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
                    <h3>Storein Report</h3>
                </div>
                <div class="row container">
                    <div class="col-sm-3">
                        <label for="srNo">Sr No</label>
                        <select class="form-control" id="srNo">
                            <option value="" selected disabled>{{ __('Select sr no') }}</option>
                            @foreach ($srNos as $srNo)
                                <option value="{{ $srNo->sr_no }}"> {{ $srNo->sr_no }}
                                </option>
                            @endforeach
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
                    url: "{{ route('storein.itemSrNoReport.view') }}",
                    method: "post",
                    data: {
                        "_token": $("meta[name='csrf-token']").attr("content"),
                        "sr_no": $('#srNo').val(),
                    },
                    success: function(response) {
                        $('#reportView').empty();
                        $('#summaryView').empty();
                        if (response.status == false) {
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
