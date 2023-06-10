@php
    use App\Helpers\AppHelper;
    $helper = AppHelper::instance();
@endphp
@extends('layouts.admin')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Storein') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Storein') }}</li>
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
                        <a class="btn btn-secondary" href="{{ route('storein.pdf') }}">
                            <i class="fas fa-download"></i> @lang('Export')
                        </a>
                        <a href="{{ route('storein.createStoreins') }}" class="btn btn-primary">
                            {{ __('Add Storein') }} <i class="fas fa-plus-circle"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="p-0 table-responsive table-custom my-3">
                <table class="table" id="storeinTable">
                    <thead>
                        <tr>
                            <th>@lang('#')</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Supplier') }}</th>
                            <th>{{ __('SR No') }}</th>
                            <th>{{ __('Bill No') }}</th>
                            <th>{{ __('PP No') }}</th>
                            <th>{{ __('Discount') }}</th>
                            <th>{{ __('Grand Total') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection

@section('extra-script')
    <script>
         var table = $('#storeinTable').DataTable({
                    lengthMenu: [
                        [30, 40, 50, -1],
                        ['30 rows', '40 rows', '50 rows', 'Show all']
                    ],
                    style: 'bootstrap4',
                    processing: true,
                    serverSide: true,
                    ajax:"{{ route('storein.storinYajraDatabales')}}",
                    columns: [{
                            data: 'DT_RowIndex'
                        },
                        {
                            data: 'purchase_date'
                        },
                        {
                            data: 'storein_type_name'
                        },
                        {
                            data: 'supplier_name'
                        },
                        {
                            data: 'sr_no'
                        },
                        {
                            data: 'bill_no'
                        },
                        {
                            data: 'pp_no'
                        },
                        {
                            data: 'total_discount'
                        },
                        {
                            data: 'grand_total'
                        },
                        {
                            data: 'action',
                            orderable: true,
                            searchable: true,
                        },
                    ]
                });
        $('body').on('click', '#dltstorein', function() {
            let id = this.getAttribute('data-id');
            // let id = $(this).attr('data-id').val();
            console.log(id);
            new swal({
                    title: "Are you sure?",
                    text: "Once deleted, data will move to trash!!",
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
                            url: "/storein/delete/" + id,
                            data: {
                                '_token': $('meta[name=csrf-token]').attr("content"),
                                "storein": id,
                            },
                            success: function(data) {
                                console.log(data);
                               new swal
                                    ({
                                        text: "Poof! Your data has been deleted!",
                                        title: "Deleted",
                                        icon: "success",
                                    });
                                location.reload();
                            },
                            error: function(xhr) {
                                console.log(xhr.responseJSON.message);
                            }
                        })

                    }
                })
        })
    </script>
@endsection
