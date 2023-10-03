@extends('layouts.admin')
@section('content')
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Raw Material Report') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Raw Material Report') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="col-md-12">
                @include('admin.includes.alert')
            </div>
            <div id="RawMaterialError" class="alert alert-danger" hidden></div>
            <form id="generateReport" action="{{ route('rawmaterial.dana.datewise.report.generate') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-sm-3">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control" />
                    </div>
                    <div class="col-sm-3">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-control" />
                    </div>
                    <div class="col-sm-3">
                        <label>Select Godam</label>
                        <select name="godam_id" class="form-control">
                            <option selected disabled value="">Select Godam</option>
                            @foreach($godams as $godam)
                            <option value="{{ $godam->id }}">{{ $godam->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <button style="margin-top: 24px;" type="submit" class="btn btn-primary" >Filter Now</button>
                    </div>

                </div>
            </form>
            <div id="reportContent" class="p-3 card card-body" style="margin-top: 20px;">
            </div>
        </div>
    </div>
@endsection
@section('extra-script')

<script>
    $(function(){

        $('#generateReport').on('submit', function(e) {
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
                            swal.fire("Generated!", "Report Generated Successfully!", "success");
                            $('#reportContent').html(data.data);

                        } else {
                            swal.fire("Failed!", data.message, "error");
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
