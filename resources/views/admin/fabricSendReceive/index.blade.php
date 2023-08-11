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
                <input type="date" value="{{ date('Y-m-d') }}" step="any" min="0" class="form-control calculator"
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
                    @foreach ($department as $data)
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
                <select class="advance-select-box form-control" id="fabricNameId" {{-- name="fabric_name_id" --}}>
                    <option value="" disabled selected>{{ __('Select Fabric Name') }}</option>
                    {{-- @foreach ($fabrics as $fabric)
                    <option value="{{ $fabric->id }}">{{ $fabric->name }}
                    </option>
                    @endforeach --}}
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
<hr>
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
                    <th>{{__('Send')}}</th>
                </tr>
            </thead>
            <tbody id="same-fabrics"></tbody>
        </table>
    </div>
</div>
<hr>
<div class="row">
    <div class="Ajaxdata col-md-12">
        <div class="p-0 table-responsive table-custom my-3">
            <table class="table" id="rawMaterialItemTable">
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
<h1 class='text-center'>Compare Lam and Unlam</h1>
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
                <p style="font-weight: bold;">Laminated</p>
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
    {{-- <div class="col-md-6 float-right ml-3">
        <button class="btn btn-danger discard">Discard</button>
    </div> --}}
</div>
<hr>
<div class="row">
    <div class="col-md-5">
        <div class="card mt-2 p-5">
            <div class="card-body">
                <div class="row p-2">
                    <div class="col-md-6">
                        <label for="" class="col-form-label">Godam</label>
                        <?php
                            $godam = \App\Models\Godam::where("status","active")->get();
                        ?>
                        <select class="advance-select-box form-control" id="autoloader_godam">
                            <option value="" selected disabled>--Choose Godam--</option>
                            @foreach($godam as $data)
                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="size" class="col-form-label">{{ __('Dana:') }}<span class="required-field">*</span>
                        </label>
                        <select class="advance-select-box form-control" id="danaNameId" name="danaNameId" disabled required>
                            <option value="" selected disabled>{{ __('--Select Plant Name--') }}</option>
                            @foreach ($dana as $danaName)
                            <option value="{{ $danaName->id }}">{{ $danaName->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="size" class="col-form-label">{{ __('Enter Qty:') }}<span class="required-field">*</span>
                        </label>
                        <input type="number" step="any" min="0" class="form-control" id="add_dana_consumption_quantity"
                            data-number="1" name="total_ul_in_mtr" min="1" disabled required>
                    </div>
                    <div class="col-md-6">
                        <label for="">Add Here</label>
                        <button class=" form-control btn btn-primary" id='add_dana_consumption' disabled>
                            Add
                        </button>
                    </div>
                </div>
                <hr>
                <div class="col-12 table-responsive">
                    <table class="table-bordered table dana-consumption-table">
                       <thead class="table-primary">
                        <tr>
                            <th>SN</th>
                            <th>Dana Name</th>
                            <th>Quantity</th>
                            <th>Actions</th>
                        </tr>
                       </thead>
                        <tbody id="dana-consumption-tbody">
                        </tbody>
                    </table>
                </div>
                {{-- <hr>
                <div class="col-md-12">
                    <h4>wastage</h4>
                    <select name="waste_type" id="waste_type" class="advance-select-box">
                        <option>--select waste--</option>
                        <option value="">Polo</option>
                        <option value="">Rafia</option>
                    </select>
                </div> --}}

            </div>
        </div>
    </div>
    <div class="card col-md-7">
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
                        </div>
                        <div>
                            <label for="size" class="col-form-label">{{ __('Total Dana:') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="number" step="any" min="0" class="form-control calculator" id="totl_dana" data-number="1"
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
                            <label for="size" class="col-form-label">{{ __('Total Lam Mtr:') }}<span
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
                            <label for="size" class="col-form-label">{{ __('Total Lam Net Wt:') }}<span
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
            <input type="text" name="selectedDanaID" class="form-control" id="selectedDanaID" readonly>
            <button class="btn btn-info" id="finalUpdate">Update</button>
            <input type="text" name="godam_id" id="godam_id" required/>
            <input type="text" name="autoloader_godam_selected" id="autoloader_godam_selected" required>
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
                <form id='sendtolaminationform' action='{{ route("fabricSendReceive.store.laminated") }}' method="post">
                    @csrf
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
                                    <label for="">standard weight gram</label>
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
                                    <input class='form-control' type="text" name="meter1"
                                        id="meter">
                                </div>
                                <div class="col-md-2">
                                    <label for="">Average</label>
                                    <input class='form-control' type="text" name="laminated_avg_weight"
                                        id="laminated_avg_weight">
                                </div>
                                <div class="col-md-2">
                                    <label for="">Gram</label>
                                    <input class='form-control' type="text" name="laminated_gram" id="laminated_gram">
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
                                    <input class='form-control' type="text" name="meter2"
                                        id="meter2">
                                </div>
                                <div class="col-md-2">
                                    <label for="">Average</label>
                                    <input class='form-control' type="text" name="laminated_avg_weight_2"
                                        id="laminated_avg_weight_2">
                                </div>
                                <div class="col-md-2">
                                    <label for="">Gram</label>
                                    <input class='form-control' type="text" name="laminated_gram_2"
                                        id="laminated_gram_2">
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
                                    <input class='form-control' type="text" name="meter3"
                                        id="meter3">
                                </div>
                                <div class="col-md-2">
                                    <label for="">Average</label>
                                    <input class='form-control' type="text" name="laminated_avg_weight_3"
                                        id="laminated_avg_weight_3">
                                </div>
                                <div class="col-md-2">
                                    <label for="">Gram</label>
                                    <input class='form-control' type="text" name="laminated_gram_3"
                                        id="laminated_gram_3">
                                </div>
                            </div>
                            <hr>
                            <div class="row d-flex justify-content-center text-center mb-2-">
                                {{-- <div class="col-md-6">
                                    <button type='submit' class="btn btn-info">Create Group</button>
                                </div> --}}
                                <div class="col-md-6">
                                    <button type='submit' class="btn btn-info">Update</button>
                                    <input type="hidden" name="idoffabricforsendtolamination"
                                                id="idoffabricforsendtolamination">
                                </div>
                                
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
    $(document).ready(function(){
        let fabricTable = null;
        /**************************** Ajax Calls **************************/
        callunlaminatedfabricajax();
        comparelamandunlam();
        getDanaConsumption();

        $("#toGodam").change(function(e){
            let godamId = $(this).val(); 
            $("#godam_id").val(godamId);

            let department_id =  $(this).val();
            let geturl = "{{ route('fabricSendReceive.get.planttype',['id'=>':id']) }}"
            $.ajax({
                url:geturl.replace(':id',department_id),
                beforeSend:function(){
                    console.log('Getting Plant type');
                },
                success:function(response){
                    addplanttype(response);
                },
                error:function(error){
                    console.log(error);
                }
            });
        });

        $("#plantType").change(function(e){
            let department_id =  $(this).val();
            let geturl = "{{ route('fabricSendReceive.get.plantname',['id'=>':id']) }}";
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

        $("#shiftName").change(function(e){
            let shift_id =  $(this).val();
            let geturl = "{{ route('fabricSendReceive.get.fabrics') }}";
            let godam_id = $("#godam_id").val();
            $.ajax({
                url : geturl,
                data : {
                    "shift_id" : shift_id,
                    "godam_id" : godam_id,
                },
                beforeSend:function(){
                    console.log('Getting Fabrics');
                },
                success:function(response){
                    getfabrics(response);
                },
                error:function(error){
                    console.log(error);
                }
            });
        });

        $("#fabricNameId").change(function(e){
            if (fabricTable !== null) {
                fabricTable.destroy();
            }

            let fabric_name_id = $(this).val();
            fabricTable = $("#sameFabricsTable").DataTable({
                serverside : true,
                processing : true,
                ajax : {
                    url : "{{ route('get.fabric.same.name.fsr') }}",
                    method : "post",
                    data : function(data){
                        data._token = $("meta[name='csrf-token']").attr("content"),
                        data.fabric_name_id = fabric_name_id
                    }
                },
                columns:[
                    { data : "DT_RowIndex" , name : "DT_RowIndex" },
                    { data : "name" , name : "name" },
                    { data : "roll_no" , name : "roll_no" },
                    { data : "gross_wt" , name : "gross_wt" },
                    { data : "net_wt" , name : "net_wt" },
                    { data : "meter" , name : "meter" },
                    { data : "average_wt" , name : "average_wt" },
                    { data : "gram_wt" , name : "gram_wt" },
                    { data : "action" , name : "action" },
                ]
            });

            // $.ajax({
            //     url :" {{ route('get.fabric.same.name') }}",
            //     method : "post",
            //     data : {
            //         "_token" : $("meta[name='csrf-token']").attr("content"),
            //        "fabric_name_id" : fabric_name_id
            //     },
            //     beforeSend:function(){
            //         console.log("getting data");
            //     },
            //     success:function(response){
            //         putsamefabricsontabledata(response)
                    
            //     },error:function(error){
            //         console.log(error);
            //     }
            // });
        })
        /**************************** Ajax Calls End **************************/
    });

    /**************************** Ajax functions **************************/

    function callunlaminatedfabricajax(){
        $.ajax({
            url : "{{ route('fabricSendReceive.get.unlaminated') }}",
            method: 'get',
            beforeSend:function(){
                console.log('getting unlaminated fabric');
            },
            success:function(response){
                emptytable();
                if(response.response != '404'){
                    filltable(response);
                }else{
                    console.log(response.response);
                }
                
            },error:function(error){
                console.log(error);
            }
        });
    }

    function addplanttype(data){
        $("#plantType").empty();
        $('#plantType').append(`<option value="" disabled selected>--Select Plant Type--</option>`);
        data.planttype.forEach( d => {
            if (d.name && d.name.startsWith('lam')) {
             $('#plantType').append(`<option value="${d.id}">${d.name}</option>`);
            }
        });
    }

    function addplantname(data){
        console.log(data);
        $("#plantName").empty();
        $('#plantName').append(`<option value="" disabled selected>--Select Plant Name--</option>`);
        data.plantname.forEach( d => {
            // if(d.name == '')
            $('#plantName').append(`<option value="${d.id}">${d.name}</option>`);
        });
    }

    function getfabrics(data){
        $('#fabricNameId').prop('disabled',false);
        $("#fabricNameId").empty();
        $('#fabricNameId').append(`<option value="" disabled selected>--Select Fabric--</option>`);
        data.fabrics.forEach(d => {
            $("#fabricNameId").append(`<option value="${d.id}">${d.name}</option>`);
        });
    }

    
    // function putsamefabricsontabledata(data){
    //     console.log(data)
    //     $("#same-fabrics").empty();
    //     data.fabrics.forEach((d,index) => {
    //         let tr = $("<tr></tr>").appendTo("#same-fabrics");
    //         tr.append(`<td>${index+1}</td>`);
    //         tr.append(`<td>${d.name}</td>`);
    //         tr.append(`<td>${d.roll_no}</td>`);
    //         tr.append(`<td>${d.gross_wt}</td>`);
    //         tr.append(`<td>${d.net_wt}</td>`);
    //         tr.append(`<td>${d.meter}</td>`);
    //         tr.append(`<td>${d.average_wt}</td>`);
    //         tr.append(`<td>${d.gram_wt}</td>`);
    //         tr.append(`<td>
    //                         <a class="btn btn-primary send_to_lower"  
    //                             data-name='${d.name}' 
    //                             data-gross_wt="${d.gross_wt}" 
    //                             data-roll_no="${d.roll_no}"  
    //                             data-id="${d.id}" 
    //                             data-net_wt = "${d.net_wt}"
    //                             data-meter = "${d.meter}"
    //                             data-average_wt = "${d.average_wt}"
    //                             data-gram_wt = "${d.gram_wt}" 
    //                             data-bill_no = "${d.bill_no}"
    //                             href="${d.id}">Send</a>
    //                     </td>`);
    //     }) 
    // }
    /**************************** Ajax functions **************************/

    /************************* Form Submission *************************/
    $(document).on("click",".send_to_lower",function(e){
        e.preventDefault()
        let godam = $("#toGodam").val()
        let plantType = $("#plantType").val()
        let plantName = $("#plantName").val()
        let shiftName = $("#shiftName").val()
        let fabric_id = $(this).val()

        let name = $(this).data("name")
        let gross_wt = $(this).data("gross_wt")
        let net_wt = $(this).data("net_wt")
        let roll_no = $(this).data("roll_no")
        let meter = $(this).data("meter")
        let average_wt = $(this).data("average_wt")
        let gram_wt = $(this).data("gram_wt")
        let bill_no = $(this).data("bill_no")
        let billDate = $("#billDate").val()
        let id = $(this).data("id") 

        console.log(godam,plantName,plantType,shiftName,gram_wt,billDate,id)
        console.log(name,gross_wt,net_wt,roll_no,meter,average_wt,bill_no)
        if(godam == null || plantName == null || plantType == null || shiftName == null){
            alert("Godam or plantName or PlanType or Shift cannnot be null");
        }
        $.ajax({
            url  : "{{ route('fabricSendReceive.send.unlaminated.revised') }}",
            method : "post",
            data:{
                "_token" : $("meta[name='csrf-token']").attr("content"),
                "name" : name,
                "gross_wt" : gross_wt,
                "net_wt" : net_wt,
                "roll_no" : roll_no,
                "meter" : meter,
                "average_wt" : average_wt,
                "gram_wt" : gram_wt,
                "bill_no" : bill_no,
                "billDate" : billDate,
                "godam" : godam,
                "plantType" : plantType,
                "plantName" : plantName,
                "shiftName" : shiftName,
                "fabric_id" : id
            },
            beforeSend:function(){
                console.log("sending")
            },
            success:function(response){
                emptytable();
                callunlaminatedfabricajax();
                emptyform();
            },
            error:function(error){
                console.log("error",error);
            }
        })
    })

    $(document).ready(function(){
        $(document).on('submit','#createRawMaterial',function(e){
            e.preventDefault();
            let action = $(this).attr('action');
            let method = $(this).attr('method');
            let formData = $(this).serialize();
           $.ajax({
            url:action,
            method : method,
            data:{
                '_token' : $('meta[name="csrf-token"]').attr('content'),
                'data' : formData
            },
            beforeSend:function(){
            },
            success:function(response){
                console.log(response);
                if(response.status == "200"){
                    emptytable();
                    callunlaminatedfabricajax();
                    emptyform();
                }else{
                    alert(response.message_error)
                }
            },
            error:function(error){
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


    // $("#fabricNameId").change(function(e){
    //     getfabricsrelated_enable();
    //     $("#rollnumberfabric").prop('required',false);
    //     $("#rollnumberfabric").prop('disabled',true);
    // });

    $("#rollnumberfabric").keyup(function(e){
        getfabricsrelated_enable();
        // $("#fabricNameId").prop('disabled',true);
        // $("#fabricNameId").prop('required',false);
    });

    function getfabricsrelated_enable(){
        $('#getfabricsrelated').prop('disabled',false);
    }

    function emptytable(){
        $('#rawMaterialItemTbody').empty();
    }

    $(document).on('click','#deletesendforlamination',function(e){
        e.preventDefault();
        let id = $(this).attr('data-id');
        deletefromunlamintedtable(id);
    });

    function filltable(data){
        console.log(data);
        data.response.forEach(d => {
            let title = d.fabric.name;
            let group = d.gram.split('-')[0];
            let result = parseFloat(title) * parseFloat(group);

            let tr = $("<tr></tr>").appendTo('#rawMaterialItemTbody');

            tr.append(`<td>#</td>`);
            tr.append(`<td>${d.fabric.name}</td>`);
            tr.append(`<td>${d.roll_no}</td>`);
            tr.append(`<td>${d.gross_wt}</td>`);
            tr.append(`<td>${d.net_wt}</td>`);
            tr.append(`<td>${d.meter}</td>`)
            tr.append(`<td>${d.fabric.average_wt}</td>`);
            tr.append(`<td>${d.gram}</td>`);
            tr.append(`<td><div class="btn-group"><a id="sendforlamination" data-group='${d.gram}' data-standard='${result}' data-title='${d.fabric.name}' href="${d.id}" data-id="${d.id}" class="btn btn-info">Send</a><a id="deletesendforlamination" class="btn btn-danger" data-id="${d.id}">delete</a></div></td>`);
        });
    }

    function emptyform(){
        // $("#createRawMaterial")[0].reset();
        return;
    }

    function deletefromunlamintedtable(data){
        $.ajax({
            url : "{{ route('fabricSendReceive.send.unlaminated.delete',['id'=>':id']) }}".replace(':id',data),
            method:'get',
            beforeSend:function(){
                console.log('deleteing from unlamintaed table');
            },
            success:function(response){
                if(response.response == '200'){
                    emptytable();
                    callunlaminatedfabricajax();
                }else if(response.response == '400'){
                    alert('Not Allowed OR Data is no longer there');
                }
            },
            error:function(error){
            }
        });
    }

    $(".discard").click(function(e){
        alert("Not Allowed")
        // $.ajax({
        //     url:"{{ route('discard') }}",
        //     method:"get",
        //     success:function(response){
        //         if(response.message == "200"){
        //             location.reload(true);
        //         }
        //         else{
        //             alert(response.message);
        //         }
        //     },
        //     error:function(response){
        //     }
        // });
    });

    $("#autoloader_godam").change(function(e){
        e.preventDefault();
        let godamId = $(this).val(); 
        $("#danaNameId").prop("disabled",false)
        $.ajax({
            url : "{{ route('get.autoloader.godam.id.into.fsr',['godamId' => ':godamId']) }}".replace(":godamId",godamId),
            method :  'get',
            success:function(response){
                putdanaNameId(response);
                $("#autoloader_godam_selected").val(godamId);
            },error:function(error){
                console.log(error);
            }
        })
    })
    /************************* Other Functionalities ***********************/

    /************************* Send for lamination **************************/
    $(document).ready(function(){
        $(document).on('click',"#sendforlamination",function(e){
            e.preventDefault();
            $('#staticBackdrop1').modal('show');
            let titleold = $('#staticBackdropLabel').text('');
            let title = $(this).attr('data-title');
            let id = $(this).attr('data-id');
            $("#laminated_fabric_name").val(title+"(Lam)");
            let laminated_fabric_group = $(this).attr('data-group');
            $("#laminated_fabric_group").val(laminated_fabric_group);
            let standard_weight_gram = $(this).attr('data-standard');
            $("#standard_weight_gram").val(standard_weight_gram);
            $('#staticBackdropLabel').text(title+" -> id = "+id);
            $("#idoffabricforsendtolamination").val(id);
            // let action="{{ route('fabricSendReceive.store.laminated',['id'=>"+id+"]) }}";
            // $('#sendtolaminationform').attr('action',action);
            // let action = "{{ route('fabricSendReceive.store.laminated', ['id' => '']) }}";
            // action = action.slice(0, -2) + `${id}`;
            // $('#sendtolaminationform').attr('action', action);
        });
    });
    // $(document).on('hidden.bs.modal', '#staticBackdrop1', function(e) {
    //     $(this).removeAttr('action');
    // });
    $('#staticBackdrop1').on('hidden.bs.modal',function(e) {
        $(this).removeAttr('action');
    });

    //calculations of average  avg ko formula nt wt/mtr*1000 //average / size  => For Gram
    $("#meter").keyup(function(){
        let net_wt_1 = $("#laminated_net_weight").val()
        let meter1 = $("#meter").val();
        let a = parseFloat(net_wt_1)
        let b = parseFloat(meter1)
        console.log(net_wt_1,meter1)
        let count1 = parseFloat((a / b)*1000).toFixed(3)
        $("#laminated_avg_weight").val(parseFloat(count1))

        let data = parseInt($("#sendforlamination").data("title"));
        let gram1 = (count1/data).toFixed(3);
        $("#laminated_gram").val(gram1)
    })

    $("#meter2").keyup(function(){
        let net_wt_1 = $("#laminated_net_weight_2").val()
        let meter1 = $("#meter2").val();
        let a = parseFloat(net_wt_1)
        let b = parseFloat(meter1)
        let count1 = parseFloat((a / b)*1000).toFixed(3)
        $("#laminated_avg_weight_2").val(parseFloat(count1))

        let data = parseInt($("#sendforlamination").data("title"));
        let gram1 = (count1/data).toFixed(3);
        $("#laminated_gram_2").val(gram1)
    })

    $("#meter3").keyup(function(){
        let net_wt_3 = $("#laminated_net_weight_3").val()
        let meter3 = $("#meter3").val();
        let a = parseFloat(net_wt_3)
        let b = parseFloat(meter3)
        let count3 = parseFloat((a / b)*1000).toFixed(3)
        $("#laminated_avg_weight_3").val(parseFloat(count3))

        let data = parseInt($("#sendforlamination").data("title"));
        let gram3 = (count3/data).toFixed(3);
        $("#laminated_gram_3").val(gram3)
    })
    




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
                callunlaminatedfabricajax();
                comparelamandunlam();
                $('#staticBackdrop1').modal('hide');

            },
            error:function(error){
            }
        });
    }


    function comparelamandunlam(){
        $.ajax({
            url : "{{ route('fabricSendReceive.compare.lamandunlam') }}",
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

    function putdanaNameId(data){
        console.log(data);
        $("#danaNameId").empty();
        $("#danaNameId").append(`<option selected disabled>--Select Dana--</option>`);
        data.data.forEach(d => {
            $("#danaNameId").append(`<option value="${d.dana_name.id}">${d.dana_name.name}</option>`);
        });
    }

    $(document).on('change',"#danaNameId",function(){
        $("#selectedDanaID").val($(this).val());
    })
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
        let id = $(this).val();
        // $.ajax({

        // });
        $("#add_dana_consumption_quantity").prop("disabled",false);
    });


    $(document).on("keyup","#add_dana_consumption_quantity",function(e){
        $("#add_dana_consumption").prop("disabled",false);
    });

    // $(document).on("click","#add_dana_consumption",function(e){
    //     let dana = $("#danaNameId").val();
    //     let consumption = $("#add_dana_consumption_quantity").val();
    //     if (consumption.trim() === '') {
    //         alert("add quantity");
    //     }else{
    //         $("#totl_dana").val(consumption);

    //         $("#selectedDanaID").val(dana);
    //     }   
    // });

    $(document).on("click","#add_dana_consumption",function(e){
        let dana = $("#danaNameId").val();
        let consumption = $("#add_dana_consumption_quantity").val();
        if(consumption.trim() === '') {
            alert("add quantity");
        }
        if(dana == ""){
            alert("choose dana");
        }
        $.ajax({
            url : "{{ route('add.dana.consumption.fsr') }}",
            method : "post",
            data : {
                "_token" : $("meta[name='csrf-token']").attr("content"),
                "dana_name_id" : dana,
                "consumption" : consumption,
                "bill_number" : $("#billnumber").val()
            },success:function(response){
                $("#dana-consumption-tbody").empty()
                $("#totl_dana").val(response.total_consumption)
                    response.consumptions.forEach((data,index)=>{
                        let tr = $("<tr></tr>").appendTo("#dana-consumption-tbody");
                        tr.append(`<td>${index+1}</td>`);
                        tr.append(`<td>${data.dananame.name}</td>`);
                        tr.append(`<td>${data.consumption_quantity}</td>`);
                        tr.append(`<td><a href="javascript:void(0)" class="btn btn-danger delete-dana" data-id="${data.id}"> <i class="fa fa-trash" aria-hidden="true"></i> </a></td>`);
                    })
                console.log(response)
            },error:function(error){
                console.log(error)
                alert("error orccured check console")
            }
        });
    });

    $(document).on("click",".delete-dana",function(){
        let id = $(this).data("id")
        // alert(id)
        $.ajax({
            url : "{{ route('delete.dana.consumption') }}",
            method : "post",
            data : {
                "_token" : $("meta[name='csrf-token']").attr("content"),
                "id" : id
            },
            success:function(response){
                console.log(response)
                if(response.status == 200){
                    getDanaConsumption();
                }
            },
            error:function(error){
                console.log("error",error)
            }
        })
    })

    function getDanaConsumption() {
            $.ajax({
                url: "{{ route('get.dana.consumption.fsr') }}",
                method: "post",
                data: {
                    "_token": $("meta[name='csrf-token']").attr("content"),
                    "billnumber": $("#billnumber").val()
                },
                success: function (response) {
                    console.log(response)
                    if (response.consumptions != null) {
                        $("#dana-consumption-tbody").empty();
                        response.consumptions.forEach((data,index) => {
                            let tr = $("<tr></tr>");
                            $("#dana-consumption-tbody").append(tr);
                            tr.append(`<td>${index++}</td>`);
                            tr.append(`<td>${data.dananame.name}</td>`);
                            tr.append(`<td>${data.consumption_quantity}</td>`);
                            tr.append(`<td><a href="javascript:void(0)" class="btn btn-danger delete-dana" data-id="${data.id}"> <i class="fa fa-trash" aria-hidden="true"></i> </a></td>`);
                        });
                        $("#totl_dana").val(response.total_consumption)
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }


    $(document).on("keyup","#fabric_waste",function(e){
        let polo_waste = parseInt($("#polo_waste").val());
        let fabric_waste = parseInt($("#fabric_waste").val());
        let total_waste = polo_waste + fabric_waste;
        $("#total_waste").val(total_waste);
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
            let godam_id = $("#godam_id").val();
            let autoloader_godam_selected = $("#autoloader_godam_selected").val();

            trimmedConsumption = consumption.trim();
            trimmedPoloWaste = polo_waste.trim();
            trimmedFabricWaste = fabric_waste.trim();
            trimmedTotalWaste = total_waste.trim();

            if(trimmedConsumption == '' || trimmedFabricWaste == '' || trimmedPoloWaste == ''){
                alert('Waste and Consumption cannot be null');
            }
            else if(godam_id == null){
                alert("please choose godam");
            }else{
            // subtractformautolad(danaNameId,consumption);
                $.ajax({
                    url : "{{ route('final.submit.fsr') }}",
                    method: "post",
                    data:{
                        "_token" : $('meta[name="csrf-token"]').attr('content'),
                        "danaNameID" : danaNameId,
                        "consumption" : trimmedConsumption,
                        "fabric_waste" : trimmedFabricWaste,
                        "polo_waste" : trimmedPoloWaste,
                        "total_waste" : trimmedTotalWaste,
                        "selectedDanaID" : selectedDanaID,
                        "godam_id" : godam_id,
                        "autoloader_godam_selected" : autoloader_godam_selected,
                        "bill_number" : $("#billnumber").val()
                    },
                    beforeSend:function(){
                        console.log("Before Send");
                    },
                    success:function(response){
                        console.log(response);
                        if(response == '200'){
                            alert("Saved Successfully");
                            setTimeout(function(){
                                location.reload();
                            },500)
                        }
                        if(response.status == '403'){
                            alert(response.message)
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