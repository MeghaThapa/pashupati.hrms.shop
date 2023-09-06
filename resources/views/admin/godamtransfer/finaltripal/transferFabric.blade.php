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
            <div class="col-md-3 form-group">
                <label for="size" class="col-form-label">{{ __('Invoice No') }}<span class="required-field">*</span>
                </label>
                <input type="text" class="form-control" id="bill_number" name="bill_number" value="{{$find_data->bill_no}}" 
                    readonly /> {{-- value="FSR-{{ getNepalidate(date('Y-m-d')).'-'.rand(0,9999)}}" --}}
                    <input type="hidden" name="tripalgodam_id" value="{{$tripalgodam_id}}" id="tripalgodam_id">
            </div>

            <div class="col-md-3 form-group">
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
            
            <div class="col-md-3 form-group">
                <label for="size" class="col-form-label">{{ __('Remarks') }}<span class="required-field">*</span>
                </label>
                <input type="text" class="form-control" id="remarks" name="remarks"
                    value="{{$find_data->remarks}}" readonly/> 
            </div>
            <div class="col-md-3 form-group">
                <label for="size" class="col-form-label">{{ __('From') }}<span class="required-field">*</span>
                </label>
                <input type="text" class="form-control" id="remarks" name="remarks"
                    value="{{$find_data->getFromGodam->name}}" readonly/> 
            </div>
            <div class="col-md-3 form-group">
                <label for="size" class="col-form-label">{{ __('To') }}<span class="required-field">*</span>
                </label>
                <input type="text" class="form-control" id="remarks" name="remarks"
                    value="{{$find_data->getToGodam->name}}" readonly/> 
            </div>
            

            <div class="col-md-3 form-group">
                <label for="stock">{{ __('FinalTripalStock') }}<span class="required-field">*</span></label>
                <select class="advance-select-box form-control @error('stock') is-invalid @enderror" id="stock_id" name="stock_id"  required>
                  <option value="" selected disabled>{{ __('Select a finaltripalstock') }}</option>
                    @foreach($stocks as $key => $stock)
                        <option value="{{ $stock->id }}">{{ $stock->name }}</option>
                    @endforeach
                </select>
                @error('togodam_id')
                <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                @enderror
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
                    <th>{{ __('Gsm') }}</th>
                    <th>{{__('Send')}}</th>
                </tr>
            </thead>
            <tbody id="same-fabrics"></tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="table-responsive table-custom my-3">
        <table class="table table-hover table-striped" id="getSaleTripalList">
            <thead class="table-info">
                <tr>
                    <th>{{ __('Sr.No') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Roll') }}</th>
                    <th>{{ __('Gross') }}</th>
                    <th>{{ __('N.W') }}</th>
                    <th>{{ __('Meter') }}</th>
                    <th>{{ __('Average') }}</th>
                    <th>{{ __('Gsm') }}</th>
                    <th>{{ __('Invoice No') }}</th>
                    <th>{{__('Send')}}</th>
                </tr>
            </thead>
            <tbody id="getSaleTripalList"></tbody>
        </table>
    </div>
</div>

<div class="card-footer">
    {{-- <input type="hidden" name="selectedDanaID" class="form-control" id="selectedDanaID" readonly> --}}
    <button type="submit" class="btn btn-info" id="finalUpdate">Update</button>
  
</div>



@endsection
@section('extra-script')
<script src="{{ asset('js/nepaliDatePicker/nepali.datepicker.v4.0.1.min.js') }}"></script>
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
<script src="{{ asset('js/storein.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
</script>

<script>

    $(document).ready(function(){
      
        let salesTripalTable = null;
        getTripalListData();



        $("body").on("click","#finalUpdate", function(event){
            var tripalgodam_id = $('#tripalgodam_id').val(),
            token = $('meta[name="csrf-token"]').attr('content');
              
            $.ajax({
              type:"POST",
              dataType:"JSON",
              url:"{{route('getTripalGodamFinalStore')}}",
              data:{
                _token: token,
                tripalgodam_id: tripalgodam_id,
            },
            success: function(response){
                location.href = '{{route('tripalGodamTransfer.index')}}';
              
            },
            error: function(event){
                alert("Sorry");
            }
           });
        });


        function getTripalListData(){
            if (salesTripalTable !== null) {
                salesTripalTable.destroy();
            } 
            // debugger;
             let tripal_id = $('#tripalgodam_id').val();

            salesTripalTable = $("#getSaleTripalList").DataTable({
                serverside : true,
                processing : true,
                lengthmenu : [
                        [5,10,25,50,100,250,500,-1],
                        [5,10,25,50,100,250,500,"All"]
                    ],
                ajax : {
                    url : "{{ route('transfer.getList') }}",
                    method : "get",
                    data :{
                        "_token" : $("meta[name='csrf-token']").attr("content"),
                        'tripal_id' : tripal_id,
                    }
                },
                columns:[
                    { data : "DT_RowIndex" , name : "DT_RowIndex" },
                    { data : "name" , name : "name" },
                    { data : "roll" , name : "roll" },
                    { data : "gross" , name : "gross" },
                    { data : "net" , name : "net" },
                    { data : "meter" , name : "meter" },
                    { data : "average" , name : "average" },
                    { data : "gsm" , name : "gsm" },
                    { data : "bill_no" , name : "bill_no" },
                    { data : "action" , name : "action" },
                ]
            });
        }
    

        $(document).on('click','.deleteTripalEntry',function(e){
            e.preventDefault();
            let id = $(this).attr('data-id');
            deletefromunlamintedtable(id);
        });

        function deletefromunlamintedtable(data){
           var data_id = data,
           token = $('meta[name="csrf-token"]').attr('content');
           $.ajax({
             type:"GET",
             dataType:"JSON",
             url:"{{route('tripalgodam.deleteList')}}",
             data:{
               _token: token,
               data_id: data_id,
           },
           success: function(response){
              $('#getSaleTripalList').DataTable().ajax.reload();
           },
           error: function(event){
               alert("Sorry");
           }
           });

         
        }


    });

    let fabricTable = null;

    $("#stock_id").change(function(e){
        if (fabricTable !== null) {
            fabricTable.destroy();
        }

        var bill_number = $('#bill_number').val(),
        bill_date = $('#date_np').val(),
        stock_id = $('#stock_id').val(),
        bill_id = $('#tripalgodam_id').val();
        // debugger;

        let fabric_name_id = $(this).val();
        fabricTable = $("#sameFabricsTable").DataTable({
            serverside : true,
            processing : true,
            ajax : {
                url : "{{ route('getFilterGodamTripal') }}",
                method : "post",
                data : function(data){
                    data._token = $("meta[name='csrf-token']").attr("content"),
                    data.stock_id = stock_id
                    data.bill_id = bill_id
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
                { data : "gsm" , name : "gsm" },
                { data : "action" , name : "action" },
            ]
        });

    })


    $(document).ready(function(){
        $(document).on('click',".sendforentry",function(e){
            e.preventDefault();

            var ids = $(this).attr('data-id'),
            tripalgodam_id = $('#tripalgodam_id').val(),
            token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
              type:"POST",
              dataType:"JSON",
              url:"{{route('getTripalGodamStore')}}",
              data:{
                _token: token,
                data_id: ids,
                tripalgodam_id: tripalgodam_id,
            },
            success: function(response){
                $('#getSaleTripalList').DataTable().ajax.reload();

            },
            error: function(event){
                alert("Sorry");
            }
        });


        });
    });

</script>

@endsection