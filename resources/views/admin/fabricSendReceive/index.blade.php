@extends('layouts.admin')

@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
    <style>
    .col-form-label{
        font-size: 12px !important;

    }
    .dynamic-btn{
        height: 18px;
        width: 4px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    #storeinSubmitBtn{
        height: 25px;
        width: 70px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 5px !important;
    }
    .fa-plus{
        font-size: 10px;
    }
    .form-control{
        font-size: 12px !important;

    }
    .select2-selection__rendered,  .select2-container--bootstrap4 .select2-selection   {
        font-size: 12px !important;
        display:flex !important;
        align-items: center !important;
        height: calc(1.6em + 0.75rem + 2px) !important;
    }
    .select2-container{
        height: calc(1.6em + 0.75rem + 2px) !important;
    }
   .taxStyle .select2-selection {
        width: 200px !important;
        }
    .form-group{
        margin-bottom: 0px !important;
    }
    .content-wrapper{
        padding-top:0px !important;
    }
    .card-body{
        padding:0px 5px!important;
    }
    .card{
        padding:0px 5px!important;
    }
    .col-md-6{
         padding:0px 2px!important;
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
                    <input type="text" step="any" min="0" class="form-control calculator" id="billNo"
                        data-number="1" name="bill_no" min="1" required>
                    @error('bill_no')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('Bill Date') }}
                    </label>
                    <input type="date" step="any" min="0" class="form-control calculator" id="billDate"
                        data-number="1" name="bill_date" placeholder="{{ __('Remarks') }}" min="1" required>

                    @error('bill_date')
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
                    <select class="advance-select-box form-control" id="shiftName" name="shift_name_id" required>
                        <option value="" selected disabled>{{ __('Select Shift Name') }}</option>
                        {{-- @foreach ($danaNames as $danaName)
                            <option value="{{ $danaName->id }}">{{ $danaName->name }}
                            </option>
                        @endforeach --}}
                    </select>
                    @error('shift_name_id')
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
                        {{-- @foreach ($danaNames as $danaName)
                            <option value="{{ $danaName->id }}">{{ $danaName->name }}
                            </option>
                        @endforeach --}}
                    </select>
                    @error('to_godam_id')
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
                       <select class="advance-select-box form-control" id="fabricNameId" name="fabric_name_id" required>
                        <option value="" selected disabled>{{ __('Select Plant Name') }}</option>
                        {{-- @foreach ($danaNames as $danaName)
                            <option value="{{ $danaName->id }}">{{ $danaName->name }}
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
                     <input type="text" step="any" min="0" class="form-control calculator" id="billNo"
                        data-number="1" name="bill_no" min="1" required>

                    @error('fabric_name_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div>
                    <button class="btn btn-primary mt-4">
                        Add
                    </button>
                </div>
            </div>
        </form>
    </div>
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
                        </tr>
                    </thead>

                    <tbody id="rawMaterialItemTbody">
                    </tbody>

                </table>
            </div>

        </div>
    </div>
   <div class="row">
    <div class="col-md-6">
        <div class="card" style="height: 100%;">

            <p style="font-weight: bold;">Unlaminated</p>
            <div class="card-body" style="padding:opx !important">
                 <table class="table table-bordered" id="rawMaterialItemTable">
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
                        </tr>
                    </thead>

                    <tbody id="rawMaterialItemTbody">
                    </tbody>

                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card" style="height: 100%;">
             <p style="font-weight:bold;">Laminated</p>
            <div class="card-body">
                <table class="table table-bordered" id="rawMaterialItemTable">
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
                        </tr>
                    </thead>

                    <tbody id="rawMaterialItemTbody">
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">

    <div class="col-md-5" style="height: 100%;">
    <div class="card card-body mt-2" style="padding5px;">
        <div class="row">
           <div class="col-md-6">
            <label for="size" class="col-form-label">{{ __('Dana:') }}<span class="required-field">*</span>
                    </label>
                        <select class="advance-select-box form-control" id="danaNameId" name="fabric_name_id" required>
                        <option value="" selected disabled>{{ __('Select Plant Name') }}</option>
                        {{-- @foreach ($danaNames as $danaName)
                            <option value="{{ $danaName->id }}">{{ $danaName->name }}
                            </option>
                        @endforeach --}}
                    </select>
                            @error('total_ul_in_mtr')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                        </span>
             @enderror
           </div>
           <div class="col-md-4">
            <label for="size" class="col-form-label">{{ __('Qty:') }}<span class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator" id="totalUlInMtr"
                                data-number="1" name="total_ul_in_mtr" min="1" required>
                            @error('total_ul_in_mtr')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
             @enderror
           </div>
           <div class="col-md-2">
            <button class="btn btn-primary">
                Add
            </button>
           </div>
        </div>
        </div>
    </div>
    <div class="col-md-7" style="height: 100%;">
        <div class="row">
                <div class="col-md-3 form-group">
                    <div>
                            <label for="size" class="col-form-label">{{ __('Total Ul Mtr:') }}<span class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator" id="totalUlInMtr"
                                data-number="1" name="total_ul_in_mtr" min="1" required>
                            @error('total_ul_in_mtr')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>
                    <div class="mb-4">
                            <label for="size" class="col-form-label">{{ __('Total Ul Mtr:') }}<span class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator" id="totalUlInMtr"
                                data-number="1" name="total_ul_in_mtr" min="1" required>
                            @error('total_ul_in_mtr')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>
                    <div>
                            <label for="size" class="col-form-label">{{ __('Polo Was:') }}<span class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator" id="poloWaste"
                                data-number="1" name="polo_waste" min="1" required>
                            @error('polo_waste')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div>
                            <label for="size" class="col-form-label">{{ __('Total Dana:') }}<span class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator" id="totalDana"
                                data-number="1" name="totl_dana" min="1" required>
                            @error('polo_waste')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                </div>
                 <div class="col-md-3 form-group">
                        <div>
                            <label for="size" class="col-form-label">{{ __('Total Lam Mtr:') }}<span class="required-field">*</span>
                        </label>
                        <input type="text" step="any" min="0" class="form-control calculator" id="totalLamInMtr"
                            data-number="1" name="total_lam_in_mtr" min="1" required>
                        @error('total_lam_in_mtr')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        </div>
                        <div class="mb-4">
                            <label for="size" class="col-form-label">{{ __('Total Lam Mtr:') }}<span class="required-field">*</span>
                        </label>
                        <input type="text" step="any" min="0" class="form-control calculator" id="totalLamInMtr"
                            data-number="1" name="total_lam_in_mtr" min="1" required>
                        @error('total_lam_in_mtr')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        </div>
                         <div>
                            <label for="size" class="col-form-label">{{ __('Fabric Was:') }}<span class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator" id="fabricWaste"
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
                            <input type="text" step="any" min="0" class="form-control calculator" id="totalWaste"
                                data-number="1" name="total_waste" min="1" required>
                            @error('polo_waste')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                </div>
                <div class="col-md-3" style="margin-top:70px;">
                    <div>
                            <label for="size" class="col-form-label">{{ __('Dif. UL Lam NW :') }}<span class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator" id="diffUuLamLamNw"
                                data-number="1" name="diff_unLam_lamNw" min="1" required>
                            @error('diff_unLam_lamNw')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>
                    <div class="mb-4">
                            <label for="size" class="col-form-label">{{ __('Total Diff:') }}<span class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator" id="totalDiff"
                                data-number="1" name="total_diff" min="1" required>
                            @error('total_ul_in_mtr')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>
                </div>
        </div>
    </div>
</div>

@endsection
@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/storein.js') }}"></script>
@endsection
