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
                <input type="hidden" name="toGodam" value="{{$find_data->godam_id}}" id="toGodam">
                <input value="{{ $find_data->getGodam->name }}" step="any" min="0" class="form-control calculator" readonly>
                @error('to_godam_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Plant Type') }}
                </label>
                <input value="{{ $find_data->getPlantType->name }}" class="form-control" readonly>
                <input type="hidden" name="plant_type_id" value="{{$find_data->planttype_id}}" id="plantType">
                
                @error('plant_type_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Plant Name') }}
                </label>
                <input type="hidden" name="plant_name_id" value="{{$find_data->plantname_id}}" id="plantName">

                <input value="{{ $find_data->getPlantName->name }}" class="form-control" readonly>
                
                @error('gp_no')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Shift') }}
                </label>
                <input value="{{ $find_data->getShift->name }}" class="form-control" readonly>

                {{-- <select class="advance-select-box form-control" id="shiftName" name="shift_name_id" disabled required>
                    <option value="" selected disabled>{{ __('Select Shift Name') }}</option>
                    @foreach ($shifts as $shift)
                    <option value="{{ $shift->id }}">{{ $shift->name }}
                    </option>
                    @endforeach
                </select> --}}
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
                    <input type="hidden" name="bill_id" value="{{$id}}" id="bill_id">
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
                            @foreach ($finaltripalname as $tripalname)
                            <option value="{{ $tripalname->id }}">{{ $tripalname->name }}
                            </option>
                            @endforeach
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
                        <input type="hidden" name="tripal_decimalname" id="tripal_decimalname"> 
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
    <div class="col-md-7">
        <form id="addFinalTripalDanaConsumption">
            <div class="card p-2">
                <div class="row">
                    
               

                    <div class="col-md-4">
                        <label for="cut_length">Dana Name</label>
                        <select
                            class="advance-select-box form-control  @error('dana_name_id') is-invalid @enderror"
                            id="danaNameId" name="dana_name_id" required>
                            <option value="">Select</option>

                            @foreach ($danas as $dana)
                                <option value="{{ $dana->id }}">
                                    {{ $dana->danaName->name }}
                                </option>
                            @endforeach
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
                    <th>Action</th>


                </tr>
            </thead>
            <tbody >
                @foreach($danalist as $list)
                <tr>
                    <td>#</td>
                    <td>{{$list->getAutoloader->fromGodam->name}}</td>

                    <td>{{$list->danaName->name}}</td>
                    <td>{{$list->quantity}}</td>

                    <td>
                        <a href="{{ route('finalDanaConsumption.delete', $list->id) }}"
                            class="btnEdit btn btn-sm btn-danger"><i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
            
        </table>
    </div>
</div>
<div class="row">
    
    <div id="success_msg" class="alert alert-success mt-2" hidden>

    </div>
    <div class="card col-md-12
    ">
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
                                name="totl_dana" min="1" readonly required value="{{$sumdana}}">
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
                            <input type="hidden" name="godam_id" id="godam_id"  value="{{$find_data->godam_id}}">
                    <input type="hidden" name="planttype_id" id="planttype_id"  value="{{$find_data->planttype_id}}">
                    <input type="hidden" name="plantname_id" id="plantname_id"  value="{{$find_data->plantname_id}}">
                    <input type="hidden" name="bill_id" id="bill_id" value="{{$id}}">
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
<script type="text/javascript">
  $("body").on("change","#tripal", function(event){
    var tripal = $('#tripal').val(),
        token = $('meta[name="csrf-token"]').attr('content');

        // debugger;
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('getFilterTripalList')}}",
      data:{
        _token: token,
        tripal: tripal,
      },
      success: function(response){
        $('#tripal_decimalname').val(response.name);

      },
      error: function(event){
        alert("Sorry");
      }
    });
  });
