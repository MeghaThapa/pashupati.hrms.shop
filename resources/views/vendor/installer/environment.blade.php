@extends('vendor.installer.layouts.master')

@section('template_title')
    {{ trans('environment_menu_templateTitle') }}
@endsection

@section('title')
    <i class="fa fa-cog fa-fw" aria-hidden="true"></i>
    {!! trans('environment_menu_title') !!}
@endsection

@section('container')

    <p class="text-center">
        {!! trans('environment_menu_desc') !!}
    </p>
    <div class="buttons">
        <a href="{{ route('LaravelInstaller::environmentWizard') }}" class="button button-wizard">
            <i class="fa fa-sliders fa-fw" aria-hidden="true"></i> {{ trans('environment_menu_wizard-button') }}
        </a>
        <a href="{{ route('LaravelInstaller::environmentClassic') }}" class="button button-classic">
            <i class="fa fa-code fa-fw" aria-hidden="true"></i> {{ trans('environment_menu_classic-button') }}
        </a>
    </div>

@endsection
