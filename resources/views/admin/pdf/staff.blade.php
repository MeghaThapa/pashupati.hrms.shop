@extends('layouts.pdf')

@section('content-area')
    <h3>@lang('All Staff')</h3>
    <table class="table-listing table table-bordered">
        <thead>
            <tr>
                <th>@lang('#')</th>
                <th>{{ __('Picture') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Phone') }}</th>
                <th>{{ __('Desigantion') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Created') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($allStaff as $key => $staff)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>
                        @if (!empty($staff->profile_picture))
                            <img src="{{ $staff->profilePic() }}" class="table-image-preview">
                        @else
                            <div class="no-preview"></div>
                        @endif
                    </td>
                    <td>{{ $staff->name }}</td>
                    <td>{{ $staff->email }} </td>
                    <td>{{ $staff->phone_number }} </td>
                    <td>{{ $staff->designation }} </td>
                    <td>
                        @if ($staff->isActive())
                            <span class="badge badge-success">{{ __('Active') }}</span>
                        @else
                            <span class="badge badge-warning">{{ __('Inactive') }}</span>
                        @endif

                    </td>
                    <td>{{ \Carbon\Carbon::parse($staff->created_at)->format('d-M-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
