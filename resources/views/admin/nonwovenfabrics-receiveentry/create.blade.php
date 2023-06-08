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

    label {
        font-weight: lighter;
        color: rgba(0, 0, 0, 0.8);
    }

    /* .select2-selection {
        width:150px !important;
    } */
</style>
@endsection

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header mb-4">
    <div class="row align-items-center">
        <div class="col-sm-6 mt-2">
            <h4><strong>NonWoven Fabric Received Entry</strong></h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item active">{{ __('Tape Entry') }}</li>
            </ol>
        </div>
    </div>
</div>
<!-- /.content-header -->
<div class="content">
    <div class="container-fluid">
        {{-- <div class="card"> --}}

            {{-- <div class="card-body"> --}}
           
                    {{-- @foreach($tapeentries as $data) --}}
                    <div class='row mt-2'>
                        <div class="col-md-2">
                            <label for="bill_date"> Date</label>
                            <input type="date" value="{{ date('Y-m-d') }}" step="any" min="0" class="form-control calculator" id="billDate" data-number="1"
                                name="bill_date" placeholder="{{ __('Remarks') }}" min="1" required>
                        </div>
                        <div class="col-md-2">
                            <label for="receipt_no">Receipt Number</label>
                            <input type="text" value="{{$receipt_no}}" id="receipt_number_1_repeat"
                                name="receipt_number" class="form-control" readonly required />
                        </div>
                        <div class="col-md-3">
                            <label for="receipt_no">To Godam</label>
                            <select class="form-control select2 advance-select-box" name="togodam" id="godam_data"
                                required> {{-- select2 advance-select-box --}}
                                <option>Select Godam/Department</option>
                                
                                @foreach($departments as $data)
                                    <option value="{{ $data->id }}">{{ $data->department }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="receipt_no">Plant Type</label>
                            <select class="form-control select2 advance-select-box" name="planttype" id="planttype_data"
                                required> {{-- advance-select-box --}}
                                {{-- @foreach($planttype as $data)
                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                @endforeach --}}
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="receipt_no">Plant Name</label>
                            <select class="form-control select2 advance-select-box" name="plantname" id="plantname_data"
                                required> {{-- advance-select-box --}}
                                {{-- @foreach($plantname as $data)
                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                @endforeach --}}
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="receipt_no">Plant Shift</label>
                            <select class="form-control select2 advance-select-box" name="shift" id="shift" required>
                                  @foreach($shifts as $data)
                                  <option value="{{ $data->id }}">{{ $data->name }}</option>
                                  @endforeach 
                              </select>
                            </select>
                        </div>
                    </div>
                    {{-- @endforeach --}}
                    <hr>
                    

                    <div class='row mt-2'>
                        
                        <div class="col-md-2">
                            <label for="fabric_roll">Fabric  Roll</label>
                            <input type="text" class="form-control" name="fabric_roll" id="fabric_roll">
                        </div>
                        <div class="col-md-2">
                            <label for="fabric_gsm">Fabric GSM</label>
                            <select class="form-control select2 advance-select-box" name="fabric_gsm" id="fabric_gsm" required>
                                  @foreach($nonwovenfabrics as $data)
                                  <option value="0">-</option>
                                  <option value="{{ $data->id }}">{{ $data->gsm }}</option>
                                  @endforeach 
                              </select>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="fabric_name">Fabric  Name</label>
                            <input type="text" class="form-control" name="fabric_name" id="fabric_name">
                        </div>
                        <div class="col-md-2">
                            <label for="fabric_color">Fabric Color</label>
                            <input type="text" class="form-control" name="fabric_color" id="fabric_color">
                        </div>

                        <div class="col-md-2">
                            <label for="fabric_length">Length</label>
                            <input type="number" class="form-control" name="fabric_length" id="fabric_length">
                        </div>
                        <div class="col-md-2">
                            <label for="gross_weight">Gross weight</label>
                            <input type="number" class="form-control" name="gross_weight" id="gross_weight">
                        </div>
                        <div class="col-md-2">
                            <label for="net_weight">Net weight</label>
                            <input type="number" class="form-control" name="net_weight" id="net_weight">
                        </div>

                    </div>


                    <button type="button" class="mt-4 btn btn-primary add_item">Add Item</button>
                    
                    
            </div>
            <hr>
            <div class='row mt-3'>
                
                <div class="col-md-12">
                    <div class="card">
                        <form role="form" method="POST" action="{{route('nonwovenfabrics-receiveentry.store')}}" class="validate" id="validate">
                          <div class="card-body">
                            @csrf
                            <div class="row">
                             <table class="table table-bordered" id="tape_entry_dana_table"
                                 style="background:rgba(241, 214, 147,0.2)">
                                 <thead class="table-warning">
                                     <tr>
                                         <th style="width:100px">Sr No</th>
                                         <th>Roll</th>
                                         <th>GSM</th>
                                         <th>FabricName</th>
                                         <th>Color</th>
                                         <th>Length</th>
                                         <th>Gross Weight</th>
                                         <th>Net Weight</th>
                                     </tr>
                                 </thead>
                                 <tbody id="bill_ppend_list"></tbody>
                                 
                             </table>
                            </div>
                          </div>
                           <button type="submit" class="btn btn-info text-capitalize" id="submit" data-toggle="tooltip" data-placement="top" title="Save Bill">Save Bill</button>
                          
                        </form>
                        
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-md-6">
                    <div class="card card-body mt-2">
                        <div class="row p-2">
                            <div class="col-md-6">
                                <label for="size" class="col-form-label">{{ __('Dana:') }}<span class="required-field">*</span>
                                </label>
                                <select class="advance-select-box form-control" id="dana" name="dana" required>
                                    <option value="" selected disabled>{{ __('Select Plant Name') }}</option>
                                    <option value="1">hi</option>
                                    {{-- @foreach ($danaNames as $danaName)
                                    <option value="{{ $danaName->id }}">{{ $danaName->name }}
                                    </option>
                                    @endforeach --}}
                                </select>
                                @error('dana')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="size" class="col-form-label">{{ __('Qty:') }}<span class="required-field">*</span>
                                </label>
                                <input type="text" step="any" min="0" class="form-control calculator" id="quantity"
                                    data-number="1" name="quantity" min="1" required>
                                @error('quantity')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary add_more">
                                    Add
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    
                    <div class="col-md-12 form-group">
                        <div>
                            <label for="size" class="col-form-label">{{ __('Filter:') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator" id="filter"
                                data-number="1" name="filter" min="1" required>
                            @error('filter')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="size" class="col-form-label">{{ __('Filament:') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator" id="filament"
                                data-number="1" name="filament" min="1" required>
                            @error('filament')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div>
                            <label for="size" class="col-form-label">{{ __('Roal coast:') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator" id="roalcoast"
                                data-number="1" name="roalcoast" min="1" required>
                            @error('roalcoast')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div>
                            <label for="size" class="col-form-label">{{ __('Strip:') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator" id="strip"
                                data-number="1" name="strip" min="1" required>
                            @error('strip')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div>
                            <label for="size" class="col-form-label">{{ __('Dana Quantity:') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator" id="dana_quanity"
                                data-number="1" name="dana_quanity" min="1" required>
                            @error('dana_quanity')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div>
                            <label for="size" class="col-form-label">{{ __('Wastage:') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator" id="wastage"
                                data-number="1" name="wastage" min="1" required>
                            @error('wastage')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div>
                            <label for="size" class="col-form-label">{{ __('Net Weight:') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator" id="total_netweight"
                                data-number="1" name="netweight" min="1"  required>
                            @error('netweight')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        

                    </div>
                    
                </div>
                <div class="col-md-6" style="height: 100%;">
                    <div class="row">
                        
                     <form role="form" method="POST" action="{{route('nonwovenfabrics-receiveentry.store')}}" class="validate" id="validate">
                       <div class="card-body">
                         @csrf
                         <div class="row">
                          <table class="table table-bordered" id="tape_entry_dana_table"
                              style="background:rgba(241, 214, 147,0.2)">
                              <thead class="table-warning">
                                  <tr>
                                      <th style="width:100px">Sr No</th>
                                      <th>Dana</th>
                                      <th>Quantity</th>
                                  </tr>
                              </thead>
                              <tbody id="dana_list"></tbody>
                              
                          </table>
                         </div>
                       </div>
                        <button type="submit" class="btn btn-info text-capitalize" id="submit" data-toggle="tooltip" data-placement="top" title="Save Bill">Save Bill</button>
                       
                     </form>
                    </div>


                </div>
                
            </div>
            <hr>
            
            <hr>
            {{--
        </div>--}}
        <!-- card-body -->
        {{--
    </div>--}}
    <!-- card -->
</div>
</div>
@push('scripts')
<script type="text/javascript">
  $('.add_more').click(function(event){
    var dana = $("#dana").val(),
        quantity = $("#quantity").val();
    debugger;
    var  token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"html",
      url:"{{route('getDanaList')}}",
      data:{
        _token:token,
        dana: dana,
        quantity: quantity,
      },
      success: function(response){
        $('#dana_list').append(response);
        $('table').on('click','#cross',function(e){
          e.preventDefault();
          $(this).closest('tr').remove();
        });
        $("#dana_quanity").val(quantity);

        

        // $("#submit").addClass('d-none');
        // $("#calculate").removeClass('d-none');
        // $('#fee,#discount-tr,#fine-tr,#net-total-tr').remove();
      },
      error:function(event){
        alert('Error');
        return false;
      }
    })
  })
</script>

<script>
    $('#filter').keyup(function(event){
      event.preventDefault();
      debugger;
      if($(this).val() != '') {
        var filter = $("#filter").val();
        var filament = $("#filament").val();
        var roalcoast = $("#roalcoast").val();
        var strip = $("#strip").val();
        // var nettotal = total_fee - amount_received - parseInt(discount) + parseInt(fine);

        $("#wastage").val(filter);
       
      }
    });

    $('#filament').keyup(function(event){
      event.preventDefault();
      debugger;
      if($(this).val() != '') {
        var filter = $("#filter").val();
        var filament = $("#filament").val();
        var roalcoast = $("#roalcoast").val();
        var strip = $("#strip").val();
        var nettotal = parseInt(filter) + parseInt(filament);

        $("#wastage").val(nettotal);
       
      }
    });

    $('#roalcoast').keyup(function(event){
      event.preventDefault();
      debugger;
      if($(this).val() != '') {
        var filter = $("#filter").val();
        var filament = $("#filament").val();
        var roalcoast = $("#roalcoast").val();
        var strip = $("#strip").val();
        var nettotal = parseInt(filter) + parseInt(filament) + parseInt(roalcoast);

        $("#wastage").val(nettotal);
       
      }
    });

    $('#strip').keyup(function(event){
      event.preventDefault();
      debugger;
      if($(this).val() != '') {
        var filter = $("#filter").val();
        var filament = $("#filament").val();
        var roalcoast = $("#roalcoast").val();
        var strip = $("#strip").val();
        var nettotal = parseInt(filter) + parseInt(filament) + parseInt(roalcoast);

        $("#wastage").val(nettotal);
       
      }
    });
</script>
<script type="text/javascript">
  $('.add_item').click(function(event){
    var receive_date = $("#tape_receive_date").val(),
        receipt_number = $("#receipt_number_1_repeat").val(),
        godam_data = $("#godam_data").val(),
        planttype_data = $("#planttype_data").val(),
        plantname_data = $("#plantname_data").val(),
        shift = $("#shift").val(),
        fabric_roll = $("#fabric_roll").val(),
        fabric_gsm = $("#fabric_gsm").val(),
        fabric_name = $("#fabric_name").val(),
        fabric_color = $("#fabric_color").val(),
        fabric_length = $("#fabric_length").val(),
        gross_weight = $("#gross_weight").val(),
        net_weight = $("#net_weight").val();
    // debugger;
    var  token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"html",
      url:"{{route('getDataList')}}",
      data:{
        _token:token,
        receive_date: receive_date,
        receipt_number: receipt_number,
        godam_data: godam_data,
        planttype_data: planttype_data,
        plantname_data: plantname_data,
        shift: shift,
        fabric_roll: fabric_roll,
        fabric_gsm: fabric_gsm,
        fabric_name: fabric_name,
        fabric_color: fabric_color,
        fabric_length: fabric_length,
        gross_weight: gross_weight,
        net_weight: net_weight,
      },
      success: function(response){
        console.log(response);
        $('#bill_ppend_list').append(response);
        $('table').on('click','#cross',function(e){
          e.preventDefault();
          $(this).closest('tr').remove();
        });

        $("#total_netweight").val();
        // $("#submit").addClass('d-none');
        // $("#calculate").removeClass('d-none');
        // $('#fee,#discount-tr,#fine-tr,#net-total-tr').remove();
      },
      error:function(event){
        alert('Error');
        return false;
      }
    })
  })
</script>
<script type="text/javascript">
  $("body").on("change","#godam_data", function(event){
    // Pace.start();
    var godam_id = $('#godam_data').val(),
        token = $('meta[name="csrf-token"]').attr('content');
      // $('#idcardShift').val(godam_id);
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('getPlantTypeList')}}",
      data:{
        _token: token,
        godam_id: godam_id
      },
      success: function(response){
        console.log(response);
        $('#planttype_data').html('');
        $('#planttype_data').append('<option value="">--Choose PlantType--</option>');
        $.each( response, function( i, val ) {
          $('#planttype_data').append('<option value='+val.id+'>'+val.name+'</option>');
        });
      },
      error: function(event){
        alert("Sorry");
      }
    });
        // Pace.stop();
  });

  $("body").on("change","#planttype_data", function(event){
    // Pace.start();
    var planttype_id = $('#planttype_data').val(),
        token = $('meta[name="csrf-token"]').attr('content');
      // $('#idcardShift').val(godam_id);
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('getPlantNameList')}}",
      data:{
        _token: token,
        planttype_id: planttype_id
      },
      success: function(response){
        console.log(response);
        $('#plantname_data').html('');
        $('#plantname_data').append('<option value="">--Choose PlantName--</option>');
        $.each( response, function( i, val ) {
          $('#plantname_data').append('<option value='+val.id+'>'+val.name+'</option>');
        });
      },
      error: function(event){
        alert("Sorry");
      }
    });
        // Pace.stop();
  });

  // $("body").on("change","#fabric_gsm", function(event){
  //   // Pace.start();
  //   var fabric_gsm_id = $('#fabric_gsm').val(),
  //       token = $('meta[name="csrf-token"]').attr('content');
  //     // $('#idcardShift').val(godam_id);
  //   $.ajax({
  //     type:"POST",
  //     dataType:"JSON",
  //     url:"{{route('getPlantNameList')}}",
  //     data:{
  //       _token: token,
  //       fabric_gsm_id: fabric_gsm_id
  //     },
  //     success: function(response){
  //       console.log(response);
  //       $('#fabric_gsm').val();
        
  //     },
  //     error: function(event){
  //       alert("Sorry");
  //     }
  //   });
  //       // Pace.stop();
  // });
</script>
<script>
    $(document).ready(function(){
            // let receipt_number = $('#receipt_number_1').val();
            // let receipt_number_repeat = $('#receipt_number_1_repeat').val(receipt_number);
        });
</script>
<script>
    $(document).ready(function(){
            $(document).on('change click','#togodam',function(){
                // $("#dana_in_kg").val('');
                getgodam();
                emptystuffs();
                revertdisables();
            });

            /***************** AJAX *******************************/
            //for plant name
            $(document).on("change click",'#planttype',function(e){ 
                // $("#planttype").on("select2:select",function(e){ 
                $.ajax({ 
                    url:"{{ route('tape.entry.ajax.plantname',['planttype_id'=>':id']) }}".replace(':id',e.target.value),
                    beforeSend:function(){
                        console.log("Getting Plant Name");
                    },
                    success:function(response){
                        // console.log(response);
                        plantname(response);
                    },
                    error:function(error){
                        console.log(error);
                    }
                });
            });
            
            $(document).on('change click','#plantname', function(e){
                $.ajax({
                    url : "{{ route('tape.entry.ajax.shift',['plantname_id'=>':id']) }}".replace(':id',e.target.value),
                    beforeSend:function(){
                        console.log("Getting Plant Name");
                    },
                    success:function(response){
                        console.log(response);
                        // plantname(response);
                        $('#shift').empty();
                        shift(response);
                    },
                    error:function(error){
                        console.log(error);
                    }
                });
            });


            $(document).on("change click",'#shift',function(e){
                $("#dana").empty();
                let department = $('#togodam').val();
                let planttype = $('#planttype').val();
                let plantname = $('#plantname').val();
                let shift = $('#shift').val();
                let csrftoken = $('meta[name="csrf-token"]').attr('content');
                console.log(department,planttype,plantname,shift);
                $.ajax({
                    url:"{{ route('tape.entry.ajax.get.danainfo') }}",
                    method : 'POST',
                    data:{
                        '_token' : csrftoken,
                        'shift' : shift,
                        'department' : department,
                        'plantname' : plantname,
                        'planttype' : planttype
                    },
                    beforeSend:function(){
                        console.log('getting dana info');
                    },
                    success:function(response){
                        console.log(response);
                        danaandquantity(response);
                        disabledstuffs();
                    },
                    error:function(error){
                        console.log(error);
                    }
                });
            });
            
            /***************** AJAX *******************************/

            /********************* ajax functions *********************/
            //for godam and planttype
            function getgodam(){
                let geturl = "{{ route('tape.entry.ajax.planttype',['department_id'=>':department_id']) }}";
                finalurl = geturl.replace(':department_id',$('#togodam').val());
                $.ajax({
                    url:finalurl,
                    method:"GET",
                    beforeSend:function(){
                        console.log("ajax fired");
                    },
                    success:function(response){
                        planttype(response);
                    },
                    error:function(error){
                        console.log(error);
                    }
                });
            }

            //planttype
            function planttype(response){
                // console.log(response);
                $('#planttype').empty();
                    $('<option></option>').text("Select Plant Type").appendTo('#planttype');
                response.planttype.forEach(data => {
                    if(data.name == "Tape Plant"){
                        $('<option></option>').attr('value', `${data.id}`).text(`${data.name}`).appendTo('#planttype');
                    }
                });
            }
            
            //for plant name
            function plantname(data){
                $('#plantname').empty();
                $('<option></option>').text("Select Plant Name").appendTo('#plantname');
                data.plantname.forEach(resp => {
                    $('<option></option>').attr('value', `${resp.id}`).text(`${resp.name}`).appendTo('#plantname');
                });
            }
            
            //for shift
            function shift(data){
                console.log(data);
                $('<option></option>').text("Select Type").appendTo('#shift');
                data.shift.forEach(d => {
                    $('<option></option>').attr('value', `${d.id}`).text(`${d.name}`).appendTo('#shift');
                });
            }

            //for dana and quantity
            function danaandquantity(response){
                let tbody = $('<tbody></tbody>').appendTo('#tape_entry_dana_table');
                tbody.attr({
                    'id':'tape_dana_tbody',
                })
                let a = 1;
                response.data.forEach(d=>{
                    let tr = $('<tr></tr>').appendTo(tbody);
                    $('<option></option>').attr('value', `${d.id}`).text(`${d.dana_name.name}`).appendTo('#dana');
                    $('<td></td>').text(a++).appendTo(tr);
                    $('<td></td>').text(`${d.dana_name.name}`).appendTo(tr);
                    $('<td></td>').text(`${d.quantity}`).appendTo(tr);
                });
                $('#dana_in_kg').attr({
                    'value': response.total_quantity
                })
            }

            /***************** Additional Functionalities ****************************/
            function emptystuffs(){
                $('#plantname').empty();
                $('#planttype').empty();
                $('#shift').empty();
                $('#tape_dana_tbody').empty();
                $('#dana').empty();
                $('#dana_in_kg').attr({
                    'value': null
                })
            }

            function disabledstuffs(){
                $('#total_in_kg').prop('disabled',false);
                $('#btn-update').prop('disabled',false);
                $('#dana_in_kg').prop('disabled',false);
                $("#btn-view").prop('disabled',false);
                $("#add_new_dana_to_autoloader_stock").prop('disabled',false);
                
            }

            function revertdisables(){
                $('#total_in_kg').prop('disabled',true);
                $('#loading').prop('disabled',true);
                $('#running').prop('disabled',true);
                $('#bypass_wast').prop('disabled',true);
                $('#dana_in_kg').prop('disabled',true);
                $("#btn-view").prop('disabled',true);
                $("#btn-update").prop('disabled',true);
                $("#add_new_dana_to_autoloader_stock").prop('disabled',true);
            }

            $(document).on("keyup","#total_in_kg",function(e){
                 $('#loading').prop('disabled',false);
            });
            $(document).on("keyup","#loading",function(e){
                 $('#running').prop('disabled',false);
            });
            $(document).on("keyup","#running",function(e){
                 $('#bypass_wast').prop('disabled',false);
            });
            /***************** Additional Functionalities End ****************************/

            /********************* ajax functions *********************/

            $("#add_new_dana_to_autoloader_stock").click(function(e){
                e.preventDefault();
            });
            $('#bypass_wast').keyup(function(e){
                $('#tape_qty_in_kg').prop('disabled',false);
                $('#tapetype').prop('disabled',false);
                let total = parseFloat($('#total_in_kg').val());
                let loading = parseFloat($('#loading').val());
                let running = parseFloat($('#running').val());
                let bypass = parseFloat($('#bypass_wast').val());
                let tapetypeval = total - loading - running - bypass;
                $("#tape_qty_in_kg").val(tapetypeval);

                let tape_qty_in_kg = $("#tape_qty_in_kg").val();
                let dana_in_kg = $("#dana_in_kg").val()
                if(tape_qty_in_kg != dana_in_kg){
                    $("#wastetype").prop('disabled',false);
                }else if(tape_qty_in_kg == dana_in_kg){
                    $("#wastetype").prop('disabled',true);
                }
            });

            /*********************** form condition checks  **********************/
            $('#tape_entry_form').submit(function(e){
                let total = parseFloat($('#total_in_kg').val());
                let loading = parseFloat($('#loading').val());
                let running = parseFloat($('#running').val());
                let bypass = parseFloat($('#bypass_wast').val());
                let dana_in_kg = parseFloat($('#dana_in_kg').val());

                $('#btn-update').prop('disabled',false);

                let final = total + loading + running + bypass;
                if(final != dana_in_kg){
                    e.preventDefault();
                    alert('not equal');
                }
                else{
                    $('#tape_entry_form').submit();
                }
            });
            /*********************** form condition checks end **********************/
        });
        
</script>

@endpush
@endsection

@section('extra-script')
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
@endsection