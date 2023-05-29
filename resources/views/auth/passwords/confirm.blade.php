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
                            <p class="text-muted mb-4 text-bold">{{ __('Please confirm your password before continuing.') }}</p>
                            <form method="POST" action="{{ route('password.confirm') }}" class="login-form">
                                @csrf
                                <div class="form-group mb-3">
                                    <input id="password" type="password" class="form-control rounded-pill border-0 shadow-sm px-4 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="{{ __('Password') }}">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-block text-uppercase login-button "><i class="fas fa-unlock"></i> {{ __('Confirm Password') }}</button>
                                <div class="text-center d-flex justify-content-between mt-4">
                                    <p>
                                        @if (Route::has('password.request'))
                                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                                <i class="fas fa-lock"></i>  {{ __('Forgot Your Password?') }}
                                            </a>
                                        @endif
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div><!-- End page content -->
            </div>
        </div><!-- End content half-->
    </div>
</div>
@endsection
