@extends('layouts.pdf')

@section('content-area')
    <h3>@lang('All Users')</h3>
    <table class="table-listing table table-bordered">
        <thead>
            <tr>
                <th>@lang('#')</th>
                <th>{{ __('Picture') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Type') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Created') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $key => $user)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>
                        @if (!empty($user->profile_picture))
                            <img src="{{ $user->profilePic() }}" class="table-image-preview">
                        @else
                            <div class="no-preview"></div>
                        @endif
                    </td>
                    <td>
                        {{ $user->name }}
                    </td>
                    <td>{{ $user->email }} </td>
                    <td>
                        @if ($user->isAdmin())
                            <span class="badge badge-success"><i class="fas fa-user-secret"></i>
                                {{ __('Admin User') }}</span>
                        @else
                            <span class="badge badge-primary"><i class="fas fa-user-tie"></i>
                                {{ __('Genereal User') }}</span>
                        @endif
                    </td>
                    <td>
                        @if ($user->isActive())
                            <span class="badge badge-success">{{ __('Active') }}</span>
                        @else
                            <span class="badge badge-warning">{{ __('Inactive') }}</span>
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d-M-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
