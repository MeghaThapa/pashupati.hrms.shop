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
                <table class="table">
                    <thead>
                        <tr>
                            <th>@lang('#')</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Supplier') }}</th>
                            <th>{{ __('SR No') }}</th>
                            <th>{{ __('Bill No') }}</th>
                            <th>{{ __('PP No') }}</th>
                            <th>{{ __('Discount') }}</th>
                            <th>{{ __('Grand Total') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $key = 0;
                        @endphp
                        @foreach ($storeinDatas as $storeinData)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $storeinData->purchase_date }}</td>
                                <td>{{ $storeinData->supplier->name }}</td>
                                <td>{{ $storeinData->sr_no ?? 'Empty' }}</td>
                                <td>{{ $storeinData->bill_no ?? 'Empty' }}</td>
                                <td>{{ $storeinData->pp_no ?? 'Empty' }}</td>
                                <td>{{ $storeinData->total_discount }}</td>
                                <td>{{ $storeinData->grand_total }}</td>
                                <td>{{ $storeinData->status }}</td>
                                <td class="d-flex " style="gap:5px;">
                                    {{-- @if ($storeinData->storein_status == 'pending')
                                        <a href="{{ route('storein.createItems', ['id' => $storeinData->id]) }}">
                                            <button class="btn btn-success">
                                                Continue
                                            </button>
                                        </a>
                                    @endif --}}
                                    <a href="{{ route('storein.invoiceView', ['storein_id' => $storeinData->id]) }}">
                                        <button class="btn btn-info">
                                            <i class="fas fa-file-invoice"></i>
                                        </button>
                                    </a>

                                    <button class="btn btn-danger" id="dltstorein" data-id="{{ $storeinData->id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>

                                    <a href="{{ route('storein.editStorein', ['storein_id' => $storeinData->id]) }}">
                                        <button class="btn btn-success">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->

            <!-- pagination start -->
            {{-- {{ $storein->links() }} --}}
            <!-- pagination end -->
        </div>
    </div>
    <!-- /.content -->
@endsection

@section('extra-script')
    <script>
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
                                // console.log(data);
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
        })
    </script>
@endsection
