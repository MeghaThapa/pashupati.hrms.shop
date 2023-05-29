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
                            @if (session('resent'))
                                <div class="alert alert-success" role="alert">
                                    {{ __('A fresh verification link has been sent to your email address.') }}
                                </div>
                            @endif
                            <p class="text-muted mb-4 text-bold">{{ __('Verify Your Email Address') }}</p>

                            {{ __('Before proceeding, please check your email for a verification link.') }}
                            {{ __('If you did not receive the email') }},
                            <form method="POST" action="{{ route('verification.resend') }}" class="d-inline login-form">
                                @csrf
                                <button type="submit" class="btn btn-block text-uppercase login-button "><i class="fas fa-paper-plane"></i> {{ __('Request Another') }}</button>
                            </form>
                        </div>
                    </div>
                </div><!-- End page content -->
            </div>
        </div><!-- End content half-->
    </div>
</div>
@endsection

