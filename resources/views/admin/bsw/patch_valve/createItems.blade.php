@extends('layouts.admin')

@section('extra-style')
    <link href="{{ asset('css/nepaliDatePicker/nepali.datepicker.v4.0.1.min.css') }}" rel="stylesheet" type="text/css" />
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
        <form id="createRawMaterial">
            @csrf

            <div class="row">
                <div class="col-md-3 form-group">
                    <label for="size" class="col-form-label">{{ __('Receipt No') }}<span class="required-field">*</span>
                    </label>
                    <input type="text" class="form-control" id="billnumber" name="bill_number"
                        value="{{ $bswFabSendcurtxReceivpatchvalveEntryData->receipt_no }}" readonly />
                </div>

                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('Date') }}
                    </label>
                    <input type="text" name="date_np" class="form-control" id="nepali-date-picker" readonly>

                    @error('nepali-date-picker')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('Plant Type') }}
                    </label>
                    <input type="text" name="plant_type_id" class="form-control" id="plantTypeId"
                        value="{{ $bswFabSendcurtxReceivpatchvalveEntryData->plantType->name }}" readonly>
                    @error('plant_type_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('Plant Name') }}
                    </label>
                    <input type="text" name="plant_name_id" class="form-control" id="plantNameId"
                        value="{{ $bswFabSendcurtxReceivpatchvalveEntryData->plantName->name }}" readonly>
                    @error('plant_type_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-2 form-group">
                    <label for="shift" class="col-form-label">{{ __('Shift') }}
                    </label>
                    <input type="text" name="shift_id" class="form-control" id="shiftId"
                        value="{{ $bswFabSendcurtxReceivpatchvalveEntryData->shift->name }}" readonly>
                    @error('shift_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-1 form-group">
                    <label for="size" class="col-form-label">{{ __('To godam') }}
                    </label>
                    <input type="text" name="godam_id" class="form-control" id="godamId"
                        value="{{ $bswFabSendcurtxReceivpatchvalveEntryData->godam->name }}"readonly>
                    @error('shift_name_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </form>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <label for="size" class="col-form-label">{{ __('Fabric Type') }}<span class="required-field">*</span>
                </label>
                <select class="advance-select-box form-control" id="fabricType" name="fabric_type">
                    <option value="" disabled selected>{{ __('Select fabric type') }}</option>
                    <option value="unlam">{{ __('UNLAM') }}</option>
                    <option value="lam">{{ __('LAM') }}</option>
                    <option value="printed">{{ __('PRINTED') }}</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="size" class="col-form-label">{{ __('Fabric Name') }}<span class="required-field">*</span>
                </label>
                <select class="form-control" id="fabricName" name="fabric_name" required>
                    <option value="" selected disabled>{{ __('Select a fabric') }}</option>
                </select>
            </div>
        </div>
    </div>
    <hr>
    {{-- first --}}
    <div class="row">
        <div class="table-responsive table-custom my-3">
            <table class="table table-hover table-striped" id="fabTable">
                <thead class="table-info">
                    <tr>
                        <th>{{ __('Sr.No') }}</th>
                        <th>{{ __('Fabric Name') }}</th>
                        <th>{{ __('Roll No') }}</th>
                        <th>{{ __('G.W') }}</th>
                        <th>{{ __('N.W') }}</th>
                        <th>{{ __('Meter') }}</th>
                        <th>{{ __('Avg') }}</th>
                        <th>{{ __('Send') }}</th>
                    </tr>
                </thead>
                <tbody id="fabTbody">

                </tbody>
            </table>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="Ajaxdata col-md-12">
            <div class="p-0 table-responsive table-custom my-3">
                <table class="table" id="bswSentLamFabTable">
                    <thead>
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

                    <tbody id="bswSentLamFabTbody">
                    </tbody>

                </table>
            </div>

        </div>
    </div>
    <hr>

    {{-- <h1 class='text-center'>Compare Lam and Unlam</h1> --}}
    <div class="row">
        <div class="col-md-12">
            <div>
                <p style="font-weight: bold;">Printed</p>
            </div>
            <table class="table table-bordered" id="bswPrintedLamFab">
                <thead>
                    <tr>
                        <th>{{ __('Sr.No') }}</th>
                        <th>{{ __('Fabric Name') }}</th>
                        <th>{{ __('Roll No') }}</th>
                        <th>{{ __('N.W') }}</th>
                        <th>{{ __('G.W') }}</th>
                        <th>{{ __('Meter') }}</th>
                        <th>{{ __('Avg') }}</th>
                        <th>{{ __('Gram') }}</th>
                    </tr>
                </thead>
                <tbody id="bswPrintedLamFabTbody">
                </tbody>
            </table>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-5">
            <form id="bswFabPrintDanaConsumption">
                <div class="card p-2">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="cut_length">Godam</label>
                            <select class="advance-select-box form-control  @error('godam_id') is-invalid @enderror"
                                id="godamIdDanaCons" name="godam_id_consmpt" required>
                                <option value=" " selected disabled>{{ __('Select Godam') }}</option>
                                {{-- @foreach ($godams as $godam)
                                    <option value="{{ $godam->fromGodam->id }}">
                                        {{ $godam->fromGodam->name }}
                                    </option>
                                @endforeach --}}
                            </select>
                        </div>
                        {{-- <div class="col-md-4">
                                        <label for="cut_length">Dana Group</label>
                                        <select
                                            class="advance-select-box form-control  @error('dana_group_id') is-invalid @enderror"
                                            id="danaGroupId" name="dana_group_id" required>
                                            <option value=" " selected disabled>{{ __('Select dana group') }}
                                            </option>
                                        </select>
                                    </div> --}}

                        <div class="col-md-5">
                            <label for="cut_length">Dana Name</label>
                            <select class="advance-select-box form-control  @error('dana_name_id') is-invalid @enderror"
                                id="danaNameId" name="dana_name_id" required>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="cut_length">Available</label>
                            <input type="text" class="form-control" id="avilableStock" name="avilable_stock"
                                readonly>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-4">
                            <label for="cut_length">Quantity</label>
                            <input type="text" class="form-control" id="quantity" name="quantity">
                        </div>
                        <div class="col-md-2 mt-4">
                            <button class="btn btn-primary ">Add</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-7">
            <table class="table table-bordered table-hover" id="danaConsumption">
                <thead>
                    <tr>
                        <th style="width:30px;">SN</th>
                        <th>Godam</th>
                        {{-- <th>Dana Group</th> --}}
                        <th>Dana Name</th>
                        <th>Quantity</th>

                    </tr>
                </thead>
                <tbody id="bswLamPrintDanaConsumpt">
                </tbody>
            </table>
        </div>
    </div>
    <div class="card col-md-12">
        <div class="card-body m-2 p-5">
            <div class="col-md-12" style="height: 100%;">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <div>
                            <label for="size" class="col-form-label">{{ __('Total Lam Mtr:') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator"
                                id="totalLamMeter" data-number="1" name="total_lam_meter" min="1" readonly
                                required>
                            @error('total_ul_in_mtr')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="size" class="col-form-label">{{ __('Total Lam Net Wt:') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator"
                                id="totalLamNetWt" data-number="1" name="total_lam_net_wt" min="1" readonly
                                required>
                            @error('total_ul_net_wt')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div>
                            <label for="size" class="col-form-label">{{ __('Trimming wst:') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator"
                                id="trimmingWst" data-number="1" name="trimming_waste" min="1" required>
                            @error('polo_waste')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div>
                            <label for="size" class="col-form-label">{{ __('waste to godam') }}<span
                                    class="required-field">*</span>
                            </label>
                            <select class="advance-select-box form-control  @error('godam_id') is-invalid @enderror"
                                id="godamIdWaste" name="godam_id_waste" required>
                                <option value=" " selected disabled>{{ __('Select Godam') }}</option>
                                <?php
                                $wasteGodams = \App\Models\Godam::where('status', 'active')->get();
                                ?>
                                @foreach ($wasteGodams as $godam)
                                    <option value="{{ $godam->id }}">
                                        {{ $godam->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('polo_waste')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div>
                            <label for="size" class="col-form-label">{{ __('Total Item:') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="number" step="any" min="0" class="form-control calculator"
                                id="totalItem" data-number="1" name="total_item" min="1" readonly required>
                            @error('polo_waste')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                    </div>

                    <div class="col-md-4 form-group">
                        <div>
                            <label for="size" class="col-form-label">{{ __('Total Print Mtr:') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator"
                                id="totalPrintInmtr" data-number="1" name="total_print_in_mtr" min="1" readonly
                                required>
                            @error('total_lam_in_mtr')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="size" class="col-form-label">{{ __('Total Print Net Wt:') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator"
                                id="totalPrintNetWt" data-number="1" name="total_print_net_wt" min="1" readonly
                                required>
                            @error('total_lam_net_wt')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div>
                            {{-- meg --}}
                            <label for="size" class="col-form-label">{{ __('Fabric Was:') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator"
                                id="fabric_waste" data-number="1" name="fabric_waste" min="1" required>
                            @error('fabric_waste')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div>
                            <label for="size" class="col-form-label">{{ __('Total Was:') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator"
                                id="total_waste" data-number="1" name="total_waste" min="1" readonly required>
                            @error('polo_waste')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4" style="margin-top:70px;">
                        <div>
                            <label for="size" class="col-form-label">{{ __('Diff Lam/Print NW :') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator"
                                id="diffLamPrintednw" data-number="1" name="diff_lam_printednw" min="1" readonly
                                required>
                            @error('diff_lam_printednw')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="size" class="col-form-label">{{ __('Total Diff:') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator"
                                id="total_diff" data-number="1" name="total_diff" min="1" readonly required>
                            @error('total_diff')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <input type="hidden" name="selectedDanaID" class="form-control" id="LamSendForPrinting" readonly>
            <button class="btn btn-info" id="printedFabEntireSave">Update</button>
            <input type="hidden" name="godam_id" id="godam_id" required />
            <input type="hidden" name="autoloader_godam_selected" id="autoloader_godam_selected" required>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop1"
        style="position: fixed;bottom: 0;top: 50%;left: 0;right: 0;margin: auto;z-index: 1050;" role="dialog"
        tabindex="-1" aria-labelledby="exampleModalcat" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div id="sendForPrintingError" class="alert alert-danger" hidden></div>
            <form id='sendLamFabForPrinting'>
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title fs-5" id="staticBackdropLabel">Printing</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mt-2 mb-2">
                            <input type="text" name="bswfabEntry_id" id="bswfabEntryId" hidden>
                            <div class="col-md-2">
                                <label for="size" class="col-form-label">{{ __('Printed Fabric') }}<span
                                        class="required-field">*</span>
                                </label>
                                <a href="#" id="addPrintedFab" class="col-md-1 btn btn-primary dynamic-btn"
                                    data-toggle="modal" tabindex="-1" data-target="#printedFabricModal"
                                    style="margin-top:0 !important; top:8px;float:right;">
                                    <i class="fas fa-plus"
                                        style="display:flex;align-items: center;justify-content: center;"></i>
                                </a>
                                <select class="advance-select-box form-control" id="printedFabId"
                                    name="printed_fabric_id">
                                    <option value="" selected disabled>{{ __('Select Fabric name') }}</option>


                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="size" class="col-form-label">{{ __('Roll No') }}<span
                                        class="required-field">*</span>
                                </label>
                                <input type="number" step="any" min="0" class="form-control" id="rollNo"
                                    data-number="1" name="roll_no" min="1" required>
                            </div>
                            <div class="col-md-2">
                                <label for="size" class="col-form-label">{{ __('GW') }}<span
                                        class="required-field">*</span>
                                </label>
                                <input type="number" step="any" min="0" class="form-control"
                                    id="grossWeight" data-number="1" name="gross_weight" min="1" required>
                            </div>
                            <div class="col-md-2">
                                <label for="size" class="col-form-label">{{ __('NW') }}<span
                                        class="required-field">*</span>
                                </label>
                                <input type="number" step="any" min="0" class="form-control" id="netWeight"
                                    data-number="1" name="net_weight" min="1" required>
                            </div>
                            <div class="col-md-2">
                                <label for="size" class="col-form-label">{{ __('meter') }}<span
                                        class="required-field">*</span>
                                </label>
                                <input type="number" step="any" min="0" class="form-control" id="meter"
                                    data-number="1" name="meter" min="1" required>
                            </div>
                            <div class="col-md-1">
                                <label for="size" class="col-form-label">{{ __('AVG') }}<span
                                        class="required-field">*</span>
                                </label>
                                <input type="number" step="any" min="0" class="form-control" id="average"
                                    data-number="1" name="average" min="1" required>
                            </div>
                            <div class="col-md-1">
                                <label for="size" class="col-form-label">{{ __('Gram') }}<span
                                        class="required-field">*</span>
                                </label>
                                <input type="number" step="any" min="0" class="form-control" id="gram"
                                    data-number="1" name="gram" min="1" required>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Add</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!--Printed Fabric Model popup-->
    <div class="modal fade" id="printedFabricModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalcat"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalcat">Add Printed Fabric</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form class="form-horizontal" id="createPrintedFabricModel">
                    <div class="modal-body">
                        <div id="printedFabricError" class="alert alert-danger" hidden></div>
                        <div class="card-body">
                            <div class="row">

                                <div class="form-group col-md-2">
                                    <label style="width:400px !important;"
                                        for="name">{{ __('Printed Fabric Name') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" style="width:430px !important; "
                                        placeholder="{{ __('Printed Fabric Name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                    <select class="form-control" id="Status" name="status">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Inactive') }}</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <!-- /.card-body -->

                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                            {{ __('Save Category') }}</button>
                        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                    </div>
                </form>


            </div>
        </div>
    </div>
@endsection
@section('extra-script')
    <script src="{{ asset('js/nepaliDatePicker/nepali.datepicker.v4.0.1.min.js') }}"></script>
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/storein.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
    <script>
        $(document).ready(function() {
            $("#nepali-date-picker").nepaliDatePicker({});
            let todayNepaliDate = {!! isset($bswFabSendcurtxReceivpatchvalveEntryData)
                ? json_encode($bswFabSendcurtxReceivpatchvalveEntryData->date)
                : 'null' !!};
            if (todayNepaliDate !== null) {
                $("#nepali-date-picker").val(todayNepaliDate);
            }
            $('#fabricName').on('select2:select', function(e) {

                fabTable.ajax.reload();
            });


            var fabTable = $('#fabTable').DataTable({
                lengthMenu: [
                    [5, 15, 30, -1],
                    ['5 rows', '15 rows', '30 rows', 'Show all']
                ],
                style: 'bootstrap',
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('fabSendCuetxReceivePatchValveItems.fabData') }}',
                    data: function(data) {
                        data.fabName = $('#fabricName').val();
                        data.fabType = $('#fabricType').val();
                        return data;
                    },
                    error: function(xhr, error, thrown) {
                        console.log("Error fetching data:", error);
                    }
                },
                columns: [{
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'roll_no'
                    },
                    {
                        data: 'gross_wt'
                    },
                    {
                        data: 'net_wt'
                    },
                    {
                        data: 'meter'
                    },
                    {
                        data: 'average_wt'
                    },
                    {
                        data: 'action'
                    },
                ],

            });

            $(document).on("click", '#lamsendEntry', function(e) {
                //   alert($(this).data("id")) megha
                let $bswcurtexto_patchVal_Entry_id = {!! $bswFabSendcurtxReceivpatchvalveEntryData->id !!}
                e.preventDefault()
                let name = $(this).data("name")
                let gross_wt = $(this).data("gross_wt")
                let net_wt = $(this).data("net_wt")
                let roll_no = $(this).data("roll_no")
                let meter = $(this).data("meter")
                // let fabric_id = $(this).data("fabric_id")
                let average_wt = $(this).data("average")
                let gram_wt = $(this).data("gram_wt")
            });
            $('#fabricName').select2({
                theme: 'bootstrap4',
                ajax: {
                    method: 'GET',
                    url: "{{ route('fabSendCuetxReceivePatchValveItems.getFabricName') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        let fabricType = $('#fabricType').val();
                        return {
                            fabricType: fabricType,
                            query: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data) {
                        console.log(data.data)
                        return {
                            results: data.data,
                            pagination: {
                                more: data.next_page_url ? true : false
                            }
                        };
                    }
                },
                placeholder: 'Select a fabric',
                minimumInputLength: 0
            });



            function setOptionInSelect(elementId, optionId, optionText) {
                let selectElement = $('#' + elementId);
                // create a new option element
                let newOption = $('<option>');

                // set the value and text of the new option element
                newOption.val(optionId).text(optionText);

                // append the new option element to the select element
                selectElement.append(newOption);

                // refresh the select2 element to update the UI
                selectElement.trigger('change.select2');
                // $('#' + elementId).val(optionId).trigger('change.select2');
            }
        });
    </script>
@endsection
