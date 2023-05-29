@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Payment Methods') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.setup') }}">{{ __('Setup') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Payment Methods') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            @include('admin.includes.alert')
            <div class="row">
                <div class="col-12 col-sm-4 offset-sm-8 col-md-3 offset-md-9 col-lg-2 offset-lg-10">
                    <a href="{{ route('payments.create') }}" class="btn btn-block btn-primary">
                        {{ __('Add Payment Method') }} <i class="fas fa-plus-circle"></i>
                    </a>
                </div>

                <div class="p-0 table-responsive table-custom my-3">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Code') }}</th>
                            <th>{{ __('Note') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Created') }}</th>
                            <th class="text-right">{{ __('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>

                        @if($paymentMethods->total() > 0)
                            @foreach ($paymentMethods as $key => $method)
                                <tr>
                                    <td>{{ ++$key  }}</td>
                                    <td>{{ $method->name }} </td>
                                    <td>{{ $method->code }} </td>
                                    <td>{{ $method->shortNote() }}</td>
                                    <td>
                                        @if($method->isActive())
                                            <span class="badge badge-success">{{ __('Active') }}</span>
                                        @else
                                            <span class="badge badge-warning">{{ __('Inactive') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($method->created_at)->format('d-M-Y') }}</td>
                                    <td class="text-right">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-secondary dropdown-toggle action-dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @if($method->isActive())
                                                    <a href="{{ route('payments.status', $method->slug)  }}" class="dropdown-item"><i class="fas fa-window-close"></i> {{ __('Inactive') }}</a>
                                                @else
                                                    <a href="{{ route('payments.status', $method->slug)  }}" class="dropdown-item"><i class="fas fa-check-square"></i> {{ __('Active') }}</a>
                                                @endif
                                                <a href="{{ route('payments.edit', $method->slug) }}" class="dropdown-item"><i class="fas fa-edit"></i> {{ __('Edit') }}</a>
                                                <a href="{{ route('payments.delete', $method->slug)  }}" class="dropdown-item delete-btn" data-msg="{{ __('Are you sure you want to delete this payment method?') }}"><i class="fas fa-trash"></i> {{ __('Delete') }}</a>
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
                                        <p>{{ __('Sorry, no payment method product found in the database. Create your very first payment method.') }}</p>
                                        <a href="{{ route('payments.create') }}" class="btn btn-primary">
                                            {{ __('Add Payment Method') }} <i class="fas fa-plus-circle"></i>
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
                {{ $paymentMethods->links() }}
                <!-- pagination end -->
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection

