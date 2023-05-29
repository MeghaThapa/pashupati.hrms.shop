@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header mb-4">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ __('Edit Staff') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('staff.index') }}">{{ __('Staff') }}</a>
                </li>
                <li class="breadcrumb-item active">{{ __('Edit Staff') }}</li>
            </ol>
        </div>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Edit staff') }}: {{ $staff->name }}</h3>
            <div class="card-tools">
                <a href="{{ route('staff.index') }}" class="btn btn-block btn-primary">
                    <i class="fas fa-long-arrow-alt-left"></i> {{ __('Go Back') }}
                </a>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
            <form class="form-horizontal" action="{{ route('staff.update', $staff->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="name">{{ __('Staff Name') }}<span class="required-field">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="{{ __('Staff Name') }}" value="{{ $staff->name }}" required>
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
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="{{ __('Email Address') }}" value="{{ $staff->email }}">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="phone" class="col-form-label">{{ __('Phone Number') }}</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" placeholder="{{ __('Phone Number') }}" value="{{ $staff->phone_number }}" >
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="department" class="col-form-label">{{ __('Department') }}</label>
                            <input type="text" class="form-control @error('department') is-invalid @enderror" id="department" name="department" placeholder="{{ __('Department') }}" value="{{ $staff->department }}" required>
                            @error('department')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="designation" class="col-form-label">{{ __('Designation') }}</label>
                            <input type="text" class="form-control @error('designation') is-invalid @enderror" id="designation" name="designation" placeholder="{{ __('Designation') }}" value="{{ $staff->designation }}" required>
                            @error('designation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="address" class="col-form-label">{{ __('Address') }}</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" placeholder="{{ __('Address') }}">{{ $staff->address }}</textarea>
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
                                <input type="file" class="custom-file-input @error('profilePic') is-invalid @enderror" id="attached-image" name="profilePic">
                                <label class="custom-file-label" for="customFile">{{ __('Choose file') }}</label>
                                @error('profilePic')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="image-preview">
                                @if($staff->profile_picture)
                                    <img src="{{ $staff->profilePic() }}" id="attached-preview-img" class="mt-3"/>
                                @else
                                    <img src="{{ asset('img/placeholder.png') }}" id="attached-preview-img" class="mt-3"/>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="status" class="col-form-label">{{ __('Status') }}</label>
                            <select class="form-control" id="status" name="status">
                                <option value="1" {{ $staff->isActive() ? 'selected' : '' }}>{{ __('Active') }}</option>
                                <option value="0" {{ $staff->isActive() ? '' : 'selected' }}>{{ __('Inactive') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-edit"></i> {{ __('Save Changes') }}</button>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </form>
        </div>
        <!-- /.card-body -->
    </div>
</div>
<!-- /.content -->
@endsection

