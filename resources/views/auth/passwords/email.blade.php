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
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <p class="text-muted mb-4 text-bold">{{ __('A password reset link will be sent to your mail.') }}</p>
                            <form method="POST" action="{{ route('password.email') }}" class="login-form">
                                @csrf
                                <div class="form-group mb-3">
                                    <input id="email" type="email" class="form-control rounded-pill border-0 shadow-sm px-4 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('Email Address') }}">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-block text-uppercase login-button "><i class="fas fa-paper-plane"></i> {{ __('Send Link') }}</button>
                            </form>
                        </div>
                    </div>
                </div><!-- End page content -->
            </div>
        </div><!-- End content half-->
    </div>
</div>
@endsection
