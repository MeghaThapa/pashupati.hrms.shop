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
                <h4><strong>Reprocess Wastage Entry</strong></h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Reprocess Wastage') }}</li>
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
                            value="{{ $reprocessWaste->date }}" readonly required />
                    </div>
                    <div class="col-md-4">
                        <label for="receipt_no">Receipt Number</label>
                        <input type="text" value="{{ $reprocessWaste->receipt_number }}" id="receipt_number_1_repeat"
                            name="receipt_number" class="form-control" readonly required />
                        <input type="hidden" name='tape_entry_id' id="cc_plant_entry_id" value="{{ $reprocessWaste->id }}">
                        <input type="hidden" value="{{ $reprocessWaste->godam_id }}" id="godam" readonly>
                    </div>
                    <div class="col-md-4">
                        <div class="col-md-12">
                            <label for="receipt_no">To Godam</label>
                            <input type="text" value="{{ $reprocessWaste->godam->name }}" readonly
                                class="form-control" />
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
                            @foreach ($shifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
        </div>
        <hr>
        <h3 class="text-center">Wastage Raw Material Creation</h3>
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
        <div class="card">
            <div class="card-header d-flex justify-content-center">
                <h3>Wastage Consumption</h3>
            </div>
        </div>
        <div class='row mt-3'>
            <div class="col-md-4 card">
                <div class="card-body">
                    <label for="">Select Wastage</label>
                    <select name="raw_materials" id="raw_materials" class="form-control advance-select-box">
                        <option selected disabled>Select Wastage</option>
                        @foreach ($wastages as $wastage)
                            <option data-quantity="{{ $wastage->wastageStock->quantity_in_kg }}"
                                value="{{ $wastage->id }}">{{ $wastage->name }}</option>
                        @endforeach
                    </select>
                    <label for="wastage_available_quantity">Available Quantity</label>
                    <input type="text" class="form-control" id="availableQuantity" readonly />


                    <label for="">Wastage Quantity</label>
                    <input type="text" class="form-control" id="wastageQuantity" name="quantity">
                    <span id="alertQuantity" class="text-red"></span>

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
                                    <th>Wastage</th>
                                    <th>Quantity</th>
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
                <h3 class="text-center">Add Reprocess Wastage to Erema Lumps</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label for="">Dye Wastage</label>
                        <input type="number" name="quantity" id="dyeWastage" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="">Cutter Wastage</label>
                        <input type="number" name="quantity" id="cutterWastage" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="">Melt Wastage</label>
                        <input type="number" name="quantity" id="meltWastage" class="form-control">
                    </div>
                    <div class="col-md-3 mt-2">
                        <button id="processWaste" type="button" class="btn btn-primary process_waste_creation">Add</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="wastageTable" class="table table-striped table-hover table-bordered  w-100">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Dye Quantity</th>
                                <th>Cutter Quantity</th>
                                <th>Melt Quantity</th>
                                <th>Total Quantity</th>
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
                    // if (data.name.startsWith("cc")) {
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
                    url: "{{ route('reprocess.wastage.get.raw.materials') }}",
                    data: function(data) {
                        data.cc_plant_entry_id = $("#cc_plant_entry_id").val()
                    },
                },
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

            

            let wastageTable = $("#wastageTable").DataTable({
                processing: true,
                serverside: true,
                ajax: "{{ route('reprocess.wastage.created.wastage', ['entry_id' => ':entry_id']) }}".replace(
                    ":entry_id", $("#cc_plant_entry_id").val()),
                columns: [{
                        name: "DT_RowIndex",
                        data: "DT_RowIndex"
                    },
                    {
                        name: "dye_quantity",
                        data: "dye_quantity"
                    },
                    {
                        name: "cutter_quantity",
                        data: "cutter_quantity"
                    },
                    {
                        name: "melt_quantity",
                        data: "melt_quantity"
                    },
                    {
                        name: "total_quantity",
                        data: "total_quantity"
                    },
                    {
                        name: "action",
                        data: "action"
                    },
                ]
            });

            let creationTable = $(".table-dana-creation").DataTable({
                processing: true,
                serverside: true,
                ajax: "{{ route('reprocess.wastage.created.dana', ['entry_id' => ':entry_id']) }}".replace(
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
            });

            function updateWasteQuantiy() {
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

            $(".add-dana-table").click(function(e) {
                e.preventDefault();
                if (parseInt($('#availableQuantity').val()) < parseInt($('#wastageQuantity').val()) ) {
                    $('#alertQuantity').html('Wastage Quantity exceeded available quantity');
                    return;
                } else {
                    $('#alertQuantity').html('');
                }
                $.ajax({
                    url: "{{ route('reprocess.wastage.add.waste') }}",
                    method: "post",
                    data: {
                        "_token": $("meta[name='csrf-token']").attr("content"),
                        "wastage_id": $('#raw_materials').val(),
                        "godam_id": $('#godam').val(),
                        "quantity": $("#wastageQuantity").val(),
                        "plantname_id": $("#plantname").val(),
                        "planttype_id": $("#planttype").val(),
                        "cc_plant_entry_id": $("#cc_plant_entry_id").val(),
                    },
                    success: function(response) {
                        tablereload()
                        putsum()
                        var optionToUpdate = $('#raw_materials option[value="' + $(
                            '#raw_materials').val() + '"]');
                        if (optionToUpdate.length > 0) {
                            optionToUpdate.attr('data-quantity', parseInt(response.data
                                .quantity_in_kg));
                        }

                        $('#availableQuantity').val(parseInt(response.data.quantity_in_kg));

                        $('#wastageQuantity').val('');
                    },
                    error: function(error) {
                        console.log(error)
                    }
                })
            });

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
                        location.href = '{{ route('cc.plant.entry.index') }}'
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
                    url: "{{ route('reprocess.wastage.get.sum', ['entry_id' => ':entry_id']) }}".replace(
                        ":entry_id", $("#cc_plant_entry_id").val()),
                    method: "get",
                    success: function(response) {
                        $("#dana_in_kg").val(response.dana_sum)
                        $("#waste_in_kg").val(response.total_waste_sum)
                        if (Number(response.sum) > 0) {
                            $("#btn-update").attr("disabled", false)
                        }
                    },
                    error: function(error) {
                        console.log(error)
                    }
                })
            }


            $(document).on('change', '#raw_materials', function() {
                var selectedOption = $(this).find('option:selected');
                var availableQuantity = selectedOption.data('quantity');
                $('#availableQuantity').val(availableQuantity);
                $('#wastageQuantity').attr('max', availableQuantity);
            });

            $(document).on('click','.waste_recycle',function(){
                $('#restore_id').val($(this).data('id'));
                $('#restoreModal').modal('show');
            });

            $(document).on('click','.confirm_recycle',function(e) {
                e.preventDefault()

                $.ajax({
                    url: "{{ route('reprocess.wastage.restore.wastage') }}",
                    method: "post",
                    data: {
                        "_token": $("meta[name='csrf-token']").attr("content"),
                        "wastage_id": $("#raw_materials").val(),
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
                            optionToUpdate.attr('data-quantity', parseInt(response.data
                                .quantity_in_kg));
                        }

                        $('#availableQuantity').val( parseInt(response.data.quantity_in_kg) );

                        $('#wastageQantity').val('');

                    },
                    error: function(error) {
                        console.log(error)
                    }
                })
            });

            $(document).on('click','#processWaste',function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{ route('reprocess.wastage.stock.entry') }}",
                    method: "post",
                    data: {
                        "_token": $("meta[name='csrf-token']").attr("content"),
                        "dye_quantity": $('#dyeWastage').val(),
                        "cutter_quantity": $('#cutterWastage').val(),
                        "melt_quantity": $('#meltWastage').val(),
                        "godam_id": $('#godam').val(),
                        "cc_plant_entry_id": $("#cc_plant_entry_id").val(),
                    },
                    success: function(response) {
                        wastageTable.ajax.reload();        
                        putsum();
                        var optionToUpdate = $('#raw_materials option[value="' + $(
                            '#raw_materials').val() + '"]');
                        if (optionToUpdate.length > 0) {
                            optionToUpdate.attr('data-quantity', parseInt(response.data
                                .quantity_in_kg));
                        }

                        $('#availableQuantity').val( parseInt(response.data.quantity_in_kg) );

                        $('#wastageQantity').val('');
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

            $(".confirm_waste_recycle_remove").click(function(e) {
                e.preventDefault()

                $.ajax({
                    url: "{{ route('reprocess.wastage.remove.recycle.wastage') }}",
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
                    url: "{{ route('reprocess.wastage.dana.stock.entry') }}",
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

            $(document).on('click', '.item_recycle_remove', function() {
                $('#restore_recycle_id').val($(this).data('id'));
                $('#restoreModal2').modal('show');
            });

            $(".confirm_recycle_remove").click(function(e) {
                e.preventDefault()

                $.ajax({
                    url: "{{ route('reprocess.wastage.remove.recycle.dana') }}",
                    method: "post",
                    data: {
                        "_token": $("meta[name='csrf-token']").attr("content"),
                        "dana_name_id": $("#raw_materials").val(),
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

        });
    </script>
@endsection
