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
    </style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6 mt-2">
                <h4><strong>Reprocess Waste Entry</strong></h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Reprocess Waste') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            <form id="tape_entry_form" method="post">
                @csrf
                <div class='row mt-2'>
                    <div class="col-md-4">
                        <label for="tape_receive_date">Date</label>
                        <input type="date" name="tape_receive_date" class="form-control"
                            value="{{ $data->date }}" readonly required />
                    </div>
                    <div class="col-md-4">
                        <label for="receipt_no">Receipt Number</label>
                        <input type="text" value="{{ $data->receipt_number }}" id="receipt_number_1_repeat"
                            name="receipt_number" class="form-control" readonly required />
                            <input type="hidden" name='tape_entry_id' id="cc_plant_entry_id" value="{{ $entry_id }}">
                            <input type="hidden" value="{{ $data->godam_id }}" id="godam" readonly>
                    </div>
                    <div class="col-md-4">
                        <div class="col-md-12">
                            <label for="receipt_no">To Godam</label>
                            <select class="form-control select2 advance-select-box" name="godam" disabled required>
                                @foreach ($godam as $data)
                                    <option selected value="{{ $data->id }}" {{ $data->id == $data->godam_id ? "selected" : ""  }}>{{ $data->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <hr>

                <div class='row mt-2'>
                    <div class="col-md-4">
                        <label for="receipt_no">Plant Type</label>
                        <select class="form-control select2 advance-select-box" name="planttype" id="planttype" required></select>
                    </div>
                    <div class="col-md-4">
                        <label for="receipt_no">Plant Name</label>
                        <select class="form-control select2 advance-select-box" name="plantname" id="plantname" required></select>
                    </div>
                    <div class="col-md-4">
                        <label for="receipt_no">Shift</label>
                        <select class="form-control select2 advance-select-box" name="shift" id="shift" required>
                            @foreach($shift as $data)
                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
        </div>
        <hr>
        <div class="table-responsive">
            
        </div>
        <hr>
        <div class='row mt-3'>
            <div class="col-md-4 card">
                <div class="card-body">
                    <label for="">Select Dana Name</label>
                    <select name="raw_materials" id="raw_materials" class="form-control advance-select-box">
                        @foreach($rawmaterials as $data)
                            <option value="{{ $data->id }}">{{ $data->danaName->name }}</option>
                        @endforeach
                    </select>
                    <label for="">Dana Quantity</label>
                    <input type="text" class="form-control" id="quantity" name="quantity">

                    <label for="">Select Waste</label>
                    <select name="waste_id" id="waste_id" class="form-control advance-select-box">
                        @foreach($wastes as $data)
                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                        @endforeach
                    </select>
                    <label>Waste Type</label>
                    <div class="row">
                        <div class="col-sm-3">Dye</div>
                        <div class="col-sm-9">
                            <input id="dye_quantity" class="form-control" type="number" name="dye_quantity" value="0" min="0" />
                        </div>
                        <div class="col-sm-3">Cutter</div>
                        <div class="col-sm-9">
                            <input id="cutter_quantity" class="form-control" type="number" name="cutter_quantity" value="0" min="0" />
                        </div>
                        <div class="col-sm-3">Melt</div>
                        <div class="col-sm-9">
                            <input id="melt_quantity" class="form-control" type="number" name="melt_quantity" value="0" min="0" />
                        </div>
                    </div>
                    <label class="mt-2" for="">Waste Quantity</label>
                    <input type="number" class="form-control" id="waste_quantity" name="quantity" value="0" min="0" disabled>
                    <button class="btn btn-primary mt-3 add-dana-table">Add</button>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body table-responsive">
                        <table class="table table-bordered w-100" id="tape_entry_dana_table"
                        style="background:rgba(241, 214, 147,0.2)">
                        <thead class="table-warning">
                            <tr>
                                <th style="width:100px">SN</th>
                                <th>Dana Name</th>
                                <th>Dana Group</th>
                                <th>Dana Qty</th>
                                <th>Waste</th>
                                <th>Dye Waste Quantity</th>
                                <th>Cutter Waste Quantity</th>
                                <th>Melt Waste Quantity</th>
                                <th>Total Waste Quantity</th>
                            </tr>
                        </thead>
                    </table>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row d-flex justify-content-center">
            <div class="col-md-12">
                <label>Total Row/Dana in Kg</label>
                <input type="text" class="form-control" name="dana_in_kg" id="dana_in_kg" readonly required />
            </div>
            <div class="col-md-12">
                <label>Total Waste in Kg</label>
                <input type="text" class="form-control" name="waste_in_kg" id="waste_in_kg" readonly required />
            </div>
        </div>
        <button type="button"  id="btn-update" class="finalsubmit btn btn-info mt-3" disabled>Update</button>
        <hr>
        </form>
    </div>
    </div>
@endsection

@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
         
            getplanttype()
            putsum()
            /***************** AJAX *******************************/

            //planttype
            function getplanttype() {
                let geturl = "{{ route('cc.plant.get.planttype.ajax') }}";
                $.ajax({
                    url: geturl,
                    method: "get",
                    data : {
                        "godam_id" :  $('#godam').val()
                    },
                    beforeSend: function() {
                        console.log("ajax fired");
                    },
                    success: function(response) {
                        planttype(response);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }

             //for plant name
            $(document).on("change", '#planttype', function(e) {
                let planttype = $('#planttype').val();
                $.ajax({
                    url: "{{ route('cc.plant.get.plantname.ajax', ['planttype_id' => ':id']) }}".replace(":id",planttype),
                    method : "get",
                    beforeSend: function() {
                        console.log("Getting Plant Name");
                    },
                    success: function(response) {
                        console.log(response);
                        plantname(response);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });

            //planttype
            function planttype(response) {
                console.log('planttype', response);
                $('#planttype').empty();
                $('<option></option>').text("Select Plant Type").appendTo('#planttype');
                response.planttype.forEach(data => {
                    // if (data.name.startsWith("cc")) {
                        $('<option></option>').attr('value', `${data.id}`).text(`${data.name}`).appendTo('#planttype');
                    // }
                });
            }

            //for plant name
            function plantname(data) {
                $('#plantname').empty();
                $('<option></option>').text("Select Plant Name").appendTo('#plantname');
                data.planttype.forEach(resp => {
                    $(`<option value="${resp.id}">${resp.name}</option>`).appendTo('#plantname');
                });
            }

            let table =  $(".table").DataTable({
                serverside : true,
                processing : true,
                ajax : {
                    url : "{{ route('reprocess.waste.get.raw.materials') }}",
                    data : function(data){
                        data.cc_plant_entry_id = $("#cc_plant_entry_id").val()
                    },
                },
                columns : [
                    { name : "DT_RowIndex" , data : "DT_RowIndex" },
                    { name : "dana" , data : "dana" },
                    { name : "dana_group" , data : "dana_group" },
                    { name : "quantity" , data : "quantity" },
                    { name : "waste" , data : "waste" },
                    { name : "dye_quantity" , data : "dye_quantity" },
                    { name : "cutter_quantity" , data : "cutter_quantity" },
                    { name : "melt_quantity" , data : "melt_quantity" },
                    { name : "total_quantity" , data : "total_quantity" },
                ]
            });

            function updateWasteQuantiy(){
                let dye_quantity = $('#dye_quantity').val();
                let cutter_quantity = $('#cutter_quantity').val();
                let melt_quantity = $('#melt_quantity').val();

                let totalQuantity = parseInt(dye_quantity) + parseInt(cutter_quantity) + parseInt(melt_quantity);
                $('#waste_quantity').val(totalQuantity);
            }

            $('#dye_quantity, #cutter_quantity, #melt_quantity').on('keyup', function() {
                console.log('done');
                updateWasteQuantiy();
            });

            $(".add-dana-table").click(function(e){
                e.preventDefault()
                $.ajax({
                    url : "{{ route('reprocess.waste.add.waste') }}",
                    method : "post",
                    data : {
                        "_token" : $("meta[name='csrf-token']").attr("content"),
                        "dana_id" : $("#raw_materials").val(),
                        "quantity" : $("#quantity").val(),
                        "plantname_id" : $("#plantname").val(),
                        "planttype_id" : $("#planttype").val(),
                        "cc_plant_entry_id" : $("#cc_plant_entry_id").val(),
                        "waste_id":$('#waste_id').val(),
                        "dye_quantity":$('#dye_quantity').val(),
                        "cutter_quantity":$('#cutter_quantity').val(),
                        "melt_quantity":$('#melt_quantity').val(),
                    },
                    success:function(response){
                        console.log(response)
                        tablereload()
                        putsum()
                    },error:function(error){
                        console.log(error)
                    }
                })
            })

            $(".finalsubmit").click(function(e){
                e.preventDefault()
                $.ajax({
                    url : "{{ route('cc.plant.final.submit') }}",
                    method : "post",
                    data : {
                        "_token" : $("meta[name='csrf-token']").attr("content") ,
                        "cc_plant_entry_id" : $("#cc_plant_entry_id").val()
                    },success:function(response){
                        location.href = '{{ route("cc.plant.entry.index") }}'
                    },error:function(error){
                        console.log(error)
                    }
                })
            })


            function tablereload(){
                table.ajax.reload()
            }

            function putsum(){
                $.ajax({
                    url : "{{ route('reprocess.waste.get.sum',['entry_id'=>':entry_id']) }}".replace(":entry_id",$("#cc_plant_entry_id").val()),
                    method : "get",
                    success:function(response){
                        console.log(response)
                        $("#dana_in_kg").val(response.dana_sum)
                        $("#waste_in_kg").val(response.total_waste_sum)
                        if(Number(response.sum) > 0){
                            $("#btn-update").attr("disabled",false)
                        }
                    },error:function(error){
                        console.log(error)
                    }
                })
            }
        });
    </script>
@endsection