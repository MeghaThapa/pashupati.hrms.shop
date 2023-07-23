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
                <input type="text" class="form-control" id="billnumber" value="Opening" name="bill_number"
                    required readonly /> 
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
                <label for="size" class="col-form-label">{{ __('Fabric Name') }}<span class="required-field">*</span>
                </label>
                <select class="advance-select-box form-control" id="fabricNameId" name="fabric_name_id"
                    required>
                    <option value="">{{ __('Select Fabric Name') }}</option>
                   @foreach ($singlestocks as $singlestock)
                    <option value="{{ $singlestock->id }}">{{ $singlestock->name }}
                    </option>
                    @endforeach
                </select>
                @error('fabric_name_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>


            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Roll') }}<span class="required-field">*</span>
                </label>
                <input type="text" step="any" min="0" class="form-control calculator" id="rollnumberfabric"
                    data-number="1" name="roll_number" min="1" required>

                @error('fabric_name_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="col-md-3 form-group">
                <label for="size" class="col-form-label">{{ __('Gram Weight') }}<span class="required-field">*</span>
                </label>
                <input type="text" step="any" min="0" class="form-control calculator" id="gram_wt"
                    data-number="1" name="gram_wt" min="1" required>

                @error('gram_wt')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Net Weight') }}<span class="required-field">*</span>
                </label>
                <input type="text" step="any" min="0" class="form-control calculator" id="net_wt"
                    data-number="1" name="net_wt" min="1" required>

                @error('net_wt')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Meter') }}<span class="required-field">*</span>
                </label>
                <input type="text" step="any" min="0" class="form-control calculator" id="meter"
                    data-number="1" name="meter" min="1" required>

                @error('meter')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Average') }}<span class="required-field">*</span>
                </label>
                <input type="text" step="any" min="0" class="form-control calculator" id="average"
                    data-number="1" name="average" min="1" required>

                @error('average')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('GSM') }}<span class="required-field">*</span>
                </label>
                <input type="text" step="any" min="0" class="form-control calculator" id="gram"
                    data-number="1" name="gram" min="1" required>

                @error('gram')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div>
                <button id="getfabricsrelated" class="btn btn-primary mt-4">
                    Add
                </button>
            </div>
            
            
            
            {{-- <div>
                <button type="submit" class="btn btn-sm btn-primary" style="margin-top:35px;">
                    Add
                </button>
            </div> --}}

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
                    </tr>
                </thead>

                <tbody id="rawMaterialItemTbody">

                    @foreach ($openingstocks as $key => $fabric)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $fabric->name }} </td>
                            <td>{{ $fabric->roll_no }} </td>
                            <td>{{ $fabric->gross_wt}} </td>
                            <td>{{ $fabric->net_wt }}</td>
                            <td>{{ $fabric->meter }}</td>
                            <td>{{$fabric->average_wt}}</td>

                        </tr>
                    @endforeach

                </tbody>
         

            </table>
        </div>

    </div>
</div>
<hr>
{{-- <h1 class='text-center'>Compare Lam and Unlam</h1> --}}

<hr>
<div class="row">
    
    <div class="card col-md-12">
        <div class="card-body m-2 p-5">
            <div class="col-md-12" style="height: 100%;">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <div>
                            <label for="size" class="col-form-label">{{ __('Total Mtr:') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator" id="total_ul_in_mtr"
                                data-number="1" name="total_ul_in_mtr" min="1" readonly required value="{{$total_net}}">
                            @error('total_ul_in_mtr')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                      
        
                    </div>
        
                    <div class="col-md-4 form-group">
                        <div>
                            <label for="size" class="col-form-label">{{ __('Total Netwt:') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator" id="total_lam_in_mtr"
                                data-number="1" name="total_lam_in_mtr" min="1" readonly required value="{{$total_meter}}">
                            @error('total_lam_in_mtr')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        
                    </div>

                </div>
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




    /************************* Form Submission *************************/
    $(document).ready(function(){

        $(document).on('click','#getfabricsrelated',function(e){
            e.preventDefault();

            var bill_number = $('#billnumber').val(),
            bill_date = $('#billDate').val(),
            godam_id = $('#toGodam').val(),
            fabric_id = $('#fabricNameId').val(),
            roll = $('#rollnumberfabric').val(),
            gram_wt = $('#gram_wt').val(),
            net_wt = $('#net_wt').val(),
            meter = $('#meter').val(),
            average = $('#average').val(),
            gram = $('#gram').val();
            // debugger;

           $.ajax({
            url : "{{ route('openingtripal.storeSingleStock') }}",
            method: 'post',
            dataType:"JSON",
            data:{
                '_token' : $('meta[name="csrf-token"]').attr('content'),
                'fabric_id' : fabric_id,
                'roll' : roll,
                'bill_number' : bill_number,
                'bill_date' : bill_date,
                'godam_id' : godam_id,
                'gram_wt' : gram_wt,
                'net_wt' : net_wt,
                'meter' : meter,
                'average' : average,
                'gram' : gram,


            },
            beforeSend:function(){
                console.log('sending form');
            },
       
            success:function(response){
                emptytable();
                location.reload();
                // if(response.response != '404'){
                //     filltable(response);
                // }else{
                //     console.log(response.response);
                // }

            },
            error:function(error){
                console.log(error);
            }
           });
        });
    })

  
    /************************* Form Submission *************************/



    function emptytable(){
        $('#rawMaterialItemTbody').empty();
    }
 


</script>
@endsection