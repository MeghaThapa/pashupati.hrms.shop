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
                        <th>{{ __('Bill No') }}</th>
                        <th>{{ __('Bill Date') }}</th>
                        <th>{{ __('From Godam') }}</th>
                        <th>{{ __('To Godam') }}</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($fabricdetails as $key => $fabricdetail)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $fabricdetail->name}} </td>
                            <td>{{ $fabricdetail->bill_no }}</td>
                            <td>{{ $fabricdetail->bill_date }}</td>
                            <td>{{ $fabricdetail->getFromGodam->name }}</td>
                            <td>{{ $fabricdetail->getToGodam->name }}</td>
                            
                            
                        </tr>
                    @endforeach
                    
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
    $(document).ready(function(){
        $(document).on('click','#getfabricsrelated',function(e){
            e.preventDefault();

            var bill_number = $('#bill_nos').val(),
            bill_date = $('#bill_dates').val(),
            fromgodam_id = $('#fromgodam_id').val(),
            togodam_id = $('#togodam_id').val(),
            fabricstock_id = $('#fabricstock_id').val(),
            group_id = $('#group_id').val(),
            bill_no = $('#bill_number').val(),
            bill_date = $('#date_np').val();
            // debugger;

           $.ajax({
            url : "{{ route('godamfabrics.getFabricStockList') }}",
            method: 'get',
            type:"POST",
            dataType:"JSON",
            data:{
                '_token' : $('meta[name="csrf-token"]').attr('content'),
                'fabricstock_id' : fabricstock_id,
                'group_id' : group_id,
                'fromgodam_id' : fromgodam_id,
                'togodam_id' : togodam_id,
                'bill_no' : bill_no,
                'bill_date' : bill_date,

            },
            beforeSend:function(){
                console.log('sending form');
            },
          
            success:function(response){
                // alert(response.fabricstocks);
                // emptytable();
                filltable(response);
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

    function filltable(response){
        // alert(response.fabricstocks);
        // console.log(response,response.fabricstocks);
        // debugger;
        response.fabricstocks.forEach(d => {
            // console.log(d.name);
            let title = d.name;
            let group = d.average_wt.split('-')[0];
            let result = parseFloat(title) * parseFloat(group);

            let tr = $("<tr></tr>").appendTo('#rawMaterialItemTbody');

            tr.append(`<td>#</td>`);
            tr.append(`<td>${d.name} (${d.fabricgroup.name})</td>`);
            tr.append(`<td>${d.roll_no}</td>`);
            tr.append(`<td>${d.gross_wt}</td>`);
            tr.append(`<td>${d.net_wt}</td>`);
            tr.append(`<td>${d.meter}</td>`)
            tr.append(`<td>${d.meter}</td>`);
            tr.append(`<td>${d.average_wt}</td>`);
            tr.append(`<td><div class="btn-group"><a id="sendforlamination" data-group='${d.fabricgroup.name}' data-standard='${d.net_wt}' data-title='${d.name}' href="${d.id}" data-id="${d.id}" data-fromgodamid='${response.fromgodam_id}' data-togodamid='${response.togodam_id}' bill_no='${response.bill_no}' bill_date='${response.bill_date}'  class="btn btn-info">Send</a></div></td>`);
        });
    }

    $(document).ready(function(){
        $(document).on('click',"#sendforlamination",function(e){
            e.preventDefault();
           
            // let title = $(this).attr('data-title');
            // let id = $(this).attr('data-id');
            // $("#laminated_fabric_name").val(title+"(SingleLam)");
            // let laminated_fabric_group = $(this).attr('data-group');
            // $("#laminated_fabric_group").val(laminated_fabric_group);
            // let standard_weight_gram = $(this).attr('data-standard');
            // $("#standard_weight_gram").val(standard_weight_gram);
            // $('#staticBackdropLabel').text(title+" -> id = "+id);
            // $("#fabricsid").val(id);

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
                    $('#tripal_decimalname').val(response.name);

                  },
                  error: function(event){
                    alert("Sorry");
                  }
                });

         
        });
    });

</script>

@endsection