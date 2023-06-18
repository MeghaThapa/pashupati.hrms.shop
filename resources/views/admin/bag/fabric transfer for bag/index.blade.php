@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Categories') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('fabric transfer for bag') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="col-md-12">
                @include('admin.includes.alert')
            </div>
            <div class="row">
                <div class="col-lg-12 float-right">
                    <div class="card-tools text-md-right">
                        <a class="btn btn-secondary" href="{{ route('categories.pdf') }}">
                            <i class="fas fa-download"></i> @lang('Export')
                        </a>
                        <a href="{{ route('fabric.transfer.entry.for.bag.create') }}" class="btn btn-primary">
                            {{ __('Create fabric transfer for bag') }} <i class="fas fa-plus-circle"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="p-0 table-responsive table-custom my-3">
                <table class="table">
                    <thead>
                    <tr>
                        <th>@lang('#')</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Note') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Created') }}</th>
                        <th class="text-right">{{ __('Action') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="10">
                                <div class="data_empty">
                                    <img src="{{ asset('img/result-not-found.svg') }}" alt="" title="">
                                    <p>{{ __('Sorry, no category found in the database. Create your very first category.') }}</p>
                                    <a href="{{ route('categories.create') }}" class="btn btn-primary">
                                        {{ __('Add Category') }} <i class="fas fa-plus-circle"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->


            <!-- pagination start -->
            <!-- pagination end -->
        </div>
    </div>
@endsection
