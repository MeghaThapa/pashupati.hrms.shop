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

        */
    </style>
@endsection
@section('content')
    {{-- message for success --}}
    <div class="row">
        <div id="RawMaterialItemsSaleError" class="alert alert-danger" hidden></div>
        <div id="success_msg" class="alert alert-success mt-2" hidden>
        </div>
    </div>
    <div style="display: flex; flex-direction:column;margin:10px;">
        <div style="display: flex; justify-content:space-between;">
            <p>Date : {{ $rawMaterialSalesEntry->bill_date }}</p>
            <p>Do No : {{ $rawMaterialSalesEntry->gp_no }}</p>
            <p>Supplier :{{ $rawMaterialSalesEntry->supplier->name }} </p>
            <p>Godam:{{ $rawMaterialSalesEntry->godam->name }}</p>
            {{-- <p>From Godam : {{ $rawMaterial->fromGodam ? $rawMaterial->fromGodam->name : 'EMPTY' }}</p>
            <p>To Godam : {{ $rawMaterial->toGodam ? $rawMaterial->toGodam->name : 'EMPTY' }}</p> --}}

        </div>
        <div style="display: flex; justify-content:space-between;">
            <p>GP No :{{ $rawMaterialSalesEntry->gp_no }} </p>
            <p>Bill no :{{ $rawMaterialSalesEntry->bill_no }} </p>
            <p>Through : {{ $rawMaterialSalesEntry->through }} </p>
            <p>Do No : {{ $rawMaterialSalesEntry->do_no }}</p>

        </div>
    </div>

    <div class="card-body p-0 m-0">
        <form id="createRawMaterialItemSales">
            @csrf

            <div class="row">

                <div class="col-md-2 form-group">
                    {{-- <input type="text" step="any" min="0" class="form-control calculator" id="rawMaterialId" --}}
                    {{-- data-number="1" name="rawMaterial_id" min="1" required hidden> --}}
                    <label for="size" class="col-form-label">{{ __('Lorry No') }}<span class="required-field">*</span>
                    </label>
                    <input type="text" step="any" min="0" class="form-control calculator" id="lorryNo"
                        data-number="1" name="lorry_no" placeholder="{{ __('Lorry No') }}" min="1" required>
                </div>

                <div class="col-md-3 form-group">
                    <label for="size" class="col-form-label">{{ __('Dana Group') }}
                    </label>
                    <select class="advance-select-box form-control" id="danaGroup" name="dana_group" required>

                        @if ($danaGroups->isEmpty())
                            <option value="" disabled>No items found</option>
                        @else
                            <option value="" selected disabled>{{ __('Select dana Group') }}</option>
                            @foreach ($danaGroups as $danaGroup)
                                <option value="{{ $danaGroup->id }}">{{ $danaGroup->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label for="size" class="col-form-label">{{ __('Dana Name') }}
                    </label>
                    <select class="advance-select-box form-control" id="danaName" name="dana_name_id" required>
                        <option value="" selected disabled>{{ __('Select dana Name') }}</option>
                    </select>
                </div>
                {{-- @if ($rawMaterial && $rawMaterial->from_godam_id) --}}
                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('Stock Qty') }}
                    </label>
                    <input type="text" step="any" min="0" class="form-control calculator" id="stockQty"
                        data-number="1" name="stock_qty" placeholder="{{ __('Stock QTY') }}" min="1" readonly>
                </div>
                {{-- @endif --}}
                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('Qty in Kg') }}
                    </label>
                    <input type="text" step="any" min="0" class="form-control calculator" id="quantityInKg"
                        data-number="1" name="quantity_in_kg" placeholder="{{ __('Remarks') }}" min="1" required>
                </div>
                <div>
                    <button type="submit" class="btn btn-sm btn-primary" style="margin-top:35px;">
                        Add
                    </button>
                </div>

            </div>
        </form>
    </div>
    {{-- <button class="btn btn-primary" data-toggle="modal" data-target="#tryModel">Open Modal</button> --}}

    <div class="row">
        <div class="Ajaxdata col-md-12">
            <div class="p-0 table-responsive table-custom my-3">
                <table class="table" id="rawMaterialItemSalesTable">
                    <thead>
                        <tr>
                            <th>{{ __('S.No') }}</th>
                            <th>{{ __('Dana Group') }}</th>
                            <th>{{ __('Dana Name') }}</th>
                            <th>{{ __('Quantity In Kg') }} </th>
                            <th>{{ __('Lorry No') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>

                    <tbody id="rawMaterialItemTbody">
                    </tbody>

                </table>

                {{-- for sub total --}}
                {{-- <input type="text" id="subTotal"> --}}
            </div>

        </div>
    </div>
    <div class="row">
        {{-- <a href="{{ route('rawMaterial.saveEntireRawMaterialSales', ['rawMaterial_id' => $rawMaterial->id]) }}"> --}}
        <button class="btn btn-info" type="submit" id="saveEntireRawMaterialSales">Save</button>
        {{-- </a> --}}
    </div>
@endsection
@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/storein.js') }}"></script>
    <script>
        $(document).ready(function() {
            getrawMaterialSalesData();

            function checkRowInTable() {
                console.log('hit')
                let tableTbody = document.querySelector("#rawMaterialItemSalesTable tbody");
                let saveBtn = document.getElementById('saveEntireRawMaterialSales');
                if (tableTbody.rows.length <= 0) {
                    saveBtn.disabled = true;
                } else {
                    saveBtn.disabled = false;
                }
            }

            $('#danaGroup').on('select2:select', function(e) {
                let danaGroup_id = e.target.value;
                let godam_id = JSON.parse(`{!! json_encode($rawMaterialSalesEntry->godam_id) !!}`);
                getDanaName(danaGroup_id, godam_id);
            });

            function getDanaName(danaGroup_id, godam_id) {
                return new Promise(function(resolve, reject) {
                    $.ajax({
                        url: "{{ route('rawMaterialItemsSalesEntry.getDanaGroupDanaName') }}",
                        data: {
                            'dana_group_id': danaGroup_id,
                            'godam_id': godam_id,
                        },
                        method: 'GET',
                        success: function(response) {
                            let selectOptions = '';
                            if (response.length == 0) {
                                selectOptions +=
                                    '<option disabled selected>' +
                                    'no items found' + '</option>';
                            } else {
                                selectOptions +=
                                    '<option disabled selected>' +
                                    'select an item' + '</option>';
                                for (var i = 0; i < response.length; i++) {
                                    selectOptions += '<option value="' +
                                        response[i].id +
                                        '">' +
                                        response[i].name + '</option>';
                                }
                            }
                            $('#danaName').html(selectOptions);
                            resolve(response);
                        },
                        error: function(xhr, status, error) {
                            reject(error);
                        }
                    });
                });
            }
            $('#danaName').on('select2:select', function(e) {
                let danaName_id = e.target.value;
                let danaGroup_id = $('#danaGroup').val();
                let godam_id = JSON.parse(`{!! json_encode($rawMaterialSalesEntry->godam_id) !!}`);
                getDanaNameStockQty(danaName_id, danaGroup_id, godam_id);
            });

            function getDanaNameStockQty(danaName_id, danaGroup_id, godam_id) {
                $.ajax({
                    url: "{{ route('rawMaterialItemsSalesEntry.getDanaStockQty') }}",
                    data: {
                        'dana_group_id': danaGroup_id,
                        'danaName_id': danaName_id,
                        'godam_id': godam_id,
                    },
                    method: 'GET',
                    success: function(response) {
                        console.log('qty', response);
                        $('#stockQty').val(response.quantity);
                    },
                    error: function(xhr, status, error) {
                        reject(error);
                    }
                });
            }
            $('#createRawMaterialItemSales').on('submit', function(e) {
                e.preventDefault();
                const form = event.target;
                let rawMaterialSalesEntry_id = JSON.parse(`{!! json_encode($rawMaterialSalesEntry->id) !!}`);
                let lorry_no = form.elements['lorry_no'];
                let dana_group_id = form.elements['dana_group'];
                let dana_name_id = form.elements['dana_name_id'];
                let quantity_in_kg = form.elements['quantity_in_kg'];
                $.ajax({
                    url: "{{ route('rawMaterialItemsSalesEntry.store') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        rawMaterialSalesEntry_id: rawMaterialSalesEntry_id,
                        lorry_no: lorry_no.value,
                        dana_group_id: dana_group_id.value,
                        dana_name_id: dana_name_id.value,
                        quantity_in_kg: quantity_in_kg.value,
                    },
                    success: function(response) {
                        setMessage('success_msg', 'succefully added another sales item');
                        $("#rawMaterialItemTbody").empty();
                        getrawMaterialSalesData();
                        checkRowInTable();
                        // clearInputFields();
                        // setIntoTable(response.rawMaterialItem);
                        // calculateTotalQuantity();
                    },
                    error: function(xhr) {
                        setMessage('RawMaterialItemsSaleError', xhr.responseJSON.message)

                        //console.log(xhr.responseJSON.message);
                    }
                });

            });
            $('#saveEntireRawMaterialSales').on('click', function(e) {
                let rawmaterial_sales_entry_id = {!! $rawMaterialSalesEntry->id !!}
                $.ajax({
                    url: "{{ route('rawMaterialSalesEntry.saveEntire') }}",
                    method: 'post',
                    data: {
                        _token: "{{ csrf_token() }}",
                        rawmaterial_sales_entry_id: rawmaterial_sales_entry_id
                    },
                    success: function(response) {
                        window.location.href =
                            "{{ route('rawMaterialSalesEntry.index') }}";

                        // rawMaterialSalesData(response);
                        // document.getElementById('totalLamMeter').value = response.totalMeter;
                        // document.getElementById('totalLamNetWt').value = (response.totalNetWt).toFixed(
                        //     2);
                    },
                    error: function(error) {
                        // Handle the error if the AJAX request fails
                        console.error(error);
                    }
                });
            });

            function getrawMaterialSalesData() {
                let rawmaterial_sales_entry_id = {!! $rawMaterialSalesEntry->id !!}
                $.ajax({
                    url: "{{ route('rawMaterialItemsSalesEntry.getSalesData') }}",
                    method: 'get',
                    data: {
                        rawmaterial_sales_entry_id: rawmaterial_sales_entry_id
                    },
                    success: function(response) {
                        rawMaterialSalesData(response);
                        checkRowInTable();

                    },
                    error: function(error) {
                        // Handle the error if the AJAX request fails
                        console.error(error);
                    }
                });
            }

            function rawMaterialSalesData(data) {
                data.forEach(d => {
                    insertDataOfrawMaterialSalesIntoTable(d)
                });
            }

            function insertDataOfrawMaterialSalesIntoTable(d) {
                let tr = $("<tr></tr>").appendTo('#rawMaterialItemSalesTable');

                tr.append(`<td>#</td>`);
                tr.append(`<td>${d.dana_group.name}</td>`);
                tr.append(`<td>${d.dana_name.name}</td>`);
                tr.append(`<td>${d.qty_in_kg}</td>`);
                tr.append(`<td>${d.lorry_no}</td>`);
                tr.append(
                    `<td><div class="btn-group"><a id="deleteRawMaterialSales" class="btn btn-danger" data-id="${d.id}">delete</a></div></td>`
                );
            }

            $(document).on('click', "#deleteRawMaterialSales", function(e) {
                e.preventDefault();
                let salesItem_id = this.getAttribute('data-id');
                new swal({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'No, cancel!',
                        reverseButtons: true
                    })
                    .then((willDelete) => {
                        if (willDelete.isConfirmed == true) {
                            $.ajax({
                                url: '{{ route('rawMaterialItemsSalesEntry.delete', ['salesItem_id' => ':lol']) }}'
                                    .replace(':lol', salesItem_id),
                                type: "DELETE",
                                data: {
                                    "_method": "DELETE",
                                    "_token": "{{ csrf_token() }}",
                                },
                                success: function(result) {
                                    $("#rawMaterialItemTbody").empty();
                                    getrawMaterialSalesData();
                                    checkRowInTable();
                                    new swal({
                                        icon: 'success',
                                        title: 'item deleted successfully',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    // checkRowInTable();
                                },
                                error: function(result) {
                                    new swal({
                                        position: 'top-end',
                                        icon: 'danger',
                                        title: 'Something Went Wrong',
                                        showConfirmButton: false,
                                        timer: 1000
                                    });
                                }
                            });
                        }
                    });

            });

            function setMessage(element_id, message) {
                let errorContainer = document.getElementById(element_id);
                let successContainer = document.getElementById(element_id);
                errorContainer.hidden = false;
                errorContainer.innerHTML = message;
                setTimeout(function() {
                    errorContainer.hidden = true;
                }, 2000);
            }
        });




        function checkRowInTable() {
            let tableTbody = document.querySelector("#rawMaterialItemSalesTable tbody");
            let saveEntireRMSales = document.getElementById('saveEntireRawMaterialSales');
            if (tableTbody.rows.length <= 0) {
                saveEntireRMSales.disabled = true;
            } else {
                saveEntireRMSales.enable = true;
            }
        }
    </script>
@endsection
