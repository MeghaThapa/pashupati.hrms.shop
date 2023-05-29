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
                        id="categoryName" name="categoryName" required>
                        <option value="" selected disabled>{{ __('Select a Category ') }}</option>
                        @foreach ($categories as $key => $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('categoryName')
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
                        id="ProductName" name="ProductName" required>
                        <option value="" selected disabled>{{ __('Select a Product') }}</option>
                        @foreach ($items as $key => $processpro)
                            <option value="{{ $processpro->id }}">{{ $processpro->item }}</option>
                        @endforeach
                    </select>
                    @error('ProductName')
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
                                data-number="1" name="quantities" placeholder="{{ __('Quantity') }}" min="1" required>
                            @error('quantities')
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
                            <select class="form-control @error('units') is-invalid @enderror" name="units" id="units" required>
                                <option value="" disabled>{{ __('Select a unit') }}</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
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
                                data-number="1" name="unitPrices" placeholder="{{ __('Unit Price') }}" min="1" required>
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
                        name="size_id" required>
                        <option value="" selected disabled>{{ __('Select a Size ') }}</option>
                        @foreach ($sizes as $size)
                            <option value="{{ $size->id }}">{{ $size->name }}</option>
                        @endforeach
                    </select>
                    @error('size_id')
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
                <button type="submit" class="btn btn-primary" style="margin-top: 40px; margin-button:40px !important;">
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
        <form action="{{ route('storein.saveEntireStorein', ['storein_id' => $storein->id]) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
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
                        @if ($storein->transport_cost) value="{{ $storein->transport_cost }}" @endif
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
                        @if ($storein->grand_total) value="{{ $storein->grand_total }}" @endif
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
                        @foreach ($taxes as $tax)
                            <option value="{{ $tax->slug }}"
                                @if ($storein->tax) {{ $storein->tax->slug == $tax->slug ? 'selected' : '' }} @endif>

                                {{ $tax->tax_type }}</option>
                        @endforeach
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
                    <input type="number" step="any" min="0" @if ($storein->paid_amount)
                    value="{{$storein->paid_amount}}"
                    @endif
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
                        @foreach ($paymentMethods as $method)
                            <option value="{{ $method->id }}">{{ $method->name }}</option>
                        @endforeach
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
        </form>
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
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">
                                                {{ $category->name }}</option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="itemSelect" class="col-form-label" style="font-weight: bold;">Item
                                        Name</label>
                                    <select class="form-control" name="item_id" id="item_id">
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}">{{ $item->item }}</option>
                                        @endforeach

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
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach

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
                                        @foreach ($categories as $key => $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
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
                                        @foreach ($SubCategory as $key => $subCategory)
                                            <option value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                        @endforeach
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
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
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
                                        @foreach ($categories as $key => $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
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
                                        @foreach ($sizes as $size)
                                            <option value="{{ $size->code }}">
                                                {{ $size->name }}({{ $size->code }})</option>
                                        @endforeach
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
                    <form class="form-horizontal"
                    {{-- @if ()

                    @else

                    @endif --}}
                    action="{{ route('units.store') }}" method="post"
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
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/storein.js') }}"></script>
    <script>
        setTimeout(function() {
            var alertMessage = document.getElementById('alert-message');
            if (alertMessage) {
                alertMessage.remove();
            }
        }, 3000);
    </script>

    <script>

    document.getElementById('categoryName').addEventListener('change', function(event) {
        var category_id = event.target.value;
        getCategoryItems(category_id);
        console.log(category_id);
   });
   function getCategoryItems(category_id){
    $.ajax({
                    url: "{{ route('storein.getcategoryItems', ['category_id' => ':Replaced']) }}".replace(
                        ':Replaced',
                        category_id),

                    method: 'GET',
                    success: function(response) {
                         console.log(response);
                        var selectOptions = '';
                        if(response.length==0){
                            selectOptions += '<option disabled selected>' + 'no items found'+ '</option>';
                        }else{
                            for (var i = 0; i < response.length; i++) {
                                selectOptions += '<option value="' + response[i].id + '">' + response[i].item + '</option>';
                            }
                        }
                            $('#ProductName').html(selectOptions);
                    },
                    error: function(xhr, status, error) {
                        reject(error);
                    }
                });
   }
    $(document).ready(function() {
        let sn = 1;
        let selectedPercentage = 0;
        setTimeout(loadWhenPageLoad, 2000);

       function loadWhenPageLoad(){
        let transport_cost = document.getElementById('transport_cost').value;
        // check whether transport cost is empty or not
            triggerTransport(transport_cost);
        }


        //Edit
        document.getElementById('editStoreinItem').addEventListener('submit', function(event) {
            event.preventDefault();
            const form = event.target;
            let storeinItem_id = form.elements['storeInItem_id'].value;
            let category_id = form.elements['category_id'].value;
            let product_id = form.elements['item_id'].value;
            let quantities = form.elements['quantity'].value;
            let unit_id = form.elements['unit_id'].value;
            let unit_price = form.elements['unitPrice'].value;
            let discount_percent = form.elements['discount_percent'].value;
            $.ajax({
                url: "{{ route('storein.EditItemStoreData') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    storein_item_id: storeinItem_id,
                    category_id: category_id,
                    product_id: product_id,
                    quantity: quantities,
                    unit_id: unit_id,
                    unit_price: unit_price,
                    discount_percentage: discount_percent
                    // Goes Into Request
                },
                success: function(response) {
                    // Hiding Model
                    // console.log(response);
                    $('#editItemModal').modal('hide');

                    // Updating changes into table row
                    updateTableRow(response, storeinItem_id);
                    // after data is updated calculation of total amt
                    totalAmountCalculation();
                },
                error: function(xhr, status, error) {
                    // handle error response here
                }
            });
        });
        // trigger transport cost input field
        $('#transport_cost').on('blur', function(event) {

            let transport_cost = $(this).val();
            triggerTransport(transport_cost);
        });
         function triggerTransport(transport_cost){
            if (transport_cost == null || transport_cost == '') {
                calculateVatAmount();
                return false;
            }
            let selectTax = document.getElementById("tax");
            let grandTotal = document.getElementById('grand_total').value;
            let discountedTotal = document.getElementById('discountedTotal').value;
            let dueAmount = document.getElementById('due_amount');


            // when vat and transport cost is selected
            if (selectTax.selectedIndex > 0 && transport_cost ) {
                calculateVatAmount();
            }
            //tax not selected and transport cost is selected
            else if (selectTax.selectedIndex ==0  && transport_cost) {
                let total_amount = parseInt(discountedTotal) + parseInt(transport_cost);
                document.getElementById('grand_total').value = total_amount;
                dueAmount.value = total_amount;
            }
         }

        // trigger event when total paid is there
        $('#totalPayment').on('blur', function(event) {
            let total_Paid = $(this).val();
            calculateTotalPayment(total_Paid);
        });

       function calculateTotalPayment(total_Paid){
            let grand_total = document.getElementById('grand_total').value;
            let due_amount = parseInt(grand_total) - parseInt(total_Paid);
            if (due_amount < 0) {
                alert('You Cannot pay more than grand total amount');
                return false;
            }
            document.getElementById('due_amount').value = due_amount;
        }


        // recent
        $('#tax').on('change', async function() {
            calculateVatAmount();
        });

        async function calculateVatAmount() {
            slugVal = document.getElementById('tax').value;

            // when $slugVal is not assign
            if (slugVal == null || !slugVal) {
                $discount_total = document.getElementById('discountedTotal').value;
                document.getElementById('grand_total').value = $discount_total;
                return false;
            }
            let taxPercent = await getVatPercentage(slugVal);
            let discountTotal = document.getElementById('discountedTotal').value;
            let taxAmount = (parseInt(discountTotal) * parseInt(taxPercent)) / 100;
            let dueAmount = document.getElementById('due_amount');

            let transport_cost = document.getElementById('transport_cost').value;
            if (transport_cost == '' || transport_cost == null) {
                let amount = parseInt(discountTotal) + parseInt(taxAmount);
                document.getElementById('grand_total').value = amount;
                dueAmount.value = amount;
            } else {
                let amount = parseInt(discountTotal) + parseInt(taxAmount) + parseInt(transport_cost);
                document.getElementById('grand_total').value = amount;
                dueAmount.value = amount;
            }

            let totalPayment = document.getElementById('totalPayment').value;
            calculateTotalPayment(totalPayment);
        }

        async function getVatPercentage(slug) {
            return new Promise(function(resolve, reject) {
                $.ajax({
                    url: "{{ route('tax.getPercentageBySlug', ['slug' => ':toBeReplaced']) }}".replace(
                        ':toBeReplaced',
                        slug),
                    method: 'GET',
                    success: function(response) {
                        resolve(response.percentage);
                    },
                    error: function(xhr, status, error) {
                        reject(error);
                    }
                });
            });

        }

        function totalAmountCalculation() {
            const myTable = document.getElementById("storeInItemTable");

            // Get the tbody element of the table
            const tbody = myTable.querySelector("tbody");

            // Get all the tr elements in the tbody
            const rows = tbody.querySelectorAll("tr");

            // Loop through the rows and do something with them
            let eachTotalAmount = 0;
            let eachDiscountAmount = 0;
            rows.forEach(row => {
                eachTotalAmount = parseInt(eachTotalAmount) + parseInt(row.querySelector('td.rowTotalAmount')
                    .innerHTML);
                eachDiscountAmount = parseInt(eachDiscountAmount) + parseInt(row.querySelector(
                        'td.rowDiscountAmount')
                    .innerHTML);

            });

            document.getElementById('discountedTotal').value = eachTotalAmount;
            document.getElementById('grand_total').value = eachTotalAmount;
            document.getElementById('due_amount').value = eachTotalAmount;
            document.getElementById('totalReceivedDiscount').value = eachDiscountAmount;
        }


        // Updating Table tr td value when something changed or updated
        function updateTableRow(response, storeinItem_id) {
            // triggering table tr by storeinItem_id
            let row = document.getElementById('editRow-' + storeinItem_id);

            //Updating tds
            row.querySelector('td.rowCategoryName').innerHTML = response.category.name;
            row.querySelector('td.rowQuantity').innerHTML = response.quantity;
            row.querySelector('td.rowsize_id').innerHTML = response.size_id;
            row.querySelector('td.rowItemName').innerHTML = response.item.item;
            row.querySelector('td.rowUnitName').innerHTML = response.unit.name;
            row.querySelector('td.rowPrice').innerHTML = response.price;
            row.querySelector('td.rowDiscountPercentage').innerHTML = response.discount_percentage;
            row.querySelector('td.rowDiscountAmount').innerHTML = response.discount_amount;
            row.querySelector('td.rowTotalAmount').innerHTML = response.total_amount;

        }

        dataRetrive();

        //Creating New Storein Item and Adding into Table
        document.getElementById('createStoreInItem').addEventListener('submit', function(event) {
            event.preventDefault();
            const form = event.target;
            let size_id = form.elements['size_id'].value;
            let category_id = form.elements['categoryName'].value;
            let product_id = form.elements['ProductName'].value;
            let quantities = form.elements['quantities'].value;
            let unit_id = form.elements['units'].value;
            let unit_price = form.elements['unitPrices'].value;
            let discount = form.elements['discounts'].value;
            $.ajax({
                url: '{{ route('storein.saveStoreinItems', ['id' => $storein->id]) }}',
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    categoryName: category_id,
                    productName: product_id,
                    quantity: quantities,
                    units: unit_id,
                    unit_price: unit_price,
                    discount: discount,
                    size_id: size_id
                    // Goes Into Request
                },
                success: function(response) {
                    // console.log(response);
                    setIntoTable(response);
                    // // calculate total amount
                    totalAmountCalculation();
                },
                error: function(xhr, status, error) {
                    // handle error response here
                }
            });
        });

        // function for creating a new row in table
        function setIntoTable(res) {
            var html = "";

            html = "<tr id=editRow-" + res.id + "><td>" + sn + "</td><td class='rowCategoryName'>" + res.category.name +
                "</td><td class='rowItemName'>" + res.item
                .item +
                "</td><td class='rowQuantity'>" +
                res.quantity +
                "</td><td class='rowsize_id'>" +
                res.size.name +
                "</td><td class='rowUnitName'>" + res.unit.name + "</td><td class='rowPrice'>" + res.price +
                "</td><td class='rowDiscountPercentage'>" + res.discount_percentage +
                "</td><td class='rowDiscountAmount'>" + res.discount_amount +
                     "</td><td class='rowTotalAmount'>" +
                res.total_amount +
                "</td> <td>" +
                "<button class='btn btn-success editItemBtn' data-toggle='modal' data-target='#editItemModal' data-id=" +
                res.id + "><i class='fas fa-edit'></i></button>" +
                "  " +
                "<button class='btn btn-danger dltstoreinItem' data-id=" +
                res.id + " ><i class='fas fa-trash-alt'></i> </button>" + "</td ></tr>";

            document.getElementById('result').innerHTML += html;
            sn++;
            // Clearing the input fields
            clearInputFields();
        }

        function clearInputFields() {
            document.getElementById('categoryName').value = "";
            document.getElementById('ProductName').value = "";
            document.getElementById('qunatities-1').value = "";
            document.getElementById('size_id').value = "";
            document.getElementById('units').value = "";
            document.getElementById('unitPrices-1').value = "";
            document.getElementById('discounts-1').value = "";
            document.getElementById('singleTotal-1').value = "";
        }

        function removeAllTableRows() {
            // Reseting SN
            sn = 1;
            var tbody = document.querySelector("#storeInItemTable tbody");
            for (var i = tbody.rows.length - 1; i >= 0; i--) {
                tbody.deleteRow(i);
            }
        }

        // when page is refressed
        function dataRetrive() {
            removeAllTableRows();
            $.ajax({
                url: '{{ route('storein.storeInItemsRetrive', ['storein_id' => $storein->id]) }}',
                method: 'GET',
                success: function(response) {
                    console.log(response);
                    if (response.storein_items.length <= 0) {
                        return false;
                    }
                    response.storein_items.forEach(itemsRow => {
                        setIntoTable(itemsRow);


                    });
                    if (response.storein_items.length > 0) {
                        // alert('fghjk');
                        editEventBtn();
                        deleteEventBtn();

                    }
                    totalAmountCalculation();
                    // console.log(response);
                },
                error: function(xhr, status, error) {
                    // handle error response here
                }
            });
        }

        function deleteEventBtn() {
            let deleteButtons = document.getElementsByClassName('dltstoreinItem');
            console.log(deleteButtons);
            for (var i = 0; i < deleteButtons.length; i++) {
                deleteButtons[i].addEventListener('click', function(event) {
                    let itemId = this.getAttribute('data-id');
                    // console.log(itemId);
                    $.ajax({
                        url: '{{ route('storein.storeinItemDelete', ['id' => ':lol']) }}'
                            .replace(':lol', itemId),
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        method: 'DELETE',
                        success: function(response) {
                            dataRetrive();
                            alert('deleted')
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                        }
                    });


                });
            }
        }

        function editEventBtn() {
            // Assign event listener to buttons with class 'editItemBtn'
            let editButtons = document.getElementsByClassName('editItemBtn');
            for (var i = 0; i < editButtons.length; i++) {
                editButtons[i].addEventListener('click', function(event) {
                    let itemId = this.getAttribute('data-id');
                    // console.log(itemId);
                    $.ajax({
                        url: '{{ route('storein.getEditItemData', ['storeinItem_id' => ':lol']) }}'
                            .replace(':lol', itemId),
                        method: 'GET',
                        success: function(response) {
                            console.log('modelevent', response);
                            $('#storein_id').val(response.storein_id);
                            $('#storeinItem_id').val(response.id);
                            $('#categoryitem_id').val(response.category_id);
                            $('#item_id').val(response.item_id);
                            $('#quantity').val(response.quantity);
                            $('#unit_id').val(response.unit_id);
                            $('#price').val(response.price);
                            $('#total_amt').text('Total Amount: ' + response.total_amount);
                            $('#discountPercent').val(response.discount_percentage);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                        }
                    });


                });
            }
        }
    });
    </script>
@endsection
