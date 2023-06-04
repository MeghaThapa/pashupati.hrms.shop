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
                    @if ($storeOut)
                        <h3 class="card-title">{{ __('Edit Existing Storeout') }}</h3>
                    @else
                        <h3 class="card-title">{{ __('Create New Storeout') }}</h3>
                    @endif

                    <div class="card-tools">
                        <a href="{{ route('storein.index') }}" class="btn btn-block btn-primary">
                            <i class="fas fa-long-arrow-alt-left"></i> {{ __('Go Back') }}
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <form
                        @if ($storeOut) action="{{ route('storeout.updateStoreOut', ['storeout_id' => $storeOut->id]) }}"
                    @else
                        action="{{ route('storeout.saveStoreout') }}" @endif
                        method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="receipt_date" class="col-form-label">{{ __('Receipt Date') }}<span
                                            class="required-field">*</span>
                                    </label>
                                    <input type="date" class="form-control" id="receipt_date" name="receipt_date"
                                        @if ($storeOut) value="{{ $storeOut->receipt_date }}" @endif
                                        placeholder="{{ __('Date') }}" required>
                                    @error('receipt_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                {{-- item --}}
                                <div class="col-md-4 form-group">
                                    <label for="receipt_no" class="col-form-label">{{ __('Receipt No') }}<span
                                            class="required-field">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="receipt_no" name="receipt_no"
                                        placeholder="{{ __('Receipt No') }}" tabindex="-1"
                                        @if ($storeOut) value="{{ $storeOut->receipt_no }}"
                                        @else
                                        value="{{ $receipt_no }}" @endif
                                        required readonly>
                                    @error('receipt_no')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                {{-- For --}}
                                <div class="col-md-4 form-group">
                                    <label for="size" class="col-form-label">{{ __('For') }}<span
                                            class="required-field">*</span>
                                    </label>
                                    <select class="advance-select-box form-control" id="for_id" name="for_id" required>
                                        <option value="" selected disabled>{{ __('Select where to move ') }}</option>
                                            @foreach ($storeinDepartment as $department)
                                            <option value="{{$department->id}}"
                                                @if ($storeOut) {{ $storeOut->for == $department->id ? 'selected' : '' }} @endif>
                                                {{ $department->name }}
                                            </option>
                                            @endforeach


                                    </select>
                                    @error('for_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <center>

                                    <button type="submit" class="btn btn-primary">
                                        @if ($storeOut)
                                            {{ __('Update') }}
                                        @else
                                            {{ __('Storeout Create') }}
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

    {{-- supplier model popup --}}
    <div class="modal fade" id="addSupplierModel" tabindex="-1" role="dialog" aria-labelledby="addDepartmentModel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" action="{{ route('suppliers.store') }}" method="POST"
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
    $(document).ready(function(){
          $('#receipt_date').focus();
        $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });

    });
   </script>
@endsection
