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
                <h4><strong>CC Plant Entry</strong></h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('CC Plant') }}</li>
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
                                @foreach ($godams as $godam)
                                    <option selected value="{{ $godam->id }}" {{ $godam->id == $godam->godam_id ? "selected" : ""  }}>{{ $godam->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="receipt_no">Dana Name</label>
                        <input type="text" value="{{ $ccplant->danaName->name }}" id="dana_name"
                            name="receipt_number" class="form-control" readonly required />
                            <input type="hidden" value="{{ $ccplant->danaName->id }}" id="dana_name_id" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="receipt_no">Dana Group</label>
                        <input type="text" value="{{ $ccplant->danaName->danagroup->name }}" id="dana_group"
                            name="receipt_number" class="form-control" readonly required />
                            <input type="hidden" value="{{ $ccplant->danaName->danagroup->id }}" id="dana_group_id" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="receipt_no">Dana Group</label>
                        <input type="text" value="{{ $ccplant->dana_quantity }}" id="dana_quantity"
                            name="receipt_number" class="form-control" readonly required />
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
                            @foreach($shift as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
        </div>
        <hr>
        <h3 class="text-center">CC Dana Creation</h3>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">DanaGroup</label>
                            <select name="danagroup" id="creation-danagroup" class="form-control select2 advance-select-box">
                                <?php
                                    $danaGroup = \App\Models\DanaGroup::get();
                                ?>
                                @foreach($danaGroup as $danag)
                                    <option value="{{ $danag->id }}">{{ $danag->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="">DanaName</label>
                            <input type="text" name="dananame" id="creation-dananame" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="">Quantity</label>
                            <input type="number" name="quantity" id="creation-quantity" class="form-control">
                        </div>
                        <div class="col-md-4 mt-2">
                            <button class="btn btn-primary dana-creation">Create</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered table-dana-creation w-100">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Dana Group</th>
                                    <th>Dana Name</th>
                                    <th>Quantity</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>   
                    </div>
                </div>
            </div>
        <hr>
        <h3 class="text-center">Dana Consumption</h3>
        <div class='row mt-3'>
            <div class="col-md-4 card">
                <div class="card-body">
                    <label for="">Dana Name</label>
                    <select name="raw_materials" id="raw_materials" class="form-control advance-select-box">
                        @foreach($rawmaterials as $rawmaterial)
                            <option value="{{ $rawmaterial->id }}">{{ $rawmaterial->danaName->name }}</option>
                        @endforeach
                    </select>
                    <label for="">Quantity</label>
                    <input type="text" class="form-control" id="quantity" name="quantity">
                    <button class="btn btn-primary mt-3 consumption-dana-table">Add</button>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered w-100" id="tape_entry_dana_table"
                        style="background:rgba(241, 214, 147,0.2)">
                        <thead class="table-warning">
                            <tr>
                                <th style="width:100px">SN</th>
                                <th>Dana Name</th>
                                <th>Dana Qty</th>
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
                <button type="button"  id="btn-update" class="finalsubmit btn btn-info mt-3" disabled>Update</button>
            </div>
        </div>
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

            let table =  $("#tape_entry_dana_table").DataTable({
                serverside : true,
                processing : true,
                ajax : {
                    url : "{{ route('cc.plant.get.cc.raw.materials') }}",
                    data : function(data){
                        data.cc_plant_entry_id = $("#cc_plant_entry_id").val()
                    },
                },
                columns : [
                    { name : "DT_RowIndex" , data : "DT_RowIndex" },
                    { name : "dana" , data : "dana" },
                    { name : "quantity" , data : "quantity" },
                ]
            })

            let creationTable = $(".table-dana-creation").DataTable({
                processing : true,
                serverside : true,
                ajax : "{{ route('cc.plant.created.dana',['entry_id' => ':entry_id']) }}".replace(":entry_id",$("#cc_plant_entry_id").val()),
                columns : [
                    { name : "DT_RowIndex" , data : "DT_RowIndex" },
                    { name : "danagroup" , data : "danagroup" },
                    { name : "dananame" , data : "dananame" },
                    { name : "quantity" , data : "quantity" },
                    { name : "action" , data : "action" },
                ]
            })

            $(".consumption-dana-table").click(function(e){
                e.preventDefault()
                $.ajax({
                    url : "{{ route('cc.plant.add.dana') }}",
                    method : "post",
                    data : {
                        "_token" : $("meta[name='csrf-token']").attr("content"),
                        "dana_id" : $("#raw_materials").val(),
                        "quantity" : $("#quantity").val(),
                        "plantname_id" : $("#plantname").val(),
                        "planttype_id" : $("#planttype").val(),
                        "cc_plant_entry_id" : $("#cc_plant_entry_id").val()
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

            $(".dana-creation").click(function(e){
                e.preventDefault()
                $.ajax({
                    url : "{{ route('cc.plant.dana.creation.temp') }}",
                    method : "post",
                    data : {
                        "_token" : $("meta[name='csrf-token']").attr("content"),
                        "cc_plant_entry_id" : $("#cc_plant_entry_id").val(),
                        "dana_name" : $("#creation-dananame").val(),
                        "dana_group" : $("#creation-danagroup").val(),
                        "quantity" : $("#creation-quantity").val(),
                        "plantname_id" : $("#plantname").val(),
                        "planttype_id" : $("#planttype").val(),
                    },success:function(response){
                        creationTable.ajax.reload()
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
                        // location.href = '{{ route("cc.plant.entry.index") }}'
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
                    url : "{{ route('cc.plant.get.sum',['entry_id'=>':entry_id']) }}".replace(":entry_id",$("#cc_plant_entry_id").val()),
                    method : "get",
                    success:function(response){
                        console.log(response)
                        $("#dana_in_kg").val(response.sum)
                        if(Number(response.sum) > 0){
                            $("#btn-update").attr("disabled",false)
                        }
                    },error:function(error){
                        console.log(error)
                    }
                })
            }
            function getCreatedDana(){

            }
        });
    </script>
@endsection