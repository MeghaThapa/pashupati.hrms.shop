@php
    use \App\Helpers\AppHelper;
    $helper = AppHelper::instance();
@endphp

@extends('layouts.auth')

@section('content')
<div class="container-fluid">
    <div class="row no-gutter">
        <!-- The image half -->
        <div class="col-md-6 d-none d-md-flex bg-image"></div>

        <!-- Content half -->
        <div class="col-md-6 bg-light">
            <div class="login d-flex align-items-center py-5">
                <!-- page content-->
                <div class="container">
                    <div class="row">
                        <div class="col-lg-10 col-xl-7 mx-auto">
                            <img src="{{ $helper->getGeneralSettigns()->logo }}" class="logo" alt="{{ $helper->getGeneralSettigns()->companyName }}">
                            <p class="text-muted mb-4 text-bold">{{ __('Welcome! Please register for an account first.') }}</p>
                            <form method="POST" action="{{ route('register') }}" class="login-form">
                                @csrf
                                <div class="form-group mb-3">
                                    <input id="name" type="text" class="form-control rounded-pill border-0 shadow-sm px-4 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="{{ __('Name') }}" required autocomplete="name" autofocus>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <input id="email" type="email" class="form-control rounded-pill border-0 shadow-sm px-4 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('Email Address') }}">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <input id="password" type="password" class="form-control rounded-pill border-0 shadow-sm px-4 text-primary"  name="password" required autocomplete="new-password" placeholder="{{ __('Password') }}">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <input id="password-confirm" type="password" class="form-control rounded-pill border-0 shadow-sm px-4 text-primary"  name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('Confirm Password') }}">
                                </div>

                                <button type="submit" class="btn btn-block text-uppercase login-button "><i class="fas fa-user-plus"></i> {{ __('Register') }}</button>
                            </form>
                        </div>
                    </div>
                </div><!-- End page content -->
            </div>
        </div><!-- End content half-->
    </div>
</div>
@endsection
