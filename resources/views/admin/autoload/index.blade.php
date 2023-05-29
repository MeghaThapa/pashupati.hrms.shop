@extends('layouts.admin')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Autoload') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Autoload') }}</li>
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
            <div class="row">
                <div class="col-lg-3 col-md-5 col-6 mb-2">
                    <form action="{{ route('storein.index') }}" method="GET" role="search">
                        <div class="input-group">
                            <input type="text" name="term" placeholder="{{ __('Type code or supplier or date ...') }}"
                                class="form-control" autocomplete="off" value="{{ request('term') ? request('term') : '' }}"
                                required>
                            <span class="input-group-append">
                                <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
                            </span>
                        </div>
                    </form>
                </div>
                <div class="col-lg-9 col-md-7 col-6">
                    <div class="card-tools text-md-right">
                        <a class="btn btn-secondary">
                            <i class="fas fa-download"></i> @lang('Export')
                        </a>
                        <button type="button" id="addAutoload"  class="btn btn-primary">
                            {{ __('Add Autoload') }} <i class="fas fa-plus-circle"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-3 card card-body">
                <table class="table table-bordered" id="rawMaterialTable">
                    <thead>
                        <tr>
                            <th>@lang('#')</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Receipt No') }}</th>
                            <th>{{ __('Supplier Name') }}</th>
                            <th>{{ __('PP No') }}</th>
                            <th>{{ __('Storein Type') }}</th>
                            <th>{{ __('From Godam') }}</th>
                            <th>{{ __('To Godam') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
             <!-- Modal -->
    <div class="modal fade" id="createAutoloadModel" tabindex="-1" role="dialog" aria-labelledby="tryModelLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tryModelLabel">Modal Title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="CreateAutoloadModel">
                <div class="modal-body">
                    @csrf
                    <div class="card-body">
                        <div id="form-error" class="alert alert-danger" hidden>

                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">

                                <label for="size" class="col-form-label">{{ __('Transfer Date') }}<span class="required-field">*</span>
                                </label>
                                <input type="date" step="any" min="0" class="form-control calculator" id="transferDate"
                                    data-number="1" name="transfer_date" placeholder="{{ __('Transfer Date') }}" min="1" required>
                                @error('transfer_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="size" class="col-form-label">{{ __('Receipt') }}
                                </label>
                                <input type="text" step="any" min="0" class="form-control calculator" id="receiptNo"
                                    data-number="1" name="receipt_no" placeholder="{{ __('Transfer Date') }}" min="1" required readonly>
                                @error('receipt_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- <div>
                                <button type="submit" class="btn btn-sm btn-primary" style="margin-top:35px;">
                                    Add
                                </button>
                            </div> --}}

                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Craete AutoLoad</button>
                </div>
            </form>
        </div>
    </div>
</div>
        @endsection
        @section('extra-script')
        <script>
        $(document).ready(function() {
                document.getElementById('addAutoload').addEventListener('click', function(e){
                    $.ajax({
                            url: '{{ route('autoLoad.getReceiptNo') }}',

                            method: 'GET',
                            success: function(response) {
                                console.log(response);
                               document.getElementById('receiptNo').value = response;
                            }
                        });
                    $('#createAutoloadModel').modal('show');
                });

                document.getElementById('CreateAutoloadModel').addEventListener('submit', function(e){
                    e.preventDefault();

                    const form = event.target;
                    let trasfer_date = form.elements['transfer_date'];
                    let receipt_no = form.elements['receipt_no'];
                    $.ajax({
                            url: '{{ route('autoLoad.store') }}',
                            method: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                trasfer_date: trasfer_date.value,
                                receipt_no: receipt_no.value,
                            },
                    success: function(response) {
                        //console.log(response.id);
                         let autoload_id = response.id;
                        //console.log(response.id);
                        window.location.href="{{route('autoLoad.createAutoloadItem', ['autoload_id' => ':Replaced'])}}" .replace(
                            ':Replaced',
                            autoload_id),


                        $('#createAutoloadModel').modal('hide');

                       // setSuccessMessage(response.message);
                    },
                    error: function(xhr, status, error) {

                    let errorMessage = xhr.responseJSON.message;

                    }

                    //$('#createAutoloadModel').modal('show');
                });

            function setSuccessMessage(message) {
                let successContainer = document.getElementById('success_msg');
                //console.log(successContainer);
                successContainer.hidden = false;
                successContainer.innerHTML = message;
                setTimeout(function() {
                    successContainer.hidden = true;
                }, 2000); // 5000 milliseconds = 5 seconds
            }

                function setMessage(element_id, message) {
                let errorContainer = document.getElementById(element_id);
                errorContainer.hidden = false;
                errorContainer.innerHTML = message;
                setTimeout(function() {
                    errorContainer.hidden = true;
                }, 2000);
            }
        });
    });

</script>
@endsection
