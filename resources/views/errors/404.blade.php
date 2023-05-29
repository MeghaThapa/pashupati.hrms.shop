@extends('layouts.auth')

@section('content')
<!-- Main content -->
<div class="container">
    <div class="page_404">
        <div class="row">
            <div class="offset-md-3 col-md-6 text-center">
                <div class="four_zero_four_bg">
                    <h1 class="text-center ">{{ __('404') }} </h1>
                </div>
                <div class="contant_box_404">
                    <h3>{{ __('Look like you\'re lost') }}</h3>
                    <p>{{ __('The page you are looking for not avaible!') }}</p>
                    <a href="{{ route('dashboard') }}" class="link_404">{{ __('Back to Dashboard') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.content -->
@endsection


