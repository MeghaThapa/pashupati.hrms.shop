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
        .update_status {
            cursor: pointer;
        }

        .invalid {
            border-color: red;
        }

        .valid {
            border-color: green;
        }
    </style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6 mt-2">
                <h4><strong>Sauda Item Entry</strong></h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Sauda Item Order') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            <div class="card mt-3">
                <div class="card-header">
                    <h4>Sauda Item Order entry</h4>
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
                    <form action="{{ route('sauda-item.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-sm-6">
                                <!-- Delivery Order Number -->
                                <div class="form-group">
                                    <label for="do_no">Sauda Number:</label>
                                    <input type="text" id="sauda_no" name="sauda_no"
                                        value="{{ old('sauda_no', '01-' . str_pad($nextId, 3, '0', STR_PAD_LEFT)) }}" readonly
                                        class="form-control">
                                </div>

                                <!-- Delivery Order Date -->
                                <div class="form-group">
                                    <label for="do_date">Sauda Date:</label>
                                    <input type="date" id="sauda_date" name="sauda_date" required class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="sauda_for">Sauda For:</label>
                                    <select  id="sauda_for" name="sauda_for" required class="form-control">
                                        <option value="Local">Local</option>
                                        <option value="Export">Export</option>
                                    </select>
                                </div>

                                <!-- Supplier ID -->
                                <div class="form-group">
                                    <label for="supplier_id">Select Supplier</label>
                                    <select class="form-control select2" id="supplier_id" name="supplier_id">
                                        <option selected disabled>Select Supplier</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}"> {{ $supplier->name }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="acc_name">Account Name:</label>
                                    <input type="text" id="acc_name" name="acc_name" required
                                        class="form-control">
                                </div>

                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="qty">Quantity:</label>
                                    <input type="text" id="qty" name="qty" required
                                        class="form-control decimal_number">
                                </div>

                                <!-- Delivery Order For Item ID -->
                                <div class="form-group">
                                    <label for="delivery_order_for_item_id">Delivery Order For Item ID:</label>
                                    <select id="delivery_order_for_item_id" class="form-control select2"
                                        name="delivery_order_for_item_id">
                                        <option selected disabled>Select For Item</option>
                                        @foreach ($deliveryOrderForItems as $deliveryOrderForItem)
                                            <option value="{{ $deliveryOrderForItem->id }}">
                                                {{ $deliveryOrderForItem->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="fabric_name">Fabric Name</label>
                                    <select id="fabric_name" name="fabric_name[]" class="form-control select2" multiple>
                                        <option value="" disabled>Select Fabric</option>
                                        @foreach($fabrics as $key => $fabric)
                                        <option value="{{ $fabric }}">{{ $fabric }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="unit_name">Unit Name:</label>
                                    <input type="text" id="unit_name" name="unit_name"
                                        class="form-control" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="rate">Rate:</label>
                                    <input type="text" id="rate" name="rate" class="form-control decimal_number">
                                </div>

                            </div>
                        </div>

                        <!-- Pending Sauda -->
                        <div class="form-group">
                            <label for="remarks">Remarks:</label>
                            <textarea id="remarks" name="remarks" rows="4" class="form-control"></textarea>
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
                    <div class="row" style="width: 100%;">
                        <div class="col-sm-4">
                            <h4>Sauda Order Entries</h4>
                        </div>
                        <div class="col-sm-4">
                            <a class="btn btn-success" href="{{ route('sauda.item.datewise.filter') }}"> Datewise
                                Sauda Order Report</a>
                        </div>
                        <div class="col-sm-4">
                            <a class="btn btn-success" href="{{ route('sauda.entry.datewise.filter') }}"> Datewise
                                Sauda Entry Report</a>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="myTable">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Sauda No</th>
                                <th>Sauda Date</th>
                                <th>Sauda For</th>
                                <th>Supplier</th>
                                <th>Account Name</th>
                                <th>For item</th>
                                <th>Fabric Name</th>
                                <th>Qty</th>
                                <th>Unit</th>
                                <th>Rate</th>
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
                    url: "{{ route('sauda-item.index') }}",
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
                        name: "sauda_no",
                        data: "sauda_no"
                    },
                    {
                        name: "sauda_date",
                        data: "sauda_date"
                    },
                    {
                        name: "sauda_for",
                        data: "sauda_for"
                    },
                    {
                        name: "supplier_id",
                        data: "supplier_id"
                    },
                    {
                        name: "acc_name",
                        data: "acc_name"
                    },
                    {
                        name: "delivery_order_for_item_id",
                        data: "delivery_order_for_item_id"
                    },
                    {
                        name: "fabric_name",
                        data: "fabric_name"
                    },
                    {
                        name: "qty",
                        data: "qty"
                    },
                    {
                        name: "unit_name",
                        data: "unit_name"
                    },
                    {
                        name: "rate",
                        data: "rate"
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

            $(document).ready(function () {
                $('#delivery_order_for_item_id').on('change', function () {
                    var selectedOptionText = $(this).find('option:selected').text().trim();
                    var unitNameInput = $('#unit_name');

                    if (['PP Woven', 'PP Non Woven', 'PP/HDPE Tripal', 'RP Granuels', 'PP/CC/Other Granuels', 'Wastage'].includes(selectedOptionText)) {
                        unitNameInput.val('Kgs');
                    } else if (['PP Bags (Unlam)', 'PP Bags (Lam)'].includes(selectedOptionText)) {
                        unitNameInput.val('Pcs');
                    }
                });
            });

        })

        function isValidDecimal(input) {
            const decimalPattern = /^\d+(\.\d+)?$/;
            return decimalPattern.test(input);
        }

        $(document).on('keyup', '.decimal_number', function() {
            const inputValue = $(this).val();
            if (isValidDecimal(inputValue)) {
                $(this).removeClass('invalid').addClass('valid');
            } else {
                $(this).removeClass('valid').addClass('invalid');
            }
        });
    </script>
@endsection
