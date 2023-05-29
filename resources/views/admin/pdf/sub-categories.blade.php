@extends('layouts.pdf')

@section('content-area')
    <h3>@lang('All Sub Categories')</h3>
    <table class="table-listing table table-bordered">
        <thead>
            <tr>
                <th>@lang('#')</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Category') }}</th>
                <th>{{ __('Sizes') }}</th>
                <th>{{ __('Note') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Created') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subCategories as $key => $subCat)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $subCat->name }} </td>
                    <td>{{ $subCat->category->name }} </td>
                    <td>{{ $subCat->sizes }} </td>
                    <td>{{ $subCat->shortNote() }} </td>
                    <td>
                        @if ($subCat->isActive())
                            <span class="badge badge-success">{{ __('Active') }}</span>
                        @else
                            <span class="badge badge-warning">{{ __('Inactive') }}</span>
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($subCat->created_at)->format('d-M-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
