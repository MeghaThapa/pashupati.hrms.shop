@php
use App\Helpers\AppHelper;
$helper = AppHelper::instance();
@endphp

@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Damage Purchases') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Damage Purchases') }}</li>
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
                    <form action="{{ route('purchaseDamage.index') }}" method="GET" role="search">
                        <div class="input-group">
                            <input type="text" name="term" placeholder="{{ __('Type reason ...') }}"
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
                        <a class="btn btn-secondary" href="{{ route('purchaseDamage.pdf') }}">
                            <i class="fas fa-download"></i> @lang('Export')
                        </a>
                        <a href="{{ route('purchaseDamage.create') }}" class="btn btn-primary">
                            {{ __('Add Damage') }} <i class="fas fa-plus-circle"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="p-0 table-responsive table-custom my-3">
                <table class="table">
                    <thead>
                    <tr>
                        <th>@lang('#')</th>
                        <th>{{ __('Purchased Code') }}</th>
                        <th>{{ __('Damage Reason') }}</th>
                        <th>{{ __('Damage Date') }}</th>
                        <th>{{ __('Note') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th class="text-right">{{ __('Action') }}</th>
                    </tr>
                    </thead>
                    <tbody>

                    @if ($damagePruchases->total() > 0)
                        @foreach ($damagePruchases as $key => $damagePurchase)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $helper->pruchaseCode($damagePurchase->purchase->purchase_code) }}
                                </td>
                                <td>{{ $damagePurchase->damage_reason }} </td>
                                <td>{{ \Carbon\Carbon::parse($damagePurchase->damage_date)->format('d-M-Y') }}
                                </td>
                                <td>{{ $damagePurchase->shortNote() }} </td>
                                <td>
                                    @if ($damagePurchase->isActive())
                                        <span class="badge badge-success">{{ __('Active') }}</span>
                                    @else
                                        <span class="badge badge-warning">{{ __('Inactive') }}</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="btn-group">
                                        <button type="button"
                                                class="btn btn-secondary dropdown-toggle action-dropdown-toggle"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            @if ($damagePurchase->isActive())
                                                <a href="{{ route('purchaseDamage.status', $damagePurchase->slug) }}"
                                                    class="dropdown-item"><i class="fas fa-window-close"></i>
                                                    {{ __('Inactive') }}</a>
                                            @else
                                                <a href="{{ route('purchaseDamage.status', $damagePurchase->slug) }}"
                                                    class="dropdown-item"><i class="fas fa-check-square"></i>
                                                    {{ __('Active') }}</a>
                                            @endif
                                            <a href="{{ route('purchaseDamage.show', $damagePurchase->slug) }}"
                                                class="dropdown-item"><i class="fas fa-eye"></i>
                                                {{ __('View') }}</a>
                                            <a href="{{ route('purchaseDamage.edit', $damagePurchase->slug) }}"
                                                class="dropdown-item"><i class="fas fa-edit"></i>
                                                {{ __('Edit') }}</a>
                                            <a href="{{ route('purchaseDamage.delete', $damagePurchase->slug) }}"
                                                class="dropdown-item delete-btn"
                                                data-msg="{{ __('Are you sure you want to delete this purchase damage?') }}"><i
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
                                    <p>{{ __('Sorry, no damage purchase found in the database. Create your very first damage purchase.') }}
                                    </p>
                                    <a href="{{ route('purchaseDamage.create') }}" class="btn btn-primary">
                                        {{ __('Add Damage') }} <i class="fas fa-plus-circle"></i>
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
            {{ $damagePruchases->links() }}
            <!-- pagination end -->
        </div>
    </div>
@endsection
