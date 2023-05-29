@extends('layouts.admin')

@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
@endsection

@section('content')
    @if (session('message'))
        <div id="alert-message" class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    <div class="card-body p-0">
        <form id="createStoreInItem">
            @csrf
            <div class="row">

                <div class="col-md-3 form-group">
                    <label for="categoryName" class="col-form-label">{{ __('Category') }}<span
                            class="required-field">*</span>
                    </label>
                    <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                        data-target="#exampleModalcat" style="margin-top:0 !important; top:0;float:right;">
                        <i class="fas fa-plus" style="display:flex;align-items: center;justify-content: center;"></i>
                    </a>
                    <select class="advance-select-box form-control @error('categoryName') is-invalid @enderror"
                        id="categoryName" name="categoryName">
                        <option value="" selected disabled>{{ __('Select a Category ') }}</option>
                        {{-- @foreach ($categories as $key => $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach --}}
                    </select>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-3 form-group">
                    <label for="products" class="col-form-label">{{ __('Item Name') }}<span class="required-field">*</span>
                    </label>
                    <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                        data-target="#exampleModaltype" style="margin-top:0 !important; top:0;float:right;">
                        <i class="fas fa-plus" style="display:flex;align-items: center;justify-content: center;"></i>
                    </a>
                    <select class="advance-select-box form-control @error('ProductName') is-invalid @enderror"
                        id="ProductName" name="ProductName">
                        <option value="" selected disabled>{{ __('Select a Product') }}</option>
                        {{-- @foreach ($items as $key => $processpro)
                            <option value="{{ $processpro->id }}">{{ $processpro->item }}</option>
                        @endforeach --}}
                    </select>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-3 form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="qunatities" class="col-form-label">{{ __('Quantity') }}<span
                                    class="required-field">*</span></label>
                            <input type="number" step="any" min="0"
                                class="form-control @error('qunatities') is-invalid @enderror calculator" id="qunatities-1"
                                data-number="1" name="quantities" placeholder="{{ __('Quantity') }}" min="1">
                            @error('qunatities')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="units" class="col-form-label">{{ __('Unit') }}<span
                                    class="required-field">*</span>
                            </label>
                            <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                                data-target="#exampleModalunit" style="margin-top:0 !important; top:0;float:right;">
                                <i class="fas fa-plus"
                                    style="display:flex;align-items: center;justify-content: center;"></i>
                            </a>
                            <select class="form-control @error('units') is-invalid @enderror" name="units" id="units">
                                <option value="" disabled>{{ __('Select a unit') }}</option>
                                {{-- @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach --}}
                            </select>
                            @error('units')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-md-3 form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="unitPrices[]" class="col-form-label">{{ __('Unit Price') }}<span
                                    class="required-field">*</span></label>
                            <input type="number" step="any" min="0"
                                class="form-control @error('unitPrices') is-invalid @enderror calculator" id="unitPrices-1"
                                data-number="1" name="unitPrices" placeholder="{{ __('Unit Price') }}" min="1">
                            @error('unitPrices')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="discounts[]" class="col-form-label">{{ __('Discount') }}(%)</label>
                            <input type="number" step="any" min="0" max="99"
                                class="form-control @error('discounts') is-invalid @enderror calculator" id="discounts-1"
                                data-number="1" name="discounts" placeholder="{{ __('Discount') }}">
                            @error('discounts')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                </div>

                <div class="col-md-3 form-group">
                    <label for="size" class="col-form-label">{{ __('Size') }}<span
                            class="required-field">*</span>
                    </label>
                    <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                        data-target="#exampleModalsize" style="margin-top:0 !important; top:0;float:right;">
                        <i class="fas fa-plus" style="display:flex;align-items: center;justify-content: center;"></i>
                    </a>
                    {{-- recent --}}
                    <select class="advance-select-box form-control @error('size') is-invalid @enderror" id="size_id"
                        name="size_id">
                        <option value="" selected disabled>{{ __('Select a Size ') }}</option>
                        {{-- @foreach ($sizes as $size)
                            <option value="{{ $size->id }}">{{ $size->name }}</option>
                        @endforeach --}}
                    </select>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-3 form-group">
                    <label for="singleTotal[]" class="col-form-label">{{ __('Total') }}</label>
                    <input type="text" step="any" min="0"
                        class="form-control @error('singleTotal') is-invalid @enderror" id="singleTotal-1"
                        name="singleTotal" placeholder="{{ __('Product Total') }}" readonly>
                </div>
                {{-- <a href="javascript:void(0);" class="col-md-1 add_button btn btn-primary dynamic-btn" title="Add More"
                onclick="addData();"><i class="fas fa-plus"></i></a> --}}
                <button type="submit" class="btn btn-primary">
                    Submit
                </button>
            </div>
        </form>
        {{-- table --}}
        <div class="row">
            <div class="Ajaxdata col-md-12">
                <div class="p-0 table-responsive table-custom my-3">
                    <table class="table" id="storeInItemTable">
                        <thead>
                            <tr>
                                <th>{{ __('S.No') }}</th>
                                <th>{{ __('Category Name') }}</th>
                                <th>{{ __('Item Name') }}</th>
                                <th>{{ __('Quantity') }}</th>
                                <th>{{ __('Size') }}</th>
                                <th>{{ __('Unit') }}</th>
                                <th>{{ __('Unit Price') }}</th>
                                <th>{{ __('Discount %') }}</th>
                                <th>{{ __('Discount') }}</th>

                                <th>{{ __('Total') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>

                        <tbody id="result" style="height: 90px;">
                        </tbody>

                    </table>

                    {{-- for sub total --}}
                    {{-- <input type="text" id="subTotal"> --}}
                </div>

            </div>
        </div>
        {{-- <form action="{{ route('storein.saveEntireStorein', ['storein_id' => $storein->id]) }}" method="POST"
            enctype="multipart/form-data">
            @csrf --}}
        <div class="row">
            <div class="col-md-3 form-group">
                <label for="subTotal" class="col-form-label">{{ __('Discounted Total') }}</label>
                <input type="number" step="any" min="0"
                    class="form-control @error('subTotal') is-invalid @enderror" id="discountedTotal"
                    name="discountedTotal" placeholder="{{ __('Discounted Total') }}" readonly>
                @error('discountedTotal')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-3 form-group">
                <label for="totalDiscount" class="col-form-label">{{ __('Total Discount') }}</label>
                <input type="number" step="any" min="0"
                    class="form-control @error('totalDiscount') is-invalid @enderror" id="totalReceivedDiscount"
                    name="totalDiscount" placeholder="{{ __('Total Discount') }}" readonly>
                @error('totalDiscount')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-3 form-group">
                <label for="transportCost" class="col-form-label">{{ __('Freight Cost') }}</label>
                <input type="number" step="any" min="0"
                    class="form-control @error('transportCost') is-invalid @enderror" id="transport_cost"
                    name="transportCost" placeholder="{{ __('Frieght Cost') }}">
                @error('transportCost')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-3 form-group">
                <label for="total" class="col-form-label">{{ __('Grand Total') }}</label>
                <input type="number" step="any" min="0"
                    class="form-control @error('total') is-invalid @enderror" id="grand_total" name="total"
                    placeholder="{{ __('Grand Total') }}" readonly>
                @error('total')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 form-group">
                <label for="tax" class="col-form-label">{{ __('Tax Type') }}
                </label>
                <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                    data-target="#exampleModaltax" style="margin-top:0 !important; top:0;float:right;">
                    <i class="fas fa-plus" style="display:flex;align-items: center;justify-content: center;"></i>
                </a>
                <select class="advance-select-box form-control @error('tax') is-invalid @enderror" id="tax"
                    name="tax">
                    <option value="" selected disabled>{{ __('Select a tax ') }}</option>
                    {{-- @foreach ($taxes as $tax)
                        <option value="{{ $tax->slug }}">{{ $tax->tax_type }}</option>
                    @endforeach --}}
                </select>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-3 form-group">
                <label for="totalPayment" class="col-form-label">{{ __('Total Paid') }}<span
                        class="required-field">*</span></label>
                <input type="number" step="any" min="0"
                    class="form-control @error('totalPayment') is-invalid @enderror" id="totalPayment"
                    name="totalPayment" value="{{ old('totalPayment') }}" placeholder="{{ __('Total Payment') }}">
                @error('totalPayment')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-3 form-group">
                <label for="total" class="col-form-label">{{ __('Due Amount') }}</label>
                <input type="number" step="any" min="0"
                    class="form-control @error('due_amount') is-invalid @enderror" id="due_amount" name="due_amount"
                    placeholder="{{ __('Due Total') }}" readonly>
                @error('due_amount')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-3 form-group">
                <label for="paymentMethod" class="col-form-label">{{ __('Payment Method') }}</label>
                <select class="advance-select-box form-control" id="paymentMethod" name="paymentMethod">
                    <option value="" selected disabled>{{ __('Select a payment method') }}
                    </option>
                    {{-- @foreach ($paymentMethods as $method)
                        <option value="{{ $method->id }}">{{ $method->name }}</option>
                    @endforeach --}}
                </select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <label for="note" class="col-form-label">{{ __('Storein Note') }}</label>
                <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note"
                    placeholder="{{ __('Purchase Note') }}">{{ old('note') }}</textarea>
                @error('note')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="purchaseImage" class="col-form-label">{{ __('Storein Image') }}</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input @error('purchaseImage') is-invalid @enderror"
                        id="attached-image" name="purchaseImage">
                    <label class="custom-file-label" for="customFile">{{ __('Choose file') }}</label>
                    @error('purchaseImage')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="image-preview">
                    <img src="" id="attached-preview-img" class="mt-3" />
                </div>
            </div>
            <div class="col-md-6 form-group">
                <label for="status" class="col-form-label">{{ __('Status') }}</label>
                <select class="form-control" id="status" name="status">
                    <option value="1">{{ __('Active') }}</option>
                    <option value="0">{{ __('Inactive') }}</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-10">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                    {{ __('Save Purchase') }}</button>
            </div>
        </div>
        {{-- </form> --}}
    </div>
    {{-- editItemsMOdal --}}
    <div class="modal fade" id="editItemModal" tabindex="-1" role="dialog" aria-labelledby="exampleModaltax"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModaltax">Edit StoreinItem</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editStoreinItem">
                        {{-- action="{{ route('storein.EditItemStoreData') }}" method="POST"
                        enctype="multipart/form-data" --}}
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <input type="text" id="storeinItem_id" name="storeInItem_id" hidden>
                                <input type="text" id="storein_id" name="storeIn_id" hidden>
                                {{-- storein_id --}}
                                <div class="form-group col-md-12">
                                    <label for="categorySelect" class="form-label" style="font-weight: bold;">Category
                                        Name</label>
                                    <select class="form-control" name="category_id" id="categoryitem_id">
                                        {{-- @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">
                                                {{ $category->name }}</option>
                                        @endforeach --}}

                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="itemSelect" class="col-form-label" style="font-weight: bold;">Item
                                        Name</label>
                                    <select class="form-control" name="item_id" id="item_id">
                                        {{-- @foreach ($items as $item)
                                            <option value="{{ $item->id }}">{{ $item->item }}</option>
                                        @endforeach --}}

                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="quantity" class="form-label" style="font-weight: bold;">Quantity</label>
                                    <input type="number" id="quantity" name="quantity" required="true" min="1"
                                        class="form-control" />
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="iUnit" class="form-label" style="font-weight: bold;">Unit</label>
                                    <select class="form-control" name="unit_id" id="unit_id">
                                        {{-- @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach --}}

                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="iUnitPrice" class="form-label" style="font-weight: bold;">Unit
                                        Price</label>
                                    <input type="number" id="price" name="unitPrice" required="true"
                                        min="1" class="form-control" />
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="discountPercent" class="form-label" style="font-weight: bold;">Discount
                                        %</label>
                                    <input type="number" id="discountPercent" name="discount_percent"
                                        class="form-control" />
                                </div>
                                <div class="form-group col-md-12">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>

                </div> --}}
            </div>
        </div>
    </div>
    {{-- edit item modal end --}}
    <!--Category Model popup-->

    <div class="modal fade" id="exampleModalcat" tabindex="-1" role="dialog" aria-labelledby="exampleModalcat"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalcat">Add Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="{{ route('categories.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">

                                <div class="form-group col-md-12">
                                    <label for="name">{{ __('Category Name') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" placeholder="{{ __('Category Name') }}"
                                        value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="note" class="col-form-label">{{ __('Category Note') }}</label>
                                    <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note"
                                        placeholder="{{ __('Category Note') }}">{{ old('note') }}</textarea>
                                    @error('note')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Inactive') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                                        {{ __('Save Category') }}</button>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                </div>
            </div>
        </div>
    </div>

    <!--Category  Model Popup End-->
    <!--Item Model popup-->

    <div class="modal fade" id="exampleModaltype" tabindex="-1" role="dialog" aria-labelledby="exampleModalitem"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalitem">Add Items</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="{{ route('items.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="item">{{ __('Items Name') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="item" name="item" placeholder="{{ __('Items Name') }}"
                                        value="{{ old('item') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="item">{{ __('Product Number') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('pnumber') is-invalid @enderror"
                                        id="pnumber" name="pnumber" placeholder="{{ __('Product No') }}"
                                        value="{{ old('pnumber') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group mt-3">
                                    <label for="categoryName">{{ __('Category Name') }}<span
                                            class="required-field">*</span></label>
                                    <select
                                        class="advance-select-box form-control @error('categoryName') is-invalid @enderror"
                                        id="categoryName" name="categoryName" required>
                                        <option value="" selected disabled>{{ __('Select a category') }}</option>
                                        {{-- @foreach ($categories as $key => $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach --}}
                                    </select>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group mt-3">
                                    <label for="subcategory">{{ __('Sub Category') }}<span
                                            class="required-field">*</span></label>
                                    <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                                        data-target="#exampleModalsubcat"
                                        style="margin-top:-5px !important; top:0;float:right;">
                                        <i class="fas fa-plus"
                                            style="display:flex;align-items: center;justify-content: center;"></i>
                                    </a>
                                    <select
                                        class="advance-select-box form-control @error('subcategory') is-invalid @enderror"
                                        id="subcategory" name="subcategory" required>
                                        <option value="" selected disabled>{{ __('Select a category') }}</option>
                                        {{-- @foreach ($SubCategory as $key => $subCategory)
                                            <option value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                        @endforeach --}}
                                    </select>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>



                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="supplier" class="col-form-label">
                                        {{ __('Supplier') }}
                                    </label>
                                    <select
                                        class="advance-select-box form-control @error('supplier') is-invalid @enderror"
                                        id="supplier" name="supplier">
                                        <option value="" selected disabled>{{ __('Select a supplier') }}</option>
                                        {{-- @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach --}}
                                    </select>
                                    @error('supplier')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>
                                <div class="form-group col-md-6">
                                    <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Inactive') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                                        {{ __('Save Items') }}</button>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                </div>
            </div>
        </div>
    </div>

    <!--Item Model Popup End-->
    <!--SubCategory Model popup-->

    <div class="modal fade" id="exampleModalsubcat" tabindex="-1" role="dialog" aria-labelledby="exampleModalsubcat"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalsubcat">Add Sub Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="{{ route('subCategories.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="name">{{ __('Sub Category Name') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" placeholder="{{ __('Sub Category Name') }}"
                                        value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group mt-3">
                                    <label for="categoryName">{{ __('Category Name') }}<span
                                            class="required-field">*</span></label>
                                    <select
                                        class="advance-select-box form-control @error('categoryName') is-invalid @enderror"
                                        id="categoryName" name="categoryName" required>
                                        <option value="" selected disabled>{{ __('Select a category') }}</option>
                                        {{-- @foreach ($categories as $key => $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach --}}
                                    </select>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="sizes" class="col-form-label">{{ __('Sizes') }}<span
                                            class="required-field">*</span></label>
                                    <select class="advance-select-box form-control @error('sizes') is-invalid @enderror"
                                        name="sizes[]" multiple="multiple"
                                        data-placeholder="{{ __('Select multiple sizes') }}" required>
                                        {{-- @foreach ($sizes as $size)
                                            <option value="{{ $size->code }}">
                                                {{ $size->name }}({{ $size->code }})</option>
                                        @endforeach --}}
                                    </select>
                                    @error('sizes')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="note" class="col-form-label">{{ __('Sub Category Note') }}</label>
                                    <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note"
                                        placeholder="{{ __('Sub Category Note') }}">{{ old('note') }}</textarea>
                                    @error('note')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Inactive') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                                        {{ __('Save Sub Category') }}</button>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                </div>
            </div>
        </div>
    </div>

    <!--SubCategory  Model Popup End-->
    <!--Unit Model popup-->

    <div class="modal fade" id="exampleModalunit" tabindex="-1" role="dialog" aria-labelledby="exampleModalunit"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalunit">Add Unit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="{{ route('units.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="name">{{ __('Unit Name') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" placeholder="{{ __('Unit Name') }}"
                                        value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="unitCode">{{ __('Unit Code') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('unitCode') is-invalid @enderror"
                                        id="unitCode" name="unitCode" placeholder="{{ __('Unit Code') }}"
                                        value="{{ old('unitCode') }}" required>
                                    @error('unitCode')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="note" class="col-form-label">{{ __('Unit Note') }}</label>
                                    <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note"
                                        placeholder="{{ __('Unit Note') }}">{{ old('note') }}</textarea>
                                    @error('note')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Inactive') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                                        {{ __('Save Unit') }}</button>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                </div>
            </div>
        </div>
    </div>

    <!--Unit  Model Popup End-->
    <!--Size Model popup-->

    <div class="modal fade" id="exampleModalsize" tabindex="-1" role="dialog" aria-labelledby="exampleModalsize"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalsize">Add Size</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="{{ route('sizes.store') }}" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="name">{{ __('Size Name') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" placeholder="{{ __('Size Name') }}"
                                        value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="sizeCode">{{ __('Size Code') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('sizeCode') is-invalid @enderror"
                                        id="sizeCode" name="sizeCode" placeholder="{{ __('Size Code') }}"
                                        value="{{ old('sizeCode') }}" required>
                                    @error('sizeCode')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="note" class="col-form-label">{{ __('Size Note') }}</label>
                                    <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note"
                                        placeholder="{{ __('Size Note') }}">{{ old('note') }}</textarea>
                                    @error('note')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Inactive') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                                        {{ __('Save Size') }}</button>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                </div>
            </div>
        </div>
    </div>

    <!--Size  Model Popup End-->

    <!--Tax Model popup-->

    <div class="modal fade" id="exampleModaltax" tabindex="-1" role="dialog" aria-labelledby="exampleModaltax"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModaltax">Add Size</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="{{ route('tax.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="name">{{ __('Tax Type') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" placeholder="{{ __('Tax Type') }}"
                                        value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="unitCode">{{ __('Tax %') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('unitCode') is-invalid @enderror"
                                        id="tax" name="tax" placeholder="{{ __('Tax %') }}"
                                        value="{{ old('tax') }}" required>
                                    @error('unitCode')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Inactive') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                                        {{ __('Save Unit') }}</button>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                </div>
            </div>
        </div>
    </div>

    <!--Tax  Model Popup End-->
@endsection

@section('extra-script')
@endsection
