@extends('layouts.admin')

@section('extra-style')
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
        <a class="btn btn-info ml-3 mb-3" href="{{ route('setup.wastage.create') }}">Create</a>
        <div class="container table-responsive">
           <div class="table-custom">
                <table class="table text-center">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Waste 1</td>
                            <td>ctuce</td>
                            <td>
                               <div class="btn-group text-center">
                                    <a class="btn btn-info" href="javascript:void(0)"><i class="fa fa-edit"></i></a>
                                    <a class="btn btn-danger" href="javascript:void(0)"><i class="fa fa-trash"></i></a>
                               </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
           </div>
        </div>
    </div>
    <!-- /.content -->
@endsection

@section('extra-script')
@endsection