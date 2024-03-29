@extends('layouts.admin')

@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/nepaliDatePicker/nepali.datepicker.v4.0.1.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .col-form-label {
            font-size: 12px !important;

        }

        .dynamic-btn {
            height: 18px;
            width: 4px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #storeinSubmitBtn {
            height: 25px;
            width: 70px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 5px !important;
        }

        .fa-plus {
            font-size: 10px;
        }

        .form-control {
            font-size: 12px !important;

        }

        .select2-selection__rendered,
        .select2-container--bootstrap4 .select2-selection {
            font-size: 12px !important;
            display: flex !important;
            align-items: center !important;
            height: calc(1.6em + 0.75rem + 2px) !important;
        }

        .select2-container {
            height: calc(1.6em + 0.75rem + 2px) !important;
        }

        .taxStyle .select2-selection {
            width: 200px !important;
        }

        .form-group {
            margin-bottom: 0px !important;
        }

        .content-wrapper {
            padding-top: 0px !important;
        }

        .card-body {
            padding: 0px 5px !important;
        }

        .card {
            padding: 0px 5px !important;
        }

        .col-md-6 {
            padding: 0px 2px !important;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <button id="doModalOpen" type="button" class="float-right btn btn-primary">View all Approved DO Numbers</button>
        </div>
    </div>
    @if ($errors->any())
        <div class="card-header">
            @foreach ($errors->all() as $error)
                <div class="alert alert-light text-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    {!! $error . '<br>' !!}
                </div>
            @endforeach
        </div>
    @endif
    <div class="card-body p-0 m-0">
        <form action="{{ route('fabric.sale.entry.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-3 form-group">
                    <label for="size" class="col-form-label">{{ __('Invoice No') }}<span class="required-field">*</span>
                    </label>
                    <input type="text" class="form-control" id="billnumber" name="bill_number" />

                    @error('bill_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-3 form-group">
                    <label for="size" class="col-form-label">{{ __('Invoice Date') }}
                    </label>
                    <input type="text" step="any" min="0" class="form-control calculator" id="billDate"
                        data-number="1" name="bill_date" placeholder="{{ __('Remarks') }}" min="1">

                    @error('bill_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6 form-group">
                    <label for="size" class="col-form-label">{{ __('PartyName') }}
                    </label>
                    <select class="advance-select-box form-control" id="partyname" name="partyname">
                        <option value="" selected disabled>{{ __('Select PartyName') }}</option>
                        @foreach ($partyname as $party)
                            <option value="{{ $party->id }}">{{ $party->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('partyname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('BillFor') }}
                    </label>
                    <select class="advance-select-box form-control" id="billfor" name="bill_for">
                        <option value="" selected disabled>{{ __('Select BillFor') }}</option>
                        <option value="local">Local</option>
                        <option value="export">Export</option>
                    </select>
                    @error('bill_for')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('Lorry No') }}<span class="required-field">*</span>
                    </label>
                    <input type="text" class="form-control" id="lory_number" name="lory_number" required />
                </div>
                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('Dp No') }}<span class="required-field">*</span>
                    </label>
                    <input type="text" class="form-control" id="dp_number" name="dp_number" required />
                </div>
                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('GP No') }}<span class="required-field">*</span>
                    </label>
                    <input type="text" class="form-control" id="gp_number" name="gp_number" required />
                </div>
                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('Remarks') }}<span class="required-field">*</span>
                    </label>
                    <input type="text" class="form-control" id="remarks" name="remarks" required />
                </div>
                <div>
                    <button type="submit" class="btn btn-sm btn-primary" style="margin-top:35px;">
                        Add
                    </button>
                </div>

            </div>

        </form>
    </div>
    <div class="p-0 table-responsive table-custom my-3">
        <table class="table table-hover table-bordered w-100">
            <thead>
                <tr>
                    <th>@lang('#')</th>
                    <th>{{ __('Invoice No') }}</th>
                    <th>{{ __('Invoice Date') }}</th>
                    <th>{{ __('Supplier') }}</th>
                    <th>{{ __('Total Net wt') }}</th>
                    <th class="text-right">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <!-- /.card-body -->

    <div class="modal fade bd-example-modal-lg" id="deliveryOrderModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Approved Delivery Orders</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="deliveryorders" class="modal-body">

                </div>
            </div>
        </div>
    </div>



@endsection
@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/storein.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/nepaliDatePicker/nepali.datepicker.v4.0.1.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
            $('#billDate').val(currentDate);
            $('#billDate').nepaliDatePicker({
                ndpYear: true,
                ndpMonth: true,
                disableAfter: currentDate,
            });

        });

        $(".table").DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ route('fabric.sale.entry.index.ajax') }}",
            },
            columns: [{
                    data: "DT_RowIndex",
                    name: "DT_RowIndex",
                    orderable: false,
                    searchable: false,
                },
                {
                    data: "bill_no",
                    name: "bill_no"
                },
                {
                    data: "bill_date",
                    name: "bill_date"
                },
                {
                    data: "supplier",
                    name: "supplier"
                },
                {
                    data: "net_wt",
                    name: "net_wt"
                },
                {
                    data: "action",
                    name: "action"
                },
            ]
        })

        $(document).on("click", ".create-sale", function(e) {
            e.preventDefault()
            location.href = "{{ route('fabric.sale.create', ['entry_id' => ':entry_id']) }}".replace(":entry_id", $(
                this).data("id"))
        })

        $('#doModalOpen').on('click',function(){

            $.ajax({
                    url: "{{ route('delivery-order.approved') }}",
                    method: "GET",
                    data: {
                        "_token": $("meta[name='csrf-token']").attr("content"),
                    },
                    success: function(response) {
                        $('#deliveryorders').html(response.data);
                    },
                    error: function(error) {
                        console.log(error)
                    }
                });

            $('#deliveryOrderModal').modal('show');

        });

        $(document).on('click','.copy_do_no',function(){

            let do_no = $(this).data('do_no');
            navigator.clipboard.writeText(do_no);

            alert('Copied DO number');

        });

        $(document).on('click','.close',function(){
            $('#deliveryOrderModal').modal('hide');
        });
    </script>
@endsection
