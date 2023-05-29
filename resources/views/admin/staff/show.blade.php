@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('View Staff') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('staff.index') }}">{{ __('Staff') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('View Staff') }}</li>
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
                        <h3 class="card-title">{{ __('View staff') }}: {{ $staff->name }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-md-12 col-lg-4 text-center justify-content-center align-self-center view-image">
                                @if (!empty($staff->profile_picture))
                                    <img src="{{ $staff->profilePic() }}" class="img-fluid">
                                @else
                                    <div class="show-no-preview"></div>
                                @endif
                            </div>
                            <div class="col-md-12 col-lg-8 table-responsive view-table">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td><strong>{{ __('Name') }}:</strong> {{ $staff->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ __('Email') }}:</strong> {{ $staff->email }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ __('Phone') }}:</strong> {{ $staff->phone_number }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ __('Department') }}:</strong> {{ $staff->department }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ __('Designation') }}:</strong> {{ $staff->designation }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ __('Address') }}:</strong> {{ $staff->address }}</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>{{ __('Status') }}:</strong>
                                                @if ($staff->isActive())
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
                    <div class="card-footer no-print">
                        <a href="{{ route('staff.index') }}" class="btn btn-primary">
                            <i class="fas fa-long-arrow-alt-left"></i> {{ __('Go Back') }}
                        </a>
                        <a href="#" class="btn btn-secondary float-right print-btn"><i class="fas fa-print"></i>
                            {{ __('Print') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection
