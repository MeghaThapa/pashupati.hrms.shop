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
            <div class="col-md-3 form-group">
                <label for="size" class="col-form-label">{{ __('Invoice No') }}<span class="required-field">*</span>
                </label>
                <input type="text" class="form-control" id="billnumber" value="{{ $bill_no }}" name="bill_number"
                    required /> {{-- value="FSR-{{ getNepalidate(date('Y-m-d')).'-'.rand(0,9999)}}" --}}
            </div>

            <div class="col-md-3 form-group">
                <label for="size" class="col-form-label">{{ __('Invoice Date') }}
                </label>
                <input type="date" value="{{ $bill_date }}" step="any" min="0" class="form-control calculator"
                    id="billDate" data-number="1" name="bill_date" placeholder="{{ __('Remarks') }}" min="1" required>

                @error('bill_date')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            
            <div class="col-md-6 form-group">
                <label for="size" class="col-form-label">{{ __('PartyName') }}
                </label>
                <select class="advance-select-box form-control" id="partyname" name="partyname" required>
                    <option value="" selected disabled>{{ __('Select PartyName') }}</option>
                    @foreach ($partyname as $party)
                    <option value="{{ $party->id }}">{{ $party->name }}
                    </option>
                    @endforeach
                </select>
                @error('plant_type_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('BillFor') }}
                </label>
                <select class="advance-select-box form-control" id="billfor" name="bill_for" required>
                    <option value="" selected disabled>{{ __('Select BillFor') }}</option>
                    <option value="1">Local</option>
                </select>
                @error('gp_no')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Lorry No') }}<span class="required-field">*</span>
                </label>
                <input type="text" class="form-control" id="lory_number"  name="lory_number"
                    required /> 
            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Dp No') }}<span class="required-field">*</span>
                </label>
                <input type="text" class="form-control" id="dp_number" name="dp_number"
                    required /> 
            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('GP No') }}<span class="required-field">*</span>
                </label>
                <input type="text" class="form-control" id="gp_number" name="gp_number"
                    required /> 
            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Remarks') }}<span class="required-field">*</span>
                </label>
                <input type="text" class="form-control" id="remarks" name="remarks"
                    required /> 
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
                <select class="advance-select-box form-control" id="fabric_id" name="fabric_id"
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
                <input type="hidden" name="bill_no" id="bill_nos" value="{{$bill_no}}">
                <input type="hidden" name="bill_date" id="bill_dates" value="{{$bill_date}}">
                <input type="hidden" name="godam_id" id="godam_data">
                <input type="hidden" name="planttype_id" id="planttype_data">
                <input type="hidden" name="plantname_id" id="plantname_data">
                <input type="hidden" name="shift_id" id="shift_data">

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
                <p style="font-weight: bold;">Tripal</p>
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
                <p style="font-weight: bold;"></p>
            </div>
            <div class="card-body table-responsive">
                <form action='{{ route("finaltripal.store") }}' method="post">
                    @csrf
                  <div class="row p-2">
                    <div class="col-md-3">
                        <label for="size" class="col-form-label">{{ __('Tripal:') }}<span class="required-field">*</span>
                        </label>
                      
                        <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                            tabindex="-1" data-target="#groupModel"
                            style="margin-top:0 !important; top:8px;float:right;">
                            <i class="fas fa-plus"
                                style="display:flex;align-items: center;justify-content: center;"></i>
                        </a>
                        <select class="advance-select-box form-control" id="tripal" name="tripal" required>
                            <option value="" selected disabled>{{ __('--Select Plant Name--') }}</option>
                           {{--  @foreach ($finaltripalname as $tripalname)
                            <option value="{{ $tripalname->id }}">{{ $tripalname->name }}
                            </option>
                            @endforeach --}}
                        </select>
                        @error('tripal')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="size" class="col-form-label">{{ __('Roll Number:') }}<span class="required-field">*</span>
                        </label>
                        <input type="text" step="any" min="0" class="form-control" id="roll"
                            data-number="1" name="roll" min="1" required>
                        @error('roll')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="size" class="col-form-label">{{ __('Gross Weight:') }}<span class="required-field">*</span>
                        </label>
                        <input type="number" step="any" min="0" class="form-control" id="gross_weight"
                            data-number="1" name="gross_weight" min="1" required>
                        @error('gross_weight')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    

                    <div class="col-md-3">
                        <label for="size" class="col-form-label">{{ __('Net Weight:') }}<span class="required-field">*</span>
                        </label>
                        <input type="number" step="any" min="0" class="form-control" id="net_wt"
                            data-number="1" name="net_wt" min="1" required>
                        @error('net_wt')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="size" class="col-form-label">{{ __('Meter:') }}<span class="required-field">*</span>
                        </label>
                        <input type="number" step="any" min="0" class="form-control" id="meter"
                            data-number="1" name="meter" min="1" required>
                        @error('meter')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="size" class="col-form-label">{{ __('Average:') }}<span class="required-field">*</span>
                        </label>
                        <input type="number" step="any" min="0" class="form-control" id="average"
                            data-number="1" name="average" min="1" required readonly>
                        @error('average')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="size" class="col-form-label">{{ __('GSM:') }}<span class="required-field">*</span>
                        </label>
                        <input type="number" step="any" min="0" class="form-control" id="gsm"
                            data-number="1" name="gsm" min="1" required readonly>
                        @error('gsm')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    

                    <div class="col-md-3">
                        <button class=" form-control btn btn-primary" id='add_dana_consumption' type="submit">
                            Add
                        </button>
                    </div>
                  </div>
                  <input type="hidden" name="bill_no" id="bill_nos" value="{{$bill_no}}">
                  <input type="hidden" name="bill_date" id="bill_dates" value="{{$bill_date}}">

                </form>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <p style="font-weight: bold;">FinalTripal</p>
            </div>
            <div class="card-body table-responsive" style="padding:opx !important">
                <table class="table table-bordered" id="finaltripal">
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
    <div class="col-md-6 float-right ml-3">
        {{-- <button class="btn btn-danger discard">Discard</button> --}}
    </div>
</div>

<hr>
<div class="row">
    <div class="col-md-5">
        <div class="card mt-2 p-5">
            <div class="card-body">
                <div class="row p-2">
                    <div class="col-md-6">
                        <label for="size" class="col-form-label">{{ __('Dana:') }}<span class="required-field">*</span>
                        </label>
                        <select class="advance-select-box form-control" id="danaNameId" name="danaNameId" required>
                            <option value="" selected disabled>{{ __('--Select Plant Name--') }}</option>
                           {{--  @foreach ($dana as $dana)
                            <option value="{{ $dana->id }}">{{ $dana->danaName->name }}
                            </option>
                            @endforeach --}}
                        </select>
                        @error('total_ul_in_mtr')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="size" class="col-form-label">{{ __('Qty:') }}<span class="required-field">*</span>
                        </label>
                        <input type="number" step="any" min="0" class="form-control" id="add_dana_consumption_quantity"
                            data-number="1" name="total_ul_in_mtr" min="1" disabled required>
                        @error('total_ul_in_mtr')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <button class=" form-control btn btn-primary" id='add_dana_consumption' >
                            Add
                        </button>
                    </div>
                </div>
            

            </div>
        </div>
    </div>
    <div id="success_msg" class="alert alert-success mt-2" hidden>

    </div>
    <div class="card col-md-7">
        <div class="card-body m-2 p-5">
            <div class="col-md-12" style="height: 100%;">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <div>
                            <label for="size" class="col-form-label">{{ __('Total DS Mtr:') }}<span
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
                            <label for="size" class="col-form-label">{{ __('Total DS Net Wt:') }}<span
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
                        {{-- <div>
                            <label for="size" class="col-form-label">{{ __('Polo Was:') }}<span class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator" id="polo_waste" data-number="1"
                                name="polo_waste" min="1" required>
                            @error('polo_waste')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div> --}}
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
                        <input type="hidden" name="bill" id="bill" value="{{$bill_no}}">
        
                    </div>
        
                    <div class="col-md-4 form-group">
                        <div>
                            <label for="size" class="col-form-label">{{ __('Total Tripal Mtr:') }}<span
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
                            <label for="size" class="col-form-label">{{ __('Total Tripal Net Wt:') }}<span
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
                            <label for="size" class="col-form-label">{{ __('Diff DS-Tripal Meter :') }}<span
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
<div class="modal fade" id="groupModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalcat"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="exampleModalcat">Add FinalTripal Name</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="error_msg" class="alert alert-danger mt-2" hidden>

            </div>
           
            <form action="{{ route('finaltripal.storeName') }}" method="post" id="tripal_form">
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <div id="storeinTypeError" class="alert alert-danger" hidden>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="name">{{ __('Final TripalName') }}<span
                                        class="required-field">*</span></label>
                                <input type="text" class="form-control @error('placement') is-invalid @enderror"
                                    id="name" name="name" placeholder="{{ __('Enter Final Tripal Name') }}"
                                    value="{{ old('storein_type_name') }}" required>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                        </div>
                        
                    </div>
                    <!-- /.card-body -->

                </div>
                <div class="modal-footer">
                    <button type="submit" id="register" class="btn btn-primary"><i class="fas fa-save"></i>
                        {{ __('Save') }}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->

@endsection
@section('extra-script')
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
<script src="{{ asset('js/storein.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
</script>
<script>


    $(document).ready(function(){
        /**************************** Ajax Calls **************************/
        // callunlaminatedfabricajax();
         // $('#fabricNameId').prop('disabled',true);
        comparelamandunlam();

        $('#tripal_form').on('submit', function () {
           $('#register').attr('disabled', 'true'); 
        });

       

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

      


     




        /**************************** Ajax Calls End **************************/
    });

    /**************************** Ajax functions **************************/

 



    /************************* Form Submission *************************/
    $(document).ready(function(){
        $(document).on('click','#getfabricsrelated',function(e){
            e.preventDefault();
            // debugger;
            let formData = $(this).serialize();

            var bill_number = $('#bill_nos').val(),
            bill_date = $('#bill_dates').val(),
            partyname = $('#partyname').val(),
            bill_for = $('#billfor').val(),
            lorry_no = $('#lory_number').val(),
            dp_no = $('#dp_number').val(),
            gp_no = $('#gp_number').val(),
            remarks = $('#remarks').val(),
            fabric_id = $('#fabric_id').val();
            debugger;
          

           $.ajax({
            url : "{{ route('salefinaltripal.getSaleFinalTripalStockList') }}",
            method: 'get',
            type:"POST",
            dataType:"JSON",
            data:{
                '_token' : $('meta[name="csrf-token"]').attr('content'),
                'data' : formData,
                'fabric_id' : fabric_id,
                'bill_number' : bill_number,
                'bill_date' : bill_date,
                'partyname' : partyname,
                'bill_for' : bill_for,
                'lorry_no' : lorry_no,
                'dp_no' : dp_no,
                'gp_no' : gp_no,
                'remarks' : remarks,

            },
            beforeSend:function(){
                console.log('sending form');
            },
            // success:function(response){
            //     emptytable();
            //     callunlaminatedfabricajax();
            //     emptyform();
            // },
            success:function(response){
                console.log(response);
                // console.log('heyyyy');
                emptytable();
                filltable(response);
                // console.log(data.fabric,response.fabric,'lol');
                if(response.response != '404'){
                    filltable(response.response);
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
 

    $("#fabricNameId").change(function(e){
        getfabricsrelated_enable();
        $("#rollnumberfabric").prop('required',false);
        $("#rollnumberfabric").prop('disabled',true);
    });



 
    function getfabricsrelated_enable(){
        $('#getfabricsrelated').prop('disabled',false);
    }

    function emptytable(){
        $('#rawMaterialItemTbody').empty();
    }

 

    function filltable(data){
        data.response.forEach(d => {
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
            tr.append(`<td><div class="btn-group"><a id="sendforlamination" data-group='${d.average_wt}' data-standard='${result}' data-title='${d.name}' href="${d.id}" data-id="${d.id}" bill_number = "${data.bill_number}" bill_date = "${data.bill_date}"  plantname_id = "${data.plantname_id}" godam_id = "${data.godam_id}" plantype_id = "${data.plantype_id}" shift_id = "${data.shift_id}" class="btn btn-info">Send</a></div></td>`);


        });
    }

    function emptyform(){
        $("#createRawMaterial")[0].reset();
    }


    $(document).ready(function(){
        $(document).on('click',"#sendforlamination",function(e){
            e.preventDefault();
               let data_id = $(this).attr('href');
               let bill_number =  $(this).attr('bill_number');
               let bill_date =  $(this).attr('bill_date');
               let godam_id =  $(this).attr('godam_id');
               let plantype_id =  $(this).attr('plantype_id');
               let plantname_id =  $(this).attr('plantname_id');
               let shift_id =  $(this).attr('shift_id');
            
               // console.log(data);

            // debugger;
            $.ajax({
                url : "{{ route('tripalEntryStore') }}",
                method: "post",
                data:{
                    "_token" : $('meta[name="csrf-token"]').attr('content'),
                    // "doublefabric_id" : ,
                    "data_id" : data_id,
                    'bill_number' : bill_number,
                    'bill_date' : bill_date,
                    'godam_id' : godam_id,
                    'plantype_id' : plantype_id,
                    'plantname_id' : plantname_id,
                    'shift_id' : shift_id,
                },
                beforeSend:function(){
                    console.log("Before Send");
                },
                success:function(response){
                    location.reload();
                    $('#sendforlamination').attr('disabled', 'true'); 
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
            // $('#staticBackdrop1').modal('show');
            // let titleold = $('#staticBackdropLabel').text('');
            // let title = $(this).attr('data-title');
            // let id = $(this).attr('data-id');
            // $("#laminated_fabric_name").val(title+"(SingleLam)");
            // let laminated_fabric_group = $(this).attr('data-group');
            // $("#laminated_fabric_group").val(laminated_fabric_group);
            // let standard_weight_gram = $(this).attr('data-standard');
            // $("#standard_weight_gram").val(standard_weight_gram);
            // $('#staticBackdropLabel').text(title+" -> id = "+id);
            // $("#fabricsid").val(id);

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
            url : "{{ route('finaltripal.getTripalFabricEntry') }}",
            method:"get",
            success:function(response){
                console.log(response);
                emptycomparelamtbody();
                emptycompareunlamtbody();
                putonlamtbody(response);
                // $("#sendtolaminationform")[0].reset();
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
        // response.lam.forEach(element => {
        //     let tr = $("<tr></tr>").appendTo("#comparelamtbody");
        //     tr.append(`<td>#</td>`);
        //     tr.append(`<td>${element.name}</td>`);
        //     tr.append(`<td>${element.roll_no}</td>`);
        //     tr.append(`<td>${element.net_wt}</td>`);
        //     tr.append(`<td>${element.gross_wt}</td>`);
        //     tr.append(`<td>${element.meter}</td>`);
        //     tr.append(`<td>${element.average_wt}</td>`);
        //     tr.append(`<td>${element.gram}</td>`);
        // });

        response.tripalentry.forEach(element => {
            let tr = $("<tr></tr>").appendTo("#compareunlamtbody");
            tr.append(`<td>#</td>`);
            tr.append(`<td>${element.name}</td>`);
            tr.append(`<td>${element.roll_no}</td>`);
            tr.append(`<td>${element.net_wt}</td>`);
            tr.append(`<td>${element.gross_wt}</td>`);
            tr.append(`<td>${element.meter}</td>`);
            tr.append(`<td>${element.average_wt}</td>`);
            tr.append(`<td>${element.gram}</td>`);
        });

        response.finaltripal.forEach(element => {
            let tr = $("<tr></tr>").appendTo("#finaltripal");
            tr.append(`<td>#</td>`);
            tr.append(`<td>${element.name}</td>`);
            tr.append(`<td>${element.roll_no}</td>`);
            tr.append(`<td>${element.net_wt}</td>`);
            tr.append(`<td>${element.gross_wt}</td>`);
            tr.append(`<td>${element.meter}</td>`);
            tr.append(`<td>${element.average_wt}</td>`);
            tr.append(`<td>${element.gram}</td>`);
        });

        weightdiffs(response);
        
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
        // debugger;
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
                    // alert("add quantity");
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
        // let polo_waste = parseInt($("#polo_waste").val());
        let fabric_waste = parseInt($("#fabric_waste").val());
        let total_waste =  fabric_waste;
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
            let godam_id = $("#toGodam").val();
            let bill =  $("#bill").val();
            // console.log(godam_id);
            // debugger;

            trimmedConsumption = consumption.trim();
            trimmedFabricWaste = fabric_waste.trim();
            trimmedTotalWaste = total_waste.trim();


            if(trimmedConsumption == '' || trimmedFabricWaste == ''){
                alert('Waste and Consumption cannot be null');
            }else{
            // subtractformautolad(danaNameId,consumption);
                $.ajax({
                    url : "{{ route('finaltripal.wastage.submit') }}",
                    method: "post",
                    data:{
                        "_token" : $('meta[name="csrf-token"]').attr('content'),
                        "danaNameID" : danaNameId,
                        "consumption" : trimmedConsumption,
                        "fabric_waste" : trimmedFabricWaste,
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