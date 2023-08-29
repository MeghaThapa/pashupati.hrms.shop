@extends('layouts.admin')

@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"
        integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ asset('css/nepaliDatePicker/nepali.datepicker.v4.0.1.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6 mt-2">
                <h4><strong>Reprocess Wastage Entry</strong></h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Reprocess Waste') }}</li>
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
                    <h4>Reprocess Wastage entry</h4>
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
                    <form action="{{ route('reprocess.wastage.entry.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label for="tape_receive_date">Godam<span class="text-danger">*</span></label>
                                <select name="godam_id" class="advance-select-box form-control" id="godam_id">
                                    @foreach ($godams as $godam)
                                        <option value="{{ $godam->id }}">{{ $godam->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="tape_receive_date">Date<span class="text-danger">*</span></label>
                                <input id="engDate" type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}"
                                    required />
                            </div>
                            <div class="col-md-6">
                                <label for="receipt_number">Receipt Number<span class="text-danger">*</span></label>
                                <input type="text" value="{{ $receipt_number }}" name="receipt_number"
                                    class="form-control" required />
                            </div>
                            <div class="col-md-6">
                                <label for="tape_receive_date">Nepali Date<span class="text-danger">*</span></label>
                                <input type="text" id="nepDate" class="form-control" value="{{ date('Y-m-d') }}"
                                    required readonly />
                            </div>
                            <div class="col-md-12">
                                <label for="receipt_number">Remarks</label>
                                <input type="text" name="remarks" class="form-control" />
                            </div>
                            <div class="col-md-12 mt-2">
                                <button class="btn btn-primary" type="submit">Create</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Reprocess Wastages</h4>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="start_date">Start Date:</label>
                            <input type="text" class="form-control ndp-nepali-calendar" id="start_date" name="start_date" value="">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="end_date">End Date:</label>
                            <input type="text" class="form-control ndp-nepali-calendar" id="end_date" name="end_date" value="">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label for="godamID">Select Godam</label>
                        <select class="form-control" id="godamID">
                            <option value="" selected disabled>{{ __('Select Godam Name') }}</option>
                            @foreach ($godams as $data)
                                <option value="{{ $data->id }}">{{ $data->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="myTable">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Receipt Number</th>
                                <th>Date</th>
                                <th>Remarks</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title">Are you sure you want to delete this entry?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Your transactions from this entry would be rolled back...</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="deleteID" />
                    <button type="button" class="btn btn-danger confirm_remove">Delete</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
    <script src="{{ asset('js/nepaliDatePicker/nepali.datepicker.v4.0.1.min.js') }}"></script>
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

            var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");

            let table = $("#myTable").DataTable({
                serverSide: true,
                processing: true,
                lengthMenu: [
                    [10, 25, 50, 100, 250, 500],
                    [10, 25, 50, 100, 250, 500]
                ],
                ajax: {
                    url: "{{ route('reprocess.wastage.entry.index.ajax') }}",
                    data: function(data) {
                        data.start_date = $('#start_date').val();
                        data.end_date = $('#end_date').val();
                        data.godam_id = $('#godamID').val();
                    },
                },
                columns: [{
                        name: "DT_RowIndex",
                        data: "DT_RowIndex",
                        orderable:false,
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
                        name: "remarks",
                        data: "remarks"
                    },
                    {
                        name: "status",
                        data: "status"
                    },
                    {
                        name: "action",
                        data: "action"
                    },
                ]
            });

            $('#start_date').val(currentDate);
            $('#start_date').nepaliDatePicker({
                ndpYear: true,
                ndpMonth: true,
                disableAfter: currentDate,
                onChange(){
                    table.draw();
                }
            
            });

            $('#end_date').val(currentDate);
            $('#end_date').nepaliDatePicker({
                ndpYear: true,
                ndpMonth: true,
                disableAfter: currentDate,
                onChange(){
                    table.draw();
                }
            });

            $('#start_date, #end_date, #godamID').on('change', function () {
                table.draw(); // Redraw the table
            });

            $(document).on("click", ".create-cc", function(e) {
                e.preventDefault()
                let entry_id = $(this).data("id")
                location.href = "{{ route('cc.plant.create', ['entry_id' => ':id']) }}".replace(":id",
                    entry_id)
            })

            $('#engDate').on('change',function(){
                $('#nepDate').val( NepaliFunctions.AD2BS($('#engDate').val()) ); 
            });

            $('#engDate').change();

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

            $(document).on('click', ".delete-cc-entry", function(e) {
                $('#deleteModal').modal('show');
                $('#deleteID').val($(this).data('id'));
            });

            $(document).on('click',".confirm_remove",function(e) {
                e.preventDefault()

                $.ajax({
                    url: "{{ route('reprocess.wastage.entry.destroy') }}",
                    method: "post",
                    data: {
                        "_token": $("meta[name='csrf-token']").attr("content"),
                        "id": $('#deleteID').val(),
                    },
                    success: function(response) {
                        table.ajax.reload();
                        $('#deleteModal').modal('hide');
                        $('#deleteId').val('');

                    },
                    error: function(error) {
                        console.log(error)
                    }
                })
            });
        })
    </script>
@endsection
