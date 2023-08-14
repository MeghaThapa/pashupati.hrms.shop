@extends('layouts.admin')

@section('extra-style')
<link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
 <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6 mt-2">
                <h4><strong>Fabric Send Receive Entry</strong></h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Fabric Send and Receive Entry') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h4>Create Entries</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('fabricSendReceive.entry.store') }}" method="post">
                        @csrf
                        <div class="row">
                            @if($errors->any())
                                @foreach($errors->all() as $error)
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        <span class="sr-only">Close</span>
                                    </button>
                                    <div class="col-12">
                                        {{ $error }}
                                    </div>
                                </div>
                                @endforeach
                            @endif
                            <div class="col-md-3">
                                <label for="tape_receive_date">Bill Date</label>
                                <input type="date" name="bill_date" class="form-control" value="{{ $bill_date }}" required/>
                           </div>
                           <div class="col-md-3">
                                <label for="bill_number">Bill Number</label>
                                <input type="text" value="{{ $bill_number }}" name="bill_number" class="form-control" required/>
                           </div>
                           <div class="col-md-3">
                                <label for="">Bill Date Np</label>
                                <input type="text" value="{{ $bill_date_np }}" class="form-control" name="bill_date_np" requierd>
                           </div>
                            <div class="col-md-3">
                                <label for="">Godam</label>
                                <select name="godam" id="godam" class="form-control select2" required>
                                    <option value="" selected disabled>--Select Godam--</option>
                                   @foreach ($godam as $data)
                                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                                   @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="">Plant Type</label>
                                <select name="planttype" id="planttype" class="form-control select2" required>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="">Plant Name</label>
                                <select name="plantname" id="plantname" class="form-control select2" required>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="">Shift</label>
                                <select name="shift" id="shift" class="form-control select2" required>
                                    @foreach($shift as $data)
                                        <option value="{{ $data->id }}">{{ $data->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                           <div class="col-md-3">
                                <label for="">Remarks</label>
                                <input type="text" name="remarks" class="form-control" placeholder="Enter remarks">
                           </div>
                           <div class="col-md-4 mt-2">
                                <button class="btn btn-primary" type="submit">Create</button>
                           </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Entries List</h4>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover table-striped w-100" id="myTable">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Bill Number</th>
                                <th>Bill Date</th>
                                <th>Bill Number NP</th>
                                <th>Godam</th>
                                <th>Plant Type</th>
                                <th>Plant Name</th>
                                <th>Shift</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-script')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
<script>
    $(".select2").select2();
</script>
@if(session()->has('message'))
    <script>
        toastr.success('{{ session()->get("message")}}');
    </script>
@endif
<script>
    $(document).ready(function(){
        let table = $(".table").DataTable({
            serverside : true,
            processing : true,
            lengthMenu: [
                            [5, 10, 30, 50, 100, 250, 500,-1], 
                            [5, 10, 30, 50, 100, 250, 500, "All"]
                        ],
            ajax: {
                url : "{{ route('fabricSendReceive.entry.ajaxlist') }}"
            },
            columns : [
                { name : "DT_RowIndex" , data : "DT_RowIndex" },
                { name : "bill_number" , data : "bill_number" },
                { name : "bill_date" , data : "bill_date" },
                { name : "bill_date_np" , data : "bill_date_np" },
                { name : "godam" , data : "godam" },
                { name : "planttype" , data : "planttype" },
                { name : "plantname" , data : "plantname" },
                { name : "shift" , data : "shift" },
                { name : "remarks" , data : "remarks" },
                { name : "action" , data : "action" },
            ]
        });

        $("#godam").change(function(e){
            let godamId = $(this).val(); 
            let department_id =  $(this).val();
            let geturl = "{{ route('fabricSendReceive.get.planttype',['id'=>':id']) }}"
            $.ajax({
                url:geturl.replace(':id',department_id),
                beforeSend:function(){
                    console.log('Getting Plant type');
                },
                success:function(response){
                    addplanttype(response);
                },
                error:function(error){
                    console.log(error);
                }
            });
        });

        $("#planttype").change(function(e){
            let department_id =  $(this).val();
            let geturl = "{{ route('fabricSendReceive.get.plantname',['id'=>':id']) }}";
            $.ajax({
                url:geturl.replace(':id',department_id),
                beforeSend:function(){
                    console.log('Getting Plant Name');
                },
                success:function(response){
                    addplantname(response);
                },
                error:function(error){
                    console.log(error);
                }
            });
        });

        function addplanttype(data){
            $("#planttype").empty();
            $('#planttype').append(`<option value="" disabled selected>--Select Plant Type--</option>`);
            data.planttype.forEach( d => {
                if (d.name && d.name.startsWith('lam')) {
                $('#planttype').append(`<option value="${d.id}">${d.name}</option>`);
                }
            });
        }
        function addplantname(data){
            console.log(data);
            $("#plantname").empty();
            $('#plantname').append(`<option value="" disabled selected>--Select Plant Name--</option>`);
            data.plantname.forEach( d => {
                $('#plantname').append(`<option value="${d.id}">${d.name}</option>`);
            });
        }
        $(document).on("click",".delete",function(e){
            e.preventDefault();
            let geturl = "{{ route('fabricSendReceive.entry.delete') }}";
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
                        method : "post",
                        data: {
                            "_token": csrf_token,
                            "id" : $(this).data("id")
                        },
                        success:function(response){
                            if(response.status == "200"){
                                Swal.fire(
                                    'Deleted!',
                                    'Your file has been deleted.',
                                    'success'
                                );
                                table.ajax.reload();
                            }
                        },
                        error:function(error){
                            console.log(error);
                        }
                    });
                }
            });
        });

        $(document).on("click",".create",function(e){
            e.preventDefault()
            let id = $(this).data("id")
            location.href =  `{{ route('fabricSendReceive.index.revised',['id'=>':id']) }}`.replace(":id",id)
        })

    })
</script>
@endsection
