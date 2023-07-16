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
                    {{-- <form action="{{ route('storein.index') }}" method="GET" role="search">
                        <div class="input-group">
                            <input type="text" name="term" placeholder="{{ __('') }}"
                                class="form-control" autocomplete="off" value="{{ request('term') ? request('term') : '' }}"
                                required>
                            <span class="input-group-append">
                                <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
                            </span>
                        </div>
                    </form> --}}
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
                <table class="table table-bordered" id="autoloadTable">
                    <thead>
                        <tr>
                            <th>@lang('S.N')</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Receipt No') }}</th>
                            <th>{{ __('Action') }}</th>

                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
             <!-- Modal -->
<div class="modal fade" id="createAutoloadModel" tabindex="-1" role="dialog" aria-labelledby="tryModelLabel" aria-hidden="true">
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

{{-- edit popup --}}
  <div class="modal fade" id="editAutoloadModel" tabindex="-1" role="dialog" aria-labelledby="tryModelLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tryModelLabel">Modal Title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="UpdateAutoloadModel" action="{{route('autoload.update')}}" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="card-body">
                        <div id="form-error" class="alert alert-danger" hidden>

                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <input type="text" step="any" min="0" class="form-control calculator" id="autoloadIdModel"
                                    data-number="1" name="autoload_id_model" min="1" required hidden>
                                <label for="size" class="col-form-label">{{ __('Transfer Date') }}<span class="required-field">*</span>
                                </label>
                                <input type="date" step="any" min="0" class="form-control calculator" id="transferDateModel"
                                    data-number="1" name="transfer_date_model" placeholder="{{ __('Transfer Date') }}" min="1" required>
                                @error('transfer_date_model')
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
                                <input type="text" step="any" min="0" class="form-control calculator" id="receiptNoModel"
                                    data-number="1" name="receipt_no_model" placeholder="{{ __('Transfer Date') }}" min="1" required readonly>
                                @error('receipt_no_model')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="hello" class="btn btn-primary edit-btn">Update AutoLoad</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- edit popup end --}}
        @endsection
        @section('extra-script')
        <script>
        $(document).ready(function() {

        $('.edit-btn').on('click', function(){
            $('#editAutoloadModel').modal('hide');
        })
            //for edit
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
        //Edit Autoload
                // document.getElementById('UpdateAutoloadModel').addEventListener('submit', function(e){
                //    e.preventDefault();
                //     console.log('hello');
                // });

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
         var table = $('#autoloadTable').DataTable({
                    lengthMenu: [
                        [30, 40, 50, -1],
                        ['30 rows', '40 rows', '50 rows', 'Show all']
                    ],
                    style: 'bootstrap4',
                    processing: true,
                    serverSide: true,
                    ajax:"{{ route('autoLoad.dataTable') }}",
                    columns: [{
                            data: 'DT_RowIndex'
                        },
                        {
                            data: 'transfer_date'
                        },
                        {
                            data: 'receipt_no'
                        },
                        {
                            data: 'action',
                            orderable: true,
                            searchable: true,
                        },
                    ]
                });
    });
    $('body').on('click','.btnAutoloadDlt',function(){
        let autoload_id = $(this).attr('data-id');
         new swal({
                    title: "Are you sure?",
                    text: "Once deleted, data will move completely removed!!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true

                })
                .then((willDelete) => {
                    if (willDelete.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('autoload.delete', ['autoload_id' => ':Replaced']) }}".replace(
                                ':Replaced',
                                autoload_id),
                            data: {
                                '_token': $('meta[name=csrf-token]').attr("content"),

                            },
                            success: function(data) {
                                new swal
                                    ({
                                        text: "Poof! Your data has been deleted!",
                                        title: "Deleted",
                                        icon: "success",
                                    })
                                location.reload();

                            }
                        })
                    }

                })

    });
    $('body').on('click', '.btnEdit', function () {
    let autoload_id = $(this).attr("data-id");
   // console.log(autoload_id);
    $.ajax({
         url: "{{ route('autoLoad.getEditItemData',['autoLoad_id'=>':autoload_id']) }}".replace(
                    ':autoload_id',
                    autoload_id),
                    method: 'GET',
                    success: function(response) {
                               // console.log(response);
                        document.getElementById('transferDateModel').value = response.transfer_date;
                        document.getElementById('receiptNoModel').value = response.receipt_no;
                        document.getElementById('autoloadIdModel').value=response.id;
                        $('#editAutoloadModel').modal('show');
                    }
        });
    });
</script>
@endsection
