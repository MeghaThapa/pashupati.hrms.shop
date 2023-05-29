@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Suppliers') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Suppliers') }}</li>
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
                    <form action="{{ route('suppliers.index') }}" method="GET" role="search">
                        <div class="input-group">
                            <input type="text" name="term"
                                placeholder="{{ __('Type name or email or company or desigantion ...') }}"
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
                        <a class="btn btn-secondary" href="{{ route('suppliers.pdf') }}">
                            <i class="fas fa-download"></i> @lang('Export')
                        </a>
                        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                            {{ __('Add Supplier') }} <i class="fas fa-plus-circle"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="p-0 table-responsive table-custom my-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('Picture') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('Company') }}</th>
                            <th>{{ __('Desigantion') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Created') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($suppliers->total() > 0)
                            @foreach ($suppliers as $key => $supplier)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>
                                        @if (!empty($supplier->profile_picture))
                                            <img src="{{ $supplier->profilePic() }}" class="table-image-preview">
                                        @else
                                            <div class="no-preview"></div>
                                        @endif
                                    </td>
                                    <td><a
                                            href="{{ route('suppliers.edit', $supplier->id) }}">{{ $supplier->name }}</a>
                                    </td>
                                    <td>{{ $supplier->email }} </td>
                                    <td>{{ $supplier->phone_number }} </td>
                                    <td>{{ $supplier->company_name }} </td>
                                    <td>{{ $supplier->designation }} </td>
                                    <td>
                                        @if ($supplier->isActive())
                                            <span class="badge badge-success">{{ __('Active') }}</span>
                                        @else
                                            <span class="badge badge-warning">{{ __('Inactive') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($supplier->created_at)->format('d-M-Y') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button"
                                                class="btn btn-secondary dropdown-toggle action-dropdown-toggle"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @if ($supplier->isActive())
                                                    <a href="{{ route('suppliers.status', $supplier->id) }}"
                                                        class="dropdown-item"><i class="fas fa-window-close"></i>
                                                        {{ __('Inactive') }}</a>
                                                @else
                                                    <a href="{{ route('suppliers.status', $supplier->id) }}"
                                                        class="dropdown-item"><i class="fas fa-check-square"></i>
                                                        {{ __('Active') }}</a>
                                                @endif
                                                <a href="{{ route('suppliers.show', $supplier->id) }}"
                                                    class="dropdown-item"><i class="fas fa-eye"></i>
                                                    {{ __('View') }}</a>
                                                <a href="{{ route('suppliers.edit', $supplier->id) }}"
                                                    class="dropdown-item"><i class="fas fa-edit"></i>
                                                    {{ __('Edit') }}</a>
                                                <a href="{{ route('suppliers.delete', $supplier->id) }}"
                                                    class="dropdown-item delete-btn"
                                                    data-msg="{{ __('Are you sure you want to delete this supplier?') }}"><i
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
                                        <p>{{ __('Sorry, no supplier found in the database. Create your very first supplier.') }}</p>
                                        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                                            {{ __('Add Supplier') }} <i class="fas fa-plus-circle"></i>
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
            {{ $suppliers->links() }}
            <!-- pagination end -->
        </div>
    </div>
    <!-- /.content -->
@endsection
