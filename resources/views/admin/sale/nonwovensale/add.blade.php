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
    <form enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Invoice No') }}<span class="required-field">*</span>
                </label>
                <input type="text" id="bill_no" class="form-control" value="{{ $find_data->bill_no }}"
                    readonly /> 
                <input type="hidden" name="nonwovensale_id" id="nonwovensale_id" value="{{$id}}">
            </div>

            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Invoice Date') }}
                </label>
                <input type="text" id="bill_date" value="{{ $find_data->bill_date }}" class="form-control calculator" readonly>

            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('PartyName ') }}
                </label>
                <input type="text" value="{{ $find_data->partyname_id }}" class="form-control calculator" readonly>

            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Bill For') }}
                </label>
                <input type="text" value="{{ $find_data->bill_for }}" class="form-control calculator" readonly>

            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Lorrynumber') }}
                </label>
                <input type="text" value="{{ $find_data->lorry_no }}" class="form-control calculator" readonly>

            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('GP number') }}
                </label>
                <input type="text" value="{{ $find_data->gp_no }}" class="form-control calculator" readonly>

            </div>

            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('DO number') }}
                </label>
                <input type="text" value="{{ $find_data->do_no }}" class="form-control calculator" readonly>

            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Remarks') }}
                </label>
                <input type="text" value="{{ $find_data->remarks }}" class="form-control calculator" readonly>

            </div>            
            <div class="col-md-3 form-group">
                <label for="fabric_gsm">Fabric GSM</label>
                <select class="form-control select2 advance-select-box" name="fabric_gsm" id="fabric_gsm" required>
                      <option value="0">Select</option>
                      @foreach($nonwovenfabrics as $data)
                      <option value="{{ $data->gsm }}">{{ $data->gsm }}</option>
                      @endforeach 
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label for="fabric_name">Fabric  Name</label>
                <select class="form-control select2 advance-select-box" name="fabric_name" id="fabric_name" required>
                   
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label for="fabric_color">Fabric Color</label>
                <select class="form-control select2 advance-select-box" name="fabric_color" id="fabric_color" required>
                   
                </select>
            </div>
            

        </div>
        
    </form>
</div>
<div class="row">
    <div class="table-responsive table-custom my-3">
        <table class="table table-hover table-striped" id="nonwovenList">
            <thead class="table-info">
                <tr>
                    <th>{{ __('Sr.No') }}</th>
                    <th>{{ __('Fabric Name') }}</th>
                    <th>{{ __('Roll No') }}</th>
                    <th>{{ __('GSM') }}</th>
                    <th>{{ __('Color') }}</th>
                    <th>{{ __('Length') }}</th>
                    <th>{{ __('Gross') }}</th>
                    <th>{{ __('Net Weight') }}</th>
                    <th>{{__('Send')}}</th>
                </tr>
            </thead>
            <tbody id="same-fabrics"></tbody>
        </table>
    </div>
</div>

