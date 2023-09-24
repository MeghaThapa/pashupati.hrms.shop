@extends('layouts.admin')

@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"
        integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ asset('css/nepaliDatePicker/nepali.datepicker.v4.0.1.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .update_status{
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6 mt-2">
                <h4><strong>Delivery Order Entry</strong></h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Delivery Order') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            <div class="card mt-3">
                <div class="card-header">
                    <h4>Delivery Order entry</h4>
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
                    <form action="{{ route('delivery-order.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-sm-6">
                                <!-- Delivery Order Number -->
                                <div class="form-group">
                                    <label for="do_no">Delivery Order Number:</label>
                                    <input type="text" id="do_no" name="do_no"
                                        value="{{ old('do_no', '01-' . str_pad($nextId, 3, '0', STR_PAD_LEFT)) }}" readonly
                                        class="form-control">
                                </div>

                                <!-- Delivery Order Date -->
                                <div class="form-group">
                                    <label for="do_date">Delivery Order Date:</label>
                                    <input type="date" id="do_date" name="do_date" required class="form-control">
                                </div>

                                <!-- Supplier ID -->
                                <div class="form-group">
                                    <label for="supplier_id">Select Supplier</label>
                                    <select class="form-control select2" name="supplier_id">
                                        <option selected disabled>Select Supplier</option>
                                        @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}"> {{ $supplier->name }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Overdue Amount -->
                                <div class="form-group">
                                    <label for="overdue_amount">Overdue Amount:</label>
                                    <input type="number" step="0.01" id="overdue_amount" name="overdue_amount" required
                                        class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="base_rate_per_kg">Base Rate per KG:</label>
                                    <input type="number" step="0.01" id="base_rate_per_kg" name="base_rate_per_kg" required
                                        class="form-control">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <!-- Total Due -->
                                <div class="form-group">
                                    <label for="total_due">Total Due:</label>
                                    <input type="number" step="0.01" id="total_due" name="total_due" required
                                        class="form-control">
                                </div>

                                <!-- Party Limit -->
                                <div class="form-group">
                                    <label for="party_limit">Party Limit:</label>
                                    <input type="number" step="0.01" id="party_limit" name="party_limit" required
                                        class="form-control">
                                </div>

                                <!-- Delivery Order For Item ID -->
                                <div class="form-group">
                                    <label for="delivery_order_for_item_id">Delivery Order For Item ID:</label>
                                    <select id="delivery_order_for_item_id" class="form-control select2" name="delivery_order_for_item_id">
                                        <option selected disabled>Select For Item</option>
                                        @foreach($deliveryOrderForItems as $deliveryOrderForItem)
                                            <option value="{{ $deliveryOrderForItem->id }}">{{ $deliveryOrderForItem->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="qty_in_mt">Quantity in MT:</label>
                                    <input type="number" step="0.01" id="qty_in_mt" name="qty_in_mt"
                                        class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="bundel_pcs">Bundle Pieces:</label>
                                    <input type="text" id="bundel_pcs" name="bundel_pcs" class="form-control">
                                </div>

                            </div>
                        </div>

                        <!-- Collection -->
                        <div class="form-group">
                            <label for="collection">Collection:</label>
                            <textarea id="collection" name="collection" rows="4" required class="form-control"></textarea>
                        </div>

                        <!-- Pending Sauda -->
                        <div class="form-group">
                            <label for="pending_sauda">Pending Sauda:</label>
                            <textarea id="pending_sauda" name="pending_sauda" rows="4" required class="form-control"></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>

                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4>Delivery Order Entries</h4>

                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="myTable">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>DO Number</th>
                                <th>DO Date</th>
                                <th>Supplier</th>
                                <th>Overdue Amount</th>
                                <th>Total Due</th>
                                <th>Party Limit</th>
                                <th>For item</th>
                                <th>Qty in MT</th>
                                <th>Bundel PCS</th>
                                <th>Base Rate/Kg</th>
                                <th>Collection</th>
                                <th>Pending Sauda</th>
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
        $(".select2").select2()
    </script>
    @if (session()->has('message'))
        <script>
            toastr.success('{{ session()->get('message') }}');
        </script>
    @endif
    <script>
        $(document).ready(function() {

            let table = $("#myTable").DataTable({
                serverSide: true,
                processing: true,
                lengthMenu: [
                    [10, 25, 50, 100, 250, 500],
                    [10, 25, 50, 100, 250, 500]
                ],
                ajax: {
                    url: "{{ route('delivery-order.index') }}",
                    data: function(data) {
                        // data.start_date = $('#start_date').val();
                        // data.end_date = $('#end_date').val();
                    },
                },
                columns: [{
                        name: "DT_RowIndex",
                        data: "DT_RowIndex",
                        orderable: false,
                    },
                    {
                        name: "do_no",
                        data: "do_no"
                    },
                    {
                        name: "do_date",
                        data: "do_date"
                    },
                    {
                        name: "supplier_id",
                        data: "supplier_id"
                    },
                    {
                        name: "overdue_amount",
                        data: "overdue_amount"
                    },
                    {
                        name: "total_due",
                        data: "total_due"
                    },
                    {
                        name: "party_limit",
                        data: "party_limit"
                    },
                    {
                        name: "delivery_order_for_item_id",
                        data: "delivery_order_for_item_id"
                    },
                    {
                        name: "qty_in_mt",
                        data: "qty_in_mt"
                    },
                    {
                        name: "bundel_pcs",
                        data: "bundel_pcs"
                    },
                    {
                        name: "base_rate_per_kg",
                        data: "base_rate_per_kg"
                    },
                    {
                        name: "collection",
                        data: "collection"
                    },
                    {
                        name: "pending_sauda",
                        data: "pending_sauda"
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

            $(document).on('click','.update_status',function(){

                Swal.fire({
                    title: 'Do you want to change the approve status to Approved ?',
                    showDenyButton: true,
                    confirmButtonText: 'Yes',
                    denyButtonText: `No`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        let url = $(this).data('url');
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                    .attr('content')
                            },
                            url: url,
                            type: "PATCH",
                            data: {
                                "status": "Approved",
                            },
                            beforeSend: function() {
                                console.log('ajax fired');
                            },
                            success: function(data) {
                                if (data.status == true) {
                                    table.ajax.reload();
                                    swal.fire("Updated!",
                                        "Status Updated Successfully!",
                                        "success");
                                } else {
                                    swal.fire("Failed!",
                                        "Status Update failed!",
                                        "error");
                                }
                            },
                            error: function(xhr) {
                                swal.fire("Failed!",
                                    "Status Update failed!",
                                    "error");
                            }
                        });
                    } else if (result.isDenied) {
                        Swal.fire('Cancelled approval', '', 'info')
                    }
                })

            });

            $('#start_date, #end_date').on('change', function() {
                table.draw(); // Redraw the table
            });

            $(document).on("click", ".create-cc", function(e) {
                e.preventDefault()
                let entry_id = $(this).data("id")
                location.href = "{{ route('cc.plant.create', ['entry_id' => ':id']) }}".replace(":id",
                    entry_id)
            });

            $(document).on('click', ".delete-cc-entry", function(e) {
                $('#deleteModal').modal('show');
                $('#deleteID').val($(this).data('id'));
            });

            $(document).on('click', ".confirm_remove", function(e) {
                e.preventDefault()

                $.ajax({
                    url: "{{ route('cc.plant.entry.destroy') }}",
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

<script>
    $(document).ready(function () {
        // Select the necessary elements
        var deliveryOrderForItemSelect = $('#delivery_order_for_item_id');
        var qtyInMtInput = $('#qty_in_mt');
        var bundelPcsInput = $('#bundel_pcs');

        // Function to enable/disable fields based on the selected option's text
        function toggleFields() {
            var selectedOptionText = deliveryOrderForItemSelect.find('option:selected').text();

            // Reset the fields
            qtyInMtInput.prop('required', false).val('');
            bundelPcsInput.prop('required', false).val('');

            // Enable/disable based on the selected option's text
            if (selectedOptionText === 'PP Woven' || selectedOptionText === 'PP Non Woven' || selectedOptionText === 'PP/HDPE Tripal' ||
                selectedOptionText === 'RP Granuels' || selectedOptionText === 'PP/CC/Other Granuels' || selectedOptionText === 'Wastage') {
                qtyInMtInput.prop('required', true).removeAttr('disabled');
                bundelPcsInput.attr('disabled', true).val('');
            } else if (selectedOptionText === 'PP Bags (Unlam)' || selectedOptionText === 'PP Bags (Lam)') {
                bundelPcsInput.prop('required', true).removeAttr('disabled');
                qtyInMtInput.attr('disabled', true).val('');
            }
        }

        // Initial state
        toggleFields();

        // Listen for changes in the select field
        deliveryOrderForItemSelect.on('change', toggleFields);
    });
</script>

@endsection
