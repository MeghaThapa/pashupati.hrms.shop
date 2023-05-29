@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header mb-4">

    <div class="row align-items-center">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ __('View Showroom') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.setup') }}">{{ __('Setup') }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('showrooms.index') }}">{{ __('Showrooms') }}</a>
                </li>
                <li class="breadcrumb-item active">{{ __('View Showroom') }}</li>
            </ol>
        </div>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container">
        <div class="row">
            <div class="card col-md-12">
                <div class="card-header">
                    <h3 class="card-title">{{ __('View showroom') }}: {{ ($showroom->name) }}</h3>

                    <div class="card-tools">
                        <a href="{{ route('showrooms.index') }}" class="btn btn-block btn-primary">
                            <i class="fas fa-long-arrow-alt-left"></i> {{ __('Go Back') }}
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">

                        <table class="table">
                            <tbody>
                                <tr>
                                    <td><strong>{{ __('Showroom Name') }}:</strong> {{ $showroom->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ __('Showroom Code') }}:</strong> {{ $showroom->code }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ __('Showroom Manager') }}:</strong> {{ $showroom->manager }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ __('Email') }}:</strong> {{ $showroom->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ __('Phone') }}:</strong> {{ $showroom->phone_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ __('Address') }}:</strong> {{ $showroom->address }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ __('Note') }}:</strong> {{ $showroom->note }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>{{ __('Status') }}:</strong>
                                        @if($showroom->isActive())
                                            <span class="badge badge-success">{{ __('Active') }}</span>
                                        @else
                                            <span class="badge badge-warning">{{ __('Inactive') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.content -->
@endsection
