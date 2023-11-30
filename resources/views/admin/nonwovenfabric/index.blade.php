@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('NonWovenFabric') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('NonWovenFabric') }}</li>
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
                <div class="col-lg-3 col-md-5 col-6 mb-2">
                    <form action="{{ route('nonwovenfabrics.index') }}" method="GET" role="search">
                        <div class="input-group">
                            <input type="text" name="term"
                                    placeholder="{{ __('Type name or category name...') }}"
                                    class="form-control" autocomplete="off"
                                    value="{{ request('term') ? request('term') : '' }}" required>
                            <span class="input-group-append">
                                    <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
                                </span>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6 col-md-7 col-6">
                    <div class="card-tools text-md-right">
                       <a href="{{ route('nonwovenfabrics.create') }}" class="btn btn-primary">
                            {{ __('Add NonWovenFabric') }} <i class="fas fa-plus-circle"></i>
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
                        <th>{{ __('Gsm') }}</th>
                        <th>{{ __('Color') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Created') }}</th>
                        <th class="text-right">{{ __('Action') }}</th>
                    </tr>
                    </thead>
                    <tbody>

                    @if ($nonwovenfabrics->total() > 0)
                        @foreach ($nonwovenfabrics as $key => $nonwovenfabric)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $nonwovenfabric->name}} </td>
                                <td>{{ $nonwovenfabric->gsm }}</td>
                                <td>{{ $nonwovenfabric->color }}</td>
                                <td>
                                    @if ($nonwovenfabric->isActive())
                                        <span class="badge badge-success">{{ __('Active') }}</span>
                                    @else
                                        <span class="badge badge-warning">{{ __('Inactive') }}</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($nonwovenfabric->created_at)->format('d-M-Y') }}</td>
                                <td class="text-right">
                                    <div class="btn-group">
                                        <button type="button"
                                                class="btn btn-secondary dropdown-toggle action-dropdown-toggle"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            @if ($nonwovenfabric->isActive())
                                                <a href="{{ route('nonwovenfabrics.status', $nonwovenfabric->id) }}"
                                                    class="dropdown-item"><i class="fas fa-window-close"></i>
                                                    {{ __('Inactive') }}</a>
                                            @else
                                                <a href="{{ route('nonwovenfabrics.status', $nonwovenfabric->id) }}"
                                                    class="dropdown-item"><i class="fas fa-check-square"></i>
                                                    {{ __('Active') }}</a>
                                            @endif
                                            <a href="{{ route('nonwovenfabrics.edit', $nonwovenfabric->id) }}"
                                                class="dropdown-item"><i class="fas fa-edit"></i>
                                                {{ __('Edit') }}</a>
                                            <a href="{{ route('nonwovenfabrics.delete', $nonwovenfabric->id) }}"
                                                class="dropdown-item delete-btn"
                                                data-msg="{{ __('Are you sure you want to delete this sub category?') }}"><i
                                                    class="fas fa-trash"></i> {{ __('Delete') }}</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="10">
                                <div class="data_empty">
                                    <img src="{{ asset('img/result-not-found.svg') }}" alt="" title="">
                                    <p>{{ __('Sorry, no sub category found in the database. Create your very first sub category.') }}
                                    </p>
                                    <a href="{{ route('nonwovenfabrics.create') }}" class="btn btn-primary">
                                        {{ __('Add Sub Category') }} <i class="fas fa-plus-circle"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->

            <!-- pagination start -->
            {{-- {{ $nonwovenfabrics->links() }} --}}
            <!-- pagination end -->
        </div>
    </div>

    <!-- /.content -->
@endsection
