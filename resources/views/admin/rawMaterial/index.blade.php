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
            <div id="RawMaterialError" class="alert alert-danger" hidden></div>
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
                        <a href="{{ route('rawMaterial.godamTransferDetail') }}" class="btn btn-success">
                            {{ __('Godam Transfer details') }}<i class="fas fa-plus-circle"></i>
                        </a>
                        <a href="{{ route('rawMaterial.create') }}" class="btn btn-primary">
                            {{ __('Add Raw Materials') }} <i class="fas fa-plus-circle"></i>
                        </a>
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
                            <th>{{ __('PP | Challan | Bill No') }}</th>
                            <th>{{ __('Storein Type') }}</th>
                            <th>{{ __('From Godam') }}</th>
                            <th>{{ __('To Godam') }}</th>
                            <th>{{ __('Total Qty') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        @endsection
        @section('extra-script')
            <script>
                $('document').ready(function() {
                    var table = $('#rawMaterialTable').DataTable({
                        lengthMenu: [
                            [30, 40, 50, -1],
                            ['30 rows', '40 rows', '50 rows', 'Show all']
                        ],
                        style: 'bootstrap4',
                        processing: true,
                        serverSide: true,
                        ajax: "{{ route('rawMaterial.dataTable') }}",
                        columns: [{
                                data: 'DT_RowIndex'
                            },
                            {
                                data: 'date'
                            },
                            {
                                data: 'receipt_no'
                            },
                            {
                                data: 'supplier_name'
                            },
                            {
                                data: 'pp_no'
                            },
                            {
                                data: 'storein_type_name'
                            },
                            {
                                data: 'from_godam_name',
                                defaultContent: 'EMPTY'
                            },
                            {
                                data: 'to_godam_name'
                            },
                            {
                                data: 'raw_material_item_quantity'
                            },

                            {
                                data: 'action',
                                orderable: true,
                                searchable: true,
                            },
                        ]
                    });
                    $('body').on('click', '#rawMaterialDeleteBtn', function(e) {
                        let rawMaterial_id = this.getAttribute('data-id');
                        console.log('js', rawMaterial_id);
                        new swal({
                            title: "Are you sure?",
                            text: "Once deleted, data will deleted completely!!",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: 'Yes, delete it!',
                            cancelButtonText: 'No, cancel!',
                            reverseButtons: true

                        }).then((willDelete) => {
                            if (willDelete.isConfirmed) {

                                $.ajax({
                                    type: "DELETE",
                                    url: "{{ route('rawMaterial.delete', ['rawMaterial_id' => ':id']) }}"
                                        .replace(':id', rawMaterial_id),
                                    data: {
                                        '_token': $('meta[name=csrf-token]').attr("content"),
                                    },
                                    success: function(data) {
                                        console.log('controller:', data);
                                        new swal
                                            ({
                                                text: "Poof! Your data has been deleted!",
                                                title: "Deleted",
                                                icon: "success",
                                            });
                                        location.reload();
                                    },
                                    error: function(xhr) {
                                        setMessage('RawMaterialError', xhr.responseJSON.message)

                                        //console.log(xhr.responseJSON.message);
                                    }
                                })

                            }
                        })
                    });

                    function setMessage(element_id, message) {
                        let errorContainer = document.getElementById(element_id);
                        errorContainer.hidden = false;
                        errorContainer.innerHTML = message;
                        setTimeout(function() {
                            errorContainer.hidden = true;
                        }, 2000);
                    }

                });
            </script>
        @endsection
