@extends('layouts.admin')

@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
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
    <div class="card-body p-0 m-0">
        <form action="{{ route('salefinaltripal.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('Invoice No') }}<span class="required-field">*</span>
                    </label>
                    <input type="text" id="bill_no" class="form-control" value="{{ $fabricsaleentry->bill_no }}"
                        readonly />
                    <input type="hidden" name="salefinal_id" id="salefinal_id" value="{{ $entry_id }}">
                </div>

                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('Invoice Date') }}
                    </label>
                    <input type="text" id="bill_date" value="{{ $fabricsaleentry->bill_date }}"
                        class="form-control calculator" readonly>

                </div>
                <div class="col-md-3 form-group">
                    <label for="size" class="col-form-label">{{ __('PartyName ') }}
                    </label>
                    <input type="text" value="{{ $fabricsaleentry->getParty->name }}" class="form-control calculator"
                        readonly>

                </div>
                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('Bill For') }}
                    </label>
                    <input type="text" value="{{ $fabricsaleentry->bill_for }}" class="form-control calculator"
                        readonly>

                </div>
                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('Lorrynumber') }}
                    </label>
                    <input type="text" value="{{ $fabricsaleentry->lorry_no }}" class="form-control calculator"
                        readonly>

                </div>
                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('GP number') }}
                    </label>
                    <input type="text" value="{{ $fabricsaleentry->gp_no }}" class="form-control calculator" readonly>

                </div>

                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('DO number') }}
                    </label>
                    <input type="text" value="{{ $fabricsaleentry->do_no }}" class="form-control calculator" readonly>

                </div>
                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('Remarks') }}
                    </label>
                    <input type="text" value="{{ $fabricsaleentry->remarks }}" class="form-control calculator" readonly>

                </div>
                <div class="col-md-4 form-group">
                    <label for="size" class="col-form-label">{{ __('Fabric Name') }}<span
                            class="required-field">*</span>
                    </label>

                    <select class="advance-select-box form-control" id="finaltripalstock_id" name="finaltripalstock_id"
                        required>
                        <option value="">{{ __('Select Tripal Name') }}</option>
                        @foreach ($fabrics as $fabric)
                            <option value="{{ $fabric->id }}">{{ $fabric->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('fabric_name_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('Roll') }}<span class="required-field">*</span>
                    </label>
                    <input type="text" step="any" min="0" class="form-control calculator"
                        id="rollnumberfabric" data-number="1" name="roll_number" min="1" disabled required>

                    @error('fabric_name_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div>
                    <button type="submit" class="btn btn-sm btn-primary" style="margin-top:35px;">
                        Add
                    </button>
                </div>

            </div>

        </form>
    </div>
    <div class="row">
        <div class="table-responsive table-custom my-3">
            <table class="table table-hover table-striped" id="sameFabricsTable">
                <thead class="table-info">
                    <tr>
                        <th>{{ __('Sr.No') }}</th>
                        <th>{{ __('Fabric Name') }}</th>
                        <th>{{ __('Roll No') }}</th>
                        <th>{{ __('G.W') }}</th>
                        <th>{{ __('N.W') }}</th>
                        <th>{{ __('Meter') }}</th>
                        <th>{{ __('Avg') }}</th>
                        <th>{{ __('Gram') }}</th>
                        <th>{{ __('Send') }}</th>
                    </tr>
                </thead>
                <tbody id="same-fabrics"></tbody>
            </table>
        </div>
    </div>

    <hr>

    <div class="row">
        {{-- <input type="hidden" name="saletripal_id" value="{{$entry_id}}" id="saletripal_id"> --}}
        <div class="table-responsive table-custom my-3">
            <table class="table table-hover table-striped w-100" id="getSaleList">
                <thead class="table-info">
                    <tr>
                        <th>{{ __('Sr.No') }}</th>
                        <th>{{ __('Fabric Name') }}</th>
                        <th>{{ __('Roll No') }}</th>
                        <th>{{ __('G.W') }}</th>
                        <th>{{ __('N.W') }}</th>
                        <th>{{ __('Meter') }}</th>
                        <th>{{ __('Avg') }}</th>
                        <th>{{ __('Gram') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody id="fabric_sale_list"></tbody>
                <tfoot>
                    <tr>
                        <th>{{ __('Sr.No') }}</th>
                        <th>{{ __('Fabric Name') }}</th>
                        <th>{{ __('Roll No') }}</th>
                        <th>{{ __('G.W') }}</th>
                        <th>{{ __('N.W') }}</th>
                        <th>{{ __('Meter') }}</th>
                        <th>{{ __('Avg') }}</th>
                        <th>{{ __('Gram') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                    <tr>
                        <td colspan="9">
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <label for="">Net Weight</label>
                                    <input type="text" class="form-control  net_wt" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="">Gross Weight</label>
                                    <input type="text" class="form-control  gross_wt" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="">Meter</label>
                                    <input type="text" class="form-control  meter" readonly>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>SR No</th>
                        <th>Fabric Name</th>
                        <th>Roll No</th>
                        <th>G.W</th>
                        <th>N.W</th>
                        <th>Meter</th>
                        <th>Avg</th>
                        <th>Gram</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 0; @endphp
                    @foreach ($soldFabrics as $fabric)
                        <tr class="fabric_item_{{ $fabric->id }}">
                            <td>{{ ++$i }}</td>
                            <td>{{ $fabric->name }}</td>
                            <td>{{ $fabric->roll_no }}</td>
                            <td>{{ $fabric->gross_wt }}</td>
                            <td>{{ $fabric->net_wt }}</td>
                            <td>{{ $fabric->meter }}</td>
                            <td>{{ $fabric->average_wt }}</td>
                            <td>{{ $fabric->gram_wt }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger restore_item"
                                    data-fabric_id="{{ $fabric->id }}" data-entry_id="{{ $entry_id }}">Restore to
                                    fabric stock</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <hr>

    <button class="update-sale-final btn btn-primary">Update</button>

    <div id="modalRestore" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Are you sure you want to restore this item?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="entryId" >
                    <input type="hidden" id="fabricId" >
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="restoreNow">Restore</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- /.card-body -->
@endsection
@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script>
        $(function(){
            let sameFabricTable = null;
            let entry_id = {{ $entry_id }}
            let salesTable = null

            getSalesData()
            getsums()

            $("#finaltripalstock_id").change(function(e) {
                if (sameFabricTable !== null) {
                    sameFabricTable.destroy();
                }

                let fabric_name_id = $(this).val();
                sameFabricTable = $("#sameFabricsTable").DataTable({
                    serverside: true,
                    processing: true,
                    ajax: {
                        url: "{{ route('get.identical.fabric.details') }}",
                        method: "post",
                        data: function(data) {
                            data._token = $("meta[name='csrf-token']").attr("content"),
                                data.fabric_name_id = fabric_name_id
                        }
                    },
                    columns: [{
                            data: "DT_RowIndex",
                            name: "DT_RowIndex"
                        },
                        {
                            data: "name",
                            name: "name"
                        },
                        {
                            data: "roll_no",
                            name: "roll_no"
                        },
                        {
                            data: "gross_wt",
                            name: "gross_wt"
                        },
                        {
                            data: "net_wt",
                            name: "net_wt"
                        },
                        {
                            data: "meter",
                            name: "meter"
                        },
                        {
                            data: "average_wt",
                            name: "average_wt"
                        },
                        {
                            data: "gram_wt",
                            name: "gram_wt"
                        },
                        {
                            data: "action",
                            name: "action"
                        },
                    ]
                });
            })

            $(document).on('click', '.restore_item', function() {
                console.log('clicked');
                let fabric_id = $(this).data('fabric_id');
                let entry_id = $(this).data('entry_id');
                $('#modalRestore').modal('show');
                $('#entryId').val(entry_id);
                $('#fabricId').val(fabric_id);
            });

            $('#restoreNow').on('click',function(){
                let fabricId = $('#fabricId').val();
                let entryId = $('#entryId').val();
                $.ajax({
                    url: "{{ route('fabric.sale.restore') }}",
                    method: "POST",
                    data: {
                        "_token": $("meta[name='csrf-token']").attr("content"),
                        "fabric_id": fabricId,
                        "entry_id": entryId,
                    },
                    beforeSend: function() {
                        console.log("sending")
                    },
                    success: function(response) {
                        console.log(response)
                        if (response.status == true) {
                            $('#modalRestore').modal('hide');
                            alert(response.message);
                            $('.fabric_item_'+response.fabric_id).remove();
                        } else {
                            alert("something went wrong check console")
                        }
                    }
                });
            });


            $(document).on("click", ".send-to-lower", function(e) {
                e.preventDefault()
                let data_id = $(this).data('id')
                $.ajax({
                    url: "{{ route('fabric.sale.store') }}",
                    method: "post",
                    data: {
                        "_token": $("meta[name='csrf-token']").attr("content"),
                        "fabric_id": data_id,
                        "entry_id": entry_id
                    },
                    beforeSend: function() {
                        console.log("sending")
                    },
                    success: function(response) {
                        console.log(response)
                        getSalesData()
                        getsums()
                        salesTable.ajax.reload();
                        sameFabricTable.ajax.reload();
                    },
                    error: function(error) {
                        console.log("error", error);
                    }
                })
            })

            function getSalesData() {
                if (salesTable !== null) {
                    salesTable.destroy();
                }

                salesTable = $("#getSaleList").DataTable({
                    serverside: true,
                    processing: true,
                    lengthMenu: [
                        [5, 10, 25, 50, 100, 250, 500, -1],
                        [5, 10, 25, 50, 100, 250, 500, "All"]
                    ],
                    ajax: {
                        url: "{{ route('fabric.sale.get.list') }}",
                        method: "get",
                        data: function(data) {
                            data.entry_id = entry_id
                        }
                    },
                    columns: [{
                            data: "DT_RowIndex",
                            name: "DT_RowIndex"
                        },
                        {
                            data: "fabric_name",
                            name: "fabric_name"
                        },
                        {
                            data: "roll",
                            name: "roll"
                        },
                        {
                            data: "gross_wt",
                            name: "gross_wt"
                        },
                        {
                            data: "net_wt",
                            name: "net_wt"
                        },
                        {
                            data: "meter",
                            name: "meter"
                        },
                        {
                            data: "average_wt",
                            name: "average_wt"
                        },
                        {
                            data: "gram_wt",
                            name: "gram_wt"
                        },
                        {
                            data: "action",
                            name: "action"
                        },
                    ]
                });
            }

            $(document).on("click", ".delete-sale", function(e) {
                e.preventDefault()
                let id = $(this).data("id")
                $.ajax({
                    url: "{{ route('fabric.sale.delete') }}",
                    method: "post",
                    data: {
                        "_token": $("meta[name='csrf-token']").attr('content'),
                        "id": id
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.status == "200") {
                            salesTable.ajax.reload();
                            sameFabricTable.ajax.reload();
                            getsums()
                        } else {
                            alert("something went wrong check console")
                        }
                    },
                    error: function(error) {
                        console.log(error)
                    }
                })
            });

            $(".update-sale-final").click(function(e) {
                e.preventDefault()
                $.ajax({
                    url: "{{ route('fabric.sale.final.update') }}",
                    method: "post",
                    data: {
                        "_token": $("meta[name='csrf-token']").attr("content"),
                        "id": entry_id
                    },
                    success: function(response) {
                        console.log(response)
                        if (response.status == "200") {
                            location.href = '{{ route('fabric.sale.entry.index') }}'
                        }
                    },
                    error: function(error) {
                        console.log(error)
                    }
                })
            });

            function getsums() {
                $.ajax({
                    url: "{{ route('fabric.sale.index.ajax.sums') }}",
                    method: "get",
                    data: {
                        "entry_id": entry_id
                    },
                    beforeSend: function() {
                        console.log("sending")
                    },
                    success: function(response) {
                        $(".net_wt").val(response.net_wt)
                        $(".gross_wt").val(response.gross_wt)
                        $(".meter").val(response.meter)
                    },
                    error: function(error) {
                        console.log(error)
                    }
                })
            }

        });

    </script>
@endsection
