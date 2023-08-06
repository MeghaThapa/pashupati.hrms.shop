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
    <form action="{{ route('openingfinaltripal.update', $finaltripalstocks->id)  }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')

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
                <input type="date" value="{{ $finaltripalstocks->date_en }}" step="any" min="0" class="form-control calculator"
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
                <select class="advance-select-box form-control" id="godama_id" name="godam_id" required>
                    <option value="" selected disabled>{{ __('Select Godam Name') }}</option>
                    @foreach ($godam as $data)
                    <option value="{{ $data->id }}" {{$finaltripalstocks->godam_id == $data->godam_id ? 'selected' : ''}}>{{ $data->name }}
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
                <label for="size" class="col-form-label">{{ __('FinalTripal Name') }}<span class="required-field">*</span>
                </label>
                <select class="advance-select-box form-control" id="tripal" name="fabric_id"
                    required>
                    <option value="">{{ __('Select FinalTripal Name') }}</option>
                   @foreach ($finaltripalname as $singlestock)
                    <option value="{{ $singlestock->id }}" {{$finaltripalstocks->finaltripalname_id == $singlestock->id ? 'selected' : ''}}>{{ $singlestock->name }}
                    </option>
                    @endforeach
                </select>
                @error('tripal')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>


            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Roll') }}<span class="required-field">*</span>
                </label>
                <input type="text" step="any" min="0" class="form-control calculator" id="rollnumberfabric"
                    data-number="1" name="roll" min="1" value="{{$finaltripalstocks->roll_no}}">

                @error('roll')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="col-md-3 form-group">
                <label for="size" class="col-form-label">{{ __('Gram Weight') }}<span class="required-field">*</span>
                </label>
                <input type="text" step="any" min="0" class="form-control calculator" id="gram_wt"
                    data-number="1" name="gram_wt" min="1" value="{{$finaltripalstocks->gram}}">

                @error('gram')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Net Weight') }}<span class="required-field">*</span>
                </label>
                <input type="text" step="any" min="0" class="form-control calculator" id="net_wt"
                    data-number="1" name="net_wt" min="1"  value="{{$finaltripalstocks->net_wt}}">

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
                    data-number="1" name="meter" min="1" value="{{$finaltripalstocks->meter}}">

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
                    data-number="1" name="average" min="1" readonly value="{{$finaltripalstocks->average_wt}}">

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
                    data-number="1" name="gsm" min="1" required readonly value="{{$finaltripalstocks->gsm}}">

                @error('gsm')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
                <input type="hidden" name="tripal_decimalname" id="tripal_decimalname">
            </div>
            <div>
                <button type="submit" class="btn btn-primary mt-4">
                    Update
                </button>
            </div>

        </div>
        
    </form>
</div>

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