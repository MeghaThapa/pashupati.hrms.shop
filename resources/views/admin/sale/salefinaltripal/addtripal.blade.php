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
    <form action="{{ route('salefinaltripal.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Invoice No') }}<span class="required-field">*</span>
                </label>
                <input type="text" id="bill_no" class="form-control" value="{{ $findtripal->bill_no }}"
                    readonly /> 
                <input type="hidden" name="salefinal_id" id="salefinal_id" value="{{$id}}">
            </div>

            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Invoice Date') }}
                </label>
                <input type="text" id="bill_date" value="{{ $findtripal->bill_date }}" class="form-control calculator" readonly>

            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('PartyName ') }}
                </label>
                <input type="text" value="{{ $findtripal->partyname_id }}" class="form-control calculator" readonly>

            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Bill For') }}
                </label>
                <input type="text" value="{{ $findtripal->bill_for }}" class="form-control calculator" readonly>

            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Lorrynumber') }}
                </label>
                <input type="text" value="{{ $findtripal->lorry_no }}" class="form-control calculator" readonly>

            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('GP number') }}
                </label>
                <input type="text" value="{{ $findtripal->gp_no }}" class="form-control calculator" readonly>

            </div>

            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('DO number') }}
                </label>
                <input type="text" value="{{ $findtripal->do_no }}" class="form-control calculator" readonly>

            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Remarks') }}
                </label>
                <input type="text" value="{{ $findtripal->remarks }}" class="form-control calculator" readonly>

            </div>            
            <div class="col-md-4 form-group">
                <label for="size" class="col-form-label">{{ __('Fabric Name') }}<span class="required-field">*</span>
                </label>
                
                <select class="advance-select-box form-control" id="finaltripalstock_id" name="finaltripalstock_id"
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
                <button type="submit" class="btn btn-sm btn-primary" style="margin-top:35px;">
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
    <input type="hidden" name="saletripal_id" value="{{$id}}" id="saletripal_id">
    <div class="table-responsive table-custom my-3">
        <table class="table table-hover table-striped" id="getSaleList">
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
                </tr>
            </thead>
            <tbody id="getSaleList"></tbody>
        </table>
        <tfoot>
            <tr>
                <td colspan="9">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <label for="">Net Weight</label>
                            <input type="text" class="form-control  net_wt" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="">Gross Weight</label>
                            <input type="text" class="form-control  gross_wt" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="">Meter</label>
                            <input type="text" class="form-control  meter" readonly>
                        </div>
                    </div>
                </td>
            </tr>
        </tfoot>
    </div>
</div>


<!-- /.card-body -->

<!-- pagination start -->
{{ $salefinaltripals->links() }}

@endsection
@section('extra-script')
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
<script src="{{ asset('js/storein.js') }}"></script>
<script src="{{ asset('js/storein.js') }}"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"> --}}

 <script>
     $(document).ready(function(){
         let fabricTable = null;
         /**************************** Ajax Calls **************************/
         // callunlaminatedfabricajax();
         // comparelamandunlam();

          getTotal()

         function getTotal(){
            var bill_id = $('#salefinal_id').val();
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
   

         $("#finaltripalstock_id").change(function(e){
             if (fabricTable !== null) {
                 fabricTable.destroy();
             }

             let fabric_name_id = $(this).val();
             fabricTable = $("#sameFabricsTable").DataTable({
                 serverside : true,
                 processing : true,
                 ajax : {
                     url : "{{ route('getfinaltripalFilter') }}",
                     method : "post",
                     data : function(data){
                         data._token = $("meta[name='csrf-token']").attr("content"),
                         data.fabric_name_id = fabric_name_id
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
         /**************************** Ajax Calls End **************************/
     });

     /**************************** Ajax functions **************************/

  

 




     /************************* Form Submission *************************/
     $(document).on("click",".send_to_lower",function(e){
         debugger;
         e.preventDefault()

         let data_id = $(this).attr('data-id')
         let bill_no = $("#bill_no").val()
         let billDate = $("#bill_date").val()
         let salefinal_id = $("#salefinal_id").val()

       
         $.ajax({
             url  : "{{ route('finalsaletripal.storeList') }}",
             method : "post",
             data:{
                 "_token" : $("meta[name='csrf-token']").attr("content"),
                 "data_id" : data_id,
                 "bill_no" : bill_no,
                 "bill_date" : billDate,
                 "salefinal_id" : salefinal_id,
              
             },
             beforeSend:function(){
                 console.log("sending")
             },
             success:function(response){
                location.reload();
                 emptytable();
                 // callunlaminatedfabricajax();
                 // emptyform();
             },
             error:function(error){
                 console.log("error",error);
             }
         })
     })

     $(document).ready(function(){
         $(document).on('submit','#createRawMaterial',function(e){
             e.preventDefault();
             let action = $(this).attr('action');
             let method = $(this).attr('method');
             let formData = $(this).serialize();
            $.ajax({
             url:action,
             method : method,
             data:{
                 '_token' : $('meta[name="csrf-token"]').attr('content'),
                 'data' : formData
             },
             beforeSend:function(){
             },
             success:function(response){
                 console.log(response);
                 if(response.status == "200"){
                     emptytable();
                     callunlaminatedfabricajax();
                     emptyform();
                 }else{
                     alert(response.message_error)
                 }
             },
             error:function(error){
             }
            });
         });
     })



 

     function emptytable(){
         $('#rawMaterialItemTbody').empty();
     }

 

     function filltable(data){
         console.log(data);
         data.response.forEach(d => {
             let title = d.fabric.name;
             let group = d.gram.split('-')[0];
             let result = parseFloat(title) * parseFloat(group);

             let tr = $("<tr></tr>").appendTo('#rawMaterialItemTbody');

             tr.append(`<td>#</td>`);
             tr.append(`<td>${d.fabric.name}</td>`);
             tr.append(`<td>${d.roll_no}</td>`);
             tr.append(`<td>${d.gross_wt}</td>`);
             tr.append(`<td>${d.net_wt}</td>`);
             tr.append(`<td>${d.meter}</td>`)
             tr.append(`<td>${d.fabric.average_wt}</td>`);
             tr.append(`<td>${d.gram}</td>`);
             tr.append(`<td><div class="btn-group"><a id="sendforlamination" data-group='${d.gram}' data-standard='${result}' data-title='${d.fabric.name}' href="${d.id}" data-id="${d.id}" class="btn btn-info">Send</a><a id="deletesendforlamination" class="btn btn-danger" data-id="${d.id}">delete</a></div></td>`);
         });
     }



 </script>   

 <script>
     $(document).ready(function(){
         let fabricTable = null;

         if (fabricTable !== null) {
             fabricTable.destroy();
         }

         let saletripal_id = $('#saletripal_id').val();
         // debugger;

         fabricTable = $("#getSaleList").DataTable({
             serverside : true,
             processing : true,
             ajax : {
                 url : "{{ route('getSaleTripalList') }}",
                 method : "post",
                 data : function(data){
                     data._token = $("meta[name='csrf-token']").attr("content"),
                     data.saletripal_id = saletripal_id
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
                 { data : "gram" , name : "gram" },
             ]
         });
       
     });


 </script>




@endsection