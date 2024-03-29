@extends('layouts.admin')

@section('extra-style')
<link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
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
    <form action="{{ route('openingnonwoven.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Date') }}<span class="required-field">*</span>
                </label>
                <input type="text" class="form-control" id="receive_date" value="{{ date('Y-m-d') }}"  name="receive_date" required/> {{-- value="FSR-{{ getNepalidate(date('Y-m-d')).'-'.rand(0,9999)}}" --}}
            </div>

            <div class="col-md-2 form-group">
                <label for="receive_no">Receipt Number</label>
                <input type="text" value="Opening" id="receipt_number_1_repeat"
                    name="receive_no" class="form-control" readonly required />
            </div>

            
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('To Godam') }}
                </label>
                <select class="advance-select-box form-control" id="toGodam" name="to_godam_id" required>
                    <option value="" selected disabled>{{ __('Select Godam Name') }}</option>
                   @foreach ($godams as $data)
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
            
            
            
            {{-- <div>
                <button type="submit" class="btn btn-sm btn-primary" style="margin-top:35px;">
                    Add
                </button>
            </div> --}}

        </div>
        <div class="row">

            <div class="col-md-3 form-group">
                <label for="fabric_roll">Fabric  Roll</label>
                <input type="text" class="form-control" name="fabric_roll" id="fabric_roll">
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

            <div class="col-md-3 form-group">
                <label for="fabric_length">Length</label>
                <input type="text" class="form-control" name="fabric_length" id="fabric_length">
            </div>
            <div class="col-md-3 form-group">
                <label for="gross_weight">Gross weight</label>
                <input type="text" class="form-control" name="gross_weight" id="gross_weight">
            </div>
            <div class="col-md-3 form-group">
                <label for="net_weight">Net weight</label>
                <input type="text" class="form-control" name="net_weight" id="net_weight">
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
            <table class="table" id="rawMaterialItemTable">
                <thead>
                    <tr>
                        <th>{{ __('Sr.No') }}</th>
                        <th>{{ __('Roll') }}</th>
                        <th>{{ __('Gsm') }}</th>
                        <th>{{ __('ItemName') }}</th>
                        <th>{{ __('Color') }}</th>
                        <th>{{ __('Length') }}</th>
                        <th>{{ __('Gross Weight') }}</th>
                        <th>{{ __('Net Weight') }}</th>
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
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
<script src="{{ asset('js/storein.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>



