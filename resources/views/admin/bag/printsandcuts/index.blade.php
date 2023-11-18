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
                <h1 class="m-0 text-dark">{{ __('Prints and Cuts') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Prints and cuts') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('prints.and.cuts.create.entry') }}" class='btn btn-primary'>Create Cuts and rolls</a>
                </div>
                <div class="table-custom card-body table-responsive">
                    <table class="table table-bordered table-hover ">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Receipt Number</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Date Np</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $index => $d)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $d->receipt_number }}</td>
                                    <td>{{ $d->status }}</td>
                                    <td>{{ $d->date }}</td>
                                    <td>{{ $d->date_np }}</td>
                                    <td>
                                        @if ($d->status == 'pending')
                                            <div class="btn-group">
                                                <a class="btn btn-primary"
                                                    href="{{ route('prints.and.cuts.createPrintedRolls', ['id' => $d->id]) }}">
                                                    <i class="fas fa-edit"></i> </a>
                                                    @if($d->printing_and_cutting_bag_items_count ==0)
                                                        <a class="btn btn-danger deleteBtn" data-id="{{$d->id}}"> <i
                                                                class="fas fa-trash"></i> </a>
                                                    @endif
                                            </div>
                                        @elseif($d->status == 'completed')
                                            <a class="btn btn-success"
                                                href="{{ route('prints.and.cuts.view', ['id' => $d->id]) }}"> <i
                                                    class="fa fa-eye" aria-hidden="true"></i> </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    $(".table").dataTable();
</script>
@section('extra-script')
<script>
$('body').on('click', '.deleteBtn', function() {
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
                                url: "{{ route('prints.and.cuts.delete', ['id' => ':id']) }}"
                                    .replace(':id', id),
                                data: {
                                    '_token': $('meta[name=csrf-token]').attr("content"),
                                },
                                success: function(data) {
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

    @if (session()->has('message'))
        <script>
            toastr.success("{{ session()->get('message') }}");
        </script>
    @elseif(session()->has('message_err'))
        <script>
            toastr.error("{{ session()->get('message_err') }}");
        </script>
    @endif
    
@endsection
