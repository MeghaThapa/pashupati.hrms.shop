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
                {{-- <h1>fghjhgvb</h1> --}}
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
                <table class="table" id="bswSentFabToItems">
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

                    <tbody id="bswSentFabToItemsTbody">
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
                <p style="font-weight: bold;">To Diffferent Stocks</p>
            </div>
            <table class="table table-bordered" id="threeDiffStockData">
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
                <tbody id="threeDiffStockDataTbody">
                </tbody>
            </table>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-5">
            <form id="bswPatchValveDanaConsumption">
                <div class="card p-2">
                </div>
                <div class="card card-body">
                    <div class="row">
                        <div id="sendToThreeDiffStockSuccess" class="alert alert-success" hidden></div>
                        <div id="sendToThreeDiffStockError" class="alert alert-danger" hidden></div>
                    </div>
                    <div class="row">

                        {{-- <div class="col-md-4">
                            <label for="cut_length">Godam</label>
                            <select class="advance-select-box form-control  @error('godam_id') is-invalid @enderror"
                                id="godamIdDanaCons" name="godam_id_consmpt" required>
                                <option value=" " selected disabled>{{ __('Select Godam') }}</option>

                            </select>
                        </div> --}}
                        <div class="col-md-5">
                            <label for="cut_length">Dana Name</label>
                            <select class="advance-select-box form-control" id="danaNameIdConsumpt"
                                name="dana_name_idConsumpt" required>
                                @if ($autoloadDatas)
                                    <option value="" selected disabled>Select Dana Name</option>
                                    @foreach ($autoloadDatas as $autoloadData)
                                        <option value="{{ $autoloadData->danaName->id }}">
                                            {{ $autoloadData->danaName->name }}</option>
                                    @endforeach
                                @else
                                    <option value="" disabled>No Dana Names available</option>
                                @endif
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
                            <input type="text" class="form-control" id="consumptQuantity" name="consumpt_quantity">
                        </div>
                        <div class="col-md-2 mt-4">
                            <button class="btn btn-primary ">Add</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-7">
            <table class="table table-bordered table-hover" id="curtexToPatchValveDanaConsumpt">
                <thead>
                    <tr>
                        <th style="width:30px;">SN</th>
                        <th>Godam</th>
                        <th>Dana Name</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="curtexToPatchValveDanaConsumptTbody">
                </tbody>
            </table>
        </div>
    </div>
    <div class="card col-md-12">
        <div class="card-body m-2 p-5">
            <div class="col-md-12" style="height: 100%;">
                <div class="row">
                    <div class="col-md-2 form-group">
                        <label for="size" class="col-form-label">{{ __('Total Out mtR:') }}<span
                                class="required-field">*</span>
                        </label>
                        <input type="text" step="any" min="0" class="form-control calculator"
                            id="totalOutMeter" data-number="1" name="total_out_meter" min="1" readonly>
                        @error('total_out_meter')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="size" class="col-form-label">{{ __('Total Valve mtR:') }}<span
                                class="required-field">*</span>
                        </label>
                        <input type="text" step="any" min="0" class="form-control calculator"
                            id="totalValveMeter" data-number="1" name="total_valve_meter" min="1" readonly>
                        @error('total_valve_meter')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="size" class="col-form-label">{{ __('Total Patch mtR:') }}<span
                                class="required-field">*</span>
                        </label>
                        <input type="text" step="any" min="0" class="form-control calculator"
                            id="totalPatchMeter" data-number="1" name="total_patch_meter" min="1" readonly>
                        @error('total_patch_meter')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="size" class="col-form-label">{{ __('Total In mtR:') }}<span
                                class="required-field">*</span>
                        </label>
                        <input type="text" step="any" min="0" class="form-control calculator"
                            id="totalInMtr" data-number="1" name="total_in_mtr" min="1" readonly>
                        @error('total_patch_meter')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="size" class="col-form-label">{{ __('DiffOut/In mtR:') }}<span
                                class="required-field">*</span>
                        </label>
                        <input type="text" step="any" min="0" class="form-control calculator"
                            id="diffOutinMtr" data-number="1" name="diff_outin_mtr" min="1" readonly>
                        @error('diff_outin_mtr')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    {{-- <div class="col-md-2 form-group">
                        <label for="size" class="col-form-label">{{ __('Total Diff:') }}<span
                                class="required-field">*</span>
                        </label>
                        <input type="text" step="any" min="0" class="form-control calculator"
                            id="totalDiff" data-number="1" name="total_diff" min="1" readonly required>
                        @error('total_diff')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div> --}}
                </div>
                <div class="row">
                    <div class="col-md-2 form-group">
                        <label for="size" class="col-form-label">{{ __('Total Out NW:') }}<span
                                class="required-field">*</span>
                        </label>
                        <input type="text" step="any" min="0" class="form-control calculator"
                            id="totalOutNW" data-number="1" name="total_out_nw" min="1" readonly required>
                        @error('total_out_nw')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="size" class="col-form-label">{{ __('Total Valve NW:') }}<span
                                class="required-field">*</span>
                        </label>
                        <input type="text" step="any" min="0" class="form-control calculator"
                            id="totalValveNw" data-number="1" name="total_valve_nw" min="1" readonly required>
                        @error('total_valve_nw')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="size" class="col-form-label">{{ __('Total Patch NW:') }}<span
                                class="required-field">*</span>
                        </label>
                        <input type="text" step="any" min="0" class="form-control calculator"
                            id="totalPatchNW" data-number="1" name="total_patch_nw" min="1" readonly required>
                        @error('total_patch_meter')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="size" class="col-form-label">{{ __('Total In NW:') }}<span
                                class="required-field">*</span>
                        </label>
                        <input type="text" step="any" min="0" class="form-control calculator"
                            id="totalInNw" data-number="1" name="total_in_nw" min="1" readonly required>
                        @error('total_in_nw')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="size" class="col-form-label">{{ __('Diff Out/In NW:') }}<span
                                class="required-field">*</span>
                        </label>
                        <input type="text" step="any" min="0" class="form-control calculator"
                            id="diffOutinNw" data-number="1" name="diff_outin_nw" min="1" readonly required>
                        @error('diff_outin_nw')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="size" class="col-form-label">{{ __('Total Diff:') }}<span
                                class="required-field">*</span>
                        </label>
                        <input type="text" step="any" min="0" class="form-control calculator"
                            id="totalDiff" data-number="1" name="total_diff" min="1" readonly required>
                        @error('total_diff')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group">
                        <label for="size" class="col-form-label">{{ __('Trem Wst:') }}<span
                                class="required-field">*</span>
                        </label>
                        <input type="text" step="any" min="0" class="form-control calculator"
                            id="tremWst" data-number="1" name="trem_wst" min="1" required>
                        @error('trem_wst')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="size" class="col-form-label">{{ __('Fabric wst:') }}<span
                                class="required-field">*</span>
                        </label>
                        <input type="text" step="any" min="0" class="form-control calculator"
                            id="fabricWst" data-number="1" name="fabric_wst" min="1" required>
                        @error('trem_wst')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="size" class="col-form-label">{{ __('Total Wastage:') }}<span
                                class="required-field">*</span>
                        </label>
                        <input type="text" step="any" min="0" class="form-control calculator"
                            id="totalWst" data-number="1" name="total_wst" min="1" required>
                        @error('total_wst')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    {{-- <div class="col-md-2 mt-4">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div> --}}

                </div>
            </div>
        </div>
        <div class="card-footer">
            <input type="hidden" name="selectedDanaID" class="form-control" id="LamSendForPrinting" readonly>
            <button class="btn btn-info" id="curtexToPatchValveEntireSave">Update</button>
            <input type="hidden" name="godam_id" id="godam_id" required />
            <input type="hidden" name="autoloader_godam_selected" id="autoloader_godam_selected" required>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop1"
        style="position: fixed;bottom: 0;top: 80%;left: 0;right: 0;margin: auto;z-index: 1050;" role="dialog"
        tabindex="-1" aria-labelledby="exampleModalcat" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl d-block">
            <div id="sendToThreeDiffStock" class="alert alert-danger" hidden></div>
            <form id='sendFabToThreeDiffStock'>
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title fs-5" id="staticBackdropLabel">To Three diff stocks</h4>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <input type="text" name="fabCurtxToPatchValEntry_id" id="fabCurtxToPatchValEntryId"
                        value="{{ $bswFabSendcurtxReceivpatchvalveEntryData->id }}" hidden>
                    <div class="modal-body">
                        <div class="row mt-2 mb-2">
                            <input type="text" name="bswfabEntry_id" id="bswfabEntryId" hidden>
                            <div class="col-md-2">
                                <label for="size" class="col-form-label">{{ __('Fabric Type') }}<span
                                        class="required-field">*</span>
                                </label>
                                <select class="advance-select-box form-control" id="fabricTypeModel"
                                    name="fabric_type_model">
                                    <option value="" selected disabled>{{ __('Select Fabric type') }}</option>
                                    <option value="roll">{{ __('roll') }}</option>
                                    <option value="valve">{{ __('valve') }}</option>
                                    <option value="patch">{{ __('patch') }}</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="size" class="col-form-label">{{ __(' crtxToPtchValFabric') }}<span
                                        class="required-field">*</span>
                                </label>
                                <a href="#" id="addPrintedFab" class="col-md-1 btn btn-primary dynamic-btn"
                                    data-toggle="modal" tabindex="-1" data-target="#curtexToPatchFabricModal"
                                    style="margin-top:0 !important; top:8px;float:right;">
                                    <i class="fas fa-plus"
                                        style="display:flex;align-items: center;justify-content: center;"></i>
                                </a>
                                <select class="advance-select-box form-control" id="CortexToPatchValFabricId"
                                    name="CortexToPatchVal_fabric_id">
                                    {{-- <option value="" selected disabled>{{ __('Select Fabric name') }}</option> --}}
                                </select>
                            </div>
                            <input type="text" id="sizeToFindGram" name="size_tofind_gram" placeholder="size">
                            <div class="col-md-2">
                                <label for="size" class="col-form-label">{{ __('Roll No') }}<span
                                        class="required-field">*</span>
                                </label>
                                <input type="number" step="any" min="0" class="form-control" id="rollNo"
                                    data-number="1" name="roll_no" min="1" required>
                            </div>
                            <div class="col-md-3">
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

                        </div>
                        <div class="row mt-2 mb-2">
                            <div class="col-md-2">
                                <label for="size" class="col-form-label">{{ __('meter') }}<span
                                        class="required-field">*</span>
                                </label>
                                <input type="number" step="any" min="0" class="form-control" id="meter"
                                    data-number="1" name="meter" min="1" required>
                            </div>
                            <div class="col-md-2">
                                <label for="size" class="col-form-label">{{ __('AVG') }}<span
                                        class="required-field">*</span>
                                </label>
                                <input type="number" step="any" min="0" class="form-control" id="average"
                                    data-number="1" name="average" min="1" required>
                            </div>
                            <div class="col-md-2">
                                <label for="size" class="col-form-label">{{ __('Gram') }}<span
                                        class="required-field">*</span>
                                </label>
                                <input type="number" step="any" min="0" class="form-control" id="gramModel"
                                    data-number="1" name="gram_model" min="1" required readonly>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Add</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!--Printed Fabric Model popup-->
    <div class="modal fade" id="curtexToPatchFabricModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalcat" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalcat">Add curtexToPatchVal Fabric</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form class="form-horizontal" id="curtexToPatchValFabricModel">
                    <div class="modal-body">
                        <div id="curtexToPatchFabricModalError" class="alert alert-danger" hidden></div>
                        <div class="card-body">
                            <div class="row">

                                <div class="form-group col-md-2">
                                    <label style="width:400px !important;" for="name">{{ __('Fabric Name') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" style="width:430px !important; "
                                        placeholder="{{ __('Printed Fabric Name') }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label style="width:400px !important;" for="name">{{ __('Fabric Group') }}<span
                                            class="required-field">*</span></label>
                                    <select class="advance-select-box form-control" id="fabricGroupId"
                                        name="fabric_group_id">
                                        <option value="" selected disabled>{{ __('Select Fabric name') }}</option>
                                        @if ($fabriGroups && $fabriGroups != null)
                                            @foreach ($fabriGroups as $fabriGroup)
                                                <option value="{{ $fabriGroup->id }}">
                                                    {{ $fabriGroup->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label style="width:400px !important;" for="name">{{ __('Fabric Type') }}<span
                                            class="required-field">*</span></label>
                                    <select class="advance-select-box form-control" id="fabricTypeModelTwo"
                                        name="fabric_type_model_two">
                                        <option value="" selected disabled>{{ __('Select Fabric Type') }}</option>
                                        <option value="roll">roll</option>
                                        <option value="valve">valve</option>
                                        <option value="patch">patch</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label style="width:400px !important;"
                                        for="name">{{ __('Standard Weight') }}<span
                                            class="required-field">*</span></label>
                                    <input type="numeric" class="form-control" id="standardWeight"
                                        name="standard_weight" style="width:430px !important; "
                                        placeholder="{{ __('Fabric Name') }}" required>
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
                        <button type="button" id="closeModalButton" class="btn btn-secondary" data-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                            {{ __('Save Category') }}</button>
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
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script> --}}

    <script>
        $(document).ready(function() {

            let fabTable = null;

            $("#nepali-date-picker").nepaliDatePicker({});
            let todayNepaliDate = {!! isset($bswFabSendcurtxReceivpatchvalveEntryData)
                ? json_encode($bswFabSendcurtxReceivpatchvalveEntryData->date)
                : 'null' !!};
            if (todayNepaliDate !== null) {
                $("#nepali-date-picker").val(todayNepaliDate);
            }
            getSentFabItemsData();
            // getDataStoredInThreeDifferentStocks();
            getDanaConsumptionData();
            //megha thapa
            $('#tremWst, #fabricWst').on("input", function(event) {
                calcuateTotalWastage();
            });

            function calcuateTotalWastage() {
                let trimWaste = parseFloat($('#tremWst').val());
                let fabricWaste = parseFloat($('#fabricWst').val());
                if (trimWaste && fabricWaste) {
                    let totalWaste = trimWaste + fabricWaste;
                    $('#totalWst').val(totalWaste);
                }
            }
            //save entire
            $('#curtexToPatchValveEntireSave').on('click', function() {
                let bswFabSendcurtxReceivpatchvalveEntry_id = {!! json_encode($bswFabSendcurtxReceivpatchvalveEntryData->id) !!};
                let trimmimg_wastage = $('#tremWst').val();
                let fabric_waste = $('#fabricWst').val();
                $.ajax({
                    url: "{{ route('fabSendCuetxReceivePatchValveEntry.saveEntire') }}",
                    method: 'post',
                    data: {
                        _token: "{{ csrf_token() }}",
                        bswFabSendcurtxReceivpatchvalveEntry_id: bswFabSendcurtxReceivpatchvalveEntry_id,
                        trimmimg_wastage: trimmimg_wastage,
                        fabric_waste: fabric_waste,
                    },
                    success: function(response) {
                        window.location.href =
                            "{{ route('fabSendCuetxReceivePatchValveEntry.index') }}";

                    },
                    error: function(xhr, status, error) {
                        setErrorMsg(xhr.responseJSON.message);
                    }
                });
            });


            $('#fabricName').on('select2:select', function(e) {
                if (fabTable != null) {
                    fabTable.destroy()
                }


                fabTable = $('#fabTable').DataTable({
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


            });
            $('#curtexToPatchValFabricModel').submit(function(event) {
                event.preventDefault();
                const form = event.target;
                let name = form.elements['name'].value;
                let fabric_group_id = form.elements['fabric_group_id'].value;
                let fabric_type = form.elements['fabric_type_model_two'].value;
                let standard_weight = form.elements['standard_weight'].value;
                let status = form.elements['status'].value;
                $.ajax({
                    url: "{{ route('curtexToPatchValFabric.store') }}",
                    method: "post",
                    data: {
                        "_token": $("meta[name='csrf-token']").attr("content"),
                        // "name": name,
                        "name": name,
                        "fabric_type": fabric_type,
                        "fabric_group_id": fabric_group_id,
                        "standard_weight": standard_weight,
                        "status": status,
                    },

                    success: function(response) {
                        console.log('done');
                        $('#danaNameId').prepend(
                            "<option value='' disabled selected>Select required</option>"
                        );

                        $('#curtexToPatchFabricModal').modal('hide');
                        setOptionInSelect('CortexToPatchValId', response.id,
                            response.name);

                    },
                    error: function(xhr, status, error) {

                        setMessage('curtexToPatchFabricModalError', xhr.responseJSON.message)
                    }
                })
                // console.log('Form submitted using jQuery event listener');


            });

            $("#netWeight, #meter").on("input", function(event) {
                calcuateAverage();
            });

            function calcuateAverage() {
                let netweight = parseFloat($('#netWeight').val());
                let meter = parseFloat($('#meter').val());
                let size = parseFloat($('#sizeToFindGram').val());
                let farbicSelected = $('#CortexToPatchValFabricId').find("option:selected:not([disabled])").length >
                    0;
                var fabricType = $('#fabricTypeModel').val();
                if (netweight && meter && fabricType != 'roll') {
                    let avg = ((netweight / meter) * 1000).toFixed(2);
                    $('#average').val(avg);
                } else if (netweight && meter && size && farbicSelected) {
                    if (fabricType == 'roll') {
                        let avg = ((netweight / meter) * 1000).toFixed(2);
                        $('#average').val(avg);
                        console.log('avg', avg);
                        let gram = (avg / size).toFixed(2);
                        console.log('grammmm', gram);

                        $('#gramModel').val(gram);
                    }
                }
            }

            $(document).on('submit', '#sendFabToThreeDiffStock', function(e) {
                e.preventDefault();
                const form = e.target;
                let fabCurtxToPatchValEntry_id = form.elements['fabCurtxToPatchValEntry_id'];
                let curtex_to_patch_val_fabric_id = form.elements['CortexToPatchVal_fabric_id'];
                let roll_no = form.elements['roll_no'];
                let gross_weight = form.elements['gross_weight'];
                let net_weight = form.elements['net_weight'];
                let meter = form.elements['meter'];
                let average = form.elements['average'];
                let gram = form.elements['gram_model'];
                let fabric_type = form.elements['fabric_type_model'];
                // if (!printed_fabric_id.value && !roll_no.value && !gross_weight.value && !net_weight
                //     .value && !meter.value && !average.value && !gram.value) {
                //     setMessage('sendForPrintingError', 'Please Fill out all fields')
                //     return false;
                // }
                $.ajax({
                    url: "{{ route('toPatchValveUnlamFabricStock.store') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        fabCurtxToPatchValEntry_id: fabCurtxToPatchValEntry_id.value,
                        curtex_to_patch_val_fabric_id: curtex_to_patch_val_fabric_id.value,
                        roll_no: roll_no.value,
                        gross_weight: gross_weight.value,
                        net_weight: net_weight.value,
                        meter: meter.value,
                        average: average.value,
                        gram: gram.value,
                        fabric_type: fabric_type.value,
                    },

                    success: function(response) {
                        console.log('store:', response);
                        $('#staticBackdrop1').modal('hide');
                        $('#threeDiffStockDataTbody').empty();
                        getDataStoredInThreeDifferentStocks();

                        // $('#bswSentLamFabTbody').empty();
                        // getDataOfPrintedLamFab();
                    },
                    error: function(xhr, status, error) {
                        setMessage('sendToThreeDiffStock', xhr.responseJSON.message)
                    }
                })
            });

            //danaConsumption
            document.getElementById('bswPatchValveDanaConsumption').addEventListener('submit',
                function(event) {
                    event.preventDefault();
                    const form = event.target;
                    var bswFabSendcurtxReceivpatchvalveEntry_id = {!! json_encode($bswFabSendcurtxReceivpatchvalveEntryData->id) !!};
                    let dana_name_id = form.elements['dana_name_idConsumpt'].value;
                    let quantity = form.elements['consumpt_quantity'].value;
                    // console.log(dana_name_id, quantity);
                    $.ajax({
                        url: "{{ route('patchValvDanaConsumpt.store') }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            bswFabSendcurtxReceivpatchvalveEntry_id: bswFabSendcurtxReceivpatchvalveEntry_id,
                            dana_name_id: dana_name_id,
                            quantity: quantity
                        },
                        success: function(response) {

                            setMessage('sendToThreeDiffStockSuccess', response.successMessage);
                            getDanaConsumptionData()
                            $('#curtexToPatchValveDanaConsumptTbody').empty();
                            // updateStockQuantity();
                        },
                        error: function(xhr, status, error) {
                            setMessage('sendToThreeDiffStockError',
                                'An error occurred while saving data');
                        }
                    });
                });

            function setMessage(element_id, message) {
                let errorContainer = document.getElementById(element_id);
                errorContainer.hidden = false;
                errorContainer.innerHTML = message;
                setTimeout(function() {
                    errorContainer.hidden = true;
                }, 2000);
            }
            //to find size to calcuate grm if roll
            $("#fabricTypeModel, #CortexToPatchValFabricId").on("change", function(event) {
                var fabricType = $('#fabricTypeModel').val();

                var farbicSelected = $('#CortexToPatchValFabricId').val();
                if (fabricType == 'roll') {
                    if (fabricType && farbicSelected) {
                        var inputName = $('#CortexToPatchValFabricId').find("option:selected").text();
                        var number = parseInt(inputName.match(/\d+/)[0]);
                        $('#sizeToFindGram').val(number);
                    }
                }
            });
            $('#danaNameIdConsumpt').on('select2:select', function(e) {
                // console.log('i am here');
                var bswFabSendcurtxReceivpatchvalveEntry_id = {!! json_encode($bswFabSendcurtxReceivpatchvalveEntryData->id) !!};

                let dana_name_id = e.params.data.id;
                // $('#danaGroupId').empty();
                getAvailableQty(bswFabSendcurtxReceivpatchvalveEntry_id, dana_name_id);
            });

            function getAvailableQty(bswFabSendcurtxReceivpatchvalveEntry_id, dana_name_id) {
                $.ajax({
                    url: "{{ route('fabSendCuetxReceivePatchValveItems.getAvailableQty') }}",
                    method: 'get',
                    data: {
                        bswFabSendcurtxReceivpatchvalveEntry_id: bswFabSendcurtxReceivpatchvalveEntry_id,
                        dana_name_id: dana_name_id
                    },
                    success: function(response) {
                        console.log('quantity megha', response)
                        $('#avilableStock').val(response);
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            }
            //to set message
            function setMessage(element_id, message) {
                let errorContainer = document.getElementById(element_id);
                errorContainer.hidden = false;
                errorContainer.innerHTML = message;
                setTimeout(function() {
                    errorContainer.hidden = true;
                }, 5000);
            }
            $(document).on("click", '#lamsendEntry', function(e) {
                //   alert($(this).data("id")) megha
                let bswcurtexto_patchVal_Entry_id = {!! $bswFabSendcurtxReceivpatchvalveEntryData->id !!}
                e.preventDefault()
                // let name = $(this).data("name")
                let fabric_id = $(this).data("fabric_id")
                // console.log(fabric_id);
                let is_laminated = $(this).data("is_laminated")
                let gross_wt = $(this).data("gross_wt")
                let roll_no = $(this).data("roll_no")
                let net_wt = $(this).data("net_wt")
                let meter = $(this).data("meter")
                let gram_wt = $(this).data("gram_wt")
                let average_wt = $(this).data("average")
                $.ajax({
                    url: "{{ route('fabSendCuetxReceivePatchValveItems.store') }}",
                    method: "post",
                    data: {
                        "_token": $("meta[name='csrf-token']").attr("content"),
                        // "name": name,
                        "is_laminated": is_laminated,
                        "fabric_id": fabric_id,
                        "gross_wt": gross_wt,
                        "roll_no": roll_no,
                        "net_wt": net_wt,
                        "meter": meter,
                        "gram_wt": gram_wt,
                        "average": average_wt,
                        "bswcurtexto_patchVal_Entry_id": bswcurtexto_patchVal_Entry_id
                    },

                    success: function(response) {
                        $('#bswSentLamFabTbody').empty();
                        getSentFabItemsData();


                    },
                    error: function(error) {
                        console.log("error", error);
                    }
                })
            });

            function getSentFabItemsData() {
                let bsw_lam_fabcurtexToPatchVal_entry_id = {!! $bswFabSendcurtxReceivpatchvalveEntryData->id !!}
                $.ajax({
                    url: "{{ route('fabSendCuetxReceivePatchValveItems.lamFabData') }}",
                    method: 'get',
                    data: {
                        bsw_lam_fabcurtexToPatchVal_entry_id: bsw_lam_fabcurtexToPatchVal_entry_id
                    },
                    success: function(response) {
                        console.log('meghaaaaa', response)
                        tableData(response.items);
                        document.getElementById('totalOutMeter').value = (response.totalMeter).toFixed(
                            2);
                        document.getElementById('totalOutNW').value = (response.totalNetWt).toFixed(2);
                        // console.log('frtyhbvcfgh', response);
                    },
                    error: function(error) {
                        // Handle the error if the AJAX request fails
                        console.error(error);
                    }
                });
            }

            function tableData(data) {
                data.forEach(d => {
                    insertDataIntoTable(d)
                });
            }

            function insertDataIntoTable(d) {
                //  console.log(d);
                let group = d.gram_wt.split('-')[0];
                // let result = parseFloat(title) * parseFloat(group);

                let tr = $("<tr></tr>").appendTo('#bswSentFabToItemsTbody');

                tr.append(`<td>#</td>`);
                let fabricName = d.fabric ? d.fabric.name : d.printedfabric.name;
                tr.append(`<td>${fabricName}</td>`);
                tr.append(`<td>${d.roll_no}</td>`);
                tr.append(`<td>${d.gross_wt}</td>`);
                tr.append(`<td>${d.net_wt}</td>`);
                tr.append(`<td>${d.meter}</td>`)
                tr.append(`<td>${d.average}</td>`);
                tr.append(`<td>${d.gram_wt}</td>`);
                tr.append(
                    `<td><div class="btn-group"><a id="sendforCutAndFlat" data-group='${d.gram_wt}' data-title='${d.name}' href="${d.id}" data-id="${d.id}" class="btn btn-info">Send</a><a id="deletecurtexToPatchValveItem" class="btn btn-danger" data-id="${d.id}">delete</a></div></td>`
                );
            }
            //for different table stock

            function getDataStoredInThreeDifferentStocks() {
                // console.log('fuck');
                let bsw_lam_fabcurtexToPatchVal_entry_id = {!! $bswFabSendcurtxReceivpatchvalveEntryData->id !!}
                $.ajax({
                    url: "{{ route('toPatchValveUnlamFabricStock.threeDiffStockData') }}",
                    method: 'get',
                    data: {
                        bsw_lam_fabcurtexToPatchVal_entry_id: bsw_lam_fabcurtexToPatchVal_entry_id
                    },
                    success: function(response) {

                        if (response && response.commonStockOfThreeStock && response
                            .commonStockOfThreeStock.length > 0) {
                            threeDiffTableData(response.commonStockOfThreeStock);
                            if (response.fabricType && response.fabricType != null) {

                                if (response.fabricType ==
                                    "valve") {
                                    document.getElementById('totalValveMeter').value = (response
                                            .totalmeter)
                                        .toFixed(
                                            2);
                                    document.getElementById('totalValveNw').value = (response.netWeight)
                                        .toFixed(
                                            2);
                                } else if (response.fabricType ==
                                    "patch") {
                                    document.getElementById('totalPatchMeter').value = (response
                                            .totalmeter)
                                        .toFixed(
                                            2);
                                    document.getElementById('totalPatchNW').value = (response.netWeight)
                                        .toFixed(
                                            2);
                                } else {}
                            }
                        }
                        // console.log('frtyhbvcfgh', response);
                    },
                    error: function(error) {
                        // Handle the error if the AJAX request fails
                        console.error(error);
                    }
                });
            }

            function threeDiffTableData(data) {
                data.forEach(d => {
                    insertDataOfDiffTableIntoTable(d)
                });
            }

            function insertDataOfDiffTableIntoTable(d) {
                //  console.log(d);
                let group = d.gross_weight.split('-')[0];
                // let result = parseFloat(title) * parseFloat(group);

                let tr = $("<tr></tr>").appendTo('#threeDiffStockDataTbody');

                tr.append(`<td>#</td>`);
                tr.append(`<td>${d.curtex_to_patch_val_fabric.name}</td>`);
                tr.append(`<td>${d.roll_no}</td>`);
                tr.append(`<td>${d.gross_weight}</td>`);
                tr.append(`<td>${d.net_weight}</td>`);
                tr.append(`<td>${d.meter}</td>`)
                tr.append(`<td>${d.avg}</td>`);
                tr.append(`<td>${d.gram_weight}</td>`);
                // tr.append(
                //     `<td><div class="btn-group"><a id="sendforCutAndFlat" data-group='${d.gross_weight}' data-title='${d.curtex_to_patch_val_fabric.name}' href="${d.id}" data-id="${d.id}" class="btn btn-info">Send</a><a id="deletesendforlamination" class="btn btn-danger" data-id="${d.id}">delete</a></div></td>`
                // );
            }

            function getDanaConsumptionData() {
                let bsw_lam_fabcurtexToPatchVal_entry_id = {!! $bswFabSendcurtxReceivpatchvalveEntryData->id !!}
                $.ajax({
                    url: "{{ route('patchValvDanaConsumpt.getDanaConsumptData') }}",
                    method: 'get',
                    data: {
                        bsw_lam_fabcurtexToPatchVal_entry_id: bsw_lam_fabcurtexToPatchVal_entry_id
                    },
                    success: function(response) {
                        danaConsumptData(response);
                        // document.getElementById('totalLamMeter').value = response.totalMeter;
                        // document.getElementById('totalLamNetWt').value = (response.totalNetWt).toFixed(
                        //     2);
                    },
                    error: function(error) {
                        // Handle the error if the AJAX request fails
                        console.error(error);
                    }
                });
            }

            function danaConsumptData(data) {
                data.forEach(d => {
                    insertDataOfDanaConsumptIntoTable(d)
                });
            }

            function insertDataOfDanaConsumptIntoTable(d) {
                //  console.log(d);
                // let group = d.gross_weight.split('-')[0];
                // let result = parseFloat(title) * parseFloat(group);

                let tr = $("<tr></tr>").appendTo('#curtexToPatchValveDanaConsumptTbody');

                tr.append(`<td>#</td>`);
                tr.append(`<td>${d.godam.name}</td>`);
                tr.append(`<td>${d.dana_name.name}</td>`);
                tr.append(`<td>${d.quantity}</td>`);
                tr.append(
                    `<td><div class="btn-group"><a id="deleteDanaConsumpt" class="btn btn-danger" data-id="${d.id}">delete</a></div></td>`
                );
            }

            //delete items
            $(document).on('click', "#deletecurtexToPatchValveItem", function(e) {
                e.preventDefault();
                let itemId = this.getAttribute('data-id');
                new swal({
                        title: "Are you sure?",
                        text: "Do you want to delete Item.",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                        closeOnClickOutside: false,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url: '{{ route('fabSendCuetxReceivePatchValveItems.delete', ['id' => ':lol']) }}'
                                    .replace(':lol', itemId),
                                type: "DELETE",
                                data: {
                                    "_method": "DELETE",
                                    "_token": "{{ csrf_token() }}",
                                },
                                success: function(result) {
                                    $('#bswSentFabToItemsTbody').empty();
                                    getSentFabItemsData();

                                    // getDanaConsumptionData();
                                    // totalAmountCalculation();
                                    new swal({
                                        title: "Success",
                                        text: "Data deleted",
                                        type: 'success',
                                        timer: '1500'
                                    });
                                    // checkRowInTable();
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

            $(document).on('click', "#deleteDanaConsumpt", function(e) {
                e.preventDefault();
                let itemId = this.getAttribute('data-id');
                new swal({
                        title: "Are you sure?",
                        text: "Do you want to delete Item.",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                        closeOnClickOutside: false,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url: '{{ route('patchValvDanaConsumpt.delete', ['id' => ':lol']) }}'
                                    .replace(':lol', itemId),
                                type: "DELETE",
                                data: {
                                    "_method": "DELETE",
                                    "_token": "{{ csrf_token() }}",
                                },
                                success: function(result) {
                                    getDanaConsumptionData();
                                    // totalAmountCalculation();
                                    new swal({
                                        title: "Success",
                                        text: "Data deleted",
                                        type: 'success',
                                        timer: '1500'
                                    });
                                    // checkRowInTable();
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

            $(document).on('click', "#sendforCutAndFlat", function(e) {
                e.preventDefault();
                $('#staticBackdrop1').modal('show');
                // if (fabricType == "printed") {
                //     $("#fabricTypeModel").append($('<option>', {
                //         value: 'patch',
                //         text: 'patch'
                //     }));
                // } else if (fabricType == "lam") {
                //     $("#fabricTypeModel").append($('<option>', {
                //         value: 'valve',
                //         text: 'valve'
                //     }));
                // } else {
                //     $("#fabricTypeModel").append($('<option>', {
                //         value: 'roll',
                //         text: 'roll'
                //     }));
                // }
            });
            $('#fabricTypeModel').on('select2:select', function(e) {
                let fabric_type = e.params.data.id;
                $('#CortexToPatchValFabricId').empty();
                getcrtxToPtchValFabricName(fabric_type);
            });

            function getcrtxToPtchValFabricName(fabric_type) {
                $.ajax({
                    url: "{{ route('curtexToPatchValFabric.getcrtxToPtchValFabricName') }}",
                    method: 'get',
                    data: {
                        fabric_type: fabric_type
                    },
                    success: function(response) {
                        if (response.length === 0) {
                            $('#CortexToPatchValFabricId').prepend(
                                "<option value='' disabled selected>No items found</option>"
                            );
                        } else {
                            $('#CortexToPatchValFabricId').prepend(
                                "<option value='' disabled selected>Select required</option>"
                            );
                            response.forEach(function(item) {

                                setOptionInSelect('CortexToPatchValFabricId', item.id,
                                    item
                                    .name);
                            });
                            console.log('frtyhbvcfgh', response);
                        }
                    },
                    error: function(error) {
                        // Handle the error if the AJAX request fails
                        console.error(error);
                    }
                });
            }

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
