@extends('layouts.admin')

@section('content')
{{--  Content Header (Page header)  --}}
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Profile') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Profile') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>
{{--  end content-header --}}

<!-- Main content -->
<div class="content">
    <div class="container">
        <div class="row">
            @include('admin.includes.alert')
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <!-- Profile Image -->
                        <div class="card">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    <img id="attached-preview-img" class="profile-user-img img-fluid img-circle" src="{{ auth()->user()->profilePic() }}" alt="{{ __('User profile picture') }}">
                                </div>
                                <h3 class="profile-username text-center">{{ ucfirst(auth()->user()->name) }}</h3>
                                <p class="text-muted text-center">{{ auth()->user()->email }}</p>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <form method="POST" class="form-horizontal" action="{{ route('admin.profile.update', auth()->user()->email) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <label for="name" class="col-form-label">{{ __('Name') }}<span class="required-field">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ auth()->user()->name }}" placeholder="{{ __('Name') }}" required>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <label for="email" class="col-form-label">{{ __('Email') }}<span class="required-field">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ auth()->user()->email }}" placeholder="{{ __('Email') }}" required>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <label for="password" class="col-form-label">{{ __('Password') }}</label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="{{ __('Password') }}">
                                            <small id="passwordHelp" class="form-text text-muted">{{ __('Leave the box empty if you don\'t want to change the password.') }}</small>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <label for="profilePic" class="col-form-label">{{ __('Profile Picture') }}</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input @error('profilePic') is-invalid @enderror" id="attached-image" name="profilePic">
                                                <label class="custom-file-label" for="customFile">{{ __('Choose file') }}</label>
                                                <small id="profilePicHelp" class="form-text text-muted">{{ __('Leave the box empty if you don\'t want to change the picture.') }}</small>
                                                @error('profilePic')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" value="{{ auth()->user()->profilePic() }}" name="old_image">
                                    <div class="row mt-2">
                                        <div class="col-md-12 form-group">
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-edit"></i> {{ __('Save Chnages') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{--  end content  --}}
@endsection

