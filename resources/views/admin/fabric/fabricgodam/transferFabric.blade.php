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
    <form id="createRawMaterial">
        @csrf

        <div class="row">
            <div class="col-md-4 form-group">
                <label for="size" class="col-form-label">{{ __('Invoice No') }}<span class="required-field">*</span>
                </label>
                <input type="text" class="form-control" id="bill_number" name="bill_number" value="{{$find_data->bill_no}}" 
                    readonly /> {{-- value="FSR-{{ getNepalidate(date('Y-m-d')).'-'.rand(0,9999)}}" --}}
                    <input type="hidden" name="fabricgodam_id" value="{{$fabricgodam_id}}" id="fabricgodam_id">
            </div>

            <div class="col-md-4 form-group">
                <label for="size" class="col-form-label">{{ __('Invoice Date') }}
                </label>
                <input class="form-control calculator"
                    id="date_np" name="bill_date" placeholder="{{ __('Remarks') }}" value="{{$find_data->bill_date}}" readonly>

                @error('bill_date')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            
            <div class="col-md-4 form-group">
                <label for="size" class="col-form-label">{{ __('Remarks') }}<span class="required-field">*</span>
                </label>
                <input type="text" class="form-control" id="remarks" name="remarks"
                    value="{{$find_data->remarks}}" readonly/> 
            </div>
            <div class="col-md-3 form-group">
                <label for="fromgodam">{{ __('From Godam') }}<span class="required-field">*</span></label>
                <select class="advance-select-box form-control @error('fromgodam') is-invalid @enderror" id="fromgodam_id" name="fromgodam_id"  required>
                    <option value="" selected disabled>{{ __('Select a fromgodam') }}</option>
                    @foreach($fromgodams as $key => $fromgodam)
                        <option value="{{ $fromgodam->id }}">{{ $fromgodam->name }}</option>
                    @endforeach
                </select>
                @error('fromgodam_id')
                <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                @enderror
            </div>
            <div class="col-md-3 form-group">
                <label for="togodam">{{ __('To Godam') }}<span class="required-field">*</span></label>
                <select class="advance-select-box form-control @error('togodam') is-invalid @enderror" id="togodam_id" name="togodam_id"  required>
                    <option value="" selected disabled>{{ __('Select a togodam') }}</option>
                    @foreach($togodams as $key => $togodam)
                        <option value="{{ $togodam->id }}">{{ $togodam->name }}</option>
                    @endforeach
                </select>
                @error('togodam_id')
                <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                @enderror
            </div>

            <div class="col-md-3 form-group">
                <label for="fabricstock">{{ __('FabricStock') }}<span class="required-field">*</span></label>
                <select class="advance-select-box form-control @error('fabricstock') is-invalid @enderror" id="fabricstock_id" name="fabricstock_id"  required>
                  {{--   <option value="" selected disabled>{{ __('Select a fabricstock') }}</option>
                    @foreach($fabricstocks as $key => $fabricstock)
                        <option value="{{ $fabricstock->id }}">{{ $fabricstock->name }}</option>
                    @endforeach --}}
                </select>
                @error('togodam_id')
                <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                @enderror
            </div>

            
            <div class="col-md-1 form-group">
                <button id="getfabricsrelated" class="btn btn-sm btn-primary" style="margin-top:35px;">
                    Add
                </button>
            </div> 

        </div>
        
    </form>
</div>
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


@endsection
@section('extra-script')
<script src="{{ asset('js/nepaliDatePicker/nepali.datepicker.v4.0.1.min.js') }}"></script>
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
<script src="{{ asset('js/storein.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
</script>
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
<script>
    let fabricTable = null;

    $("#fabricstock_id").change(function(e){
        if (fabricTable !== null) {
            fabricTable.destroy();
        }

        var bill_number = $('#bill_number').val(),
        bill_date = $('#date_np').val(),
        fromgodam_id = $('#fromgodam_id').val(),
        togodam_id = $('#togodam_id').val(),
        fabricstock_id = $('#fabricstock_id').val();

        let fabric_name_id = $(this).val();
        fabricTable = $("#sameFabricsTable").DataTable({
            serverside : true,
            processing : true,
            ajax : {
                url : "{{ route('get.fabric.same.name') }}",
                method : "post",
                data : function(data){
                    data._token = $("meta[name='csrf-token']").attr("content"),
                    data.fabric_name_id = fabric_name_id,
                    data.bill_number = bill_number,
                    data.bill_date = bill_date,
                    data.fromgodam_id = fromgodam_id,
                    data.togodam_id = togodam_id
                    // data.fabricstock_id = fabricstock_id
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

    })



    $("#fromgodam_id").change(function(e){

        let godam_id =  $(this).val();


        $.ajax({
            url : "{{ route('fabricSendReceive.get.fabrics') }}",
            method: 'get',
            type:"POST",
            dataType:"JSON",
            data:{
                '_token' : $('meta[name="csrf-token"]').attr('content'),
                'godam_id' : godam_id,

            },
            beforeSend:function(){
                console.log('Getting Plant type');
            },
            success:function(response){
                addfabric(response);
            
            },
            error:function(error){
                console.log(error);
            }
        });
    });

    function addfabric(data){
        $("#fabricstock_id").empty();
        $('#fabricstock_id').append(`<option value="" disabled selected>--Select Plant Type--</option>`);
        data.fabrics.forEach( d => {
            $('#fabricstock_id').append(`<option value="${d.id}">${d.name}</option>`);
        });
    }

   



    

    $(document).ready(function(){
        $(document).on('click',".sendforlamination",function(e){
            e.preventDefault();

            var ids = $(this).attr('data-id'),
                fromgodam_id = $(this).attr('data-fromgodamid'),
                togodam_id = $(this).attr('data-togodamid'),
                bill_no = $(this).attr('bill_no'),
                bill_date = $(this).attr('bill_date'),
                fabricgodam_id = $('#fabricgodam_id').val(),
                token = $('meta[name="csrf-token"]').attr('content');

                // debugger;

                $.ajax({
                  type:"POST",
                  dataType:"JSON",
                  url:"{{route('getFabricGodamStore')}}",
                  data:{
                    _token: token,
                    ids: ids,
                    fromgodam_id: fromgodam_id,
                    togodam_id: togodam_id,
                    bill_no: bill_no,
                    bill_date: bill_date,
                    fabricgodam_id: fabricgodam_id,
                  },
                  success: function(response){
                    location.reload();
                    

                  },
                  error: function(event){
                    alert("Sorry");
                  }
                });

         
        });
    });

</script>

@endsection