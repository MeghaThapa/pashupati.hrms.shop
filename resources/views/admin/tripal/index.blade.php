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
<div id="error_msg" class="alert alert-danger mt-2" hidden>

</div>
<div class="card-body p-0 m-0">
    <form id="createRawMaterial">
        @csrf

        <div class="row">
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Bill No') }}<span class="required-field">*</span>
                </label>
                <input type="text" class="form-control" id="billnumber" value="{{ $bill_no }}" name="bill_number"
                    required /> {{-- value="FSR-{{ getNepalidate(date('Y-m-d')).'-'.rand(0,9999)}}" --}}
            </div>

            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Bill Date') }}
                </label>
                <input type="date" value="{{ $bill_date }}" step="any" min="0" class="form-control calculator"
                    id="billDate" data-number="1" name="bill_date" placeholder="{{ __('Remarks') }}" min="1" required>

                @error('bill_date')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('To Godam') }}
                </label>
                <select class="advance-select-box form-control" id="toGodam" name="to_godam_id" required>
                    <option value="" selected disabled>{{ __('Select Godam Name') }}</option>
                    @foreach ($godam as $data)
                    <option value="{{ $data->id }}">{{ $data->name }}
                    </option>
                    @endforeach
                </select>
                @error('to_godam_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Plant Type') }}
                </label>
                <select class="advance-select-box form-control" id="plantType" name="plant_type_id" required>
                    <option value="" selected disabled>{{ __('Select Plant Name') }}</option>
                    {{-- @foreach ($danaNames as $danaName)
                    <option value="{{ $danaName->id }}">{{ $danaName->name }}
                    </option>
                    @endforeach --}}
                </select>
                @error('plant_type_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Plant Name') }}
                </label>
                <select class="advance-select-box form-control" id="plantName" name="plant_name_id" required>
                    <option value="" selected disabled>{{ __('Select Plant Name') }}</option>
                    {{-- @foreach ($danaNames as $danaName)
                    <option value="{{ $danaName->id }}">{{ $danaName->name }}
                    </option>
                    @endforeach --}}
                </select>
                @error('gp_no')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Shift') }}
                </label>
                <select class="advance-select-box form-control" id="shiftName" name="shift_name_id" disabled required>
                    <option value="" selected disabled>{{ __('Select Shift Name') }}</option>
                    @foreach ($shifts as $shift)
                    <option value="{{ $shift->id }}">{{ $shift->name }}
                    </option>
                    @endforeach
                </select>
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
                <label for="size" class="col-form-label">{{ __('Fabric Name') }}<span class="required-field">*</span>
                </label>
                <select class="advance-select-box form-control" id="fabricNameId" name="fabric_name_id"
                    required>
                    <option value="">{{ __('Select Fabric Name') }}</option>
                </select>
                @error('fabric_name_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="col-md-1 mt-5">
                <center>
                    <p>OR</p>
                </center>
            </div>

            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Roll') }}<span class="required-field">*</span>
                </label>
                <input type="text" step="any" min="0" class="form-control calculator" id="rollnumberfabric"
                    data-number="1" name="roll_number" min="1" disabled required>

                @error('fabric_name_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div>
                <button id="getfabricsrelated" disabled class="btn btn-primary mt-4">
                    Add
                </button>
            </div>
        </div>
    </form>
</div>
<div class="row">
    <div class="Ajaxdata col-md-12">
        <div class="p-0 table-responsive table-custom my-3">
            <table class="table" id="rawMaterialItemTable" >
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
                        <th>{{__('Send')}}</th>
                    </tr>
                </thead>

                <tbody id="rawMaterialItemTbody">
                </tbody>

            </table>
        </div>

    </div>
</div>
<hr>
{{-- <h1 class='text-center'>Compare Lam and Unlam</h1> --}}
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <p style="font-weight: bold;">Unlaminated</p>
            </div>
            <div class="card-body table-responsive" style="padding:opx !important">
                <table class="table table-bordered" id="compareunlamtable">
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

                    <tbody id="compareunlamtbody">
                    </tbody>

                </table>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <p style="font-weight: bold;">Single Laminated</p>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered" id="comparelamtable">
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

                    <tbody id="comparelamtbody">
                    </tbody>

                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6 float-right ml-3">
        {{-- <button class="btn btn-danger discard">Discard</button> --}}
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-7">
        <form id="addSingleTripalDanaConsumption">
            <div class="card p-2">
                <div class="row">
                    <div class="col-md-4">
                        <label for="cut_length">Godam</label>
                        <select
                            class="advance-select-box form-control  @error('godam_id') is-invalid @enderror"
                            id="godamId" name="godam_id" required>
                            <option value=" " selected disabled>{{ __('Select Godam') }}</option>
                            @foreach ($godams as $godam)
                                <option value="{{ $godam->fromGodam->id }}">
                                    {{ $godam->fromGodam->name }}
                                </option>
                            @endforeach
                            <input type="hidden" name="bill_no" value="{{$bill_no}}" id="bill_no">
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

                    <div class="col-md-4">
                        <label for="cut_length">Dana Name</label>
                        <select
                            class="advance-select-box form-control  @error('dana_name_id') is-invalid @enderror"
                            id="danaNameId" name="dana_name_id" required>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="cut_length">Available</label>
                        <input type="text" class="form-control" id="avilableStock"
                            name="avilable_stock" readonly>
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
    <div class="col-md-5">
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
            <tbody id="printsCutsDanaConsumpt">
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    

    <div class="card col-md-12">
        <div class="card-body m-2 p-5">
            <div class="col-md-12" style="height: 100%;">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <div>
                            <label for="size" class="col-form-label">{{ __('Total Ul Mtr:') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator" id="total_ul_in_mtr"
                                data-number="1" name="total_ul_in_mtr" min="1" readonly required>
                            @error('total_ul_in_mtr')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="size" class="col-form-label">{{ __('Total Ul Net Wt:') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator" id="total_ul_net_wt"
                                data-number="1" name="total_ul_net_wt" min="1" readonly required>
                            @error('total_ul_net_wt')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div>
                            <label for="size" class="col-form-label">{{ __('Polo Was:') }}<span class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator" id="polo_waste" data-number="1"
                                name="polo_waste" min="1" required>
                            @error('polo_waste')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <input type="hidden" name="bill" id="bill" value="{{$bill_no}}">
                        </div>
                        <div>
                            <label for="size" class="col-form-label">{{ __('Total Dana:') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="number" step="any" min="0" class="form-control calculator" id="totl_dana" value="{{$sumdana}}" data-number="1"
                                name="totl_dana" min="1" readonly required>
                            @error('polo_waste')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
        
                    </div>
        
                    <div class="col-md-4 form-group">
                        <div>
                            <label for="size" class="col-form-label">{{ __('Total SingleLam Mtr:') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator" id="total_lam_in_mtr"
                                data-number="1" name="total_lam_in_mtr" min="1" readonly required>
                            @error('total_lam_in_mtr')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="size" class="col-form-label">{{ __('Total SingleLam Net Wt:') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator" id="total_lam_net_wt"
                                data-number="1" name="total_lam_net_wt" min="1" readonly required>
                            @error('total_lam_net_wt')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div>
                            <label for="size" class="col-form-label">{{ __('Fabric Was:') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator" id="fabric_waste"
                                data-number="1" name="fabric_waste" min="1" required>
                            @error('fabric_waste')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div>
                            <label for="size" class="col-form-label">{{ __('Total Was:') }}<span class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator" id="total_waste"
                                data-number="1" name="total_waste" min="1" readonly required>
                            @error('polo_waste')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
        
                    <div class="col-md-4" style="margin-top:70px;">
                        <div>
                            <label for="size" class="col-form-label">{{ __('Diff UL Lam Meter :') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator" id="diff_unLam_lamNw"
                                data-number="1" name="diff_unLam_lamNw" min="1" readonly required>
                            @error('diff_unLam_lamNw')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="size" class="col-form-label">{{ __('Total Diff Net Weight:') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator" id="total_diff" data-number="1"
                                name="total_diff" min="1" readonly required>
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
            <input type="hidden" name="selectedDanaID" class="form-control" id="selectedDanaID" readonly>
            <button class="btn btn-info" disabled id="finalUpdate">Update</button>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id='sendtolaminationform' action='{{ route("tripal.store") }}' method="post">
                    @csrf
                    <input type="hidden" name="bill_no" id="bill_nos" value="{{$bill_no}}">
                    <input type="hidden" name="bill_date" id="bill_dates" value="{{$bill_date}}">
                    <input type="hidden" name="godam_id" id="godam_ids">
                    <input type="hidden" name="planttype_id" id="planttype_id">
                    <input type="hidden" name="plantname_id" id="plantname_id">
                    <input type="text" name="tripal_decimalname" id="tripal_decimalname">
                    <div class="card">
                        <div class="card-body">
                            <div class="row m-2 p-3">
                                <div class="col-md-6">
                                    <label for="">Fabric</label>
                                    <input class='form-control' type="text" name="laminated_fabric_name"
                                        id="laminated_fabric_name" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Group</label>
                                    <input class='form-control' type="text" name="laminated_fabric_group"
                                        id="laminated_fabric_group" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Net weight gram</label>
                                    <input class='form-control' type="text" name="standard_weight_gram"
                                        id="standard_weight_gram" readonly>
                                </div>
                            </div>
                            <hr>
                            
                            <div class="row m-2 p-3 d-flex justify-content-center">
                                <div class="col-md-2">
                                    <label for="">Roll</label>
                                    <input class='form-control' type="text" name="laminated_roll_no"
                                        id="laminated_roll_no">
                                </div>
                                <div class="col-md-2">
                                    <label for="">Gross Weight</label>
                                    <input class='form-control' type="text" name="laminated_gross_weight"
                                        id="laminated_gross_weight">
                                </div>
                                
                                
                                
                                <div class="col-md-2">
                                    <label for="">Net Weight</label>
                                    <input class='form-control' type="text" name="laminated_net_weight"
                                        id="laminated_net_weight">
                                </div>
                                <div class="col-md-2">
                                    <label for="">Meter</label>
                                    <input class='form-control' type="text" name="laminated_meter" id="laminated_meter">
                                </div>
                                <div class="col-md-2">
                                    <label for="">Average</label>
                                    <input class='form-control' type="text" name="laminated_avg_weight"
                                        id="laminated_avg_weight" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Gram</label>
                                    <input class='form-control' type="text" name="laminated_gram" id="laminated_gram" readonly>
                                </div>
                            </div>
                            <div class="row m-2 p-3 d-flex justify-content-center">
                                <div class="col-md-2">
                                    <label for="">Roll</label>
                                    <input class='form-control' type="text" name="laminated_roll_no_2"
                                        id="laminated_roll_no_2">
                                </div>
                                <div class="col-md-2">
                                    <label for="">Gross Weight</label>
                                    <input class='form-control' type="text" name="laminated_gross_weight_2"
                                        id="laminated_gross_weight_2">
                                </div>
                                
                                
                                
                                <div class="col-md-2">
                                    <label for="">Net Weight</label>
                                    <input class='form-control' type="text" name="laminated_net_weight_2"
                                        id="laminated_net_weight_2">
                                </div>

                                <div class="col-md-2">
                                    <label for="">Meter</label>
                                    <input class='form-control' type="text" name="laminated_meter_2"
                                        id="laminated_meter_2">
                                </div>
                                <div class="col-md-2">
                                    <label for="">Average</label>
                                    <input class='form-control' type="text" name="laminated_avg_weight_2"
                                        id="laminated_avg_weight_2" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Gram</label>
                                    <input class='form-control' type="text" name="laminated_gram_2"
                                        id="laminated_gram_2" readonly>
                                </div>
                            </div>
                            <div class="row m-2 p-3 d-flex justify-content-center">
                                <div class="col-md-2">
                                    <label for="">Roll</label>
                                    <input class='form-control' type="text" name="laminated_roll_no_3"
                                        id="laminated_roll_no_3">
                                </div>
                                <div class="col-md-2">
                                    <label for="">Gross Weight</label>
                                    <input class='form-control' type="text" name="laminated_gross_weight_3"
                                        id="laminated_gross_weight_3">
                                </div>
                                
                                
                                
                                <div class="col-md-2">
                                    <label for="">Net Weight</label>
                                    <input class='form-control' type="text" name="laminated_net_weight_3"
                                        id="laminated_net_weight_3">
                                </div>

                                <div class="col-md-2">
                                    <label for="">Meter</label>
                                    <input class='form-control' type="text" name="laminated_meter_3"
                                        id="laminated_meter_3">
                                </div>
                                <div class="col-md-2">
                                    <label for="">Average</label>
                                    <input class='form-control' type="text" name="laminated_avg_weight_3"
                                        id="laminated_avg_weight_3" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Gram</label>
                                    <input class='form-control' type="text" name="laminated_gram_3"
                                        id="laminated_gram_3" readonly>
                                </div>
                            </div>
                            <hr>
                            <div class="row d-flex justify-content-center text-center mb-2-">
                                {{-- <div class="col-md-6">
                                    <button type='submit' class="btn btn-info">Create Group</button>
                                </div> --}}
                                <div class="col-md-6">
                                    <button type='submit' class="btn btn-info">Update</button>
                                </div>
                                <input type="hidden" name="fabricsid"
                                    id="fabricsid">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

<script>


 

    $(document).on("keyup","#laminated_meter",function(e){

        let net_wt = parseInt($("#laminated_net_weight").val());
        let meter = parseInt($("#laminated_meter").val());
        let test = (net_wt / meter) * 1000;
        let average = test.toFixed(2);
        $("#laminated_avg_weight").val(average);


        let tripal_decimalname = parseInt($("#tripal_decimalname").val());

        let data = (tripal_decimalname / 2);
        // let data = (tripal_decimalname / 2);
        let datas = data.toFixed(2);

        let gram = (average) / datas;
        let finalgram = gram.toFixed(2);


        $("#laminated_gram").val(finalgram);

    });

    $(document).on("keyup","#laminated_net_weight",function(e){

        let net_wt = parseInt($("#laminated_net_weight").val());
        let meter = parseInt($("#laminated_meter").val());
        let average = (net_wt / meter) * 1000;
        $("#laminated_avg_weight").val(average);

    });

    $(document).on("keyup","#laminated_meter_2",function(e){

        let net_wt = parseInt($("#laminated_net_weight_2").val());
        let meter = parseInt($("#laminated_meter_2").val());
        let test = (net_wt / meter) * 1000;
        let average = Math.round(test);
        $("#laminated_avg_weight_2").val(average);

        let tripal_decimalname = parseInt($("#tripal_decimalname").val());

        let data = (tripal_decimalname / 2);
        // let data = (tripal_decimalname / 2);
        let datas = data.toFixed(2);

        let gram = (average) / datas;
        let finalgram = gram.toFixed(2);


        $("#laminated_gram_2").val(finalgram);

    });

    $(document).on("keyup","#laminated_net_weight_2",function(e){

        let net_wt = parseInt($("#laminated_net_weight_2").val());
        let meter = parseInt($("#laminated_meter_2").val());
        let average = (net_wt / meter) * 1000;
        $("#laminated_avg_weight_2").val(average);

    });

    $(document).on("keyup","#laminated_meter_3",function(e){

        let net_wt = parseInt($("#laminated_net_weight_3").val());
        let meter = parseInt($("#laminated_meter_3").val());
        let test = (net_wt / meter) * 1000;
        let average = Math.round(test);
        $("#laminated_avg_weight_3").val(average);

        let tripal_decimalname = parseInt($("#tripal_decimalname").val());

        let data = (tripal_decimalname / 2);
        // let data = (tripal_decimalname / 2);
        let datas = data.toFixed(2);

        let gram = (average) / datas;
        let finalgram = gram.toFixed(2);


        $("#laminated_gram_3").val(finalgram);

    });

    $(document).on("keyup","#laminated_net_weight_3",function(e){

        let net_wt = parseInt($("#laminated_net_weight_3").val());
        let meter = parseInt($("#laminated_meter_3").val());
        let average = (net_wt / meter) * 1000;
        $("#laminated_avg_weight_3").val(average);

    });

</script>
<script>
    $(document).ready(function(){
        /**************************** Ajax Calls **************************/
        // callunlaminatedfabricajax();
         // $('#fabricNameId').prop('disabled',true);
        comparelamandunlam();

        pageRefresh();

        async function pageRefresh() {
            await getdanaConsumptionData();
            deleteDanaConsumptionData();
        }

        let sn = 1;

        //remove all table data
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

        $("body").on("submit","#wastesubmit", function(event){
            // Pace.start();
            var fabric_name = $('#fabric_name').val(),
            fabric_gsm = $('#fabric_gsm').val(),
            token = $('meta[name="csrf-token"]').attr('content');
              // $('#idcardShift').val(godam_id);
            $.ajax({
              type:"POST",
              dataType:"JSON",
              url:"{{route('getFabricNameColorList')}}",
              data:{
                _token: token,
                fabric_name: fabric_name,
                fabric_gsm: fabric_gsm
            },
            success: function(response){
                console.log(response);
                $('#fabric_color').html('');
                $('#fabric_color').append('<option value="">--Choose FabricName--</option>');
                $.each( response, function( i, val ) {
                  $('#fabric_color').append('<option value='+val.color+'>'+val.color+'</option>');
              });
            },
            error: function(event){
                alert("Sorry");
            }
        });
        });

        $("#toGodam").change(function(e){

            let department_id =  $(this).val();
            let geturl = "{{ route('fabricSendReceive.get.planttype',['id'=>':id']) }}"
            $("#godam_ids").val(department_id);
            $.ajax({
                url:geturl.replace(':id',department_id),
                beforeSend:function(){
                    console.log('Getting Plant type');
                },
                success:function(response){
                    addplanttype(response);
                    addfabrictype(response);
                },
                error:function(error){
                    console.log(error);
                }
            });
        });

        $('#godamId').on('select2:select', function(e) {
            let godam_id = e.params.data.id;
            $('#danaGroupId').empty();
            getDanaName(godam_id);
        });

        $('#danaNameId').on('select2:select', function(e) {
            // console.log('df');
            // debugger;
            let godam_id = document.getElementById('godamId').value;
            let dana_name_id = e.params.data.id;
            $('#avilableStock').empty();
            getStockQuantity(godam_id, dana_name_id);
        });

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
                    console.log('stockQty', response);
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


        function getDanaName(dana_group_id) {
            let godam_id = document.getElementById('godamId').value;
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

        function getDanaGroup(godam_id) {
            $.ajax({
                url: "{{ route('printsAndCuts.getDanaGroup') }}",
                method: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    godam_id: godam_id,
                },
                success: function(response) {
                    $('#danaGroupId').prepend(
                        "<option value='' disabled selected>Select required data</option>"
                    );
                    response.danaGroups.forEach(function(item) {
                        setOptionInSelect('danaGroupId', item.dana_group.id,
                            item.dana_group
                            .name);
                    });
                },
                error: function(xhr, status, error) {
                    setErrorMsg(xhr.responseJSON.message);
                }
            });
        }

        //save addPrintsAndDanaConsumption
        document.getElementById('addSingleTripalDanaConsumption').addEventListener('submit',
            function(e) {
                //let printCutEntry_id = {!! json_encode($data->id) !!};
                e.preventDefault();
                debugger;
                const form = e.target;
                let bill_no = $("#bill_no").val();
                //console.log('testing', printCutEntry_id);
                let godam_id = form.elements['godam_id'];
                let dana_name_id = form.elements['dana_name_id'];
                let quantity = form.elements['quantity'];
                $.ajax({
                    url: "{{ route('singleTripalDanaConsumption.store') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        bill_no: bill_no,
                        godam_id: godam_id.value,
                        dana_name_id: dana_name_id.value,
                        quantity: quantity.value
                    },
                    success: function(response) {
                        removeAllTableRows('danaConsumption');
                        getdanaConsumptionData();
                        // setIntoConsumptionTable(response);
                        // getdanaConsumptionData();
                        updateStockQuantity();
                    },
                    error: function(xhr, status, error) {
                        setErrorMsg(xhr.responseJSON.message);
                    }
                });
        });

        function updateStockQuantity() {
            let godam_id = $('#godamId').val();
            // let dana_group_id = $('#danaGroupId').val();
            let dana_name_id = $('#danaNameId').val();
            getStockQuantity(godam_id, dana_name_id);
        }

        //remove all table data
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

        function setIntoConsumptionTable(res) {
            var html = "";
            html = "<tr id=editConsumptRow-" + res.id + "><td>" + sn +
                "</td><td class='rowGodamName'>" + res.godam.name +
                "</td><td class='rowDanaName'>" + res.dana_name.name +
                "</td><td class='rowQuantity'>" + res.quantity +
                "</td></tr>";


            document.getElementById('printsCutsDanaConsumpt').innerHTML += html;
            sn++;
            // Clearing the input fields
            // clearInputFields();
        }

        function getdanaConsumptionData() {
            return new Promise(function(resolve, reject) {
                let bill_no = $("#bill_no").val();
                //console.log('printAndCutEntry_id_data', printAndCutEntry_id_data);
                $.ajax({
                    url: '{{ route('singleTripal.getSingleTripalDanaConsumption') }}',
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        bill_no: bill_no
                    },
                    success: function(response) {
                        console.log('consumption data hgftyuhb', response);
                        response.forEach(function(response) {
                            console.log('qty', response.quantity)
                            setIntoConsumptionTable(response)
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

        function setErrorMsg(errorMessage) {
            let errorContainer = document.getElementById('error_msg');
            errorContainer.hidden = false;
            errorContainer.innerHTML = errorMessage;
            setTimeout(function() {
                errorContainer.hidden = true;
            }, 2000);
        }



        $("#plantType").change(function(e){
            let department_id =  $(this).val();
            let geturl = "{{ route('fabricSendReceive.get.plantname',['id'=>':id']) }}";
            $("#planttype_id").val(department_id);
            $.ajax({
                url:geturl.replace(':id',department_id),
                beforeSend:function(){
                    console.log('Getting Plant Name');
                },
                success:function(response){
                    addplantname(response);
                },
                error:function(error){
                    console.log(error);
                }
            });
        });

        $("#plantName").change(function(e){
            let department_id =  $(this).val();
            $("#plantname_id").val(department_id);
        
        });




        /**************************** Ajax Calls End **************************/
    });

    /**************************** Ajax functions **************************/

 

    function addplanttype(data){
        $("#plantType").empty();
        $('#plantType').append(`<option value="" disabled selected>--Select Plant Type--</option>`);
        data.planttype.forEach( d => {
            $('#plantType').append(`<option value="${d.id}">${d.name}</option>`);
        });
    }

    function addfabrictype(data){
        $("#fabricNameId").empty();
        $('#fabricNameId').append(`<option value="" disabled selected>--Select Fabric--</option>`);
        data.godamfabrics.forEach( d => {
            $('#fabricNameId').append(`<option value="${d.id}">${d.name}</option>`);
        });
    }

    function addplantname(data){
        $("#plantName").empty();
        $('#plantName').append(`<option value="" disabled selected>--Select Plant Name--</option>`);
        data.plantname.forEach( d => {
            // if(d.name == '')
            $('#plantName').append(`<option value="${d.id}">${d.name}</option>`);


        });
    }
    /**************************** Ajax functions **************************/

    /************************* Form Submission *************************/
    $(document).ready(function(){
        $(document).on('click','#getfabricsrelated',function(e){
            e.preventDefault();
            let formData = $(this).serialize();

            var bill_number = $('#bill_nos').val(),
            bill_date = $('#bill_dates').val(),
            godam_id = $('#godam_ids').val(),
            plantype_id = $('#planttype_id').val(),
            plantname_id = $('#plantname_id').val(),
            shift_id = $('#shift_id').val(),
            fabric_id = $('#fabricNameId').val(),
            roll = $('#rollnumberfabric').val();

           $.ajax({
            url : "{{ route('tripal.getFabric') }}",
            method: 'get',
            type:"POST",
            dataType:"JSON",
            data:{
                '_token' : $('meta[name="csrf-token"]').attr('content'),
                'data' : formData,
                'fabric_id' : fabric_id,
                'roll' : roll

            },
            beforeSend:function(){
                console.log('sending form');
            },
          
            success:function(response){
                emptytable();
                if(response.response != '404'){
                    filltable(response);
                }else{
                    console.log(response.response);
                }

            },
            error:function(error){
                console.log(error);
            }
           });
        });
    })

 
    /************************* Form Submission *************************/

    /************************* Other Functionalities ***********************/
    $("#plantName").change(function(e){
        $('#shiftName').prop('disabled',false);
        $('#createRawMaterial').attr({
            'action' : "{{ route('fabricSendReceive.send.unlaminated') }}",
            'method' : "post"
        });
        $("#rollnumberfabric").prop('disabled',false);
    });

    $("#fabricNameId").change(function(e){
        getfabricsrelated_enable();
        $("#rollnumberfabric").prop('required',false);
        $("#rollnumberfabric").prop('disabled',true);
    });



    $("#rollnumberfabric").keyup(function(e){
        getfabricsrelated_enable();
        $("#fabricNameId").prop('disabled',true);
        $("#fabricNameId").prop('required',false);
    });

    function getfabricsrelated_enable(){
        $('#getfabricsrelated').prop('disabled',false);
    }

    function emptytable(){
        $('#rawMaterialItemTbody').empty();
    }


    function filltable(data){
        // console.log(data.response);
        data.response.forEach(d => {
            console.log(d.name);
            let title = d.name;
            let group = d.average_wt.split('-')[0];
            let result = parseFloat(title) * parseFloat(group);

            let tr = $("<tr></tr>").appendTo('#rawMaterialItemTbody');

            tr.append(`<td>#</td>`);
            tr.append(`<td>${d.name}</td>`);
            tr.append(`<td>${d.roll_no}</td>`);
            tr.append(`<td>${d.gross_wt}</td>`);
            tr.append(`<td>${d.net_wt}</td>`);
            tr.append(`<td>${d.meter}</td>`)
            tr.append(`<td>${d.meter}</td>`);
            tr.append(`<td>${d.average_wt}</td>`);
            tr.append(`<td><div class="btn-group"><a id="sendforlamination" data-group='${d.fabricgroup.name}' data-standard='${d.net_wt}' data-title='${d.name}' href="${d.id}" data-id="${d.id}" class="btn btn-info">Send</a></div></td>`);
        });
    }

    function emptyform(){
        $("#createRawMaterial")[0].reset();
    }

    /************************* Other Functionalities ***********************/

    /************************* Send for lamination **************************/
    $(document).ready(function(){
        $(document).on('click',"#sendforlamination",function(e){
            e.preventDefault();
            $('#staticBackdrop1').modal('show');
            let titleold = $('#staticBackdropLabel').text('');
            let title = $(this).attr('data-title');
            let id = $(this).attr('data-id');
            $("#laminated_fabric_name").val(title+"(SingleLam)");
            let laminated_fabric_group = $(this).attr('data-group');
            $("#laminated_fabric_group").val(laminated_fabric_group);
            let standard_weight_gram = $(this).attr('data-standard');
            $("#standard_weight_gram").val(standard_weight_gram);
            $('#staticBackdropLabel').text(title+" -> id = "+id);
            $("#fabricsid").val(id);

            var titles = $(this).attr('data-title'),
                ids = $(this).attr('data-id'),
                token = $('meta[name="csrf-token"]').attr('content');

                // debugger;

                $.ajax({
                  type:"POST",
                  dataType:"JSON",
                  url:"{{route('getFilterFabricTripalList')}}",
                  data:{
                    _token: token,
                    titles: titles,
                    ids: ids,
                  },
                  success: function(response){
                    $('#tripal_decimalname').val(response.name);

                  },
                  error: function(event){
                    alert("Sorry");
                  }
                });

         
        });
    });
    // $(document).on('hidden.bs.modal', '#staticBackdrop1', function(e) {
    //     $(this).removeAttr('action');
    // });
    $('#staticBackdrop1').on('hidden.bs.modal',function(e) {
        $(this).removeAttr('action');
    });

    $("#sendtolaminationform").submit(function(e) {
        e.preventDefault();
        console.log(e);
        let action = $(this).attr('action');
        let method = $(this).attr('method');
        data = $("#sendtolaminationform").serialize();
        sendtolaminationformpostsubmit(data,action,method);
    });

    function sendtolaminationformpostsubmit(formdata,formaction,formmethod){
        $.ajax({
            url:formaction,
            method:formmethod,
            data:{
                "_token": $('meta[name="csrf-token"]').attr('content'),
                "data" : formdata
            },
            beforeSend:function(){
                console.log('sending');
            },
            success:function(response){
                // callunlaminatedfabricajax();
                comparelamandunlam();
                $('#staticBackdrop1').modal('hide');
                console.log(response);

            },
            error:function(error){
                console.log(error);
            }
        });
    }


    function comparelamandunlam(){
        $.ajax({
            url : "{{ route('tripal.getUnlamSingleLam') }}",
            method:"get",
            success:function(response){
                emptycomparelamtbody();
                emptycompareunlamtbody();
                putonlamtbody(response);
                $("#sendtolaminationform")[0].reset();
                weightdiffs(response);
            },
            error:function(error){
                console.log(error);
            }
        });
    }
    /************************* Send for lamination **************************/

    /********** put on tbodys compare *********************/
    function emptycomparelamtbody(){
        $("#comparelamtbody").empty();
    }
    function emptycompareunlamtbody(){
        $("#compareunlamtbody").empty();
    }

    function putonlamtbody(response){
        console.log(response);
        response.lam.forEach(element => {
            let tr = $("<tr></tr>").appendTo("#comparelamtbody");
            tr.append(`<td>#</td>`);
            tr.append(`<td>${element.name}</td>`);
            tr.append(`<td>${element.roll_no}</td>`);
            tr.append(`<td>${element.net_wt}</td>`);
            tr.append(`<td>${element.gross_wt}</td>`);
            tr.append(`<td>${element.meter}</td>`);
            tr.append(`<td>${element.average_wt}</td>`);
            tr.append(`<td>${element.gram}</td>`);
        });

        response.unlam.forEach(element => {
            let tr = $("<tr></tr>").appendTo("#compareunlamtbody");
            tr.append(`<td>#</td>`);
            tr.append(`<td>${element.fabric.name}</td>`);
            tr.append(`<td>${element.roll_no}</td>`);
            tr.append(`<td>${element.net_wt}</td>`);
            tr.append(`<td>${element.gross_wt}</td>`);
            tr.append(`<td>${element.meter}</td>`);
            tr.append(`<td>${element.average}</td>`);
            tr.append(`<td>${element.gram}</td>`);
        });
        
    }
    /********** put on tbodys *********************/

    /********************** dana consumption and wastage and weight differences ******************************/
    function weightdiffs(response){
        let total_ul_in_mtr = response.ul_mtr_total;
        let total_ul_net_wt = response.ul_net_wt_total;
        let total_lam_in_mtr = response.lam_mtr_total;
        let total_lam_net_wt = response.lam_net_wt_total;

        $("#total_ul_in_mtr").val(total_ul_in_mtr);
        $("#total_ul_net_wt").val(total_ul_net_wt);
        $("#total_lam_in_mtr").val(total_lam_in_mtr);
        $("#total_lam_net_wt").val(total_lam_net_wt);

        let diff_unLam_lamNw = parseFloat(total_lam_in_mtr) - parseFloat(total_ul_in_mtr);
        diff_unLam_lamNw = diff_unLam_lamNw.toFixed(4);
        $('#diff_unLam_lamNw').val(diff_unLam_lamNw);

        let total_diff = parseFloat(total_lam_net_wt) - parseFloat(total_ul_net_wt);
        total_diff = total_diff.toFixed(4);
        $("#total_diff").val(total_diff);
    }

    $("#danaNameId").on("change",function(e){
        $("#add_dana_consumption_quantity").prop("disabled",false);
    });


    $(document).on("keyup","#add_dana_consumption_quantity",function(e){
        $("#add_dana_consumption").prop("disabled",false);
    });

    $(document).on("keyup","#polo_waste",function(e){
        $("#finalUpdate").prop("disabled",false);
    });

    $(document).on("keyup","#fabric_waste",function(e){
        $("#finalUpdate").prop("disabled",false);
    });


    $(document).on("click","#add_dana_consumption",function(e){
        let dana = $("#danaNameId").val();
        let consumption = $("#add_dana_consumption_quantity").val();
   
        $.ajax({
            url:"{{ route('dana.autoload.checkAutoloadQuantity') }}",
            method : 'post',
            data:{
                '_token' : $('meta[name="csrf-token"]').attr('content'),
                'danaid' : dana
            },
            beforeSend:function(){
                console.log('Getting Plant type');
            },
            success:function(response){
                // console.log(response.itemquantity);

             // if(consumption > response.itemquantity){
             //    alert('kk');
             // }
             // else{
             //    alert('issue');

             // }

                // if(response.itemquantity > consumption ? 'hi' : 'ok'){
                //     alert('stock exceeded');
                // }
             
                if (consumption.trim() === '') {
                    alert("add quantity");
                }else{
                    $("#totl_dana").val(consumption);

                    $("#selectedDanaID").val(dana);
                }   
             
            },
            error:function(error){
                console.log(error);
            }


        });

      
       


    });



    $(document).on("keyup","#fabric_waste",function(e){
        let polo_waste = parseInt($("#polo_waste").val());
        let fabric_waste = parseInt($("#fabric_waste").val());
        let total_waste = polo_waste + fabric_waste;
        $("#total_waste").val(total_waste);

    });



    $(document).on("keyup","#laminated_meter",function(e){
        let laminated_net_weight = parseInt($("#laminated_net_weight").val());
        let laminated_meter = parseInt($("#laminated_meter").val());
        let laminated_avg_weight =  (laminated_net_weight / laminated_meter) * 1000;
        $("#laminated_avg_weight").val(laminated_avg_weight);

    });

    $(document).on("keyup","#laminated_meter_2",function(e){
        let laminated_net_weight = parseInt($("#laminated_net_weight_2").val());
        let laminated_meter = parseInt($("#laminated_meter_2").val());
        let laminated_avg_weight =  (laminated_net_weight / laminated_meter) * 1000;
        let final = round(laminated_avg_weight, 3);
        $("#laminated_avg_weight_2").val(final);

    });

    $(document).on("keyup","#laminated_meter_3",function(e){
        let laminated_net_weight = parseInt($("#laminated_net_weight_3").val());
        let laminated_meter = parseInt($("#laminated_meter_3").val());
        let laminated_avg_weight =  (laminated_net_weight / laminated_meter) * 1000;
        let final = round(laminated_avg_weight, 3);
        $("#laminated_avg_weight_3").val(final);

    });

    /********************** dana consumption and wastage and differences ******************************/

    /************************** Check weights matches for consumption and lamination and final submit   **********************************/
        /*******
         * @this portion adds to fabric stock and subtraction from fabric stock
         ********/

         $(document).on("click","#finalUpdate",function(e){

            let danaNameId = $("#selectedDanaID").val();
            let consumption = $("#add_dana_consumption_quantity").val();
            let fabric_waste = $("#fabric_waste").val();
            let total_waste = $('#total_waste').val();
            let selectedDanaID = $("#selectedDanaID").val();
            let polo_waste = $("#polo_waste").val();
            let godam_id = $("#toGodam").val();
            let bill = $("#bill").val();
            // console.log(godam_id);

            trimmedConsumption = consumption;
            trimmedPoloWaste = polo_waste.trim();
            trimmedFabricWaste = fabric_waste.trim();
            trimmedTotalWaste = total_waste.trim();


            if(trimmedFabricWaste == '' || trimmedPoloWaste == ''){
                alert('Waste and Consumption cannot be null');
            }else{
            // subtractformautolad(danaNameId,consumption);
                $.ajax({
                    url : "{{ route('tripal.wastage.submit') }}",
                    method: "post",
                    data:{
                        "_token" : $('meta[name="csrf-token"]').attr('content'),
                        "danaNameID" : danaNameId,
                        "consumption" : trimmedConsumption,
                        "fabric_waste" : trimmedFabricWaste,
                        "polo_waste" : trimmedPoloWaste,
                        "total_waste" : trimmedTotalWaste,
                        "selectedDanaID" : selectedDanaID,
                        "bill" : bill
                    },
                    beforeSend:function(){
                        console.log("Before Send");
                    },
                    success:function(response){
                        console.log(response);
                        if(response == '200'){
                            location.reload();
                        }else{

                        }
                    },
                    error:function(error){
                        console.log(error);
                    }
                }); 
            }
        });

    // function subtractformautolad(danaNameId,consumption){
    //     $.ajax({
    //         url:"{{ route('subtract.dana.from.autoloder') }}",
    //         method: "post",
    //         data:{
    //             '_token' : $('meta[name="csrf-token"]').attr('content'),
    //             "danaId" : danaNameId,
    //             "quantity" : consumption
    //         },
    //         success:function(response){
    //             console.log(response);
    //         },
    //         error:function(error){
    //             console.log(error);
    //         }
    //     });
    // }

    /************************** Check weights matches for consumption and lamination and final submit   **********************************/
</script>
@endsection