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
                <h1 class="m-0 text-dark">{{ __('Storeout') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Storeout') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <div class="row">
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
        {{-- <div class="col-sm-3">
            <label for="godamID">Select Godam</label>
            <select class="form-control" id="godamID">
                <option value="" selected disabled>{{ __('Select Godam Name') }}</option>
                @foreach ($godams as $godam)
                    <option value="{{ $godam->id }}">{{ $godam->name }}
                    </option>
                @endforeach
            </select>
        </div> --}}
    </div>
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="col-md-12">
                @include('admin.includes.alert')
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-5 col-6 mb-2">
                    <form action="{{ route('storeout.index') }}" method="GET" role="search">
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
                        <a href="{{ route('storeout.create') }}" class="btn btn-primary">
                            {{ __('Add Storeout') }} <i class="fas fa-plus-circle"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="p-0 table-responsive table-custom my-3">
                <table class="table" id="storeoutTable">
                    <thead>
                        <tr>
                            <th>@lang('SN')</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Receipt NO') }}</th>
                            <th>{{ __('For') }}</th>
                            <th>{{ __('Total') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th style="text-align: center !important;">{{ __('Action') }}</th>
                        </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    <!-- /.content -->
@endsection
@section('extra-script')
    <script>
        $(document).ready(function() {
            var table = $('#storeoutTable').DataTable({
                lengthMenu: [
                    [30, 40, 50, -1],
                    ['30 rows', '40 rows', '50 rows', 'Show all']
                ],
                style: 'bootstrap4',
                processing: true,
                serverSide: true,
                 ajax: {
                    url: "{{ route('storeout.storoutYajraDatabales') }}",
                    data: function(data) {
                        data.start_date = $('#start_date').val() ?? null;
                        data.end_date = $('#end_date').val() ?? null;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'receipt_date'
                    },
                    {
                        data: 'receipt_no'
                    },
                    {
                        data: 'storein_department_name'
                    },
                    {
                        data: 'total_amount'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'action',
                        orderable: true,
                        searchable: true,
                    },
                ]
            });
             $('#start_date, #end_date').on('change', function() {
                table.ajax.reload(); // Redraw the table
            });
            $('body').on('click', '#dltStoreout', function() {
                let id = this.getAttribute('data-id');
                // let id = $(this).attr('data-id').val();
                console.log(id);
                new swal({
                        title: "Are you sure?",
                        text: "Once deleted, data will deleted completely!!",
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
                                url: "{{ route('storeout.deleteStoreout', ['storeout_id' => ':id']) }}"
                                    .replace(':id', id),
                                data: {
                                    '_token': $('meta[name=csrf-token]').attr("content"),
                                },
                                success: function(data) {
                                    console.log('hello', data);
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
        });
    </script>
@endsection
