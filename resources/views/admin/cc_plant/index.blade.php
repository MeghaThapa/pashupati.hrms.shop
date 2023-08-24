@extends('layouts.admin')

@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"
        integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6 mt-2">
                <h4><strong>CC Plant Entry</strong></h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('CC plant') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            <div class="bg-light text-right rounded">
                <a href="" class="rounded-pill btn btn-primary text-light">View Detailed Report</a>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h4>CC Plant entry</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="border-rounded border-danger alert alert-light text-danger text-center alert-dismissible fade show"
                            role="alert">
                            <button type="button" class="close" data-dismiss="alert" text-danger aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">Close</span>
                            </button>
                            @foreach ($errors->all() as $error)
                                {!! $error . '<br>' !!}
                            @endforeach
                        </div>
                    @endif
                    <form action="{{ route('cc.plant.entry.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label for="tape_receive_date">Godam<span class="text-danger">*</span></label>
                                <select name="godam_id" class="advance-select-box form-control" id="godam_id" required>
                                    <option disabled selected>Select Godam</option>
                                    @foreach ($godam as $data)
                                        <option value="{{ $data->id }}">{{ $data->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="tape_receive_date">Date<span class="text-danger">*</span></label>
                                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}"
                                    required />
                            </div>
                            <div class="col-md-6">
                                <label for="">Date NP<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="date_np" id="date_np">
                            </div>
                            <div class="col-md-6">
                                <label for="receipt_number">Receipt Number<span class="text-danger">*</span></label>
                                <input type="text" value="{{ $receipt_number }}" name="receipt_number"
                                    class="form-control" required />
                            </div>
                            <div class="col-md-6">
                                <label for="receipt_number">Dana Name</label>
                                <select id="danaName" name="dana_name" class="form-control" required>
                                    <option selected disabled>Select Dana</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="available_quantity">Available Quantity</label>
                                <input type="text" id="available_quantity"  name="available_quantity" class="form-control" readonly />
                            </div>
                            <div class="col-md-3">
                                <label for="dana_quantity">Dana Quantity</label>
                                <input id="dana_quantity" type="number" name="dana_quantity" class="form-control" required />
                            </div>
                            <div class="col-md-12">
                                <label for="receipt_number">Remarks</label>
                                <input type="text" name="remarks" class="form-control" />
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
                    <h4>CC Entries</h4>

                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="myTable">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Receipt Number</th>
                                <th>Date</th>
                                <th>Date NP</th>
                                <th>Godam</th>
                                <th>Dana Name</th>
                                <th>Dana Group</th>
                                <th>Dana Quantity</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('extra-script')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script>
        $(".advance-select-box").select2()
    </script>
    @if (session()->has('message'))
        <script>
            toastr.success('{{ session()->get('message') }}');
        </script>
    @endif
    <script>
        $(document).ready(function() {

            $("#myTable").DataTable({
                serverside: true,
                processing: true,
                lengthMenu: [
                    [10, 25, 50, 100, 250, 500],
                    [10, 25, 50, 100, 250, 500]
                ],
                ajax: "{{ route('cc.plant.entry.index.ajax') }}",
                columns: [{
                        name: "DT_RowIndex",
                        data: "DT_RowIndex"
                    },
                    {
                        name: "receipt_number",
                        data: "receipt_number"
                    },
                    {
                        name: "date",
                        data: "date"
                    },
                    {
                        name: "date_np",
                        data: "date_np"
                    },
                    {
                        name: "godam_name",
                        data: "godam_name"
                    },
                    {
                        name: "dana_name",
                        data: "dana_name"
                    },
                    {
                        name: "dana_group",
                        data: "dana_group"
                    },
                    {
                        name: "dana_quantity",
                        data: "dana_quantity"
                    },
                    {
                        name: "remarks",
                        data: "remarks"
                    },
                    {
                        name: "action",
                        data: "action"
                    },
                ]
            });

            $(document).on('change','#godam_id',function(){
                let godam_id = $(this).val();
                $.ajax({
                    url: "{{ route('cc.plant.get.dananame.ajax', ['godam_id' => ':id']) }}".replace(":id",godam_id),
                    method : "get",
                    beforeSend: function() {
                        // console.log("Getting Plant Name");
                    },
                    success: function(response) {
                        if(response.status == true){
                            $.each(response.data,function(k,v){
                                $("#danaName").append('<option data-quantity="'+v.quantity+'" value="' + v.id +
                                    '">' + v.dana_name.name+ '   ( '+ v.dana_group.name +' )' + '</option>');
                            });
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });

            $("#danaName").on("change", function() {
                var selectedOption = $(this).find("option:selected");
                var dataQuantity = selectedOption.data("quantity");
                $('#available_quantity').val(dataQuantity);
                $("#dana_quantity").attr("max", dataQuantity);
            });

            $(document).on("click", ".create-cc", function(e) {
                e.preventDefault()
                let entry_id = $(this).data("id")
                location.href = "{{ route('cc.plant.create', ['entry_id' => ':id']) }}".replace(":id",
                    entry_id)
            })

            $("#trash").submit(function(e) {
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
                            method: "POST",
                            data: {
                                _token: csrf_token
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Your file has been deleted.',
                                    'success'
                                );
                                location.reload(true);
                            },
                            error: function(error) {
                                console.log(error);
                            }
                        });
                    }
                });
            });
        })
    </script>
@endsection
