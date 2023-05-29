@extends('layouts.admin')

@section('content')
    {{--   Content Header (Page header) --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{ __('Dashboard') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">{{ __('Dashboard') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    {{--  end content-header --}}

    {{-- Main content --}}
    <div class="content">
        <div class="container-fluid">
            @if($stats->staff > 0 && $stats->suppliers > 0 && $stats->categories > 0 && $stats->subCats > 0 && $stats->purchases > 0)
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-3 stats">
                        <a href="{{ route('categories.index') }}">
                            <div class="info-box">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-tags"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('Product Categories') }}</span>
                                    <span class="info-box-number">{{ $stats->categories }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 stats">
                        <a href="{{ route('staff.index') }}">
                            <div class="info-box">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('Staff') }}</span>
                                    <span class="info-box-number">{{ $stats->staff }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 stats">
                        <a href="{{ route('suppliers.index') }}">
                            <div class="info-box">
                                <span class="info-box-icon bg-success elevation-1"><i
                                            class="fas fa-people-carry"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('Suppliers') }}</span>
                                    <span class="info-box-number">{{ $stats->suppliers }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 stats">
                        <a href="{{ route('expenses.index') }}">
                            <div class="info-box">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-wallet"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('Expenses') }}</span>
                                    <span class="info-box-number">{{ $stats->expenses }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 stats">
                        <a href="{{ route('purchases.index') }}">
                            <div class="info-box">
                                <span class="info-box-icon bg-success elevation-1"><i
                                            class="fas fa-shopping-basket"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('Purchases') }}</span>
                                    <span class="info-box-number">{{ $stats->purchases }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 stats">
                        <a href="{{ route('processing.index') }}">
                            <div class="info-box">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-tools"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('Processing Products') }}</span>
                                    <span class="info-box-number">{{ $stats->processing }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 stats">
                        <a href="{{ route('finished.index') }}">
                            <div class="info-box">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-th-list"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('Finished Products') }}</span>
                                    <span class="info-box-number">{{ $stats->finished }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 stats">
                        <a href="{{ route('transferred.index') }}">
                            <div class="info-box">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-random"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('Transferred Products') }}</span>
                                    <span class="info-box-number">{{ $stats->transferred }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="todo-box col-md-12">
                        <div class="card ">
                            <div class="card-header ui-sortable-handle">
                                <h3 class="card-title">
                                    <i class="ion ion-clipboard mr-1"></i>
                                    {{ __('To Do List') }}
                                </h3>
                            </div>
                            <div class="card-body">
                                <ul class="todo-list ui-sortable">
                                    <li>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="setup" name="setup"
                                                   @if(\App\Models\Size::count() > 0 && \App\Models\PaymentMethod::count() > 0 && \App\Models\Unit::count() > 0 && \App\Models\ProcessingStep::count() > 0 && \App\Models\Showroom::count() > 0) checked @endif>

                                            <label class="custom-control-label todo-label" for="setup">
                                                @if(\App\Models\Size::count() > 0 && \App\Models\PaymentMethod::count() > 0 && \App\Models\Unit::count() > 0 && \App\Models\ProcessingStep::count() > 0 && \App\Models\Showroom::count() > 0)
                                                    <del>{{ __('You haven\'t customized the setup page yet. Setup your application and create some sizes, units, etc.') }}</del>
                                                @else
                                                    {{ __('You haven\'t customized the setup page yet. Setup your application and create some sizes, units, etc.') }}
                                                @endif
                                            </label>
                                        </div>
                                        <div class="text-right todo-btn xs-text-left">
                                            <a href="{{ route('admin.setup') }}" class="btn btn-success">
                                                {{ __('Setup') }}
                                                <i class="fas fa-paper-plane"></i>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="category"
                                                   name="category" {{ $stats->categories > 0 ? 'checked' : '' }}>
                                            <label class="custom-control-label todo-label" for="category">
                                                @if($stats->categories > 0)
                                                    <del>{{ __('You haven\'t created a category yet. Create a new category.') }}</del>
                                                @else
                                                    {{ __('You haven\'t created a category yet. Create a new category.') }}
                                                @endif

                                            </label>
                                        </div>
                                        <div class="text-right todo-btn xs-text-left">
                                            <a href="{{ route('categories.create') }}" class="btn btn-success">

                                                @if($stats->categories > 0)
                                                    {{ __('Add Another') }}
                                                @else
                                                    {{ __('Add New') }}
                                                @endif
                                                <i class="fas fa-plus-circle"></i>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="subCategory"
                                                   name="subCategory" {{ $stats->subCats > 0 ? 'checked' :  '' }}>
                                            <label class="custom-control-label todo-label" for="subCategory">
                                                @if($stats->subCats > 0)
                                                    <del>{{ __('You haven\'t created a sub category yet. Create a new sub category.') }}</del>
                                                @else
                                                    {{ __('You haven\'t created a sub category yet. Create a new sub category.') }}
                                                @endif

                                            </label>
                                        </div>
                                        <div class="text-right todo-btn xs-text-left">
                                            <a href="{{ route('subCategories.create') }}" class="btn btn-success">

                                                @if($stats->subCats > 0)
                                                    {{ __('Add Anohter') }}
                                                @else
                                                    {{ __('Add New') }}
                                                @endif
                                                <i class="fas fa-plus-circle"></i>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="staff"
                                                   name="staff" {{ $stats->staff > 0 ? 'checked' : '' }}>
                                            <label class="custom-control-label todo-label" for="staff">
                                                @if($stats->staff > 0)
                                                    <del>{{ __('You haven\'t created a staff yet. Create a new staff.') }}</del>
                                                @else
                                                    {{ __('You haven\'t created a staff yet. Create a new staff.') }}
                                                @endif
                                            </label>
                                        </div>
                                        <div class="text-right todo-btn xs-text-left">
                                            <a href="{{ route('staff.create') }}" class="btn btn-success">
                                                @if($stats->staff > 0)
                                                    {{ __('Add Another') }}
                                                @else
                                                    {{ __('Add New') }}
                                                @endif
                                                <i class="fas fa-plus-circle"></i>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="supplier"
                                                   name="supplier" {{ $stats->suppliers > 0 ? 'checked' : '' }}>
                                            <label class="custom-control-label todo-label" for="supplier">
                                                @if($stats->suppliers > 0)
                                                    <del>{{ __('You haven\'t created a supplier yet. Create a new supplier.') }}</del>
                                                @else
                                                    {{ __('You haven\'t created a supplier yet. Create a new supplier.') }}
                                                @endif
                                            </label>
                                        </div>
                                        <div class="text-right todo-btn xs-text-left">
                                            <a href="{{ route('suppliers.create') }}" class="btn btn-success">
                                                @if($stats->suppliers > 0)
                                                    {{ __('Add Another') }}
                                                @else
                                                    {{ __('Add New') }}
                                                @endif
                                                <i class="fas fa-plus-circle"></i>
                                            </a>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="purchase"
                                                   name="purchase" {{ $stats->purchases > 0 ? 'checked' : '' }}>
                                            <label class="custom-control-label todo-label" for="purchase">
                                                @if($stats->purchases > 0)
                                                    <del>{{ __('You haven\'t created a purchase yet. Create a new purchase.') }}</del>
                                                @else
                                                    {{ __('You haven\'t created a purchase yet. Create a new purchase.') }}
                                                @endif
                                            </label>
                                        </div>
                                        <div class="text-right todo-btn xs-text-left">
                                            <a href="{{ route('purchases.create') }}" class="btn btn-success">

                                                @if($stats->purchases > 0)
                                                    {{ __('Add Another') }}
                                                @else
                                                    {{ __('Add New') }}
                                                @endif
                                                <i class="fas fa-plus-circle"></i>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-md-12 col-lg-6">
                    <div class="card card-success w-100">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Purcahses in last 12 months') }}</h3>
                        </div>
                        <div class="card-body">
                            {!! $purchasesChart->container() !!}
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <div class="col-md-12 col-lg-6">
                    <div class="card card-success w-100">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Expenses in last 12 months') }}</h3>
                        </div>
                        <div class="card-body">
                            {!! $expenseChart->container() !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Finished quantities in last 12 months') }}</h3>
                        </div>
                        <div class="card-body">
                            {!! $finishedQtyChart->container() !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Transferred quantities in last 12 months') }}</h3>
                        </div>
                        <div class="card-body">
                            {!! $transferredQtyChart->container() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--  end main content --}}
@endsection

@section('extra-script')
    {{-- ChartScript --}}
    <script src="{{ asset('js/Chart.min.js') }}" charset=utf-8></script>
    @if($purchasesChart)
        {!! $purchasesChart->script() !!}
    @endif

    @if($finishedQtyChart)
        {!! $finishedQtyChart->script() !!}
    @endif

    @if($transferredQtyChart)
        {!! $transferredQtyChart->script() !!}
    @endif

    @if($expenseChart)
        {!! $expenseChart->script() !!}
    @endif
@endsection

