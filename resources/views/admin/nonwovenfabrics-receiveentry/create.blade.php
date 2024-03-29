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
    <form id="createRawMaterial" action="{{ route('nonwovenfabrics-receiveentry.store') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Date') }}<span class="required-field">*</span>
                </label>
                <input type="text" class="form-control" id="receive_date" value="{{ $find_data->bill_date }}"  name="receive_date" readonly/>
                <input type="hidden" value="{{$bill_id}}" name="bill_id" id="bill_id">
            </div>

            <div class="col-md-2 form-group">
                <label for="receive_no">Receipt Number</label>
                <input type="text" value="{{$find_data->bill_no}}" id="receipt_number_1_repeat"
                    name="receive_no" class="form-control" readonly />
            </div>


            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('To Godam') }}
                </label>
                <input type="text" value="{{$find_data->getGodam->name}}" id="godams"
                    name="receive_no" class="form-control" readonly />


            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Plant Type') }}
                </label>
                <input type="text" value="{{$find_data->getPlantType->name}}" id="godams"
                    name="receive_no" class="form-control" readonly />
            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Plant Name') }}
                </label>
                <input type="text" value="{{$find_data->getPlantName->name}}" id="godams"
                    name="receive_no" class="form-control" readonly />

            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Shift') }}
                </label>
                <input type="text" value="{{$find_data->getShift->name}}" id="godams"
                    name="receive_no" class="form-control" readonly />
            </div>


        </div>
        <div class="row">

            <div class="col-md-3 form-group">
                <label for="fabric_roll">Fabric  Roll</label>
                <input type="text" class="form-control" name="fabric_roll" id="fabric_roll">

                 <input type="hidden" class="form-control" name="bill_no" id="bill_no" value="{{$find_data->bill_no}}">
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
                <button id="getfabricsrelated" class="btn btn-primary mt-4">
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
                    </tr>
                </thead>

                <tbody id="rawMaterialItemTbody">
                </tbody>

            </table>
        </div>

    </div>
