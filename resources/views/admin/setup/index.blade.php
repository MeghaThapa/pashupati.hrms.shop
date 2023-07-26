@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{ __('Setup') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('Setup') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container">
            <div class="row set-up-page">
                <div class="col-md-4 col-sm-6 col-12">
                    <a href="{{ route('admin.setup.general') }}">
                        <div class="info-box">
                            <span class="info-box-icon bg-success">
                                <i class="fas fa-tools"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('General Settings') }}</span>
                                <span class="info-box-number">{{ \App\Models\GeneralSetting::count() }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </a>
                </div>
                <!-- /.col -->
                <div class="col-md-4 col-sm-6 col-12">
                    <a href="{{ route('payments.index') }}">
                        <div class="info-box">
                            <span class="info-box-icon bg-success">
                                <i class="fas fa-wallet"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('Payment Methods') }}</span>
                                <span class="info-box-number text-bold">{{ App\Models\PaymentMethod::count() }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </a>
                </div>
                <!-- /.col -->
                <div class="col-md-4 col-sm-6 col-12">
                    <a href="{{ route('processing-steps.index') }}">
                        <div class="info-box">
                            <span class="info-box-icon bg-success">
                                <i class="fas fa-stream"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('Processing Steps') }}</span>
                                <span class="info-box-number text-bold">{{ App\Models\ProcessingStep::count() }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </a>
                </div>
                <!-- /.col -->
                <div class="col-md-4 col-sm-6 col-12">
                    <a href="{{ route('processing-subcat.index') }}">
                        <div class="info-box">
                            <span class="info-box-icon bg-success">
                                <i class="fas fa-stream"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('Processing Subcategory') }}</span>
                                <span class="info-box-number text-bold">{{ App\Models\ProcessingSubcat::count() }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </a>
                </div>
                <!-- /.col -->
                <div class="col-md-4 col-sm-6 col-12">
                    <a href="{{ route('sizes.index') }}">
                        <div class="info-box">
                            <span class="info-box-icon bg-success">
                                <i class="fas fa-ruler"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('Sizes Setting') }}</span>
                                <span class="info-box-number">{{ App\Models\Size::count() }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </a>
                </div>
                <!-- /.col -->
                <div class="col-md-4 col-sm-6 col-12">
                    <a href="{{ route('showrooms.index') }}">
                        <div class="info-box">
                            <span class="info-box-icon bg-success">
                                <i class="fas fa-store"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('Showrooms') }}</span>
                                <span class="info-box-number text-bold">{{ App\Models\Showroom::count() }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </a>
                </div>
                <!-- /.col -->
                <div class="col-md-4 col-sm-6 col-12">
                    <a href="{{ route('units.index') }}">
                        <div class="info-box">
                            <span class="info-box-icon bg-success">
                                <i class="fas fa-balance-scale"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('Units Setting') }}</span>
                                <span class="info-box-number">{{ App\Models\Unit::count() }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </a>
                </div>
                <!-- /.col -->

                <div class="col-md-4 col-sm-6 col-12">
                    <a href="{{ route('storeinsetup.index') }}">
                        <div class="info-box">
                            <span class="info-box-icon bg-success">
                                <i class="fas fa-wallet"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('Setupstorein') }}</span>
                                <span class="info-box-number text-bold">{{ App\Models\Setupstorein::count() }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </a>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <a href="{{ route('storeoutsetup.index') }}">
                        <div class="info-box">
                            <span class="info-box-icon bg-success">
                                <i class="fas fa-wallet"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('Setupstoreout') }}</span>
                                <span class="info-box-number text-bold">{{ App\Models\Setupstoreout::count() }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </a>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <a href="{{ route('tax.index') }}">
                        <div class="info-box">
                            <span class="info-box-icon bg-success">
                                <i class="fas fa-wallet"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('Tax Type') }}</span>
                                <span class="info-box-number text-bold">{{ App\Models\Tax::count() }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </a>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <a href="{{ route('department.index') }}">
                        <div class="info-box">
                            <span class="info-box-icon bg-success">
                                <i class="fas fa-wallet"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('Processing Department') }}</span>
                                <span class="info-box-number text-bold">{{ App\Models\Department::count() }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </a>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <a href="{{ route('shift.index') }}">
                        <div class="info-box">
                            <span class="info-box-icon bg-success">
                                <i class="fas fa-wallet"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('Shift') }}</span>
                                {{-- <span class="info-box-number text-bold">{{ App\Models\Department::count() }}</span> --}}
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </a>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <a href="{{ route('setup.wastage.index') }}">
                        <div class="info-box">
                            <span class="info-box-icon bg-success">
                                <i class="fas fa-wallet"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('Wastages Type') }}</span>
                                <span class="info-box-number text-bold">{{ App\Models\Wastages::count() }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </a>
                </div>
                 <div class="col-md-4 col-sm-6 col-12">
                    <a href="{{ route('godam.index') }}">
                        <div class="info-box">
                            <span class="info-box-icon bg-success">
                                <i class="fas fa-wallet"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('Godam') }}</span>
                                <span class="info-box-number text-bold">{{ App\Models\Godam::count() }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </a>
                </div>

                 

                {{-- Yo Chaidaina --}}
                {{-- <div class="col-md-4 col-sm-6 col-12">
                    <a href="{{ route('processing.categories') }}">
                        <div class="info-box">
                            <span class="info-box-icon bg-success">
                                <i class="fas fa-wallet"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ __('Processing Categories') }}</span>
                                <span class="info-box-number text-bold">{{ App\Models\Department::count() }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </a>
                </div> --}}

            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection
