@extends('layouts.admin')
@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('fabric transfer for bag receipts') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('fabric transfer for bag receipts') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    {{-- message for success --}}
                    <div id="success_msg" class="alert alert-success mt-2" hidden></div>
                    <div id="error_msg" class="alert alert-danger mt-2" hidden></div>
                    <button class='go-back btn btn-primary float-right'>Go Back</button>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="post" action="{{ route('fabric.transfer.entry.for.bag.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="receipt_number"> Receipt Number</label>
                                    <input type="text" step="any" min="0" value="{{ $bill_no }}"
                                        class="form-control" name="receipt_number" id="receipt-number" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="receipt_date"> Receipt Date</label>
                                    <input type="date" value="{{ date('Y-m-d') }}" step="any" min="0"
                                        class="form-control" name="receipt_date" id="receipt_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="receipt_date">Date NP</label>
                                    <input type="text" value="{{ $date_np }}" step="any" min="0"
                                        class="form-control" name="date_np" id="receipt_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    Create
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('extra-script')
    <script>
        $(document).on("click", ".go-back", function() {
            history.back();
        });
    </script>
    @if (session()->has('message'))
        <script>
            toastr.success("{{ session()->get('message') }}");
        </script>
    @elseif(session()->has('message_err'))
        <script>
            toastr.error("{{ session()->get('message_err') }}");
        </script>
    @endif
@endsection
