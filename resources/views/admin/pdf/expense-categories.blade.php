@extends('layouts.pdf')

@section('content-area')
    <h3>@lang('All Expense Categoires')</h3>
    <table class="table-listing table table-bordered">
        <thead>
            <tr>
                <th>@lang('#')</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Note') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Created') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $key => $category)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->shortNote() }} </td>
                    <td>
                        @if ($category->isActive())
                            <span class="badge badge-success">{{ __('Active') }}</span>
                        @else
                            <span class="badge badge-warning">{{ __('Inactive') }}</span>
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($category->created_at)->format('d-M-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