<div class="row">
    <input type="hidden" name="saletripal_id" value="{{$id}}" id="saletripal_id">
    <div class="table-responsive table-custom my-3">
        <table class="table table-hover table-striped" id="getSaleNonwovenList">
            <thead class="table-info">
                <tr>
                    <th>{{ __('Sr.No') }}</th>
                    <th>{{ __('Fabric Name') }}</th>
                    <th>{{ __('Fabric Roll') }}</th>
                    <th>{{ __('Fabric GSM') }}</th>
                    <th>{{ __('Fabric Color') }}</th>
                    <th>{{ __('Length') }}</th>
                    <th>{{ __('Gross') }}</th>
                    <th>{{ __('Net Weight') }}</th>
                    <th>{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody id="getSaleNonwovenList"></tbody>
        </table>
        
    </div>
</div>

<div class="card-footer">
 
    <button type="submit" class="btn btn-info" id="finalUpdate">Update</button>
 

</div>


<!-- pagination start -->


@endsection
@section('extra-script')
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
<script src="{{ asset('js/storein.js') }}"></script>
<script src="{{ asset('js/storein.js') }}"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"> --}}

 <script>

      $("body").on("change","#fabric_gsm", function(event){
        // Pace.start();

        var fabric_gsm = $('#fabric_gsm').val(),
            token = $('meta[name="csrf-token"]').attr('content');
          
        $.ajax({
          type:"POST",
          dataType:"JSON",
          url:"{{route('getFabricNameList')}}",
          data:{
            _token: token,
            fabric_gsm: fabric_gsm
          },
          success: function(response){
            console.log(response);
            $('#fabric_name').html('');
            $('#fabric_name').append('<option value="">--Choose FabricName--</option>');
            $.each( response, function( i, val ) {

              $('#fabric_name').append(`<option value="${val.slug}">${val.name}</option>`);
              // $('#fabric_name').append('<option value='+val.slug+'>'+val.name+'</option>');
            });
            filterData();

          },
          error: function(event){
            alert("Sorry");
          }
        });
    });

   

      $("body").on("change","#fabric_name", function(event){
          // Pace.start();
          var fabric_name = $('#fabric_name').val(),
          fabric_gsm = $('#fabric_gsm').val(),
          token = $('meta[name="csrf-token"]').attr('content');
          
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
            filterData();

          },
          error: function(event){
              alert("Sorry");
          }
      });
      });

      $("body").on("change","#fabric_color", function(event){
            filterData();
      });

      let fabricTable = null;

     

      function filterData(){

        if (fabricTable !== null) {
            fabricTable.destroy();
        }

        var fabric_gsm = $('#fabric_gsm').val();
        var fabric_name = $('#fabric_name').val();
        var fabric_color = $('#fabric_color').val();
        // debugger;
        

        // let fabric_name_id = $(this).val();
        fabricTable = $("#nonwovenList").DataTable({
            serverside : true,
            processing : true,
            ajax : {
                url : "{{ route('getFilterGodamNonwoven') }}",
                method : "post",
                data : function(data){
                    data._token = $("meta[name='csrf-token']").attr("content"),
                    data.fabric_gsm = fabric_gsm
                    data.fabric_name = fabric_name
                    data.fabric_color = fabric_color
                }
            },
            columns:[
                { data : "DT_RowIndex" , name : "DT_RowIndex" },
                { data : "fabric_name" , name : "fabric_name" },
                { data : "fabric_roll" , name : "fabric_roll" },
                { data : "fabric_gsm" , name : "fabric_gsm" },
                { data : "fabric_color" , name : "fabric_color" },
                { data : "length" , name : "length" },
                { data : "gross_weight" , name : "gross_weight" },
                { data : "net_weight" , name : "net_weight" },
                { data : "action" , name : "action" },
            ]
        });
        
      }

     $(document).ready(function(){
         let fabricTable = null;
         let salesTripalTable = null;

          getTotal()
          getNonwovenSalesData()

          $("body").on("click","#finalUpdate", function(event){
              // Pace.start();
              // debugger;
              var nonwovensale_id = $('#nonwovensale_id').val(),
              token = $('meta[name="csrf-token"]').attr('content');
                
              $.ajax({
                type:"POST",
                dataType:"JSON",
                url:"{{route('nonwovensalefinal.storeList')}}",
                data:{
                  _token: token,
                  nonwovensale_id: nonwovensale_id,
                },
              success: function(response){
                  // location.reload();
                location.href = '{{route('nonwovenSale.index')}}';
                
              },
              error: function(event){
                  alert("Sorry");
              }
             });
          })

         function getTotal(){
            var bill_id = $('#nonwovensale_id').val();
             $.ajax({
                 url : "{{ route('getTripalSaleTotal') }}",
                 method : "get",
                 data : {
                     "bill_id" : bill_id
                 },
                 beforeSend:function(){
                     console.log("sending")
                 },
                 success:function(response){
                     $(".net_wt").val(response.net_wt)
                     $(".gross_wt").val(response.gross_wt)
                     $(".meter").val(response.meter)
                 },error:function(error){
                     console.log(error)
                 }
             })
         }

    

         $(document).on("click",".deleteTripalEntry",function(e){
             debugger;
             e.preventDefault()

             let data_id = $(this).attr('data-id')

           
             $.ajax({
                 url  : "{{ route('nonwovenSale.deleteFinalSaleEntry') }}",
                 method : "get",
                 data:{
                     "_token" : $("meta[name='csrf-token']").attr("content"),
                     "data_id" : data_id,
                  
                 },
                 beforeSend:function(){
                     console.log("sending")
                 },
                 success:function(response){
                    salesTripalTable.ajax.reload();
                 },
                 error:function(error){
                     console.log("error",error);
                 }
             })
         })


         function getNonwovenSalesData(){
             if (salesTripalTable !== null) {
                 salesTripalTable.destroy();
             } 
             // debugger;
              let nonwovensale_id = $('#nonwovensale_id').val();

             salesTripalTable = $("#getSaleNonwovenList").DataTable({
                 serverside : true,
                 processing : true,
                 lengthmenu : [
                         [5,10,25,50,100,250,500,-1],
                         [5,10,25,50,100,250,500,"All"]
                     ],
                 ajax : {
                     url : "{{ route('getSaleNonwovenList') }}",
                     method : "get",
                     data :{
                         "_token" : $("meta[name='csrf-token']").attr("content"),
                         'nonwovensale_id' : nonwovensale_id,
                     }
                 },
                 columns:[
                     { data : "DT_RowIndex" , name : "DT_RowIndex" },
                     { data : "fabric_name" , name : "fabric_name" },
                     { data : "fabric_roll" , name : "fabric_roll" },
                     { data : "fabric_gsm" , name : "fabric_gsm" },
                     { data : "fabric_color" , name : "fabric_color" },
                     { data : "net_weight" , name : "net_weight" },
                     { data : "gross_weight" , name : "gross_weight" },
                     { data : "length" , name : "length" },
                     { data : "action" , name : "action" },
                 ]
             });
         }

   
     });

   
     $(document).on("click",".sendforentry",function(e){
         e.preventDefault()

         let data_id = $(this).attr('data-id')
         let bill_no = $("#bill_no").val()
         let billDate = $("#bill_date").val()
         let nonwovensale_id = $("#nonwovensale_id").val()
       
         $.ajax({
             url  : "{{ route('finalnonwoven.storeEntryList') }}",
             method : "post",
             data:{
                 "_token" : $("meta[name='csrf-token']").attr("content"),
                 "data_id" : data_id,
                 "nonwovensale_id" : nonwovensale_id,
              
             },
             beforeSend:function(){
                 console.log("sending")
             },
             success:function(response){
                $('#getSaleNonwovenList').DataTable().ajax.reload();
             },
             error:function(error){
                 console.log("error",error);
             }
         })
     })

 </script>   

@endsection