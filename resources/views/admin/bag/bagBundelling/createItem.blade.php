@extends('layouts.admin')
@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div id="error_msg" class="alert alert-danger mt-2" hidden>

    </div>
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Bag Bundelling') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Bag Bundelling') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    {{-- <div id="successMsgDisplayContainer" class="alert alert-success"> --}}
    <div id="success_msg" class="alert alert-success mt-2" hidden>

    </div>
    {{-- <input type="text" value="{{ $data->id }}"> --}}
    {{-- </div> --}}
    <div class="content">
        <div class="container-fluid">
            <a class='btn btn-primary go-back float-right'>Go back</a>
            <br><br>
            <div class="form">
                <form action="{{ route('bagBundelItem.store') }}" id="bagBundelItemStore">
                    <div class="row">
                        <input type="text" name="bag_bundel_entry_id" value="{{ $bagBundelEntry->id }}" hidden>
                        <div class="col-md-2">
                            <label for="receipt_number">Receipt Number</label>
                            <input type="text" class="form-control" id='receiptNumber'
                                value="{{ $bagBundelEntry->receipt_no }}" name='receipt_number' readonly>
                        </div>
                        <div class="col-md-2">
                            <label for="Date NP">Receipt Date</label>
                            <input type="text" class="form-control" id='receiptDate'
                                value="{{ $bagBundelEntry->receipt_date }}" name='receipt_date' readonly>
                        </div>
                        <div class="col-md-2">
                            <label for="Date NP">Nepali Date</label>
                            <input type="text" class="form-control" id='nepaliDate'
                                value="{{ $bagBundelEntry->nepali_date }}" name='nepali_date' readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="cut_length">Group</label>
                            <select class="advance-select-box form-control  @error('godam_id') is-invalid @enderror"
                                id="groupId" name="group_id" required>
                                <option value=" " selected disabled>{{ __('Select group') }}</option>
                                @foreach ($groups as $groupD)
                                    <option value="{{ $groupD->id }}">
                                        {{ $groupD->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="Fabric">Brand Bag</label>
                            <select name="brand_bag_id" class="form-control advance-select-box" id="brandBagId">
                                <option value="" selected disabled>Select Brand Bag</option>
                            </select>
                        </div>

                    </div>
                    <div class="row m-1">
                        <div class="col-md-2">
                            <label for="available_stock">Available Stock</label>
                            <input type="text" class="form-control" id="availableStock" name="available_stock" readonly>
                        </div>
                        <div class="col-md-2">
                            <label for="gross_weight">Qty in Kg</label>
                            <input type="text" class="form-control" id="qtyInKg" name="quantity_in_kg">
                        </div>
                        <div class="col-md-2">
                            <label for="gross_weight">Quantity in pcs</label>
                            <input type="text" class="form-control" id="quantityInPcs" name="quantity_in_pcs">
                        </div>
                        <div class="col-md-2">
                            <label for="gross_weight">Avg Weight</label>
                            <input type="text" class="form-control" id="avgWeight" name="avg_weight">
                        </div>
                        <div class="col-md-2">
                            <label for="gross_weight">Bundel No</label>
                            <input type="text" class="form-control" id="bundelNo" name="bundel_no"
                                value="{{ $bundleNo }}">
                        </div>
                        <div class="col-md-2 mt-4">
                            <button class="btn btn-primary">Add</button>
                        </div>
                    </div>
            </div>

            </form>
            <div class="table-custom table-responsive">
                <table class="table table-hover table-bordered" id="bagBundellingTable">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Brand Group</th>
                            <th>Bag Brand</th>
                            <th>Quantity Kg</th>
                            <th>Quantity Piece</th>
                            <th>Average</th>
                            <th>Bundle No</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="bagBundellingData">
                        @php
                            $i = 0;
                            $totalKg = 0;
                            $toalPcs = 0;
                        @endphp
                        @foreach ($bagBundelItems as $item)
                            <tr id="editRow-1">
                                <td>{{ ++$i }}</td>
                                <td class="rowGroupName">{{ $item->group->name }}</td>
                                <td class="rowBrandBagName">{{ $item->bagBrand->name }}</td>
                                <td class="rowQuantity_piece">{{ $item->qty_in_kg }}</td>
                                <td class="rowAverage">{{ $item->qty_pcs }}</td>
                                <td class="rowWastage">{{ $item->average_weight }}</td>
                                <td class="rowRollno">{{ $item->bundel_no }}</td>
                                <td>
                                    <button class="btn btn-danger dltBagBundelItem" data-id="{{ $item->id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                            @php
                                $totalKg += $item->qty_in_kg;
                                $toalPcs += $item->qty_pcs;
                            @endphp
                        @endforeach
                        <tr style="font-weight: 600;">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{ $totalKg }}</td>
                            <td>{{ $toalPcs }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <div class="row mx-1 mt-3">
                <button class="btn btn-success" id="updateBagBundellingEntry">Update</button>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>

    <script>
        //get table data when page refresh
        refresh();

        async function refresh() {
            // await getBagBundellingItemData();
            deleteEventBtn();
        }
        @if (session()->has('message'))
            toastr.success("{{ session()->get('message') }}");
        @elseif (session()->has('message_err'))

            toastr.error("{{ session()->get('message_err') }}");
        @endif

        $('#groupId').on('select2:select', function(e) {
            let group_id = e.params.data.id;
            getBagBrand(group_id);
        });

        $('#brandBagId').on('select2:select', function(e) {
            let brand_bag_id = e.params.data.id;
            let group_id = document.getElementById('groupId').value
            $("#availableStock").val("");
            getAvailableStock(brand_bag_id, group_id);
        });
        // calcaulateAvgWeight();
        let quantityInKg = document.getElementById('qtyInKg');
        let quantityInPcs = document.getElementById('quantityInPcs');
        let avgWeight = document.getElementById('avgWeight');
        quantityInKg.addEventListener('input', averageWeight);
        quantityInPcs.addEventListener('input', averageWeight);

        function averageWeight() {
            let valueInKg = parseFloat(quantityInKg.value.trim());
            let valueInPcs = parseFloat(quantityInPcs.value.trim());
            if (!isNaN(valueInKg) && !isNaN(valueInPcs)) {
                avgWeight.value = (valueInKg / valueInPcs) * 1000;
            } else {
                averageWeight.value = ''; // Clear the average weight field if input values are not valid numbers
            }
        }
        document.getElementById("updateBagBundellingEntry").addEventListener('click', function() {
            let bagBundellingEntry_id = {!! json_encode($bagBundelEntry->id) !!}
            let receipt_number = document.getElementById("receiptNumber").value;
            $.ajax({
                url: "{{ route('bagBundelling.saveEntireBagBundelling') }}",
                method: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    bagBundellingEntry_id: bagBundellingEntry_id,
                },
                success: function(response) {

                    window.location.href = "{{ route('bagBundelling.index') }}";
                },
                error: function(xhr, status, error) {
                    alert(xhr.responseJSON.message);
                    // setErrorMsg(xhr.responseJSON.message);
                }
            });
        });

        function setSuccessMessage(message) {
            let successContainer = document.getElementById('success_msg');
            //console.log(successContainer);
            successContainer.hidden = false;
            successContainer.innerHTML = message;
            setTimeout(function() {
                successContainer.hidden = true;
            }, 2000); // 5000 milliseconds = 5 seconds
        }

        $('#bagBundelItemStore').on('submit', function(e) {
            e.preventDefault();
            let url = $(this).attr('action');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    console.log('ajax fired');
                },
                success: function(data) {
                    if (data.status == true) {
                        $('#bagBundellingData').empty();
                        $('#bagBundellingData').html(data.view);
                        $('#bundelNo').val(data.newNumber);
                        $('#qtyInKg').val("");
                        $('#quantityInPcs').val("");
                        $('#avgWeight').val("");
                        $('#availableStock').val(data.available_stock);
                    } else {
                        swal.fire("Failed!", "Bundle item Addition failed!", "error");
                    }
                },
                error: function(xhr) {
                    var i = 0;
                    $('.help-block').remove();
                    $('.has-error').removeClass('has-error');
                    for (var error in xhr.responseJSON.errors) {
                        $('#add_' + error).removeClass('has-error');
                        $('#add_' + error).addClass('has-error');
                        $('#error_' + error).html(
                            '<span class="help-block ' + error + '">*' + xhr
                            .responseJSON.errors[
                                error] + '</span>');
                        i++;
                    }
                }
            });
        });

        //clear input fields
        function clearInputFields() {
            document.getElementById('qtyInKg').value = "";
            document.getElementById('quantityInPcs').value = "";
            document.getElementById('avgWeight').value = "";
            // document.getElementById('bundlePcs').value = "";
            $('#groupId').val($('#groupId option:first').val()).change();
            $('#brandBagId').val($('#brandBagId option:first').val()).change();
        }

        function getAvailableStock(brand_bag_id, group_id) {
            $.ajax({
                url: "{{ route('bagBundelItem.getAvailableStock') }}",
                method: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    brand_bag_id: brand_bag_id,
                    group_id: group_id,
                },
                success: function(response) {
                    document.getElementById('availableStock').value = response.availableStock
                        .quantity_piece;
                },
                error: function(xhr, status, error) {
                    setErrorMsg(xhr.responseJSON.message);
                }
            });
        }

        //delete bagBundelItem
        function deleteEventBtn() {

            let deleteButtons = document.getElementsByClassName('dltBagBundelItem');
            for (let i = 0; i < deleteButtons.length; i++) {
                deleteButtons[i].addEventListener('click', function(event) {
                    let bagBundelingItemId = this.getAttribute('data-id');

                    new swal({
                        title: "Are you sure?",
                        text: "Do you want to delete Item.",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                        showCancelButton: true,
                        cancelButtonText: 'Cancel',
                        closeOnClickOutside: false,
                    }).then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url: '{{ route('bagBundelItem.deleteBagBundelItem', ['bagBundelItemId' => ':id']) }}'
                                    .replace(':id', bagBundelingItemId),
                                type: "DELETE",
                                data: {
                                    "_method": "DELETE",
                                    "_token": "{{ csrf_token() }}",
                                },
                                success: function(response) {
                                    removeAllTableRows('bagBundellingTable');
                                    refresh();
                                    // updateBundleNo();
                                },
                                error: function(result) {
                                    new swal({
                                        title: "Error",
                                        text: "something went wrong",
                                        type: 'error',
                                        timer: '1500'
                                    });
                                }
                            });
                        }
                    });

                });

            }
        }

        function removeAllTableRows(tableId) {
            // Resetting SN
            sn = 1;
            let tableTbody = document.querySelector("#" + tableId + " tbody");
            //let tableTbody = tableId.querySelector("tbody");
            if (tableTbody) {
                for (var i = tableTbody.rows.length - 1; i >= 0; i--) {
                    tableTbody.deleteRow(i);
                }
            }
        }

        function getBagBrand(group_id) {
            $.ajax({

                url: "{{ route('bundelling.getBrandBag') }}",
                method: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    group_id: group_id,
                },
                success: function(response) {
                    $('#danaGroupId').prepend(
                        "<option value='' disabled selected>Select required data</option>");
                    response.brandBags.forEach(function(item) {
                        setOptionInSelect('brandBagId', item.id, item.name);
                    });
                },
                error: function(xhr, status, error) {
                    setErrorMsg(xhr.responseJSON.message);
                }
            });
        }

        function setOptionInSelect(elementId, optionId, optionText) {
            let selectElement = $('#' + elementId);
            let newOption = $('<option>');
            newOption.val(optionId).text(optionText);
            selectElement.append(newOption);
            selectElement.trigger('change.select2');

        }

        function setErrorMsg(errorMessage) {
            let errorContainer = document.getElementById('error_msg');
            errorContainer.hidden = false;
            errorContainer.innerHTML = errorMessage;
            setTimeout(function() {
                errorContainer.hidden = true;
            }, 5000);
        }


        function calculateBundles(quantity_Pcs_cal, bundle_pcs_cal) {
            try {
                let bundles = Math.floor(quantity_Pcs_cal / bundle_pcs_cal);
                let remainingPieces = quantity_Pcs_cal % bundle_pcs_cal;
                if (remainingPieces > 0) {
                    throw new Error(`Cannot create complete bundles. Remaining pieces: ${remainingPieces}`);
                }
                return true;
            } catch (error) {
                setErrorMsg(error.message);
                return false;
            }
        }
    </script>
@endsection
