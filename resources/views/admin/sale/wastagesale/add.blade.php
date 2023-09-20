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

        
        
    </form>

    <div class="row">
        <div class="col-md-2 form-group">
            <label for="size" class="col-form-label">{{ __('Challan No') }}<span class="required-field">*</span>
            </label>
            <input type="text" id="bill_no" class="form-control" value="{{ $find_data->bill_no }}"
                readonly /> 
            <input type="hidden" name="bill_id" id="bill_id" value="{{$id}}">
        </div>

        <div class="col-md-2 form-group">
            <label for="size" class="col-form-label">{{ __('Date') }}
            </label>
            <input type="text" id="bill_date" value="{{ $find_data->bill_date }}" class="form-control calculator" readonly>

        </div>
        <div class="col-md-2 form-group">
            <label for="size" class="col-form-label">{{ __('PartyName ') }}
            </label>
            <input type="text" value="{{ $find_data->partyname_id }}" class="form-control calculator" readonly>

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
                    
        <div class="col-md-3 form-group">
            <label for="wastage">Wastage</label>
            <select class="form-control select2 advance-select-box" name="wastage" id="wastage" required>
                  <option value="0">Select</option>
                  @foreach($wastagestocks as $data)
                  <option value="{{ $data->id }}">{{ $data->wastage->name }}</option>
                  @endforeach 
            </select>
        </div>
        
        <div class="col-md-3 form-group">
            <label for="quantity">Quantity</label>
            <input type="text" class="form-control calculator" id="quantity">
        </div>

        <div class="col-md-3 form-group">
            <label for="quantity"> Available Quantity</label>
            <input type="text" class="form-control calculator" id="available_quantity" readonly>
        </div>

        <div class="col-md-3 form-group pt-4">
            <button class="btn btn-info" id="submitEntry">ADD</button>
            {{-- <button id="submitEntry">Submit</button> --}}
        </div>
        

    </div>
</div>
<div class="row">
    <div class="table-responsive table-custom my-3">
        <table class="table table-hover table-striped" id="nonwovenList">
            <thead class="table-info">
                <tr>
                    <th>{{ __('Sr.No') }}</th>
                    <th>{{ __('Waste') }}</th>
                    <th>{{ __('Quantity') }}</th>
                    <th>{{__('Action')}}</th>
                </tr>
            </thead>
            <tbody id="same-fabrics"></tbody>
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
    $("body").on("change","#wastage", function(event){
        // Pace.start();
        debugger;
        var wastage = $('#wastage').val(),
        token = $('meta[name="csrf-token"]').attr('content');
          
        $.ajax({
          type:"GET",
          dataType:"JSON",
          url:"{{route('wastageSale.getQuantity')}}",
          data:{
            _token: token,
            wastage: wastage,
          },
        success: function(response){
            console.log(response);
           $('#available_quantity').val(response.quantity);
          // location.href = '{{route('wastageSale.index')}}';
          
        },
        error: function(event){
            alert("Sorry");
        }
       });
    })

    $("body").on("keypress","#quantity", function(event){
        // Pace.start();
        // debugger;
        var quantity = $('#quantity').val(),
        token = $('meta[name="csrf-token"]').attr('content');
        var total = $('#available_quantity').val();
        // debugger;

        if(quantity > total){
            $( "#finalUpdate" ).prop( "disabled", true );
            // alert(' quantity exceeded');
        }else{
            // alert('m');
        }
          
       //  $.ajax({
       //    type:"GET",
       //    dataType:"JSON",
       //    url:"{{route('wastageSale.getQuantity')}}",
       //    data:{
       //      _token: token,
       //      wastage: wastage,
       //    },
       //  // success: function(response){
       //  //     console.log(response);
       //  //    $('#available_quantity').val(response.quantity);
       //  //   // location.href = '{{route('wastageSale.index')}}';
          
       //  // },
       //  error: function(event){
       //      alert("Sorry");
       //  }
       // });
    })

    $(document).ready(function(){
        let fabricTable = null;
        let salesTripalTable = null;

         getTotal()
         filterData()

         $("body").on("click","#finalUpdate", function(event){
             // Pace.start();
             // debugger;
             var bill_id = $('#bill_id').val(),
             token = $('meta[name="csrf-token"]').attr('content');
               
             $.ajax({
               type:"POST",
               dataType:"JSON",
               url:"{{route('wastageSale.storeFinalEntry')}}",
               data:{
                 _token: token,
                 bill_id: bill_id,
               },
             success: function(response){
               location.href = '{{route('wastageSale.index')}}';
               
             },
             error: function(event){
                 alert("Sorry");
             }
            });
         })

        

        function getTotal(){
           var bill_id = $('#wastagesale_id').val();
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


        function filterData(){

          if (fabricTable !== null) {
              fabricTable.destroy();
          }

          var bill_id = $('#bill_id').val();
          

          fabricTable = $("#nonwovenList").DataTable({
              serverside : true,
              processing : true,
              ajax : {
                  url : "{{ route('getFilterWastageList') }}",
                  method : "post",
                  data : function(data){
                      data._token = $("meta[name='csrf-token']").attr("content"),
                      data.bill_id = bill_id
                  }
              },
              columns:[
                  { data : "DT_RowIndex" , name : "DT_RowIndex" },
                  { data : "waste" , name : "waste" },
                  { data : "quantity" , name : "quantity" },
                  { data : "action" , name : "action" },
              ]
          });
          
        }

    

        $(document).on("click",".deleteEntry",function(e){
            e.preventDefault()

            let data_id = $(this).attr('data-id')

          
            $.ajax({
                url  : "{{ route('wastageSale.deleteWastageEntry') }}",
                method : "get",
                data:{
                    "_token" : $("meta[name='csrf-token']").attr("content"),
                    "data_id" : data_id,
                 
                },
                beforeSend:function(){
                    console.log("sending")
                },
                success:function(response){
                   fabricTable.ajax.reload();
                },
                error:function(error){
                    console.log("error",error);
                }
            })
        })

          $("body").on("click","#submitEntry", function(event){

            var wastage = $('#wastage').val(),
                quantity = $('#quantity').val(),
                available_quantity = $('#available_quantity').val(),
                bill_id = $('#bill_id').val(),
                token = $('meta[name="csrf-token"]').attr('content');
              
            $.ajax({
              type:"POST",
              dataType:"JSON",
              url:"{{route('wastageSale.storeEntry')}}",
              data:{
                _token: token,
                wastage: wastage,
                quantity: quantity,
                available_quantity: available_quantity,
                bill_id: bill_id
              },
              success: function(response){
                fabricTable.ajax.reload();
                alert(response.message);

              },
              error: function(event){
                alert("Sorry");
              }
            });
        });



    
    });

   
     $(document).on("click",".sendforentry",function(e){
         e.preventDefault()

         let data_id = $(this).attr('data-id')
         let wastagesale_id = $("#wastagesale_id").val()
       
         $.ajax({
             url  : "{{ route('wastageSale.storeFinalEntry') }}",
             method : "post",
             data:{
                 "_token" : $("meta[name='csrf-token']").attr("content"),
                 "data_id" : data_id,
                 "wastagesale_id" : wastagesale_id,
              
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