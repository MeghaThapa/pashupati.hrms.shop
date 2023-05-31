@extends('layouts.admin')

@section('extra-style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
 <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6 mt-2">
                <h4 ><strong>Tape Receive Entry</strong></h4>
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
            <div class="card">
                <div class="card-header">
                    <h4>Create Tape Entries</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('tape.entry.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label for="tape_receive_date">Tape Date</label>
                                <input type="date" name="tape_receive_date" class="form-control" value="{{ date("Y-m-d") }}" required/>
                           </div>
                           <div class="col-md-6">
                                <label for="receipt_number">Receipt Number</label>
                                <input type="text" value="{{ 'TR'.'-'.getNepaliDate(date('Y-m-d')).'-'.rand(0,999) }}" name="receipt_number" class="form-control" required/>
                           </div>
                           <div class="col-md-4 mt-2">
                                <button class="btn btn-primary" type="submit">Create Tape Entry</button>
                           </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Tape Entries</h4>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="myTable">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Receipt Number</th>
                                <th>Tape Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($tapeentries) > 0)
                                @foreach($tapeentries as $key => $data)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $data->receipt_number }}</td>
                                        <td>{{ $data->tape_entry_date }}</td>
                                        <td>
                                            <div class="button-group">
                                                <a href="{{ route('tape.entry.receive.create',['id'=>$data->id]) }}" class="btn btn-info" title="create tape receive entry">Create</a>
                                                <a href="javascript:void(0)"class="btn btn-danger">Delete</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center">No Data Found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-script')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@if(session()->has('message'))
    <script>
        toastr.success('{{ session()->get("message")}}');
    </script>
@endif
<script>
    let table = new DataTable('#myTable');
</script>
@endsection
