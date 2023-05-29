@extends('vendor.installer.layouts.master')

@section('template_title')
    {{ trans('templateTitle') }}
@endsection

@section('title')

    {{ trans('installer_title') }}
@endsection

@section('container')
    @if (\Session::has('msg'))
        <div class="alert alert-danger">
            <ul>
                <li>{!! \Session::get('msg') !!}</li>
            </ul>
        </div>
    @endif
    <form method="post" action="{{ route('verifyPurchaseCode') }}" class="tabs-wrap">
        @csrf
        <div class="form-group {{ $errors->has('name') ? ' has-error ' : '' }}">
            <label for="name">
                {{ trans('buyer_name') }}
            </label>
            <input type="text" name="name" id="name"  placeholder="{{ trans('buyer_name_placeholder') }}" required value="{{ old('name') }}" />
            @if ($errors->has('name'))
                <span class="error-block">
                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                    {{ $errors->first('name') }}
                </span>
            @endif
        </div>
        <div class="form-group {{ $errors->has('email') ? ' has-error ' : '' }}">
            <label for="email">
                {{ trans('buyer_email') }}
            </label>
            <input type="text" name="email" id="email"  placeholder="{{ trans('buyer_email_placeholder') }}" required value="{{ old('email') }}" />
            @if ($errors->has('email'))
                <span class="error-block">
                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                    {{ $errors->first('email') }}
                </span>
            @endif
        </div>
        <div class="form-group {{ $errors->has('purchase_code') ? ' has-error ' : '' }}">
            <label for="purchase_code">
                {{ trans('installer_code') }}
            </label>
            <input type="text" name="purchase_code" id="purchase_code" placeholder="{{ trans('installer_code') }}"  required value="{{ old('purchase_code') }}" />
            @if ($errors->has('purchase_code'))
                <span class="error-block">
                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                    {{ $errors->first('purchase_code') }}
                </span>
            @endif
        </div>
        <div class="buttons">
            <button class="button" type="submit">
                {{ trans('next') }}
                <i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
            </button>
        </div>
    </form>
@endsection
