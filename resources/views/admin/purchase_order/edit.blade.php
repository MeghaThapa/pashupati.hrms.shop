@extends('layouts.admin')
@section('extra-style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Purchase Orders') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Purchase Orders') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="col-md-12">
                @include('admin.includes.alert')
            </div>
            <div id="RawMaterialError" class="alert alert-danger" hidden></div>
            <div class="card card-primary">
                <div class="card-header">
                    <h3>
                        Add Purchase Order Items on Indent No: {{ $purchaseOrder->indent_no }}
                    </h3>
                    <span style="float:right;">Date: {{ $purchaseOrder->date }}</span>
                </div>
                <div class="card-body">
                    <form id="addData" action="{{ route('purchase-order.update',$purchaseOrder->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="">Category</label>
                                <select class="form-control select2" name="category_id" id="categoryId">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label for="">Department Name</label>
                                <select class="form-control select2" name="storein_department_id" id="StoreInDepartment">
                                    <option value="">Select Department</option>
                                </select>
                                <span id="error_storein_department_id"></span>
                            </div>
                            <div class="col-sm-3">
                                <label for="">Item Name</label>
                                <select class="form-control select2" name="store_in_item_id" id="itemName">
                                    <option value="">Select Item</option>
                                </select>
                                <span id="error_store_in_item_id"></span>
                            </div>
                            <div class="col-sm-3">
                                <label for="">Parts Number</label>
                                <span id="pNumber" class="form-control">-</span>
                            </div>
                            <div class="col-sm-3">
                                <label for="">Last Purchase From</label>
                                <span id="lastPurchaseFrom" class="form-control">-</span>
                            </div>
                            <div class="col-sm-3">
                                <label for="">Purchase Rate</label>
                                <span id="purchaseRate" class="form-control">-</span>
                            </div>
                            <div class="col-sm-2">
                                <label for="">Quantity In Stock</label>
                                <span id="quantityInStock" class="form-control">-</span>
                            </div>
                            <div class="col-sm-2">
                                <label for="">Size</label>
                                <span id="size" class="form-control">-</span>
                            </div>
                            <div class="col-sm-2">
                                <label for="">Req Qty</label>
                                <input class="form-control" type="text" name="req_quantity" />
                            </div>
                            <div class="col-sm-12">
                                <label for="">Remarks</label>
                                <input class="form-control" type="text" name="remarks"/>
                            </div>
                            <div class="col-sm-12 mt-4">
                                <button  type="submit" class="btn btn-sm btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="p-3 card card-body table-responsive">
                <table class="table table-bordered" id="primaryTable">
                    <thead>
                        <tr>
                            <th>@lang('#')</th>
                            <th>{{ __('Item Name') }}</th>
                            <th>{{ __('Department Name') }}</th>
                            <th>{{ __('Parts No') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Size') }}</th>
                            <th>{{ __('Stock Quantity') }}</th>
                            <th>{{ __('Req Quantity') }}</th>
                            <th>{{ __('Last Purchase From') }}</th>
                            <th>{{ __('Purchase Rate') }}</th>
                            <th>{{ __('Remarks') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if(auth()->user()->hasRole('Admin'))
    <div class="card card-success">
        <div class="card-header">
            <h3>Complete Purchase Order</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('purchase-order.confirm',$purchaseOrder->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-success">Mark as Complete</button>
            </form>
        </div>
    </div>
    @endif
@endsection
@section('extra-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(function() {

            $('.select2').select2();

            function clearAutoData(){
                $('#pNumber').html('-');
                $('#lastPurchaseFrom').html('-');
                $('#purchaseRate').html('-');
                $('#quantityInStock').html('-');
                $('#size').html('-');
            }

            let table = $("#primaryTable").DataTable({
                serverSide: false,
                processing: true,
                ajax: {
                    url: "{{ route('purchase-order.item.index',$purchaseOrder->id) }}",
                    data: function(data) {
                        // data.start_date = $("#start_date").val(),
                    },
                },
                columns: [{
                        name: "DT_RowIndex",
                        data: "DT_RowIndex",
                        orderable:false,
                        sortable:false,
                    },
                    {
                        name: "item_name",
                        data: "item_name"
                    },
                    {
                        name: "storein_department_id",
                        data: "storein_department_id"
                    },
                    {
                        name: "parts_number",
                        data: "parts_number"
                    },
                    {
                        name: "category",
                        data: "category"
                    },
                    {
                        name: "size",
                        data: "size"
                    },
                    {
                        name: "stock_quantity",
                        data: "stock_quantity"
                    },
                    {
                        name: "req_quantity",
                        data: "req_quantity"
                    },
                    {
                        name: "last_purchase_from",
                        data: "last_purchase_from"
                    },
                    {
                        name: "purchase_rate",
                        data: "purchase_rate"
                    },
                    {
                        name: "remarks",
                        data: "remarks"
                    },
                    {
                        name: "action",
                        data: "action"
                    },
                ]
            });

            $('#addNew').on('click', function() {
                $('#addModal').modal('show');
            });

            $('#addData').on('submit', function(e) {
                e.preventDefault();
                let url = $(this).attr('action');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function() {
                        console.log('ajax fired');
                    },
                    success: function(data) {
                        if (data.status == true) {
                            table.ajax.reload();
                        } else {
                            swal.fire("Failed!", 'Adding Data Failed', "error");
                        }
                        $('.select2').select2();
                    },
                    error: function(xhr) {
                        var i = 0;
                        $('.help-block').remove();
                        $('.has-error').removeClass('has-error');
                        for (var error in xhr.responseJSON.errors) {
                            $('#add_' + error).removeClass('has-error');
                            $('#add_' + error).addClass('has-error');
                            $('#error_' + error).html(
                                '<span class="help-block ' + error + '">*' + xhr
                                .responseJSON.errors[
                                    error] + '</span>');
                            i++;
                        }
                    }
                });
            });

            $(document).on('click','.delete_item', function(e) {
                e.preventDefault();
                let url = $(this).data('url');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    type: "DELETE",
                    data: {},
                    beforeSend: function() {
                        console.log('ajax fired');
                    },
                    success: function(data) {
                        if (data.status == true) {
                            table.ajax.reload();
                        } else {
                            swal.fire("Failed!", 'Adding Data Failed', "error");
                        }
                        $('.select2').select2();
                    },
                    error: function(xhr) {
                        var i = 0;
                        $('.help-block').remove();
                        $('.has-error').removeClass('has-error');
                        for (var error in xhr.responseJSON.errors) {
                            $('#add_' + error).removeClass('has-error');
                            $('#add_' + error).addClass('has-error');
                            $('#error_' + error).html(
                                '<span class="help-block ' + error + '">*' + xhr
                                .responseJSON.errors[
                                    error] + '</span>');
                            i++;
                        }
                    }
                });
            });

            $('#StoreInDepartment').on('change',function(){
                let id = $(this).val();
                let url = "{{ route('purchase-order.item.getStoreInItems') }}";
                $.ajax({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    type: "POST",
                    data: {
                        "id":id
                    },
                    beforeSend:function(){
                        console.log('ajax fired');
                    },
                    success: function (data) {
                            clearAutoData();
                            $('#itemName').empty();
                            $("#itemName").append('<option value="" selected="selected">Select Item</option>');
                            $.each(data.data, function (key, value) {
                                $("#itemName").append('<option value="' + value.id + '">' + value.name+ ' - '+ value.size.name + '</option>');
                            });
                            $('.select2').select2();
                    },
                    error:function(xhr){
                        console.log(xhr);
                    }
                });

            });

            $('#categoryId').on('change',function(){
                let id = $(this).val();
                let url = "{{ route('purchase-order.item.getStoreInDepartments') }}";
                $.ajax({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    type: "POST",
                    data: {
                        "id":id
                    },
                    beforeSend:function(){
                        console.log('ajax fired');
                    },
                    success: function (data) {
                            clearAutoData();
                            $('#itemName').empty();
                            $("#itemName").append('<option value="" selected="selected">Select Item</option>');
                            $('#StoreInDepartment').empty();
                            $("#StoreInDepartment").append('<option value="" selected="selected">Select Department</option>');
                            $.each(data.data, function (key, value) {
                                $("#StoreInDepartment").append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                            $('.select2').select2();
                    },
                    error:function(xhr){
                        console.log(xhr);
                    }
                });

            });

            $('#itemName').on('change',function(){
                let id = $(this).val();
                let url = "{{ route('purchase-order.item.getItemDetails') }}";
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    type: "POST",
                    data: {
                        "id":id
                    },
                    beforeSend:function(){
                        console.log('ajax fired');
                    },
                    success: function (data) {
                            if(data.data.pnumber){
                                $('#pNumber').html(data.data.pnumber)
                            }else{
                                $('#pNumber').html('-')
                            }

                            if(data.data.size.name){
                                $('#size').html(data.data.size.name)
                            }else{
                                $('#size').html('-')
                            }

                            if(data.stock){
                                $('#quantityInStock').html(data.stock.quantity);
                            }else{
                                $('#quantityInStock').html('-');
                            }

                            if(data.lastPurchaseItem){
                                $('#lastPurchaseFrom').html(data.lastPurchaseItem.store_in.supplier.name)
                            }else{
                                $('#lastPurchaseFrom').html('-')
                            }

                            if(data.lastPurchaseItem){
                                $('#purchaseRate').html(data.lastPurchaseItem.price)
                            }else{
                                $('#purchaseRate').html('-')
                            }

                            $('.select2').select2();
                    },
                    error:function(xhr){
                        console.log(xhr);
                    }
                });

            });


        });
    </script>
@endsection
