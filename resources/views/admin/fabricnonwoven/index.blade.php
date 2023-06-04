@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Non Woven Factory Receive Entry') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('NonWovwn Factory Receive Entry') }}</li>
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
                    <form action="{{ route('fabric-groups.index') }}" method="GET" role="search">
                        <div class="input-group">
                            <input type="text" name="term" placeholder="{{ __('Type name or code...') }}"
                                    class="form-control" autocomplete="off"
                                    value="{{ request('term') ? request('term') : '' }}" required>
                            <span class="input-group-append">
                                    <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
                                </span>
                        </div>
                    </form>
                </div>
                <div class="col-lg-9 col-md-7 col-6">
                    <div class="card-tools text-md-right">
                        <a class="btn btn-secondary" href="{{ route('fabric-groups.pdf') }}">
                            <i class="fas fa-download"></i> @lang('Export')
                        </a>
                        <a href="{{ route('nonwovenfabrics.create') }}" class="btn btn-primary">
                            {{ __('Add NonWoven Recive Entry') }} <i class="fas fa-plus-circle"></i>
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
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Created') }}</th>
                        <th class="text-right">{{ __('Action') }}</th>
                    </tr>
                    </thead>
                {{--     <tbody>

                    @if ($fabricgroups->total() > 0)
                        @foreach ($fabricgroups as $key => $fabric_groups)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>
                                    <a href="{{ route('fabric-groups.edit', $fabric_groups->slug) }}">
                                        {{ $fabric_groups->name }}</a>
                                </td>
                                <td>
                                    @if ($fabric_groups->isActive())
                                        <span class="badge badge-success">{{ __('Active') }}</span>
                                    @else
                                        <span class="badge badge-warning">{{ __('Inactive') }}</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($fabric_groups->created_at)->format('d-M-Y') }}</td>
                                <td class="text-right">
                                    <div class="btn-group">
                                        <button type="button"
                                                class="btn btn-secondary dropdown-toggle action-dropdown-toggle"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            @if ($fabric_groups->isActive())
                                                <a href="{{ route('fabric-groups.status', $fabric_groups->slug) }}"
                                                    class="dropdown-item"><i class="fas fa-window-close"></i>
                                                    {{ __('Inactive') }}</a>
                                            @else
                                                <a href="{{ route('fabric-groups.status', $fabric_groups->slug) }}"
                                                    class="dropdown-item"><i class="fas fa-check-square"></i>
                                                    {{ __('Active') }}</a>
                                            @endif
                                            <a href="{{ route('fabric-groups.edit', $fabric_groups->slug) }}"
                                                class="dropdown-item"><i class="fas fa-edit"></i>
                                                {{ __('Edit') }}</a>
                                            <a href="{{ route('fabric-groups.delete', $fabric_groups->slug) }}"
                                                class="dropdown-item delete-btn"
                                                data-msg="{{ __('Are you sure you want to delete this fabric_groups?') }}"><i
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
                                    <p>{{ __('Sorry, no fabric_groups found in the database. Create your very first fabric_groups.') }}</p>
                                    <a href="{{ route('fabric-groups.create') }}" class="btn btn-primary">
                                        {{ __('Add FabricGroup') }} <i class="fas fa-plus-circle"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endif
                    </tbody> --}}
                </table>
            </div>
            <!-- /.card-body -->


            <!-- pagination start -->
            {{-- {{ $fabricgroups->links() }} --}}
            <!-- pagination end -->
        </div>
    </div>
@endsection
