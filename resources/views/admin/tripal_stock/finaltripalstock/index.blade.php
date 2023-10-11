@extends('layouts.admin')
@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/nepaliDatePicker/nepali.datepicker.v4.0.1.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div style="display: flex;
    justify-content: space-evenly;
    flex-direction: column;
    align-items: center;">

        <img class="lg-logo" src="{{ $settings->logo }}" alt="{{ $settings->companyName }}" width="250" height="50">
        <h3>FinalTripal Stock</h3>
    </div>
    <form id="filterData" action="{{ route('finaltripal.getFilterList') }}" method="POST">
        @csrf
    <div class="row">
        <div class="col-sm-3">
            <label for="tripalName">Select Name</label>
            <select name="name" class="advance-select-box form-control" id="tripalName">
                <option value="">{{ __('Select Tripal Name') }}</option>
                @foreach ($finaltripalname as $tripalName)
                    <option value="{{ $tripalName }}">{{ $tripalName }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-sm-3">
            <label for="godamID">Select Godam</label>
            <select name="department_id" class="form-control" id="godamID">
                <option value="" selected disabled>{{ __('Select Godam Name') }}</option>
                @foreach ($godams as $godam)
                    <option value="{{ $godam->id }}">{{ $godam->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3">
            <button type="submit" class="btn btn-primary" style="margin-top:26px;">Filter</button>
        </div>
    </div>
    </form>

    <div class="p-0 table-responsive table-custom my-3">
        <div id="tableContent"></div>
    </div>
@endsection
@section('extra-script')
    <script src="{{ asset('js/nepaliDatePicker/nepali.datepicker.v4.0.1.min.js') }}"></script>
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>


    <script type="text/javascript">
        $(document).ready(function() {

            $('#filterData').on('submit', function(e) {
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
                        $('#tableContent').html(data.data);
                        swal.fire("Success!", "Report Generated Successfully!", "success");
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

        });
    </script>
@endsection
