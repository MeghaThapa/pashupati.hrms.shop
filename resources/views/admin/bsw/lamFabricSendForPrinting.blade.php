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
                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('Receipt No') }}<span class="required-field">*</span>
                    </label>
                    <input type="text" class="form-control" id="billnumber" name="bill_number"
                        value="{{ $bswLamFabForPrintingEntry->receipt_no }}" readonly />
                    {{-- value="FSR-{{ getNepalidate(date('Y-m-d')).'-'.rand(0,9999)}}" --}}
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
                        value="{{ $bswLamFabForPrintingEntry->plantType->name }}" readonly>
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
                        value="{{ $bswLamFabForPrintingEntry->plantName->name }}" readonly>
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
                        value="{{ $bswLamFabForPrintingEntry->shift->name }}" readonly>
                    @error('shift_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('To godam') }}
                    </label>
                    <input type="text" name="godam_id" class="form-control" id="godamId"
                        value="{{ $bswLamFabForPrintingEntry->godam->name }}" readonly>
                    @error('shift_name_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                {{-- <div>
                <button type="submit" class="btn btn-sm btn-primary" style="margin-top:35px;">
                    Add
                </button>
            </div> --}}

            </div>
            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="size" class="col-form-label">{{ __('Group Name') }}<span
                            class="required-field">*</span>
                    </label>
                    <input type="text" name="group_id" class="form-control" id="groupId"
                        value="{{ $bswLamFabForPrintingEntry->group->name }}" readonly>
                    @error('group_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-2">
                    <label for="Fabric">Brand Bag</label>
                    <input type="text" name="bag_brand_id" class="form-control" id="bagBrandId"
                        value="{{ $bswLamFabForPrintingEntry->bagBrand->name }}" readonly>
                </div>
                {{-- <div>
                    <button id="getfabricsrelated" class="btn btn-primary mt-4">
                        Add
                    </button>
                </div> --}}
            </div>
        </form>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <label for="size" class="col-form-label">{{ __('Laminate Fabric') }}<span
                        class="required-field">*</span>
                </label>
                <select class="advance-select-box form-control" id="laminatedFabricId" name="laminated_fabric_id">
                    <option value="" disabled selected>{{ __('Select group Name') }}</option>
                    @foreach ($uniqueFabrics as $uniqueFabric)
                        <option value="{{ $uniqueFabric->name }}">
                            {{ $uniqueFabric->name }} / ({{ $uniqueFabric->fabricgroup->name }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 mt-5">
                <center>
                    <p>OR</p>
                </center>
            </div>
            <div class="col-md-2">
                <label for="size" class="col-form-label">{{ __('Rol No') }}<span class="required-field">*</span>
                </label>
                <input type="text" class="form-control" id="rollNo" name="roll_number" />
            </div>
        </div>
    </div>
    <hr>
    {{-- first --}}
    <div class="row">
        <div class="table-responsive table-custom my-3">
            <table class="table table-hover table-striped" id="bswLamFabTable">
                <thead class="table-info">
                    <tr>
                        <th>{{ __('Sr.No') }}</th>
                        <th>{{ __('Fabric Name') }}</th>
                        <th>{{ __('Roll No') }}</th>
                        <th>{{ __('G.W') }}</th>
                        <th>{{ __('N.W') }}</th>
                        <th>{{ __('Meter') }}</th>
                        <th>{{ __('Avg') }}</th>
                        {{-- <th>{{ __('Gram') }}</th> --}}
                        <th>{{ __('Send') }}</th>
                    </tr>
                </thead>
                <tbody id="bswLamFab"></tbody>
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
                                @foreach ($godams as $godam)
                                    <option value="{{ $godam->fromGodam->id }}">
                                        {{ $godam->fromGodam->name }}
                                    </option>
                                @endforeach
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
                            <input type="text" name="bswfabEntry_id" id="bswfabEntryId"
                                value="{{ $bswLamFabForPrintingEntry->id }}" hidden>
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
                                    @if ($printedFabrics && $printedFabrics != null)
                                        @foreach ($printedFabrics as $printedFabric)
                                            <option value="{{ $printedFabric->id }}">{{ $printedFabric->name }}</option>
                                        @endforeach
                                    @endif

                                </select>
                            </div>
                            <input type="text" id="size" name="size" placeholder="size" hidden>
                            <div class="col-md-1">
                                <label for="size" class="col-form-label">{{ __('Roll No') }}<span
                                        class="required-field">*</span>
                                </label>
                                <input type="text" step="any" min="0" class="form-control" id="rollNo"
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
                            <div class="col-md-2">
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
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script> --}}
    <script>
        $(document).ready(function() {
            $("#nepali-date-picker").nepaliDatePicker({});
            let todayNepaliDate = {!! isset($bswLamFabForPrintingEntry) ? json_encode($bswLamFabForPrintingEntry->date) : 'null' !!};
            if (todayNepaliDate !== null) {
                $("#nepali-date-picker").val(todayNepaliDate);
            }


            getDataOfBswSentLam();
            getDataOfPrintedLamFab();
            getDataOfDanaConsumption();
            $('#trimmingWst, #fabric_waste').on('input', calculateResult);
            let id = {!! json_encode($bswLamFabForPrintingEntry->id) !!};
            $('#printedFabEntireSave').on('click', function() {
                let bswfabEntry_id = {!! json_encode($bswLamFabForPrintingEntry->id) !!};
                let trimmimg_wastage = $('#trimmingWst').val();
                let fabric_waste = $('#fabric_waste').val();
                let wastage_godam_id = $('#godamIdWaste').val();
                // console.log('i am here');
                $.ajax({
                    url: "{{ route('fabPrintingEntry.saveEntire') }}",
                    method: 'post',
                    data: {
                        _token: "{{ csrf_token() }}",
                        bswfabEntry_id: bswfabEntry_id,
                        trimmimg_wastage: trimmimg_wastage,
                        fabric_waste: fabric_waste,
                        wastage_godam_id: wastage_godam_id
                    },
                    success: function(response) {
                        window.location.href =
                            "{{ route('BswLamFabSendForPrinting.index') }}";

                    },
                    error: function(xhr, status, error) {
                        setErrorMsg(xhr.responseJSON.message);
                    }
                });
            });
            //megha

            $("#netWeight, #meter").on("input", function(event) {
                calcuateAverageGram();
            });

            function calcuateAverageGram() {
                let netweight = parseFloat($('#netWeight').val());
                let meter = parseFloat($('#meter').val());
                let size = parseFloat($('#size').val());
                let farbicSelected = $('#printedFabId').find("option:selected:not([disabled])").length > 0;
                if (netweight && meter && farbicSelected) {
                    let avg = ((netweight / meter) * 1000).toFixed(2);
                    let gram = (avg / size).toFixed(2);
                    $('#average').val(avg);
                    $('#gram').val(gram);
                }
            }
            //watage calculation
            function calculateResult() {
                var trimmingWaste = parseFloat($('#trimmingWst').val()) || 0; // Parse as float or default to 0
                var fabricWaste = parseFloat($('#fabric_waste').val()) || 0; // Parse as float or default to 0
                var resultValue = trimmingWaste + fabricWaste; // Perform your desired calculation here
                $('#total_waste').val(resultValue.toFixed(2)); // Display the result with 2 decimal places
            }

            $(document).on('click', "#sendforPrinting", function(e) {
                e.preventDefault();
                $('#staticBackdrop1').modal('show');
            });
            $(document).on('click', '#addPrintedFab', function() {
                $('#printedFabricModal').modal('show');
            });
            $('#godamIdDanaCons').on('select2:select', function(e) {
                // console.log('i am here');
                let godam_id = e.params.data.id;
                $('#danaGroupId').empty();
                getDanaName(godam_id);
            });

            $('#danaNameId').on('select2:select', function(e) {
                // console.log('df');
                let godam_id = document.getElementById('godamIdDanaCons').value;
                let dana_name_id = e.params.data.id;
                $('#avilableStock').empty();
                getStockQuantity(godam_id, dana_name_id);
            });
            $('#printedFabId').on('select2:select', function(e) {
                var inputName = $(this).find("option:selected").text();
                var number = parseInt(inputName.match(/\d+/)[0]);
                $('#size').val(number);
                calcuateAverageGram();
            });


            // printsAndCuts.getStockQuantity
            function getStockQuantity(godam_id, dana_name_id) {
                $.ajax({
                    url: "{{ route('printsAndCuts.getStockQuantity') }}",
                    method: 'post',
                    data: {
                        _token: "{{ csrf_token() }}",
                        godam_id: godam_id,
                        dana_name_id: dana_name_id
                    },
                    success: function(response) {
                        document.getElementById('avilableStock').value = response.quantity;
                        // response.forEach(function(item) {
                        //     setOptionInSelect('danaNameId', item.dana_name.id, item.dana_name
                        //         .name);
                        // });
                    },
                    error: function(xhr, status, error) {
                        setErrorMsg(xhr.responseJSON.message);
                    }
                });
            }
            //save dana consumption
            document.getElementById('bswFabPrintDanaConsumption').addEventListener('submit',
                function(e) {
                    e.preventDefault();
                    const form = e.target;
                    let bswfabEntry_id = {!! json_encode($bswLamFabForPrintingEntry->id) !!};
                    //console.log('testing', printCutEntry_id);
                    let godam_id = form.elements['godam_id_consmpt'];
                    let dana_name_id = form.elements['dana_name_id'];
                    let quantity = form.elements['quantity'];
                    $.ajax({
                        url: "{{ route('printFabDanaConsumpt.store') }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            bswfabEntry_id: bswfabEntry_id,
                            godam_id: godam_id.value,
                            dana_name_id: dana_name_id.value,
                            quantity: quantity.value
                        },
                        success: function(response) {
                            console.log(response);
                            getDataOfDanaConsumption();

                            // removeAllTableRows('danaConsumption');
                            // getdanaConsumptionData();
                            // setIntoConsumptionTable(response);
                            // getdanaConsumptionData();
                            // updateStockQuantity();
                        },
                        error: function(xhr, status, error) {
                            setErrorMsg(xhr.responseJSON.message);
                        }
                    });
                });

            //dana consumption
            function getDataOfDanaConsumption() {
                let bsw_lam_fab_for_printing_entry_id = {!! $bswLamFabForPrintingEntry->id !!}
                $.ajax({
                    url: "{{ route('printFabDanaConsumpt.getData') }}",
                    method: 'get',
                    data: {
                        bsw_lam_fab_for_printing_entry_id: bsw_lam_fab_for_printing_entry_id
                    },
                    success: function(response) {

                        $('#bswLamPrintDanaConsumpt').empty();
                        consumptTableData(response.printedFabDanaConsumpt);
                        document.getElementById('totalItem').value = response.totalQuantity;
                    },
                    error: function(error) {
                        // Handle the error if the AJAX request fails
                        console.error(error);
                    }
                });
            }

            function getDanaName(godam_id) {
                // let godam_id = document.getElementById('godamId').value;
                $.ajax({
                    url: "{{ route('printsAndCuts.getDanaName') }}",
                    method: 'post',
                    data: {
                        _token: "{{ csrf_token() }}",
                        //dana_group_id: dana_group_id,
                        godam_id: godam_id

                    },
                    success: function(response) {

                        $('#danaNameId').prepend(
                            "<option value='' disabled selected>Select required</option>"
                        );
                        response.forEach(function(item) {
                            // console.log('sdfg', item);
                            setOptionInSelect('danaNameId', item.dana_name.id, item
                                .dana_name
                                .name);
                        });
                    },
                    error: function(xhr, status, error) {
                        setErrorMsg(xhr.responseJSON.message);
                    }
                });
            }

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

            $(document).on('submit', '#sendLamFabForPrinting', function(e) {
                e.preventDefault();
                const form = e.target;
                let bswfabEntry_id = form.elements['bswfabEntry_id'];
                let printed_fabric_id = form.elements['printed_fabric_id'];
                let roll_no = form.elements['roll_no'];
                let gross_weight = form.elements['gross_weight'];
                let net_weight = form.elements['net_weight'];
                let meter = form.elements['meter'];
                let average = form.elements['average'];
                let gram = form.elements['gram'];
                if (!printed_fabric_id.value && !roll_no.value && !gross_weight.value && !net_weight
                    .value && !meter.value && !average.value && !gram.value) {
                    setMessage('sendForPrintingError', 'Please Fill out all fields')
                    return false;
                }
                $.ajax({
                    url: "{{ route('bswLamPrintedFabStock.store') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        bswfabEntry_id: bswfabEntry_id.value,
                        printed_fabric_id: printed_fabric_id.value,
                        roll_no: roll_no.value,
                        gross_weight: gross_weight.value,
                        net_weight: net_weight.value,
                        meter: meter.value,
                        average: average.value,
                        gram: gram.value,
                    },

                    success: function(response) {
                        // console.log('name', response);
                        $('#staticBackdrop1').modal('hide');
                        $('#bswSentLamFabTbody').empty();
                        getDataOfPrintedLamFab();
                    },
                    error: function(xhr, status, error) {
                        setMessage('sendForPrintingError', xhr.responseJSON.message)
                    }
                })
            });

            $(document).on('submit', '#createPrintedFabricModel', function(e) {
                e.preventDefault();
                const form = e.target;
                let name = form.elements['name'];
                let status = form.elements['status'];
                if (!name.value && !status.value) {
                    setMessage('printedFabricError', 'Please Fill out all fields')
                    return false;
                }
                $.ajax({
                    url: "{{ route('printedFabric.store') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: name.value,
                        status: status.value,
                    },
                    success: function(response) {
                        console.log('name', response);
                        $('#printedFabricModal').modal('hide');

                        name.value = '';
                        status.value = '';

                        let selectElement = document.getElementById('printedFabId');
                        let optionElement = document.createElement('option');

                        optionElement.value = response.id;
                        optionElement.text = response.name;
                        selectElement.appendChild(optionElement);
                        optionElement.selected = true;
                    },
                    error: function(xhr, status, error) {

                        setMessage('printedFabricError', xhr.responseJSON.message)
                    }
                })
            });

            function setMessage(element_id, message) {
                let errorContainer = document.getElementById(element_id);
                errorContainer.hidden = false;
                errorContainer.innerHTML = message;
                setTimeout(function() {
                    errorContainer.hidden = true;
                }, 2000);
            }
            //for printed
            function getDataOfPrintedLamFab() {
                let bsw_lam_fab_for_printing_entry_id = {!! $bswLamFabForPrintingEntry->id !!}
                $.ajax({
                    url: "{{ route('bswLamPrintedFabStock.printedLamFabData') }}",
                    method: 'get',
                    data: {
                        bsw_lam_fab_for_printing_entry_id: bsw_lam_fab_for_printing_entry_id
                    },
                    success: function(response) {
                        // console.log('printedFabric data', response);

                        $('#bswPrintedLamFabTbody').empty();
                        printedTableData(response.bswLamPrintedFabricStocks);
                        document.getElementById('totalPrintInmtr').value = response.totalMeter;
                        document.getElementById('totalPrintNetWt').value = response.totalNetWt;
                    },
                    error: function(error) {
                        // Handle the error if the AJAX request fails
                        console.error(error);
                    }
                });
            }

            //for dana consumpt
            function consumptTableData(data) {
                data.forEach(d => {
                    insertDataIntoConsumptTable(d)
                });
            }

            function insertDataIntoConsumptTable(d) {
                // let group = d.gram_wt.split('-')[0];
                // let result = parseFloat(title) * parseFloat(group);

                let tr = $("<tr></tr>").appendTo('#bswLamPrintDanaConsumpt');

                tr.append(`<td>#</td>`);
                tr.append(`<td>${d.godam.name}</td>`);
                tr.append(`<td>${d.dana_name.name}</td>`);
                tr.append(`<td>${d.quantity}</td>`);
            }

            function printedTableData(data) {
                data.forEach(d => {
                    insertDataIntoPrintedFabTable(d)
                });
            }

            function insertDataIntoPrintedFabTable(d) {
                // let group = d.gram_wt.split('-')[0];
                // let result = parseFloat(title) * parseFloat(group);

                let tr = $("<tr></tr>").appendTo('#bswPrintedLamFabTbody');

                tr.append(`<td>#</td>`);
                tr.append(`<td>${d.printed_fabric.name}</td>`);
                tr.append(`<td>${d.roll_no}</td>`);
                tr.append(`<td>${d.gross_weight}</td>`);
                tr.append(`<td>${d.net_weight}</td>`);
                tr.append(`<td>${d.meter}</td>`)
                tr.append(`<td>${d.average}</td>`);
                tr.append(`<td>${d.gram_weight}</td>`);
                // tr.append(
                //     `<td><div class="btn-group"><a id="sendforPrinting" data-group='${d.gram_wt}' data-title='${d.fabric.name}' href="${d.id}" data-id="${d.id}" class="btn btn-info">Send</a><a id="deletesendforlamination" class="btn btn-danger" data-id="${d.id}">delete</a></div></td>`
                // );
            }
            //for printed end
            //for sent fabric
            function getDataOfBswSentLam() {
                let bsw_lam_fab_for_printing_entry_id = {!! $bswLamFabForPrintingEntry->id !!}
                $.ajax({
                    url: "{{ route('bswSentLamFab.lamFabData') }}",
                    method: 'get',
                    data: {
                        bsw_lam_fab_for_printing_entry_id: bsw_lam_fab_for_printing_entry_id
                    },
                    success: function(response) {
                        // console.log('megha', response)
                        tableData(response.bswSentLamFab);
                        document.getElementById('totalLamMeter').value = response.totalMeter;
                        document.getElementById('totalLamNetWt').value = (response.totalNetWt).toFixed(
                            2);
                        // console.log('frtyhbvcfgh', response);
                    },
                    error: function(error) {
                        // Handle the error if the AJAX request fails
                        console.error(error);
                    }
                });
            }

            $('#laminatedFabricId').on('select2:select', function(e) {
                bswDatatable.ajax.reload();
            });

            var bswDatatable = $('#bswLamFabTable').DataTable({
                lengthMenu: [
                    [5, 15, 30, -1],
                    ['5 rows', '15 rows', '30 rows', 'Show all']
                ],
                style: 'bootstrap',
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('BswLamFabSendForPrinting.lamFabData') }}',
                    data: function(data) {
                        data.lamFabName = $('#laminatedFabricId').val()
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
            //first table data
            // function putDataInBswLamFabTbl(data) {
            //     console.log(data)
            //     $("#bswLamFab").empty();
            //     data.forEach((d, index) => {
            //         let tr = $("<tr></tr>").appendTo("#bswLamFab");
            //         tr.append(`<td>${index+1}</td>`);
            //         tr.append(`<td>${d.name}</td>`);
            //         tr.append(`<td>${d.roll_no}</td>`);
            //         tr.append(`<td>${d.gross_wt}</td>`);
            //         tr.append(`<td>${d.net_wt}</td>`);
            //         tr.append(`<td>${d.meter}</td>`);
            //         tr.append(`<td>${d.average_wt}</td>`);

            //         tr.append(`<td>
        //                 <a class="btn btn-primary send_to_lower"
        //                     data-name='${d.name}'
        //                     data-gross_wt= "${d.gross_wt}"
        //                     data-roll_no= "${d.roll_no}"
        //                     data-id= "${d.id}"
        //                     data-fabric_id= "${d.fabric_id}"
        //                     data-net_wt = "${d.net_wt}"
        //                     data-meter = "${d.meter}"
        //                     data-gram_wt = "${d.gram_wt}"
        //                     data-average = "${d.average_wt}"
        //                     href="${d.id}">Send</a>
        //             </td>`);
            //     })
            // }
            /************************* Form Submission *************************/

            $(document).on("click", '#lamsendEntry', function(e) {
                // alert($(this).data("id"))
                let $bsw_lam_fab_for_printing_entry_id = {!! $bswLamFabForPrintingEntry->id !!}
                // console.log('bsw_lam_fab_for_printing_entry_id', $bsw_lam_fab_for_printing_entry_id);
                e.preventDefault()
                let name = $(this).data("name")
                let gross_wt = $(this).data("gross_wt")
                let net_wt = $(this).data("net_wt")
                let roll_no = $(this).data("roll_no")
                let meter = $(this).data("meter")
                let fabric_id = $(this).data("fabric_id")
                let average_wt = $(this).data("average")
                let gram_wt = $(this).data("gram_wt")

                let id = $(this).data("id")
                $.ajax({
                    url: "{{ route('bswSentLamFab.store') }}",
                    method: "post",
                    data: {
                        "_token": $("meta[name='csrf-token']").attr("content"),
                        "gross_wt": gross_wt,
                        "net_wt": net_wt,
                        "roll_no": roll_no,
                        "meter": meter,
                        "average": average_wt,
                        "gram_wt": gram_wt,
                        "fabric_stock_id": id,
                        "fabric_id": fabric_id,
                        "bsw_lam_fab_for_printing_entry_id": $bsw_lam_fab_for_printing_entry_id
                    },

                    success: function(response) {
                        $('#bswSentLamFabTbody').empty();
                        getDataOfBswSentLam();


                    },
                    error: function(error) {
                        console.log("error", error);
                    }
                })

            })

            function insertDataIntoTable(d) {
                //  console.log(d);
                let group = d.gram_wt.split('-')[0];
                // let result = parseFloat(title) * parseFloat(group);

                let tr = $("<tr></tr>").appendTo('#bswSentLamFabTbody');

                tr.append(`<td>#</td>`);
                tr.append(`<td>${d.fabric.name}</td>`);
                tr.append(`<td>${d.roll_no}</td>`);
                tr.append(`<td>${d.gross_wt}</td>`);
                tr.append(`<td>${d.net_wt}</td>`);
                tr.append(`<td>${d.meter}</td>`)
                tr.append(`<td>${d.average}</td>`);
                tr.append(`<td>${d.gram_wt}</td>`);
                tr.append(
                    `<td><div class="btn-group"><a id="sendforPrinting" data-group='${d.gram_wt}' data-title='${d.fabric.name}' href="${d.id}" data-id="${d.id}" class="btn btn-info">Send</a><a id="deletesendforlamination" class="btn btn-danger" data-id="${d.id}">delete</a></div></td>`
                );
            }

            function tableData(data) {
                data.forEach(d => {
                    insertDataIntoTable(d)
                });
            }
        });
    </script>
@endsection
