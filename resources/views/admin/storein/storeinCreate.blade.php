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
    @if ($errors->any())
        <div id="error-container" class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div>
        <div class="content-header mb-4">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    @if ($storeinData)
                        <h1 class="m-0 text-dark">{{ __('Edit Storein') }}</h1>
                    @else
                        <h1 class="m-0 text-dark">{{ __('Create Storein') }}</h1>
                    @endif

                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('storein.index') }}">{{ __('Storein') }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('Create Storein') }}</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="card">
                <div class="card-header">
                    @if ($storeinData)
                        <h3 class="card-title">{{ __('Edit Existing Storein') }}</h3>
                    @else
                        <h3 class="card-title">{{ __('Create New Storein') }}</h3>
                    @endif

                    <div class="card-tools">
                        <a href="{{ route('storein.index') }}" class="btn btn-block btn-primary">
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
                        @if ($storeinData) action="{{ route('storein.updateStorein', ['storein_id' => $storeinData->id]) }}"
                    @else
                    action="{{ route('storein.saveStorein') }}" @endif
                        method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="type" class="col-form-label">{{ __('Storein Type') }}<span
                                            class="required-field">*</span>
                                    </label>
                                     <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal" tabindex="-1"
                                        data-target="#storeinTypeModel" style="margin-top:0 !important; top:8px;float:right;">
                                        <i class="fas fa-plus" style="display:flex;align-items: center;justify-content: center;"></i>
                                    </a>
                                    {{-- @if()
                                    {{ $storeinData->storein_id }} --}}
                                    <select class="advance-select-box form-control @error('type') is-invalid @enderror"
                                        id="type" name="type" required focus>
                                        <option value="" selected disabled>{{ __('Select a store in type') }}
                                        </option>

                                        @foreach ($storeintype as $type)
                                            <option
                                                @if ($storeinData) {{ $type->id == $storeinData->storein_type_id ? 'selected' : '' }} @endif
                                                value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach

                                        {{-- <option value="">cvghjk</option> --}}
                                    </select>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-4 form-group">


                                    <label for="srno" class="col-form-label">{{ __('SR No.') }}</label>
                                    <input type="text" class="form-control @error('srno') is-invalid @enderror"
                                        id="srno" name="srno"
                                        @if ($storeinData) value="{{ $storeinData->sr_no }}" @endif>
                                    @error('srno')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="billno" class="col-form-label">{{ __('Bill No.') }}</label>
                                    <input type="text" class="form-control @error('billno') is-invalid @enderror"
                                        id="billno" name="billno"
                                        @if ($storeinData) value="{{ $storeinData->bill_no }}" @endif>
                                    @error('billno')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="ppno" class="col-form-label">{{ __('PP No.') }}</label>
                                    <input type="text" class="form-control @error('ppno') is-invalid @enderror"
                                        id="ppno" name="ppno"
                                        @if ($storeinData) value="{{ $storeinData->pp_no }}" @endif>
                                    @error('ppno')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="purchaseDate" class="col-form-label">{{ __('Date') }}<span
                                            class="required-field">*</span></label>
                                    <input type="date" class="form-control @error('purchaseDate') is-invalid @enderror"
                                        id="purchaseDate" name="purchaseDate"
                                        @if ($storeinData) value="{{ $storeinData->purchase_date }}" @endif
                                        required>
                                    @error('purchaseDate')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-4 form-group">

                                    <label for="supplier" class="col-form-label">{{ __('Supplier') }}<span
                                            class="required-field">*</span>
                                    </label>
                                    <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                                        data-target="#addSupplierModel" style="margin-top:0 !important; top:0;float:right;">
                                        <i class="fas fa-plus"
                                            style="display:flex;align-items: center;justify-content: center;"></i>
                                    </a>
                                    <select class="advance-select-box form-control @error('supplier') is-invalid @enderror"
                                        id="supplier" name="supplier" required>
                                        <option value="" selected disabled>{{ __('Select a supplier') }}</option>
                                        @foreach ($suppliers as $supplier)
                                            <option
                                                @if ($storeinData) {{ $supplier->id == $storeinData->supplier_id ? 'selected' : '' }} @endif
                                                value="{{ $supplier->id }}">
                                                {{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('supplier')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>
                            </div>
                            <div>
                                <center>

                                    <button type="submit" class="btn btn-primary">
                                        @if ($storeinData)
                                            {{ __('Update') }}
                                        @else
                                            {{ __('Create') }}
                                        @endif
                                    </button>
                                </center>
                            </div>
                        </div>
                    </form>
                </div>
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
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="note" name="note"
                                       >{{ old('note') }}</textarea>
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
    {{-- supplier model popup --}}
    <div class="modal fade" id="addSupplierModel" tabindex="-1" role="dialog" aria-labelledby="addDepartmentModel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" action="{{ route('suppliers.store') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addDepartmentModel">Add Supplier</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="card-body">
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
                                        <label class="custom-file-label" for="customFile">{{ __('Choose file') }}</label>
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

    document.getElementById('modelFormStoreinType').addEventListener('submit', function(e){
        e.preventDefault();
         const form = e.target;
          let name= form.elements['storein_type_name'];
          let code= form.elements['code'];
          let note= form.elements['note'];
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

                    name.value='';
                    code.value='';
                    note.value='';
                   let  selectElement=document.getElementById('type');
                    let option = document.createElement('option');
                    option.value=response.storeinType.id;
                    option.text=response.storeinType.name;
                    selectElement.append(option);
                },
                error:function(xhr, status, error){
                      setMessage('storeinTypeError',xhr.responseJSON.message);
                }

            });

    });
     function setMessage(element_id, message) {
            let errorContainer = document.getElementById(element_id);
            errorContainer.hidden = false;
            errorContainer.innerHTML = message;
            setTimeout(function() {
                errorContainer.hidden = true;
            }, 2000);
        }
    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
      });


        // steal focus during close - only capture once and stop propogation
        $('select.select2').on('select2:closing', function (e) {
        $(e.target).data("select2").$selection.one('focus focusin', function (e) {
            e.stopPropagation();
        });
        });
        $(document).ready(function() {
            let ppno_input = document.getElementById('ppno');
            let srno_input = document.getElementById('srno');
            let billno_input = document.getElementById('billno');
            ppno_input.disabled = true;
            srno_input.disabled = true;
            billno_input.disabled = true;

            let editObj =JSON.parse(`{!! json_encode($storeinData) !!}`);
            console.log(editObj);
            if(editObj){
                let typeName = editObj.storein_type.name.toLowerCase();
                console.log(typeName);
                if(typeName=='import'){
                     billno_input.disabled = true;
                     ppno_input.disabled = false;
                     srno_input.disabled = false;
                }
                else if(typeName=='local'){
                     ppno_input.disabled = true;
                    srno_input.disabled = false;
                    billno_input.disabled = false;
                }
                else if(typeName=='sapat'){
                    billno_input.disabled = false;
                    ppno_input.disabled = true;
                    srno_input.disabled = true;
                }

            }

            console.log(editObj)
           $('#type').focus();



            // Hide the error message after 5 seconds
            setTimeout(function() {
                $('#error-container').fadeOut('fast');
            }, 3000);
            $('#type').on('select2:select', function(e) {
                let selectedName = e.params.data.text.replace(/\s/g, "").toLowerCase();
                if (selectedName == "local") {
                    ppno_input.disabled = true;
                    ppno_input.value ="";
                    srno_input.disabled = false;
                    billno_input.disabled = false;
                    srno_input.required = true;
                    billno_input.required = true;
                }
                if (selectedName == "import") {
                    billno_input.disabled = true;
                    billno_input.value = "";
                    ppno_input.disabled = false;
                    srno_input.disabled = false;
                    ppno_input.required = true;
                    srno_input.required = true;
                }
                if (selectedName == "sapat") {
                    billno_input.disabled = false;
                    ppno_input.disabled = true;
                    srno_input.disabled = true;
                    ppno_input.value ="";
                    srno_input.value = "";
                    billno_input.required = true;
                }
            });

        });
    </script>
@endsection
