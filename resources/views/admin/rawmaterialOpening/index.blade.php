@extends('layouts.admin')
@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Raw Material Opening') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Opening Rawmaterial') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('openingRawmaterialEntry.create') }}" class='btn btn-primary'>opening ramaterial
                        entry</a>
                </div>
                <div class="table-custom card-body table-responsive">
                    <table class="table table-bordered table-hover" id="rawMaterialOpeningTable">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Opening Date</th>
                                <th>Receipt Date</th>
                                <th>To Godam</th>
                                <th>Action</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach ($rawmaterialOpeningDatas as $index => $rawmaterialOpeningData)
                                <tr>
                                    <td>
                                        {{ $index + 1 }}
                                    </td>
                                    <td>{{ $rawmaterialOpeningData->opening_date }}</td>
                                    <td>{{ $rawmaterialOpeningData->receipt_no }}</td>
                                    <td>{{ $rawmaterialOpeningData->godam->name }}</td>
                                    <td>
                                        @if ($rawmaterialOpeningData->status == 'running')
                                            <div class="btn-group">
                                                <a class="btn btn-primary">
                                                    <i class="fas fa-edit"></i> </a>
                                                <a class="btn btn-danger" href="javascript:void(0)"> <i
                                                        class="fas fa-trash    "></i> </a>
                                            </div>
                                        @elseif($rawmaterialOpeningData->status == 'completed')
                                            <a class="btn btn-success" href="javascript:void(0)"> <i class="fa fa-eye"
                                                    aria-hidden="true"></i> </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-script')
    @if (session()->has('message'))
        <script>
            toastr.success("{{ session()->get('message') }}");
        </script>
    @elseif(session()->has('message_err'))
        <script>
            toastr.error("{{ session()->get('message_err') }}");
        </script>
    @endif
    <script>
        var table = $('#rawMaterialOpeningTable').DataTable({
            lengthMenu: [
                [30, 40, 50, -1],
                ['30 rows', '40 rows', '50 rows', 'Show all']
            ],
            style: 'bootstrap4',
            processing: true,
            serverSide: true,
            ajax: "{{ route('rawMaterialOpening.tableData') }}",
            columns: [{
                    data: 'DT_RowIndex'
                },
                {
                    data: 'opening_date'
                },
                {
                    data: 'receipt_no'
                },
                {
                    data: 'godam.name'
                },
                {
                    data: 'statusBtnn',

                },

                {
                    data: 'action',
                    orderable: true,
                    searchable: true,
                },
            ]


        });
        $('body').on('click', '#dltRawmaterialEntry', function() {
            let id = this.getAttribute('data-id');
            // let id = $(this).attr('data-id').val();
            // console.log(id);
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
                            url: "/openingRawmaterialEntry/delete/" + id,
                            data: {
                                '_token': $('meta[name=csrf-token]').attr("content"),
                                "storein": id,
                            },
                            success: function(data) {
                                console.log('delete data', data);
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
