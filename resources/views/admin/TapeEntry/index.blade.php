@extends('layouts.admin')

@section('extra-style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
 <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6 mt-2">
                <h4 ><strong>Tape Receive Entry</strong></h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Tape Entry') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            <div class="bg-light text-right rounded">
                <a href="{{ route('tape.report') }}" class="rounded-pill btn btn-primary text-light">View Detailed Report</a>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h4>Create Tape Entries</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('tape.entry.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label for="tape_receive_date">Tape Date</label>
                                <input type="date" name="tape_receive_date" class="form-control" value="{{ date("Y-m-d") }}" required/>
                           </div>
                           <div class="col-md-6">
                                <label for="receipt_number">Receipt Number</label>
                                <input type="text" value="{{ $receipt_number }}" name="receipt_number" class="form-control" required/>
                           </div>
                           <div class="col-md-4 mt-2">
                                <button class="btn btn-primary" type="submit">Create Tape Entry</button>
                           </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Tape Entries</h4>

                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="myTable">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Receipt Number</th>
                                <th>Tape Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($tapeentries) > 0)
                                @foreach($tapeentries as $key => $data)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $data->receipt_number }}  <span class="badge @if($data->status == 'created') badge-success @else badge-warning @endif">{{ $data->status }}</span></td>
                                        <td>{{ $data->tape_entry_date }}</span></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('tape.entry.edit',$data->id) }}" class="btn btn-primary" title="create tape receive entry"><i class='fa fa-edit'></i></a>
                                                @if($data->status == 'pending')
                                                    <a href="{{ route('tape.entry.receive.create',['id'=>$data->id]) }}" class="btn btn-info" title="create tape receive entry"><i class='fa fa-plus'></i></a>
                                                    <form id="trash" action="{{ route('tape.entry.receive.delete',['id'=>$data->id]) }}">
                                                        <button type="submit" class="btn btn-danger ml-2"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                @elseif($data->status == 'created')
                                                    <a data-url="{{ route('tape.entry.receive.view',['id'=>$data->id]) }}" type="submit" class="btn btn-info tape_info_production"><i class="fa fa-eye"></i></a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center">No Data Found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('extra-script')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
@if(session()->has('message'))
    <script>
        toastr.success('{{ session()->get("message")}}');
    </script>
@endif
<script>
    let table = new DataTable('#myTable');
</script>
<script>
    $(document).ready(function() {
        $(".tape_info_production").click(function(){
            let url = $(this).data("url")
            $.ajax({
                url : url,
                method : "get",
                success:function(response){
                    $("#modelId").modal("show")
                    $(".modal-body").append(`
                        <p>${response}</p>
                    `)
                },
                error:function(error){
                    console.log(error);
                }
            });
        })

     })
</script>
<script>
   $("#trash").submit(function(e){
    e.preventDefault();
    let geturl = $('#trash').attr('action');
    let csrf_token = $('meta[name="csrf-token"]').attr('content');
    Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: geturl,
                method : "POST",
                data: {
                    _token: csrf_token
                },
                success:function(response){
                    Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                    );
                    location.reload(true);
                },
                error:function(error){
                    console.log(error);
                }
            });
        }
    });
   });
</script>
@endsection
