@extends('layouts.admin')

@section('content')
    {{-- @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif --}}

    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Shift') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Shift') }}</li>
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
                    <form action="{{ route('staff.index') }}" method="GET" role="search">
                        <div class="input-group">
                            <input type="text" name="term"
                                placeholder="{{ __('Type name or email or department or desigantion ...') }}"
                                class="form-control" autocomplete="off" value="" required>
                            <span class="input-group-append">
                                <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
                            </span>
                        </div>
                    </form>
                </div>
                <div class="col-lg-9 col-md-7 col-6">
                    <div class="card-tools text-md-right">
                        <a class="btn btn-secondary" href="{{ route('staff.pdf') }}">
                            <i class="fas fa-download"></i> @lang('Export')
                        </a>
                        <a href="{{ route('shift.create') }}" class="btn btn-primary">
                            {{ __('Add Shift') }} <i class="fas fa-plus-circle"></i>
                        </a>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="p-0 table-responsive table-custom my-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('S.N') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Satrt_date') }}</th>
                            <th>{{ __('End_date') }}</th>
                            {{-- <th>{{ __('Status') }}</th> --}}
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            use Carbon\Carbon;
                        @endphp
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($shifts as $shift)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $shift->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }}
                                </td>
                                <td>{{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }}</td>
                                <td>
                                    <a>
                                        <button class="btn btn-success" id="shiftEdit" data-toggle="modal"
                                            data-id="{{ $shift->id }}" data-target="#editShiftModal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </a>
                                    <a>
                                        <button class="btn btn-danger" id="dltShift" data-id="{{ $shift->id }}">
                                            <i class="fas fa-trash-alt"></i>
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
            {{-- {{ $allStaff->links() }} --}}
            <!-- pagination end -->
        </div>
    </div>
    <!-- /.content -->
    {{-- editShiftMOdal pop up --}}
    <div class="modal fade" id="editShiftModal" tabindex="-1" role="dialog" aria-labelledby="exampleModaltax"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModaltax">Edit Shift</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('shift.update', ['id' => $shift->id]) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="shiftId" name="shift_id" value="" required hidden>
                                    <label for="categorySelect" class="form-label" style="font-weight: bold;">Shift
                                        Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" placeholder="{{ __('Shift Name') }}" value=""
                                        required>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="startTime" class="col-form-label" style="font-weight: bold;">Start
                                        Time</label>
                                    <input type="time" class="form-control @error('name') is-invalid @enderror"
                                        id="startTime" name="start_time" placeholder="{{ __('Start Time') }}"
                                        value="{{ old('name') }}" required>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="endTime" class="form-label" style="font-weight: bold;">End Time</label>
                                    <input type="time" class="form-control @error('name') is-invalid @enderror"
                                        id="endTime" name="end_time" placeholder="{{ __('End Time') }}"
                                        value="{{ old('name') }}" required>
                                </div>

                                <div class="col-md-12 form-group">
                                    <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="active">{{ __('Active') }}</option>
                                        <option value="inactive">{{ __('Inactive') }}</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>

                </div> --}}
            </div>
        </div>
    </div>
    {{-- edit item modal end --}}
@endsection
@section('extra-script')
    <script>
        $('body').on('click', '#shiftEdit', function() {
            let id = $(this).attr('data-id');

            $.ajax({
                type: "GET",
                url: "{{ route('shift.getShiftData', ['id' => ':Replaced']) }}".replace(
                    ':Replaced',
                    id),
                success: function(data) {
                    $('#shiftId').val(data.id);
                    $('#name').val(data.name);
                    $('#startTime').val(data.start_time);
                    $('#endTime').val(data.end_time);
                    $('#status').val(data.status);

                }
            })
        });
        $('body').on('click', '#dltShift', function() {
            let id = $(this).attr('data-id');
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
                            url: "{{ route('shift.delete', ['id' => ':Replaced']) }}".replace(
                                ':Replaced',
                                id),
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
    </script>
@endsection
