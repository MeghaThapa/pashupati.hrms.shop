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
            margin-top: 38px !important;
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

        /* .select2-selection {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                width:150px !important;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            } */
    </style>
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div id="error_msg" class="alert alert-danger mt-2" hidden>

    </div>
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Bag Selling') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Bag Selling') }}</li>
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
        <div class="row" style="float:right">
            <a class='btn btn-primary go-back float-right'href="{{ route('bagSelling.index') }}">Go back</a>
        </div>
        <br>
        <div class=" p-0 m-0">
            <form action="" id="bagSellingItemStore">
                <div class="row ">
                    <input type="text" name="bag_selling_entry_id" value="{{ $bagSellingEntry->id }}" hidden>
                    <div class="col-md-2">
                        <label for="receipt_number">Challan Number</label>
                        <input type="text" class="form-control" value="{{ $bagSellingEntry->challan_no }}"
                            id='challanNumber' name='challan_number' readonly>
                    </div>
                    <div class="col-md-2">
                        <label for="Date NP"> Date</label>
                        <input type="text" class="form-control" value="{{ $bagSellingEntry->date }}" id='date'
                            name='date' readonly>
                    </div>
                    <div class="col-md-2">
                        <label for="Date NP">Nepali Date</label>
                        <input type="text" class="form-control" value="{{ $bagSellingEntry->nepali_date }}"
                            id='nepaliDate' name='nepali_date' readonly>
                    </div>
                    <div class="col-md-2">
                        <label for="Fabric">Supplier</label>
                        <input type="text" class="form-control" value="{{ $bagSellingEntry->supplier->name }}"
                            id='nepaliDate' name='nepali_date' readonly>
                    </div>
                    <div class="col-md-2">
                        <label for="Fabric">Gp No</label>
                        <input type="text" class="form-control" value="{{ $bagSellingEntry->gp_no }}" id='gpNo'
                            name='gp_no' readonly>
                    </div>
                    <div class="col-md-2">
                        <label for="Fabric">Lorry No</label>
                        <input type="text" class="form-control" value="{{ $bagSellingEntry->lorry_no }}" id='lorryNo'
                            name='lorry_no' readonly>
                    </div>
                </div>
                <div class="row m-1">
                    <div class="col-md-3">
                        <label for="cut_length">Do No</label>
                        <input type="text" class="form-control" value="{{ $bagSellingEntry->do_no }}" id='doNo'
                            name='do_no' readonly>
                    </div>
                    <div class="col-md-2">
                        <label for="available_stock">Rem</label>
                        <input type="text" class="form-control" value="{{ $bagSellingEntry->rem }}" id="rem"
                            name="rem" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="gross_weight">Group</label>
                        <select class="advance-select-box form-control" id="group" name="group_id" required>
                            <option value="" selected disabled>{{ __('Select a group') }}</option>
                            @foreach ($groups as $groupData)
                                <option value="{{ $groupData->group->id }}">{{ $groupData->group->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="gross_weight">Brand Bag</label>
                        <select class="advance-select-box form-control" id="brandBag" name="brand_bag_id" required>
                            <option value=" " selected disabled>{{ __('Select bag brand') }}</option>
                            {{-- @foreach ($categories as $key => $category)
                                <option value="{{ $category->id }}"
                                    {{ old('categoryName') == $category->id ? 'selected' : '' }}>{{ $category->name }}
                                </option>
                            @endforeach --}}
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="gross_weight">Bundel No</label>
                        <select class="advance-select-box form-control" id="bundelNo" name="bundel_no" required>
                            <option value=" " selected disabled>{{ __('Select bag brand') }}</option>
                            {{-- @foreach ($categories as $key => $category)
                                <option value="{{ $category->id }}"
                                    {{ old('categoryName') == $category->id ? 'selected' : '' }}>{{ $category->name }}
                                </option>
                            @endforeach --}}
                        </select>
                    </div>
                </div>
                <div class="row  m-1">
                    <div class="col-md-3">
                        <label for="cut_length">Pcs</label>
                        <input type="text" class="form-control" id='pcs' name='pcs'>
                    </div>
                    <div class="col-md-3">
                        <label for="cut_length">Weight</label>
                        <input type="text" class="form-control" id='weight' name='weight'>
                    </div>
                    <div class="col-md-3">
                        <label for="cut_length">Average</label>
                        <input type="text" class="form-control" id='average' name='average'>
                    </div>
                    <div class="col-md-3">
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
                </tbody>
            </table>
        </div>

        <div class="row mx-1 mt-3">
            <button class="btn btn-success" id="updateBagSellingEntry">Update</button>
        </div>
    </div>

    </div>
@endsection
@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            refresh();
            async function refresh() {
                await getBagSellingItemData();
            }

            function getBagSellingItemData() {
                // console.log('mega');
                let bagSellingEntry_id = {!! json_encode($bagSellingEntry->id) !!}
                console.log('hello', bagSellingEntry_id);
                return new Promise(function(resolve, reject) {
                    $.ajax({
                        url: "{{ route('bagSellingItem.getBagSellingItemData') }}",
                        method: 'get',
                        data: {
                            _token: "{{ csrf_token() }}",
                            bagSellingEntry_id: bagSellingEntry_id,
                        },
                        success: function(response) {
                            response.forEach(function($item) {
                                setIntoTable($item)
                            })
                            resolve();
                        },
                        error: function(xhr, status, error) {
                            setErrorMsg(xhr.responseJSON.message);
                            reject();
                        }
                    });
                });
            }
            @if (session()->has('message'))
                toastr.success("{{ session()->get('message') }}");
            @elseif (session()->has('message_err'))

                toastr.error("{{ session()->get('message_err') }}");
            @endif

            $('#group').on('select2:select', function(e) {
                let group_id = e.params.data.id;
                emptyAllFields();
                getBagBrand(group_id);
            });

            function emptyAllFields() {
                $('#brandBag').empty();
                $('#bundelNo').empty();
                $('#pcs').val('');
                $('#weight').val('');
                $('#average').val('');
            }
            $('#brandBag').on('select2:select', function(e) {
                let brandBrandId = e.params.data.id;
                $('#bundelNo').empty().prepend('<option value="" selected disable>Select Bag No</option>');
                let groupId = document.getElementById('group').value;
                getBundelNo(brandBrandId, groupId);
            });

            $('#bundelNo').on('select2:select', function(e) {
                let bundel_no = e.params.data.id;
                getPcsWeightAvg(bundel_no);

            });
            document.getElementById('updateBagSellingEntry').addEventListener('click', function(e) {

                if ($('#bagBundellingTable').find('tbody tr').length <= 0) {
                    alert('There is no data in table at least one entry required');
                    return false;
                }

                let bagSellingEntry_id = {!! json_encode($bagSellingEntry->id) !!}
                // let bagSellingEntry_id = {!! json_encode($bagSellingEntry->id) !!}
                $.ajax({
                    url: "{{ route('bagSellingItem.saveEntireBagSellingEntry') }}",
                    method: 'post',
                    data: {
                        _token: "{{ csrf_token() }}",
                        bagSellingEntry_id: bagSellingEntry_id,
                    },
                    success: function(response) {
                        window.location.href = "{{ route('bagSelling.index') }}";
                        //setIntoTable(response);

                    },
                    error: function(xhr, status, error) {
                        // setMessage
                        setErrorMsg(xhr.responseJSON.message);
                    }
                });

            })

            document.getElementById('bagSellingItemStore').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = e.target;
                let bag_selling_entry_id = form.elements['bag_selling_entry_id'].value;
                let group_id = form.elements['group_id'].value;
                let brand_bag_id = form.elements['brand_bag_id'].value;
                let bundel_no = form.elements['bundel_no'].value;
                let pcs = form.elements['pcs'].value;
                let weight = form.elements['weight'].value;
                let average = form.elements['average'].value;
                // console.log('bundel_data', bundel_no);
                $.ajax({
                    url: "{{ route('bagSellingItem.store') }}",
                    method: 'post',
                    data: {
                        _token: "{{ csrf_token() }}",
                        bag_selling_entry_id: bag_selling_entry_id,
                        group_id: group_id,
                        brand_bag_id: brand_bag_id,
                        bundel_no: bundel_no,
                        pcs: pcs,
                        weight: weight,
                        average: average
                    },
                    success: function(response) {
                        $('#group').val('').trigger('change');
                        emptyAllFields();
                        setIntoTable(response);
                        // getBagSellingItemData();

                    },
                    error: function(xhr, status, error) {
                        console.log('error:', xhr.responseJSON)
                        setErrorMsg(xhr.responseJSON.errorInfo[2]);
                    }
                });

            });
            let sn = 1;

            function setIntoTable(res) {
                var html = "";
                html = "<tr id=editRow-" + res.id + "><td>" + sn +
                    "</td><td class='rowGroupName'>" + res.group.name +
                    "</td><td class='rowBrandBagName'>" + res.brand_bag.name +
                    "</td><td class='rowQuantity_piece'>" + res.weight +
                    "</td><td class='rowAverage'>" + res.pcs +
                    "</td><td class='rowWastage'>" + res.average +
                    "</td><td class='rowRollno'>" + res.bundel_no +
                    "</td> <td>" +
                    "<button class='btn btn-danger dltBagBundelItem' data-id=" +
                    res.id + " ><i class='fas fa-trash-alt'></i> </button>" + "</td></tr>";
                document.getElementById('bagBundellingData').innerHTML += html;
                sn++;
                // clearInputFields();
            }

            function getPcsWeightAvg(bundel_no) {
                $.ajax({
                    url: "{{ route('bagSelling.getPcsWeightAvg') }}",
                    method: 'get',
                    data: {
                        _token: "{{ csrf_token() }}",
                        bundel_no: bundel_no,

                    },
                    success: function(response) {

                        document.getElementById('pcs').value = response.qty_pcs;
                        document.getElementById('weight').value = response.qty_in_kg;
                        document.getElementById('average').value = response.average_weight;


                    },
                    error: function(xhr, status, error) {
                        setErrorMsg(xhr.responseJSON.message);
                    }
                });
            }

            function getBundelNo(brandBrandId, groupId) {
                $.ajax({
                    url: "{{ route('bagSelling.getBundleNo') }}",
                    method: 'get',
                    data: {
                        _token: "{{ csrf_token() }}",
                        brandBrandId: brandBrandId,
                        group_id: groupId

                    },
                    success: function(response) {
                        response.forEach(function(response) {
                            setOptionInSelect('bundelNo', response.bundle_no, response
                                .bundle_no);
                        });

                    },
                    error: function(xhr, status, error) {
                        setErrorMsg(xhr.responseJSON.message);
                    }
                });
            }

            function getBagBrand(group_id) {
                $.ajax({
                    url: "{{ route('bagSelling.getBagBrand') }}",
                    method: 'get',
                    data: {
                        _token: "{{ csrf_token() }}",
                        //dana_group_id: dana_group_id,
                        group_id: group_id

                    },
                    success: function(response) {
                        $('#brandBag').prepend(
                            "<option value='' disabled selected>Select required data</option>");
                        response.forEach(function(item) {
                            setOptionInSelect('brandBag', item.bag_brand.id, item
                                .bag_brand.name);
                        });
                    },
                    error: function(xhr, status, error) {
                        setErrorMsg(xhr.responseJSON.message);
                    }
                });
            }

            function setErrorMsg(errorMessage) {
                let errorContainer = document.getElementById('error_msg');
                errorContainer.hidden = false;
                errorContainer.innerHTML = errorMessage;
                setTimeout(function() {
                    errorContainer.hidden = true;
                }, 5000);
            }

            function setOptionInSelect(elementId, optionId, optionText) {
                let selectElement = $('#' + elementId);
                let newOption = $('<option>');
                newOption.val(optionId).text(optionText);
                selectElement.append(newOption);
                selectElement.trigger('change.select2');

            }
        });
    </script>
@endsection