</div>
<div class="row">
    <div class="col-md-7">
        <form id="addSingleTripalDanaConsumption">
            <div class="card p-2">
                <div class="row">

                    <div class="col-md-4">
                        <label for="cut_length">Dana Name</label>
                        <select
                            class="advance-select-box form-control  @error('dana_name_id') is-invalid @enderror"
                            id="danaNameId" name="dana_name_id" required>
                            <option value="">Select</option>

                            @foreach ($danas as $dana)
                                <option value="{{ $dana->id }}">
                                    {{ $dana->danaName->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="cut_length">Available</label>
                        <input type="text" class="form-control" id="avilableStock"
                            name="avilable_stock" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="cut_length">Quantity</label>
                        <input type="text" class="form-control" id="quantity" name="quantity">
                    </div>
                    <div class="col-md-2 mt-4">
                        <button class="btn btn-primary ">Add</button>
                    </div>
                </div>

            </div>
        </form>
    </div>
    <div class="col-md-5">
        <table class="table table-bordered table-hover" id="danaConsumption">
            <thead>
                <tr>
                    <th style="width:30px;">SN</th>
                    <th>Godam</th>
                    {{-- <th>Dana Group</th> --}}
                    <th>Dana Name</th>
                    <th>Quantity</th>
                    <th>Action</th>

                </tr>
            </thead>
            <tbody >
                @foreach($danalist as $list)
                <tr>
                    <td>#</td>
                    <td>{{$list->godam->name}}</td>
                    <td>{{$list->danaName->name}}</td>
                    <td>{{$list->quantity}}</td>

                    <td>

                        <a class="btnEdit btn btn-sm btn-danger" href="{{ route('nonwovenDanaConsumption.delete', $list->id) }}"><i class="fas fa-trash"></i>
                            </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row">

    {{-- <div class="col-md-5">
        <div class="card mt-2 p-5">
            <div class="card-body">

                <div class="row p-2">
                    <div class="col-md-6">
                        <label for="size" class="col-form-label">{{ __('Dana:') }}<span class="required-field">*</span>
                        </label>
                        <select class="advance-select-box form-control" id="danaNameId" name="danaNameId" required>
                            <option value="" selected disabled>{{ __('-Select Dana Name-') }}</option>
                            @foreach ($dana as $dana)
                            <option value="{{ $dana->id }}">{{ $dana->danaName->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('total_ul_in_mtr')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="size" class="col-form-label">{{ __('Qty:') }}<span class="required-field">*</span>
                        </label>
                        <input type="number" step="any" min="0" class="form-control" id="add_dana_consumption_quantity"
                            data-number="1" name="total_ul_in_mtr" min="1" disabled required>
                        @error('total_ul_in_mtr')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <button class=" form-control btn btn-primary" id='add_dana_consumption' disabled>
                            Add
                        </button>
                    </div>
                </div>


            </div>
        </div>
    </div> --}}

    <div class="card col-md-7">
        <div class="card-body m-2 p-5">
            <div class="col-md-12" style="height: 100%;">
                <div class="row">
                    <div class="col-md-4 form-group">
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
                            <input type="text" name="dana_id" id="dana_id">
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div >
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

                    </div>

                    <div class="col-md-4 form-group">
                        <div>
                            <label for="size" class="col-form-label">{{ __('Dana Quantity:') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator" id="dana_quanity"
                                data-number="1" name="dana_quanity" min="1" required value="{{$sumdana}}">
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
                                data-number="1" name="netweight" value="{{$getnetweight}}" min="1"  required readonly>
                            @error('netweight')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                    </div>

                </div>
            </div>
        </div>
        <div class="card-footer">
            <input type="hidden" name="selectedDanaID" class="form-control" id="selectedDanaID" readonly>
            <input type="hidden" name="billno" class="form-control" id="billno" value="{{$receipt_no}}" readonly>
            <button class="btn btn-primary" disabled id="finalUpdate">Update</button>
        </div>
    </div>
    {{-- <div class="col-md-7" style="height: 100%;">
        <div class="row">
            <div class="col-md-6 form-group">

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
    </div> --}}
</div>


  <!-- Modal -->
 {{--  <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id='sendtolaminationform' method="post">
                @csrf
                <div class="card">
                    <div class="card-body">
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
                        <div class="row d-flex justify-content-center text-center mb-2-">
                            <div class="col-md-6">
                                <button type='submit' class="btn btn-info">Create Group</button>
                            </div>
                            <div class="col-md-6">
                                <button type='submit' class="btn btn-info">Update</button>
                            </div>
                            <input type="text" name="idoffabricforsendtolamination" id="idoffabricforsendtolamination">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div> --}}


@endsection
@section('extra-script')
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
<script src="{{ asset('js/storein.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
<script>

    //save addPrintsAndDanaConsumption
    document.getElementById('addSingleTripalDanaConsumption').addEventListener('submit',
        function(e) {
            e.preventDefault();
            // debugger;
            const form = e.target;
            let bill_no = $("#bill_no").val();
            let bill_id = $("#bill_id").val();
            //console.log('testing', printCutEntry_id);
            let autoloader_id = form.elements['dana_name_id'];
            let quantity = form.elements['quantity'];
            $.ajax({
                url: "{{ route('nonwovenDanaConsumption.store') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    bill_no: bill_no,
                    bill_id: bill_id,
                    autoloader_id: autoloader_id.value,
                    quantity: quantity.value
                },
                success: function(response) {
                    location.reload();
                    removeAllTableRows('danaConsumption');
                    getdanaConsumptionData();
                    // setIntoConsumptionTable(response);
                    // getdanaConsumptionData();
                    updateStockQuantity();
                },
                error: function(xhr, status, error) {
                    setErrorMsg(xhr.responseJSON.message);
                }
            });
    });


    $("#danaNameId").on("change",function(e){
        var danaid = $(this).val();
        $("#add_dana_consumption_quantity").prop("disabled",false);
    });


    $(document).on("keyup","#add_dana_consumption_quantity",function(e){
        $("#add_dana_consumption").prop("disabled",false);
    });

    $(document).on("keyup","#filter",function(e){
        $("#finalUpdate").prop("disabled",false);
    });

    $(document).on("click","#add_dana_consumption",function(e){
        // debugger;
        let dana = $("#danaNameId").val();
        let consumption = $("#add_dana_consumption_quantity").val();
        $("#dana_quanity").val(consumption);

        $.ajax({
            url:"{{ route('dana.autoload.checkAutoloadQuantity') }}",
            method : 'post',
            data:{
                '_token' : $('meta[name="csrf-token"]').attr('content'),
                'danaid' : dana
            },
            beforeSend:function(){
                console.log('Getting Plant type');
            },
            success:function(response){

                if (consumption.trim() === '') {
                    alert("add quantity");
                }else{
                    $("#totl_dana").val(consumption);

                    $("#selectedDanaID").val(dana);
                }

            },
            error:function(error){
                console.log(error);
            }

        });

    });
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
        var nettotal = parseInt(filter) + parseInt(filament) + parseInt(roalcoast) + parseInt(strip);

        $("#wastage").val(nettotal);

      }
    });
</script>
<script type="text/javascript">


    $('#danaNameId').on('select2:select', function(e) {

        let autoloader_id = e.params.data.id;
        $('#avilableStock').empty();
        getStockQuantity(autoloader_id);
    });

    function getStockQuantity(autoloader_id) {
        $.ajax({
            url: "{{ route('tripalDana.getStockQuantity') }}",
            method: 'post',
            data: {
                _token: "{{ csrf_token() }}",
                autoloader_id: autoloader_id,
            },
            success: function(response) {
                console.log('stockQty', response);
                document.getElementById('avilableStock').value = response.quantity;
                // response.forEach(function(item) {
                //     setOptionInSelect('danaNameId', item.dana_name.id, item.dana_name
                //         .name);
                // });
            },
            error: function(xhr, status, error) {
                setErrorMsg(xhr.responseJSON.message);
            }
        });
    }

   $(document).on("click","#finalUpdate",function(e){

      let danaNameId = $("#selectedDanaID").val();
      let consumption = $("#add_dana_consumption_quantity").val();
      let wastage = $("#wastage").val();
      // let total_waste = $('#total_waste').val();
      let selectedDanaID = $("#selectedDanaID").val();
      let filter = $("#filter").val();
      let filament = $("#filament").val();
      let roalcoast = $("#roalcoast").val();
      let strip = $("#strip").val();
      let godam_id = $("#toGodam").val();
      let bill = $("#bill_id").val();


      trimmedFilter = filter.trim();
      // trimmedFabricWaste = fabric_waste.trim();
      trimmedTotalWaste = wastage.trim();


      if(trimmedFilter == '' || trimmedTotalWaste == ''){
          alert('Waste and Consumption cannot be null');
      }else{

          $.ajax({
              url : "{{ route('storeWastage') }}",
              method: "post",
              data:{
                  "_token" : $('meta[name="csrf-token"]').attr('content'),
                  "danaNameID" : danaNameId,
                  "total_waste" : trimmedTotalWaste,
                  "filter" : filter,
                  "filament" : filament,
                  "roal_coast" : roalcoast,
                  "strip" : strip,
                  "selectedDanaID" : selectedDanaID,
                  "bill" : bill
              },
              beforeSend:function(){
                  console.log("Before Send");
              },
              success:function(response){
                  location.href='{{route('nonwovenfabrics-receiveentry.index')}}'
                  // if(response == '200'){
                  //     location.reload();
                  // }else{

                  // }
              },
              error:function(error){
                  console.log(error);
              }
          });
      }
  });
</script>

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
        let bill_id =  $('#bill_id').val();
        $.ajax({
            url : "{{ route('nonwovenfabric.getReceiveEntryData') }}",
            method: 'get',
            data:{
                "_token": $('meta[name="csrf-token"]').attr('content'),
                "bill_id" : bill_id
            },
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

</script>
@endsection