<script>
    $(document).ready(function(){
        /**************************** Ajax Calls **************************/
        callunlaminatedfabricajax();


        $("body").on("change","#fabric_gsm", function(event){
          // Pace.start();
          var fabric_gsm = $('#fabric_gsm').val(),
              token = $('meta[name="csrf-token"]').attr('content');
            // $('#idcardShift').val(godam_id);
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
            console.log(fabric_name);
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

        $("#shiftName").change(function(e){
            let department_id =  $(this).val();
            let geturl = "{{ route('fabricSendReceive.get.fabrics') }}";
            $.ajax({
                url:geturl.replace(':id',department_id),
                beforeSend:function(){
                    console.log('Getting Fabrics');
                },
                success:function(response){
                    getfabrics(response);
                },
                error:function(error){
                    console.log(error);
                }
            });
        });
        /**************************** Ajax Calls End **************************/
    });

    /**************************** Ajax functions **************************/

    function callunlaminatedfabricajax(){
        $.ajax({
            url : "{{ route('nonwovenfabric.getOpeningNonwovenData') }}",
            method: 'get',
            beforeSend:function(){
                console.log('getting unlaminated fabric');
            },
            success:function(response){
                emptytable();
                // console.log(response);
                if(response.response != '404'){
                    filltable(response);
                }else{
                    console.log(response.response);
                }
                
            },error:function(error){
                console.log(error);
            }
        });
    }

    function addplanttype(data){
        $("#plantType").empty();
        $('#plantType').append(`<option value="" disabled selected>Select Planttype</option>`);
        data.planttype.forEach( d => {
            $('#plantType').append(`<option value="${d.id}">${d.name}</option>`);
        });
    }

    function addplantname(data){
        console.log(data);
        $("#plantName").empty();
        $('#plantName').append(`<option value="" disabled selected>Select Plantname</option>`);
        data.plantname.forEach( d => {
            // if(d.name == '')
            $('#plantName').append(`<option value="${d.id}">${d.name}</option>`);
        });
    }

    function getfabrics(data){
        $('#fabricNameId').prop('disabled',false);
        $("#fabricNameId").empty();
        $('#fabricNameId').append(`<option value="" disabled selected>Select Fabric</option>`);
        data.fabrics.forEach(d => {
            $("#fabricNameId").append(`<option value="${d.id}">${d.name}</option>`);
        });
    }
    /**************************** Ajax functions **************************/

    /************************* Form Submission *************************/
    $(document).ready(function(){
        $(document).on('submit','#createRawMaterial',function(e){
            e.preventDefault();
            let action = $(this).attr('action');
            let method = $(this).attr('method');
            let formData = $(this).serialize();
            debugger;
           $.ajax({
            url:action,
            method : method,
            data:{
                '_token' : $('meta[name="csrf-token"]').attr('content'),
                'data' : formData
            },
            beforeSend:function(){
                console.log('sending form');
            },
            success:function(response){
                emptytable();
                callunlaminatedfabricajax();
                emptyform();
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
            'action' : "{{ route('nonwovenfabrics-receiveentry.store') }}",
            'method' : "post"
        });
        $("#rollnumberfabric").prop('disabled',false);
    });

    // $("#fabricNameId").change(function(e){
    //     getfabricsrelated_enable();
    //     $("#rollnumberfabric").prop('required',false);
    //     $("#rollnumberfabric").prop('disabled',true);
    // });

    // $("#rollnumberfabric").keyup(function(e){
    //     getfabricsrelated_enable();
    //     $("#fabricNameId").prop('disabled',true);
    //     $("#fabricNameId").prop('required',false);
    // });

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
        // console.log(data);
        data.response.forEach(d => {
            console.log(d);
            let title = d.nonfabric_id;
            let group = d.gram;
            // let group = d.gram.split('-')[0];
            let result = d.gram;
            // let result = parseFloat(title) * parseFloat(group);

            let tr = $("<tr></tr>").appendTo('#rawMaterialItemTbody');

            tr.append(`<td>${d.id}</td>`);
            tr.append(`<td>${d.fabric_roll}</td>`);
            tr.append(`<td>${d.fabric_gsm}</td>`);
            tr.append(`<td>${d.fabric_name}</td>`);
            tr.append(`<td>${d.fabric_color}</td>`);
            tr.append(`<td>${d.length}</td>`);
            tr.append(`<td>${d.gross_weight}</td>`);
            tr.append(`<td>${d.net_weight}</td>`);
            // tr.append(`<td><div class="btn-group"><a id="deletesendforlamination" class="btn btn-danger" data-id="${d.id}">delete</a></div></td>`);
        });
    }

    function emptyform(){
        $("#createRawMaterial")[0].reset();
    }

    $(document).ready(function(){
        $(document).on('click',"#sendforlamination",function(e){
            e.preventDefault();
            $('#staticBackdrop1').modal('show');
            let titleold = $('#staticBackdropLabel').text('');
            let title = $(this).attr('data-title');
            let id = $(this).attr('data-id');
            $("#laminated_fabric_name").val(title+"(Lam)");
            let laminated_fabric_group = $(this).attr('data-group');
            $("#laminated_fabric_group").val(laminated_fabric_group);
            let standard_weight_gram = $(this).attr('data-standard');
            $("#standard_weight_gram").val(standard_weight_gram);
            $('#staticBackdropLabel').text(title+" -> id = "+id);
            $("#idoffabricforsendtolamination").val(id);
                   });
    });
   
        $('#staticBackdrop1').on('hidden.bs.modal',function(e) {
        $(this).removeAttr('action');
    });
    /************************* Send for lamination **************************/
</script>
@endsection 