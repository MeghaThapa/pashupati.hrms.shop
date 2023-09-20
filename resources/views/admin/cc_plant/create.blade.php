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
                            value="{{ $ccPlantEntry->date }}" readonly required />
                    </div>
                    <div class="col-md-4">
                        <label for="receipt_no">Receipt Number</label>
                        <input type="text" value="{{ $ccPlantEntry->receipt_number }}" id="receipt_number_1_repeat"
                            name="receipt_number" class="form-control" readonly required />
                        <input type="hidden" name='tape_entry_id' id="cc_plant_entry_id" value="{{ $entry_id }}">
                        <input type="hidden" value="{{ $ccPlantEntry->godam_id }}" id="godam" readonly>
                    </div>
                    <div class="col-md-4">
                        <div class="col-md-12">
                            <label for="receipt_no">To Godam</label>
                            <input class="form-control" type="text" name="godam_name"
                                value="{{ $ccPlantEntry->godam->name }}" readonly />
                            <input type="hidden" name="godam" value="{{ $ccPlantEntry->godam_id }}" readonly />
                        </div>
                    </div>
                </div>

                <hr>

                <div class='row mt-2'>
                    <div class="col-md-4">
                        <label for="receipt_no">Plant Type</label>
                        <select class="form-control select2 advance-select-box" name="planttype" id="planttype"
                            required></select>
                    </div>
                    <div class="col-md-4">
                        <label for="receipt_no">Plant Name</label>
                        <select class="form-control select2 advance-select-box" name="plantname" id="plantname"
                            required></select>
                    </div>
                    <div class="col-md-4">
                        <label for="receipt_no">Shift</label>
                        <select class="form-control select2 advance-select-box" name="shift" id="shift" required>
                            @foreach ($shift as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
        </div>
        <hr>
        <h3 class="text-center">CC Raw Material Creation</h3>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label for="">DanaGroup</label>
                        <select name="danagroup" id="creationDanaGroup" class="form-control select2 advance-select-box">
                            <?php
                            $danaGroup = \App\Models\DanaGroup::get();
                            ?>
                            <option selected disabled>Select Dana Group</option>
                            @foreach ($danaGroup as $danag)
                                <option value="{{ $danag->id }}">{{ $danag->name }}</option>
                            @endforeach
                            
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="">DanaName</label>
                        <select name="raw_materials" id="createDanaName" class="form-control advance-select-box">
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="">Quantity</label>
                        <input type="number" name="quantity" id="danaCreationQuantity" class="form-control">
                    </div>
                    <div class="col-md-4 mt-2">
                        <button class="btn btn-primary process_dana_creation">Create</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dana_entry_table" class="table table-striped table-hover table-bordered table-dana-creation w-100">
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
                        @foreach ($danaNames as $danaName)
                            <option data-quanity="{{ $danaName->rawMaterialStock->quantity }}"
                                value="{{ $danaName->id }}">{{ $danaName->name }} ( {{ $danaName->danagroup->name }} )
                            </option>
                        @endforeach
                    </select>
                    <label>Available Quantity</label>
                    <input type="number" id="available_quantity" name="available_quantity" class="form-control"
                        readonly />
                    <label for="">Dana Quantity </label>
                    <input type="text" class="form-control" id="dana_quantity" name="quantity">
                    <span class="text-red" id="alert_error_dana_quantity"></span>
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
                                    <th>Action</th>
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
        </div>
        <hr>

        <div class="card">
            <div class="card-header">
                <h3 class="text-center">Add CC Wastage </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label for="">Select Wastage</label>
                        <select name="danagroup" id="creationWasteID" class="form-control select2 advance-select-box">
                            <option selected disabled>Select Wastage</option>
                            @foreach ($wastages as $wastage)
                                <option value="{{ $wastage->id }}">{{ $wastage->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="">Quantity</label>
                        <input type="number" name="quantity" id="wastageQuantity" class="form-control">
                    </div>
                    <div class="col-md-4 mt-2">
                        <button id="processWaste" type="button" class="btn btn-primary process_waste_creation">Create</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="wastage_table" class="table table-striped table-hover table-bordered  w-100">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Wastage</th>
                                <th>Quantity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        </form>
    </div>
    </div>

    <div id="restoreModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Are you sure you want to remove this item?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Once removed your stock will get restored.</p>
                    <input type="hidden" id="restore_id" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary confirm_recycle">Confirm</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div id="restoreModal2" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Are you sure you want to remove this item?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Once removed your stock will get restored.</p>
                    <input type="hidden" id="restore_recycle_id" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary confirm_recycle_remove">Confirm</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div id="restoreModal3" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Are you sure you want to remove this item?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Once removed your stock will get restored.</p>
                    <input type="hidden" id="restore_wastage_id" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary confirm_waste_recycle_remove">Confirm</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
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
                    data: {
                        "godam_id": $('#godam').val()
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

            $(document).on('change', '#raw_materials', function() {
                var selectedOption = $(this).find('option:selected');
                var availableQuantity = selectedOption.data('quanity');
                $('#available_quantity').val(availableQuantity);
                $('#dana_quantity').attr('max', availableQuantity);
            });

            $('#raw_materials').trigger('change');

            $(document).on('click', '.item_recycle', function() {
                $('#restore_id').val($(this).data('id'));
                $('#restoreModal').modal('show');
            });

            $(document).on('click', '.item_recycle_remove', function() {
                $('#restore_recycle_id').val($(this).data('id'));
                $('#restoreModal2').modal('show');
            });

            //for plant name
            $(document).on("change", '#planttype', function(e) {
                let planttype = $('#planttype').val();
                $.ajax({
                    url: "{{ route('cc.plant.get.plantname.ajax', ['planttype_id' => ':id']) }}"
                        .replace(":id", planttype),
                    method: "get",
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
                    $('<option></option>').attr('value', `${data.id}`).text(`${data.name}`).appendTo(
                        '#planttype');
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

            let table = $("#tape_entry_dana_table").DataTable({
                serverside: true,
                processing: true,
                ajax: {
                    url: "{{ route('cc.plant.get.cc.raw.materials') }}",
                    data: function(data) {
                        data.cc_plant_entry_id = $("#cc_plant_entry_id").val()
                    },
                },
                columns: [{
                        name: "DT_RowIndex",
                        data: "DT_RowIndex"
                    },
                    {
                        name: "dana",
                        data: "dana"
                    },
                    {
                        name: "quantity",
                        data: "quantity"
                    },
                    {
                        name: "action",
                        data: "action"
                    },
                ]
            })

            let creationTable = $(".table-dana-creation").DataTable({
                processing: true,
                serverside: true,
                ajax: "{{ route('cc.plant.created.dana', ['entry_id' => ':entry_id']) }}".replace(
                    ":entry_id", $("#cc_plant_entry_id").val()),
                columns: [{
                        name: "DT_RowIndex",
                        data: "DT_RowIndex"
                    },
                    {
                        name: "dana_group",
                        data: "dana_group"
                    },
                    {
                        name: "dana_name",
                        data: "dana_name"
                    },
                    {
                        name: "quantity",
                        data: "quantity"
                    },
                    {
                        name: "action",
                        data: "action"
                    },
                ]
            })
            
            let wastageTable = $("#wastage_table").DataTable({
                processing: true,
                serverside: true,
                ajax: "{{ route('cc.plant.created.wastage', ['entry_id' => ':entry_id']) }}".replace(
                    ":entry_id", $("#cc_plant_entry_id").val()),
                columns: [{
                        name: "DT_RowIndex",
                        data: "DT_RowIndex"
                    },
                    {
                        name: "wastage",
                        data: "wastage"
                    },
                    {
                        name: "quantity",
                        data: "quantity"
                    },
                    {
                        name: "action",
                        data: "action"
                    },
                ]
            });

            $(".consumption-dana-table").click(function(e) {
                e.preventDefault()

                if (parseInt($('#available_quantity').val()) < parseInt($('#dana_quantity').val())) {
                    $('#alert_error_dana_quantity').html('Dana Quantity is more than Available Quantity');
                    return;
                } else {
                    $('#alert_error_dana_quantity').html('');
                }

                $.ajax({
                    url: "{{ route('cc.plant.add.dana') }}",
                    method: "post",
                    data: {
                        "_token": $("meta[name='csrf-token']").attr("content"),
                        "dana_id": $("#raw_materials").val(),
                        "quantity": $("#dana_quantity").val(),
                        "plantname_id": $("#plantname").val(),
                        "planttype_id": $("#planttype").val(),
                        "cc_plant_entry_id": $("#cc_plant_entry_id").val()
                    },
                    success: function(response) {
                        tablereload()
                        putsum()
                        var optionToUpdate = $('#raw_materials option[value="' + $(
                            '#raw_materials').val() + '"]');
                        if (optionToUpdate.length > 0) {
                            optionToUpdate.attr('data-quanity', parseInt(response.data
                                .quantity));
                        }

                        $('#available_quantity').val(parseInt(response.data.quantity));

                        $('#dana_quantity').val('');

                    },
                    error: function(error) {
                        console.log(error)
                    }
                })
            })

            $(".dana-creation").click(function(e) {
                e.preventDefault()
                $.ajax({
                    url: "{{ route('cc.plant.dana.creation.temp') }}",
                    method: "post",
                    data: {
                        "_token": $("meta[name='csrf-token']").attr("content"),
                        "cc_plant_entry_id": $("#cc_plant_entry_id").val(),
                        "dana_name": $("#creation-dananame").val(),
                        "dana_group": $("#creation-danagroup").val(),
                        "quantity": $("#creation-quantity").val(),
                        "plant_name_id": $("#plantname").val(),
                        "plant_type_id": $("#planttype").val(),
                    },
                    success: function(response) {
                        creationTable.ajax.reload()
                    },
                    error: function(error) {
                        console.log(error)
                    }
                })
            })

            $(".finalsubmit").click(function(e) {
                e.preventDefault()
                $.ajax({
                    url: "{{ route('cc.plant.final.submit') }}",
                    method: "post",
                    data: {
                        "_token": $("meta[name='csrf-token']").attr("content"),
                        "cc_plant_entry_id": $("#cc_plant_entry_id").val()
                    },
                    success: function(response) {
                        // location.href = '{{ route('cc.plant.entry.index') }}'
                    },
                    error: function(error) {
                        console.log(error)
                    }
                })
            })


            function tablereload() {
                table.ajax.reload()

            }

            function putsum() {
                $.ajax({
                    url: "{{ route('cc.plant.get.sum', ['entry_id' => ':entry_id']) }}".replace(
                        ":entry_id",
                        $("#cc_plant_entry_id").val()),
                    method: "get",
                    success: function(response) {
                        console.log(response)
                        $("#dana_in_kg").val(response.sum)
                        if (Number(response.sum) > 0) {
                            $("#btn-update").attr("disabled", false)
                        }
                    },
                    error: function(error) {
                        console.log(error)
                    }
                })
            }

            $(".confirm_recycle").click(function(e) {
                e.preventDefault()

                $.ajax({
                    url: "{{ route('cc.plant.remove.dana') }}",
                    method: "post",
                    data: {
                        "_token": $("meta[name='csrf-token']").attr("content"),
                        "dana_id": $("#raw_materials").val(),
                        "restore_id": $('#restore_id').val(),
                        "cc_plant_entry_id": $("#cc_plant_entry_id").val()
                    },
                    success: function(response) {
                        tablereload()
                        putsum()
                        $('#restoreModal').modal('hide');
                        var optionToUpdate = $('#raw_materials option[value="' + $(
                            '#raw_materials').val() + '"]');
                        if (optionToUpdate.length > 0) {
                            optionToUpdate.attr('data-quanity', parseInt(response.data
                                .quantity));
                        }

                        $('#available_quantity').val(parseInt(response.data.quantity));

                        $('#dana_quantity').val('');

                    },
                    error: function(error) {
                        console.log(error)
                    }
                })
            });
            
            $(".confirm_recycle_remove").click(function(e) {
                e.preventDefault()

                $.ajax({
                    url: "{{ route('cc.plant.remove.recycle.dana') }}",
                    method: "post",
                    data: {
                        "_token": $("meta[name='csrf-token']").attr("content"),
                        "godam_id": $("#godam").val(),
                        "restore_recycle_id": $('#restore_recycle_id').val(),
                        "cc_plant_entry_id": $("#cc_plant_entry_id").val()
                    },
                    success: function(response) {
                        creationTable.ajax.reload();
                        putsum()
                        $('#restoreModal2').modal('hide');
                        var optionToUpdate = $('#raw_materials option[value="' + $(
                            '#raw_materials').val() + '"]');
                        if (optionToUpdate.length > 0) {
                            optionToUpdate.attr('data-quanity', parseInt(response.data
                                .quantity));
                        }

                        $('#available_quantity').val(parseInt(response.data.quantity));

                        $('#danaCreationQuantity').val('');

                        $('#dana_quantity').val('');

                    },
                    error: function(error) {
                        console.log(error)
                    }
                })
            });
            
            $(".confirm_waste_recycle_remove").click(function(e) {
                e.preventDefault()

                $.ajax({
                    url: "{{ route('cc.plant.remove.recycle.wastage') }}",
                    method: "post",
                    data: {
                        "_token": $("meta[name='csrf-token']").attr("content"),
                        "godam_id": $("#godam").val(),
                        "restore_wastage_id": $('#restore_wastage_id').val(),
                        "cc_plant_entry_id": $("#cc_plant_entry_id").val()
                    },
                    success: function(response) {
                        wastageTable.ajax.reload();
                        $('#restoreModal3').modal('hide');

                    },
                    error: function(error) {
                        console.log(error)
                    }
                })
            });


            $('#creationDanaGroup').on('change',function(){

                $.ajax({
                    url: "{{ route('cc.plant.danagroup.godam.dana') }}",
                    method: "post",
                    data: {
                        "_token": $("meta[name='csrf-token']").attr("content"),
                        "dana_group_id": $(this).val(),
                        "godam_id": $('#godam').val(),
                    },
                    success: function(response) {
                        $('#createDanaName').empty();
                        $(response.data).each(function(k,v){
                            $('#createDanaName').append('<option value="'+v.id+'">'+v.name+'</option>')
                        })

                    },
                    error: function(error) {
                        console.log(error)
                    }
                });

            });

            $(document).on('click','.process_dana_creation',function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{ route('cc.plant.dana.stock.entry') }}",
                    method: "post",
                    data: {
                        "_token": $("meta[name='csrf-token']").attr("content"),
                        "dana_name_id": $('#createDanaName').val(),
                        "dana_group_id": $('#creationDanaGroup').val(),
                        "plant_type_id": $('#planttype').val(),
                        "plant_name_id": $('#plantname').val(),
                        "quantity": $('#danaCreationQuantity').val(),
                        "godam_id": $('#godam').val(),
                        "cc_plant_entry_id": $("#cc_plant_entry_id").val(),
                    },
                    success: function(response) {
                        creationTable.ajax.reload();        
                        putsum();
                        $('#restoreModal').modal('hide');
                        var optionToUpdate = $('#raw_materials option[value="' + $(
                            '#raw_materials').val() + '"]');
                        if (optionToUpdate.length > 0) {
                            optionToUpdate.attr('data-quanity', parseInt(response.data
                                .quantity));
                        }

                        $('#available_quantity').val(parseInt(response.data.quantity));

                        $('#dana_quantity').val('');

                    },
                    error: function(error) {
                        console.log(error)
                    }
                });

            });

            $(document).on('click','.wastage_recycle_remove',function(){
                $('#restore_wastage_id').val($(this).data('id'));
                $('#restoreModal3').modal('show');
            });
            
            $(document).on('click','#processWaste',function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{ route('cc.plant.wastage.entry') }}",
                    method: "post",
                    data: {
                        "_token": $("meta[name='csrf-token']").attr("content"),
                        "wastage_id" : $('#creationWasteID').val(),
                        "quantity": $('#wastageQuantity').val(),
                        "godam_id": $('#godam').val(),
                        "cc_plant_entry_id": $("#cc_plant_entry_id").val(),
                    },
                    success: function(response) {
                        wastageTable.ajax.reload();        
                        putsum();
                    },
                    error: function(error) {
                        console.log(error)
                    }
                });

            });

        });
    </script>
@endsection
