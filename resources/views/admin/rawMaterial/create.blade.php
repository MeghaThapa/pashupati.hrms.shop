@extends('layouts.admin')
@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
@endsection
@section('content')
    @if ($errors->any())
        <div id="error-container">
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    <div class="card-body p-0 m-0">
        <div class="card">
            <div class="card-header">

                <h3 class="card-title">{{ __('Create Raw Material') }}</h3>

                <div class="card-tools">
                    <a href="{{ route('rawMaterial.index') }}" class="btn btn-block btn-primary">
                        <i class="fas fa-long-arrow-alt-left"></i> {{ __('Go Back') }}
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                {{-- <a href="{{ route('storein.storeinItemCreateLayout', ['storein_id' => 11]) }}">
                    <button class="btn btn-primary">
                        Layout
                    </button>
                </a> --}}

                <form
                    @if ($rawMaterial) action="{{ route('rawMaterial.update', ['rawMaterial_id' => $rawMaterial->id]) }}"
                    @else action="{{ route('rawMaterial.store') }}" @endif
                    method="POST">
                    @csrf
                    <div class="row p-2">
                        <div class="col-md-2 form-group">
                            <label for="Category" class="col-form-label">{{ __('Date') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="date" step="any" min="0" class="form-control calculator"
                                id="date" data-number="1" name="date" placeholder="{{ __('date') }}"
                                @if ($rawMaterial) value="{{ $rawMaterial->date }}" @else value="<?php echo date('Y-m-d'); ?>" @endif
                                min="1" required>
                            @error('date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        {{-- supplier --}}
                        <div class="col-md-4 form-group">
                            <label for="size" class="col-form-label">{{ __('Party Name') }}<span
                                    class="required-field">*</span>
                            </label>
                            {{-- <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal" tabindex="-1"
                                        data-target="#addSupplierModel" style="margin-top:0 !important; top:8px;float:right;">
                                        <i class="fas fa-plus" style="display:flex;align-items: center;justify-content: center;"></i>
                            </a> --}}
                            <select class="advance-select-box form-control @error('size') is-invalid @enderror"
                                id="supplierId" name="supplier_id" required>
                                <option value="" selected disabled>{{ __('Select a Supplier ') }}</option>
                                @foreach ($suppliers as $supplier)
                                    <option
                                        @if ($rawMaterial) {{ $supplier->id == $rawMaterial->supplier_id ? 'selected' : '' }} @endif
                                        value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            @error('size_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        {{-- storein type --}}
                        <div class="col-md-3 form-group">
                            <label for="size" class="col-form-label">{{ __('Storein Type') }}<span
                                    class="required-field">*</span>
                            </label>
                            <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                                tabindex="-1" data-target="#storeinTypeModel"
                                style="margin-top:0 !important; top:8px;float:right;">
                                <i class="fas fa-plus"
                                    style="display:flex;align-items: center;justify-content: center;"></i>
                            </a>
                            <select class="advance-select-box form-control" id="Type_id" name="Type_id" required>
                                <option value="" selected disabled>{{ __('Select a type ') }}</option>
                                @foreach ($storeinTypes as $storeinType)
                                    <option value="{{ $storeinType->id }}">{{ $storeinType->name }}</option>
                                @endforeach
                            </select>

                            @error('size_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        {{-- ppno --}}
                        <div class="col-md-3 form-group">
                            <label for="products" class="col-form-label">{{ __('PP No') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator"
                                id="ppNo" data-number="1" name="pp_no" placeholder="{{ __('PP No') }}"
                                @if ($rawMaterial) value="{{ $rawMaterial->pp_no }}" @endif min="1"
                                required>
                            @error('pp_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>




                    </div>
                    <div class="row">
                        {{-- bill no --}}
                        <div class="col-md-3 form-group">
                            <label for="products" class="col-form-label">{{ __('Bill No') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator"
                                id="billNo" data-number="1" name="bill_no" placeholder="{{ __('Bill No') }}"
                                @if ($rawMaterial) value="{{ $rawMaterial->bill_no }}" @endif min="1"
                                required>
                            @error('bill_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="size" class="col-form-label">{{ __('From Godam') }}
                            </label>
                            <select class="advance-select-box form-control" id="fromGodam" name="from_godam_id" required>
                                <option value="" selected disabled>{{ __('Select a godam') }}</option>
                                @foreach ($godams as $godam)
                                    <option value="{{ $godam->id }}">{{ $godam->name }}</option>
                                @endforeach
                            </select>
                            @error('size_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="size" class="col-form-label">{{ __('Challan No') }}
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator"
                                id="challanNo" data-number="1" name="challan_no" placeholder="{{ __('Challan No') }}"
                                min="1" required>
                            @error('size_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="size" class="col-form-label">{{ __('GP No') }}
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator"
                                id="gpNo" data-number="1" name="gp_no" placeholder="{{ __('GP No') }}"
                                min="1" required>
                            @error('gp_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label for="size" class="col-form-label">{{ __('To Godam') }}<span
                                    class="required-field">*</span>
                            </label>
                            <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                                tabindex="-1" data-target="#godamModel"
                                style="margin-top:0 !important; top:8px;float:right;">
                                <i class="fas fa-plus"
                                    style="display:flex;align-items: center;justify-content: center;"></i>
                            </a>
                            <select class="advance-select-box form-control" id="toGodamId" name="to_godam_id" required>
                                <option value="" selected disabled>{{ __('Select a type ') }}</option>
                                @foreach ($godams as $godam)
                                    <option
                                        @if ($rawMaterial) {{ $rawMaterial->to_godam_id == $godam->id ? 'selected' : '' }} @endif
                                        value="{{ $godam->id }}">{{ $godam->name }}</option>
                                @endforeach
                            </select>
                            @error('size_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-3 form-group">
                            <label for="size" class="col-form-label">{{ __('Receipt No') }}
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator"
                                id="ReceiptNo" data-number="1" name="Receipt_no"
                                @if ($rawMaterial) value="{{ $rawMaterial->receipt_no }}"@else value= "{{ $receipt_no }}" @endif
                                placeholder="{{ __('Receipt No') }}" min="1" required readonly>
                            @error('Receipt_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="size" class="col-form-label">{{ __('Remarks') }}
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator"
                                id="remarks" data-number="1" name="remarks"
                                @if ($rawMaterial) value="{{ $rawMaterial->remark }}" @endif
                                placeholder="{{ __('Remarks') }}" min="1">
                            @error('gp_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div style="margin-bottom:10px;">
                        <center>
                            <button type="submit" class="btn btn-primary">
                                @if ($rawMaterial)
                                    Update
                                @else
                                    Create
                                @endif

                            </button>
                        </center>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--Storein Type  Model popup-->
    <div class="modal fade" id="storeinTypeModel" tabindex="-1" role="dialog" aria-labelledby="exampleModaltax"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModaltax">Add Storein Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="modelFormStoreinType">
                    @csrf
                    <div class="modal-body">
                        <div class="card-body">
                            <div id="storeinTypeError" class="alert alert-danger" hidden>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="name">{{ __('name') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('placement') is-invalid @enderror"
                                        id="storeinType" name="storein_type_name" placeholder="{{ __('Placement') }}"
                                        value="{{ old('storein_type_name') }}" required>
                                    @error('storein_type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="department">{{ __('Code') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="code" name="code" placeholder="{{ __('Code') }}"
                                        value="{{ old('code') }}" required>
                                    @error('department')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="note">{{ __('Notes') }}</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="note" name="note">{{ old('note') }}</textarea>
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
                                        <option value="active">{{ __('Active') }}</option>
                                        <option value="inactive">{{ __('Inactive') }}</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <!-- /.card-body -->

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                            {{ __('Save Storein Type') }}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Close
                        </button>
                        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--Storein Type Model Popup End-->
    <!--Godam Model popup-->
    <div class="modal fade" id="godamModel" tabindex="-1" role="dialog" aria-labelledby="exampleModaltax"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModaltax">Add Godam</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="modelFormGodam">
                    @csrf
                    <div class="modal-body">
                        <div class="card-body">
                            <div id="godamError" class="alert alert-danger" hidden>
                            </div>
                            <div class="row">
                                <label for="name">{{ __('name') }}<span class="required-field">*</span></label>
                                <input type="text" class="form-control @error('placement') is-invalid @enderror"
                                    id="godamName" name="godam_name" placeholder="{{ __('godam name') }}"
                                    value="{{ old('name') }}" required>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="row">
                                <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                <select class="form-control" id="godamStatus" name="godam_status">
                                    <option value="active">{{ __('Active') }}</option>
                                    <option value="inactive">{{ __('Inactive') }}</option>
                                </select>
                            </div>

                        </div>
                        <!-- /.card-body -->

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                            {{ __('Save Storein Type') }}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Close
                        </button>
                        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--Godam Popup End-->

    {{-- supplier model popup --}}
    <div class="modal fade" id="addSupplierModel" tabindex="-1" role="dialog" aria-labelledby="addDepartmentModel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="supplierCreateModel">

                    <div class="modal-header">
                        <h5 class="modal-title" id="addDepartmentModel">Add Supplier</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="card-body">
                            <div id="supplierError" class="alert alert-danger" hidden></div>
                            <div class="row">

                                <div class="form-group col-md-12">

                                    <label for="name">{{ __('Supplier Name') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" placeholder="{{ __('Supplier Name') }}"
                                        value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="email" class="col-form-label">{{ __('Email Address') }}</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" placeholder="{{ __('Email Address') }}"
                                        value="{{ old('email') }}">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="phone" class="col-form-label">{{ __('Phone Number') }}</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" placeholder="{{ __('Phone Number') }}"
                                        value="{{ old('phone') }}">
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="company" class="col-form-label">{{ __('Company Name') }}<span
                                            class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('company') is-invalid @enderror"
                                        id="company" name="company" placeholder="{{ __('Company Name') }}"
                                        value="{{ old('company') }}" required>
                                    @error('company')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="designation" class="col-form-label">{{ __('Designation') }}</label>
                                    <input type="text" class="form-control @error('designation') is-invalid @enderror"
                                        id="designation" name="designation" placeholder="{{ __('Designation') }}"
                                        value="{{ old('designation') }}">
                                    @error('designation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="address">{{ __('Address') }}</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address"
                                        placeholder="{{ __('Address') }}">{{ old('address') }}</textarea>
                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="profilePic" class="col-form-label">{{ __('Profile Picture') }}</label>
                                    <div class="custom-file">
                                        <input type="file"
                                            class="custom-file-input @error('profilePic') is-invalid @enderror"
                                            id="attached-image" name="profilePic">
                                        <label class="custom-file-label"
                                            for="customFile">{{ __('Choose file') }}</label>
                                        @error('profilePic')
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
                                <div class="col-sm-6 ">

                                </div>
                                <div class="col-sm-6 ">

                                    <button type="submit" class="btn btn-primary float-right"><i
                                            class="fas fa-save"></i>
                                        {{ __('Save Supplier') }}</button>


                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                        Close
                                    </button>

                                </div>
                            </div>
                            <!-- /.card-body -->

                        </div>
                </form>
            </div>
        </div>
    </div>
    {{-- supplier model popup end --}}
@endsection
@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/storein.js') }}"></script>
    <script>
        $(document).ready(function() {

            refreshRawMaterial();

            function refreshRawMaterial() {
                disableFields();
                updateData();
            }

            function updateData() {
                let rawMaterial = JSON.parse(`{!! json_encode($rawMaterial) !!}`);
                if (!rawMaterial) {
                    return false;
                }
                toggleInputsByStoreInType(rawMaterial.storein_type.name, rawMaterial);
            }

            function disableFields() {
                let from_godam = document.getElementById('fromGodam');
                let challan_no = document.getElementById('challanNo');
                let gp_no = document.getElementById('gpNo');
                let bill_no = document.getElementById('billNo');
                let pp_no = document.getElementById('ppNo');

                pp_no.disabled = true;
                bill_no.disabled = true;
                from_godam.disabled = true;
                challan_no.disabled = true;
                gp_no.disabled = true;
            }

            $('#Type_id').on('select2:select', function(e) {
                let selectedName = e.params.data.text.replace(/\s/g, "");
                toggleInputsByStoreInType(selectedName);
            });

            function toggleInputsByStoreInType(selectedName = '', rawMaterial = null) {
                let from_godam = document.getElementById('fromGodam');
                let challan_no = document.getElementById('challanNo');
                let gp_no = document.getElementById('gpNo');
                let pp_no = document.getElementById('ppNo');
                let bill_no = document.getElementById('billNo');

                if (selectedName.toLowerCase() == 'godam') {
                    from_godam.disabled = false;
                    pp_no.disabled = true;
                    challan_no.disabled = false;
                    gp_no.disabled = false;
                    bill_no.disabled = true;

                    from_godam.required = true;
                    challan_no.required = true;
                    gp_no.required = true;
                    pp_no.required = false;
                    bill_no.required = false;
                    if (rawMaterial) {
                        $('#fromGodam').val(rawMaterial.from_godam_id).change();
                        challan_no.value = rawMaterial.challan_no;
                        gpNo.value = rawMaterial.gp_no;
                    }

                } else if (selectedName.toLowerCase() == 'local') {
                    from_godam.disabled = true;
                    challan_no.disabled = true;
                    gp_no.disabled = true;
                    pp_no.disabled = true;
                    bill_no.disabled = false;

                    from_godam.required = false;
                    challan_no.required = false;
                    gp_no.required = false;
                    pp_no.required = false;
                    bill_no.required = true;
                    if (rawMaterial) {
                        $('#fromGodam').val(rawMaterial.from_godam_id).change();
                        bill_no.value = rawMaterial.bill_no;
                    }

                } else if (selectedName.toLowerCase() == 'import') {
                    from_godam.disabled = true;
                    challan_no.disabled = true;
                    gp_no.disabled = true;
                    pp_no.disabled = false;
                    bill_no.disabled = true;

                    from_godam.required = false;
                    challan_no.required = false;
                    gp_no.required = false;
                    pp_no.required = true;
                    bill_no.required = false;
                    if (rawMaterial) {
                        $('#fromGodam').val(rawMaterial.from_godam_id).change();
                        pp_no.value = rawMaterial.pp_no;
                    }
                }
            }

            function setMessage(element_id, message) {
                let errorContainer = document.getElementById(element_id);
                errorContainer.hidden = false;
                errorContainer.innerHTML = message;
                setTimeout(function() {
                    errorContainer.hidden = true;
                }, 2000);
            }
            //supplier save
            document.getElementById('supplierCreateModel').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = e.target;
                let name = form.elements['name'];
                let email = form.elements['email'];
                let phone = form.elements['phone'];
                let company = form.elements['company'];
                let designation = form.elements['designation'];
                let address = form.elements['address'];
                //let profilePic = null;
                let status = form.elements['status'];
                if (!name.value && !status.value && !email.value && !phone.value && !designation.value) {
                    setMessage('supplierError', 'Please Fill out all fields')
                    return false;
                }
                $.ajax({
                    url: "{{ route('suppliers.store') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: name.value,
                        email: email.value,
                        phone: phone.value,
                        company: company.value,
                        designation: designation.value,
                        address: address.value,
                        // profilePic:profilePic.value,
                        status: status.value,
                    },
                    success: function(response) {
                        $('#addSupplierModel').modal('hide');
                        name.value = '';
                        let selectElement = document.getElementById('supplierId');
                        let option = document.createElement('option');
                        option.value = response.godam.id;
                        option.text = response.godam.name;
                        selectElement.append(option);
                    },
                    error: function(xhr, status, error) {
                        setMessage('supplierError', xhr.responseJSON.message);
                    }

                });
            });
            //Godam save
            document.getElementById('modelFormGodam').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = e.target;
                let name = form.elements['godam_name'];
                let status = form.elements['godam_status'];
                if (!name.value &&
                    !status.value) {
                    setMessage('godamError', 'Please Fill out all fields')
                    return false;
                }
                $.ajax({
                    url: "{{ route('godam.store') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: name.value,
                        status: status.value,
                    },
                    success: function(response) {
                        $('#godamModel').modal('hide');
                        name.value = '';
                        let selectElement = document.getElementById('toGodamId');
                        let option = document.createElement('option');
                        option.value = response.godam.id;
                        option.text = response.godam.name;
                        selectElement.append(option);
                    },
                    error: function(xhr, status, error) {
                        setMessage('storeinTypeError', xhr.responseJSON.message);
                    }

                });
            })

            //storein type save
            document.getElementById('modelFormStoreinType').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = e.target;
                let name = form.elements['storein_type_name'];
                let code = form.elements['code'];
                let note = form.elements['note'];
                let status = form.elements['status'];
                if (!name.value &&
                    !status.value && !code.value && !note.value) {
                    setMessage('storeinTypeError', 'Please Fill out all fields')
                    return false;
                }
                $.ajax({
                    url: "{{ route('storeinType.store') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: name.value,
                        code: code.value,
                        note: note.value,
                        status: status.value,
                    },
                    success: function(response) {

                        $('#storeinTypeModel').modal('hide');

                        name.value = '';
                        code.value = '';
                        note.value = '';
                        let selectElement = document.getElementById('Type_id');
                        let option = document.createElement('option');
                        option.value = response.storeinType.id;
                        option.text = response.storeinType.name;
                        selectElement.append(option);
                    },
                    error: function(xhr, status, error) {
                        setMessage('storeinTypeError', xhr.responseJSON.message);
                    }

                });

            });
            setTimeout(function() {
                $('#error-container').fadeOut('fast');
            }, 1000);
        });
    </script>
@endsection
