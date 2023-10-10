@extends('layouts.admin')
@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
@endsection
@section('content')

    <div style="display: flex;
    justify-content: space-evenly;
    flex-direction: column;
    align-items: center;">

        <img class="lg-logo" src="{{ $settings->logo }}" alt="{{ $settings->companyName }}" width="250" height="50">
        <h3>NonwovenFabric Receive Entry Stock</h3>
    </div>

    <form id="filterData" action="{{ route('nonwovenfabrics-receiveentrystock.filterStock') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-sm-3">
                <label>Fabric GSM</label>
                <select name="fabric_gsm" class="form-control select2">
                    <option value="">Select GSM</option>
                    @foreach($uniqueGSMs as $uniqueGSM)
                        <option>{{ $uniqueGSM->fabric_gsm }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <label>Fabric Name</label>
                <select name="fabric_name" class="form-control select2">
                    <option value="">Select Fabric Name</option>
                    @foreach($uniqueFabricNames as $uniqueFabricName)
                        <option>{{ $uniqueFabricName->fabric_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <label for="">Fabric Color</label>
                <select name="fabric_color" class="form-control select2">
                    <option value="">Select Fabric Color</option>
                    @foreach($uniqueFabricColors as $uniqueFabricColor)
                        <option>{{ $uniqueFabricColor->fabric_color }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <button style="margin-top: 22px;" type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-md-12">
            <div class="p-0 table-responsive table-custom my-3">
                <div id="tableContent"></div>
            </div>
        </div>
    </div>
@endsection
@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script>
        $('.select2').select2();
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
    </script>
@endsection
