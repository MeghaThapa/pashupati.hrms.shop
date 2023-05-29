@extends('vendor.installer.layouts.master')

@section('template_title')
    {{ trans('welcome_templateTitle') }}
@endsection

@section('title')
    {{ trans('welcome_title') }}
@endsection

@section('container')
    <p class="text-center">
      {{ trans('welcome_message') }}
    </p>
    <p class="text-center">
      <a href="{{ route('LaravelInstaller::requirements') }}" class="button">
        {{ trans('welcome_next') }}
        <i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
      </a>
    </p>
@endsection
