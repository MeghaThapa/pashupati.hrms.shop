@extends('layouts.pdf')

@section('content-area')
    <h3>@lang('All Suppliers')</h3>
    <table class="table-listing table table-bordered">
        <thead>
            <tr class="table-danger">
                <th scope="col">@lang('#')</th>
                <th scope="col">@lang('Picture')</th>
                <th scope="col">@lang('Name')</th>
                <th scope="col">@lang('Email')</th>
                <th scope="col">@lang('Phone')</th>
                <th scope="col">@lang('Company')</th>
                <th scope="col">@lang('Status')</th>
                <th scope="col">@lang('Created')</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($suppliers as $key => $supplier)
                <tr>
                    <td scope="row">{{ ++$key }}</td>
                    <td>
                        @if (!empty($supplier->profile_picture))
                            <img src="{{ $supplier->profilePic() }}" class="table-image-preview">
                        @else
                            <div class="no-preview"></div>
                        @endif
                    </td>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->email }} </td>
                    <td>{{ $supplier->phone_number }} </td>
                    <td>{{ $supplier->company_name }} </td>
                    <td>
                        @if ($supplier->isActive())
                            <span class="badge badge-success">{{ __('Active') }}</span>
                        @else
                            <span class="badge badge-warning">{{ __('Inactive') }}</span>
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($supplier->created_at)->format('d-M-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
