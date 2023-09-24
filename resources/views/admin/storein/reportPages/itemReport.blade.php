@extends('layouts.admin')
@section('extra-style')
    <!-- <link href="{{ asset('css/nepaliDatePicker/nepali.datepicker.v4.0.1.min.css') }}" rel="stylesheet" type="text/css" /> -->
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
    <!-- <link href="{{ asset('css/nepaliDatePicker/nepali.datepicker.v4.0.1.min.css') }}" rel="stylesheet" type="text/css" /> -->
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Item Report') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Item Report') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3>Item Report</h3>
                </div>
                <div class="row container">
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
                        <label for="srNo">Item</label>
                        <select class="form-control" id="item">
                            <option value="" selected disabled>{{ __('Item') }}</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->name }}"> {{ $item->name }}
                                </option>
                            @endforeach
                        </select>

                    </div>
                    <div class="col-sm-3">
                        <label for="size">Size</label>
                        <select class="form-control" id="size">
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group mt-4">
                            <button id="generateReport" class="btn btn-primary">Generate</button>
                        </div>
                    </div>
                </div>
                <div class="card card-body">
                    {{-- <div id="topContent">

                    </div> --}}
                    <h3 id="title"
                        style=" font-weight: bold;text-decoration: underline;  text-align: center; margin: 0 auto;">
                    </h3>
                    <h4 id="dateRange" style="text-align: center; margin: 0 auto;">
                    </h4>
                    {{-- <h4 id="itemDetails" style="text-align: center; margin: 0 auto;">
                    </h4> --}}
                    {{-- <h3>FOR ITEM : Bearing AND SIZE : 6201 zz</h3> --}}
                    <p id="dataParagraph"></p>
                    <div class="table-custom table-responsive" id="reportView">

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

            $('#item').select2();
            $('#size').select2();
            $('#item').on('change', function(e) {



                e.preventDefault();
                $.ajax({
                    url: "{{ route('item.getSize') }}",
                    method: "GET",
                    data: {
                        "item_name": $('#item').val(),
                    },
                    success: function(response) {
                        $('#size').prepend(
                            "<option value='' disabled selected>Select required size</option>"
                        );
                        $('#danaGroupId').prepend(
                            "<option value='' disabled selected>Select required data</option>"
                        );
                        response.forEach(function(obj) {

                            setOptionInSelect('size', obj.size.id, obj.size.name)
                        });
                        $('#item').append(response.data);

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
                const startDateValue = $('#start_date').val();
                const endDateValue = $('#end_date').val();
                const dateText =
                    `Date Wise In/Purchase Item Report For Date: ${startDateValue} to ${endDateValue}`;
                const item_name = $('#item').val();
                const size = $('#item').val();
                // const selectedText = selectedOption.textContent;
                e.preventDefault()
                $.ajax({
                    url: "{{ route('storein.itemReport.view') }}",
                    method: "post",
                    data: {
                        "_token": $("meta[name='csrf-token']").attr("content"),
                        "start_date": $('#start_date').val(),
                        "end_date": $('#end_date').val(),
                        "item_name": $('#item').val(),
                        "size_id": $('#size').val(),
                    },
                    success: function(response) {
                        $('#title').text('PASHUPATI SYNPACK (STORE)');
                        $('#dateRange').text(dateText);

                        // console.log(response);
                        $('#reportView').empty();
                        // $('#summaryView').empty();
                        if (response.status == false) {
                            alert(response.message);
                            return;
                        }
                        $('#reportView').append(response.data);
                        // $('#summaryView').append(response.summary);
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
