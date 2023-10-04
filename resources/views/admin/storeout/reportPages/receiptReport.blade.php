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
                        <label for="srNo">For</label>
                        <select class="form-control" id="for">
                            <option value="" selected disabled>{{ __('Select godam') }}</option>
                            @foreach ($godams as $godam)
                                <option value="{{ $godam->id }}"> {{ $godam->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-3">
                        <label for="srNo">Receipt No</label>
                        <select class="form-control" id="receiptNo">
                            {{-- @foreach ($srNos as $srNo)
                                <option value="{{ $srNo->sr_no }}"> {{ $srNo->sr_no }}
                                </option>
                            @endforeach --}}
                        </select>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group mt-4">
                            <button id="generateReport" class="btn btn-primary">Generate</button>
                        </div>
                    </div>
                </div>
                <div class="card card-body">
                    <h3 id="title"
                        style=" font-weight: bold;text-decoration: underline;  text-align: center; margin: 0 auto;">
                    </h3>
                    <h4 id="dateRange" style="text-align: center; margin: 0 auto;">
                    </h4>
                    <div class="table-custom card-body table-responsive" id="reportView">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/storein.js') }}"></script>
    <script>
        $(function() {
            $('#for').select2();
            $('#receiptNo').select2();
            $('#for').on('change', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('storeout.getReceiptNo') }}",
                    method: "GET",
                    data: {
                        "godam_id": $('#for').val(),
                    },
                    success: function(response) {
                        $('#receiptNo').prepend(
                            "<option value='' disabled selected>Select required receipt no</option>"
                        );
                        response.forEach(function(obj) {
                            // $('#receiptNo option[value=""]').remove();
                            setOptionInSelect('receiptNo', obj.receipt_no, obj
                                .receipt_no)
                        });
                        $('#receiptNo').append(response.data);

                    },
                    error: function(error) {
                        console.log(error)
                    }
                })
            })

            function setOptionInSelect(elementId, optionId, optionText) {
                let selectElement = $('#' + elementId);
                let newOption = $('<option>');
                newOption.val(optionId).text(optionText);
                selectElement.append(newOption);
                selectElement.trigger('change.select2');
            }

            $("#generateReport").click(function(e) {
                e.preventDefault()
                $.ajax({
                    url: "{{ route('storeout.receiptReport.view') }}",
                    method: "post",
                    data: {
                        "_token": $("meta[name='csrf-token']").attr("content"),
                        "godam_id": $('#for').val(),
                        "receipt_no": $('#receiptNo').val()
                    },
                    success: function(response) {
                        $('#title').text('PASHUPATI SYNPACK (STORE)');
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