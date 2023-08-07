@extends('layouts.admin')

@section('extra-style')
<link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
<link href="{{ asset('css/nepaliDatePicker/nepali.datepicker.v4.0.1.min.css') }}" rel="stylesheet" type="text/css" />
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
    <form action="{{ route('openingfinaltripal.storeFinalStock') }}" method="post" enctype="multipart/form-data">
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
                <input type="text" name="bill_date" class="form-control"
                    id="date_np">

                @error('bill_date')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('To Godam') }}
                </label>
                <select class="advance-select-box form-control" id="godama_id" name="godam_id" required>
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
                <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                    tabindex="-1" data-target="#groupModel"
                    style="margin-top:0 !important; top:8px;float:right;">
                    <i class="fas fa-plus"
                        style="display:flex;align-items: center;justify-content: center;"></i>
                </a>
                <label for="size" class="col-form-label">{{ __('FinalTripal Name') }}<span class="required-field">*</span>
                </label>
                <select class="advance-select-box form-control" id="tripal" name="fabric_id"
                    required>
                    <option value="">{{ __('Select FinalTripal Name') }}</option>
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
                    data-number="1" name="roll" min="1" required>

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
                    data-number="1" name="average" min="1" required readonly>

                @error('average')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('GSM') }}<span class="required-field">*</span>
                </label>
                <input type="text" step="any" min="0" class="form-control calculator" id="gsm"
                    data-number="1" name="gsm" min="1" required readonly>

                @error('gram')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
                <input type="hidden" name="tripal_decimalname" id="tripal_decimalname">
            </div>
            <div>
                <button type="submit" class="btn btn-primary mt-4">
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
                        <th>{{ __('FinalTripal Name') }}</th>
                        <th>{{ __('Roll No') }}</th>
                        <th>{{ __('G.W') }}</th>
                        <th>{{ __('N.W') }}</th>
                        <th>{{ __('Meter') }}</th>
                        <th>{{ __('Avg') }}</th>
                        <th>{{ __('GSM') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>

                <tbody id="rawMaterialItemTbody">

                    @foreach ($openingstocks as $key => $fabric)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $fabric->name }} </td>
                            <td>{{ $fabric->roll_no }} </td>
                            <td>{{ $fabric->gram}} </td>
                            <td>{{ $fabric->net_wt }}</td>
                            <td>{{ $fabric->meter }}</td>
                            <td>{{$fabric->average_wt}}</td>
                            <td>{{$fabric->gsm}}</td>
                            <td class="text-right">
                                <div class="btn-group">
                                    <button type="button"
                                            class="btn btn-secondary dropdown-toggle action-dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                      
                                        <a href="{{ route('openingfinaltripal.edit', $fabric->id) }}"
                                            class="dropdown-item"><i class="fas fa-edit"></i>
                                            {{ __('Edit') }}</a>
                                        <a href="{{ route('openingfinaltripal.delete', $fabric->id) }}"
                                            class="dropdown-item delete-btn"
                                            data-msg="{{ __('Are you sure you want to delete this sub category?') }}"><i
                                                class="fas fa-trash"></i> {{ __('Delete') }}</a>
                                    </div>
                                </div>
                            </td>


                        </tr>
                    @endforeach

                </tbody>


            </table>

        </div>
        {{ $openingstocks->links() }}

    </div>
</div>
<hr>

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
                                data-number="1" name="total_ul_in_mtr" min="1" readonly required value="{{$total_meter}}">
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
                                data-number="1" name="total_lam_in_mtr" min="1" readonly required value="{{$total_net}}">
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



@endsection
@section('extra-script')
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
<script src="{{ asset('js/storein.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
</script>
<script src="{{ asset('js/nepaliDatePicker/nepali.datepicker.v4.0.1.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
  var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
  $('#date_np').val(currentDate);
  $('#date_np').nepaliDatePicker({
    ndpYear: true,
    ndpMonth: true,
    disableAfter: currentDate,
    });
  
  });
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

        let data = (tripal_decimalname / 39.37);
        let datas = data.toFixed(2);

        let gsm = (average) / datas;
        console.log(tripal_decimalname,data,gsm);
        let finalgsm = gsm.toFixed(2);


        $("#gsm").val(finalgsm);

    });

    $(document).on("keyup","#net_wt",function(e){

        let net_wt = parseInt($("#net_wt").val());
        let meter = parseInt($("#meter").val());
        let average = (net_wt / meter) * 1000;
        $("#average").val(average);

    });

</script>

@endsection