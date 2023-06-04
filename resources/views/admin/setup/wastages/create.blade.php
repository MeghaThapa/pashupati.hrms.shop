@extends('layouts.admin')

@section('extra-style')
<link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{ __('Wastage') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('Setup') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h3>Create wastage</h3>
                    <button class="float-right btn btn-info go-back">Go back</button>
                </div>
                <div class="card-body">
                    <form action=" {{ route('setup.wastage.store')}} " method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Name</label>
                                <input class="form-control" type="text" name="wastage_name" id="wastage_name" value="{{ old('wastage_name') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="">Status</label>
                                <select name="status" id="status" class="select2 advanced-select-box form-control">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-4 mt-3">
                               <button type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection

@section('extra-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
@if(session()->has('message'))
<script>
    $(document).ready(function(e){
        toastr.info('{{ session()->get("message") }}');
    });
</script>
@endif
@endsection