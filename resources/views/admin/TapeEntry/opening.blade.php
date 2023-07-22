@extends('layouts.admin')

@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6 mt-2">
                <h4><strong>Tape Receive Entry</strong></h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Tape Entry') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            <form id="tape_entry_opening" action="{{ route('tape.opening.store') }}" method="post">
            @csrf
                <div class="card">
                    @if(session()->has('message'))
                        <div class="card-header">
                            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                               {{ session()->get("message") }}
                            </div>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <label for="">Opening Date</label>
                                <input type="date" name="opening_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="">Receipt Number</label>
                                <input type="text" name="receipt_number" class="form-control" value="opening" readonly required>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="">To Godam</label>
                                <select name="to_godam" id="to_godam" class="advance-select-box" required>
                                    <option disabled selected>--Select Godam--</option>
                                    @foreach($godam as $data)
                                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="">Tape Quantity</label>
                                <input type="number" name="tape_quantity" class="form-control" placeholder="Enter Quantity in kg" required>
                            </div>
                            <hr>
                            <div class="col-md-3 mt-3">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
    @endpush
@endsection

@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
@endsection