</script>
<script>

    $(document).on("keyup","#meter",function(e){

        let net_wt = parseInt($("#net_wt").val());
        let meter = parseInt($("#meter").val());
        let test = (net_wt / meter) * 1000;
        let average = Math.round(test);
        $("#average").val(average);

        let tripal_decimalname = parseInt($("#tripal_decimalname").val());

        let data = tripal_decimalname / 39.37;
        let datas = data.toFixed(2);

        let gsm = (average) / datas;
        let finalgsm = gsm.toFixed(2);


        $("#gsm").val(finalgsm);

    });

    $(document).on("keyup","#net_wt",function(e){

        let net_wt = parseInt($("#net_wt").val());
        let meter = parseInt($("#meter").val());
        let average = (net_wt / meter) * 1000;
        $("#average").val(average);

    });



    $(document).ready(function(){
        /**************************** Ajax Calls **************************/
        // callunlaminatedfabricajax();
         // $('#fabricNameId').prop('disabled',true);
        comparelamandunlam();


        pageRefresh();

        async function pageRefresh() {
            await getdanaConsumptionData();
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


        $('#godamId').on('select2:select', function(e) {
            let godam_id = e.params.data.id;
            $('#danaGroupId').empty();
            getDanaName(godam_id);
        });

        $('#danaNameId').on('select2:select', function(e) {
            // console.log('df');
            // debugger;
            let autoloader_id = e.params.data.id;
            $('#avilableStock').empty();
            getStockQuantity(autoloader_id);
        });

        function getStockQuantity(autoloader_id) {
            $.ajax({
                url: "{{ route('tripalDana.getStockQuantity') }}",
                method: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    autoloader_id: autoloader_id,
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
        document.getElementById('addFinalTripalDanaConsumption').addEventListener('submit',
            function(e) {
                e.preventDefault();
                debugger;
                const form = e.target;
                let bill_no = $("#bill_no").val();
                let bill_id = $("#bill_id").val();
                //console.log('testing', printCutEntry_id);
                let autoloader_id = form.elements['dana_name_id'];
                let quantity = form.elements['quantity'];
                $.ajax({
                    url: "{{ route('finalTripalDanaConsumption.store') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        bill_id: bill_id,
                        bill_no: bill_no,
                        autoloader_id: autoloader_id.value,
                        quantity: quantity.value
                    },
                    success: function(response) {
                        location.reload();
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
                    url: '{{ route('finalTripal.getFinalTripalDanaConsumption') }}',
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

        $("#toGodam").change(function(e){

            let department_id =  $(this).val();
            let geturl = "{{ route('fabricSendReceive.get.planttype',['id'=>':id']) }}"
            $("#godam_data").val(department_id);
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
            $("#planttype_data").val(department_id);
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
            $("#plantname_data").val(department_id);
        
        });

        $("#shiftName").change(function(e){
            let shift_id =  $(this).val();
            $("#shift_data").val(shift_id);
        
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
            // debugger;
            let action = $(this).attr('action');
            let method = $(this).attr('method');
            let formData = $(this).serialize();

            var bill_number = $('#bill_nos').val(),
            bill_date = $('#bill_dates').val(),
            godam_id = $('#godam_data').val(),
            plantype_id = $('#planttype_data').val(),
            plantname_id = $('#plantname_data').val(),
            shift_id = $('#shift_data').val(),
            fabric_id = $('#fabricNameId').val(),
            roll = $('#rollnumberfabric').val();
            // debugger;

           $.ajax({
            url : "{{ route('finaltripal.getDoubleFabricStockList') }}",
            method: 'get',
            type:"POST",
            dataType:"JSON",
            data:{
                '_token' : $('meta[name="csrf-token"]').attr('content'),
                'data' : formData,
                'fabric_id' : fabric_id,
                'roll' : roll,
                'bill_number' : bill_number,
                'bill_date' : bill_date,
                'godam_id' : godam_id,
                'plantype_id' : plantype_id,
                'plantname_id' : plantname_id,
                'shift_id' : shift_id,


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

    $(document).on('click','#deletesendforlamination',function(e){
        e.preventDefault();
        let id = $(this).attr('data-id');
        deletefromunlamintedtable(id);
    });

    function filltable(data){
        // console.log(data,data.godam_id);
        // console.log(data.response,data);
        data.response.forEach(d => {
            console.log(data,d,'hey');
        // console.log(d.godam_id,d.plantype_id,data.shift_id,'hey');
            
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
                console.log(error);
            }
        });
    }

    $(".discard").click(function(e){
        $.ajax({
            url:"{{ route('discard') }}",
            method:"get",
            success:function(response){
                if(response.message == "200"){
                    location.reload(true);
                }
                else{
                    alert(response.message);
                }
            },
            error:function(response){
                console.log(response);
            }
        });
    });
    /************************* Other Functionalities ***********************/

    /************************* Send for lamination **************************/

     // $('#tripal_form').on('submit', function () {
     //       $('#register').attr('disabled', 'true'); 
     //    });
    $(document).ready(function(){
        $(document).on('click',"#sendforlamination",function(e){
            e.preventDefault();
               let data_id = $(this).attr('href');
               let bill_number =  $(this).attr('bill_number');
               let bill_date =  $(this).attr('bill_date');
               let godam_id =  $("#godam_id").val();
               let plantype_id =  $("#planttype_id").val();
               let plantname_id =  $("#plantname_id").val();
               let shift_id =  $("#shift_id").val();
               let bill_id = $("#bill_id").val();
            
               // console.log(data);

            debugger;
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
                    'bill_id' : bill_id,
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
        var bill_id = $('#bill_id').val();
        $.ajax({
            url : "{{ route('finaltripal.getTripalFabricEntry') }}",
            method:"get",
            data:{
                "_token": $('meta[name="csrf-token"]').attr('content'),
                "bill_id" : bill_id
            },
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

        let diff_unLam_lamNw = parseFloat(total_ul_in_mtr) - parseFloat(total_lam_in_mtr);
        diff_unLam_lamNw = diff_unLam_lamNw.toFixed(4);
        $('#diff_unLam_lamNw').val(diff_unLam_lamNw);

        let total_diff = parseFloat(total_ul_net_wt) - parseFloat(total_lam_net_wt);
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

            trimmedConsumption = consumption;
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