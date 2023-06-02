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
                <h1 class="m-0 text-dark">{{ __('Edit Fabric') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('fabrics.index') }}">{{ __('Sub Categories') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Edit Fabric') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="card">
            <div class="card-header">
                {{-- <h3 class="card-title">{{ __('Edit sub category') }}: {{ $fabric->name }}</h3> --}}
                <div class="card-tools">
                    <a href="{{ route('fabrics.index') }}" class="btn btn-block btn-primary">
                        <i class="fas fa-long-arrow-alt-left"></i> {{ __('Go Back') }}
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <form class="form-horizontal" action="{{ route('fabrics.update', $fabrics->slug) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="name">{{ __('Fabric Name') }}<span class="required-field">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="{{ __('Fabric Name') }}" value="{{ $fabrics->name }}" required>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="roll_no">{{ __('Roll No') }}<span class="required-field">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="roll_no" name="roll_no" placeholder="{{ __('Roll No') }}" value="{{ $fabrics->roll_no }}" required>
                                @error('roll_no')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="loom_no">{{ __('Loom No') }}<span class="required-field">*</span></label>
                                <input type="text" class="form-control @error('loom_no') is-invalid @enderror" id="loom_no" name="loom_no" placeholder="{{ __('Loom No') }}" value="{{ $fabrics->loom_no }}" required>
                                @error('loom_no')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="gross_wt">{{ __('Gross Weight') }}<span class="required-field">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="gross_wt" name="gross_wt" placeholder="{{ __('Gross Weight') }}" value="{{ $fabrics->gross_wt }}" required>
                                @error('gross_wt')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="net_wt">{{ __('Net Weight') }}<span class="required-field">*</span></label>
                                <input type="text" class="form-control @error('net_wt') is-invalid @enderror" id="net_wt" name="net_wt" placeholder="{{ __('Net Weight') }}" value="{{ $fabrics->net_wt}}" required>
                                @error('net_wt')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6 mt-3">
                                <label for="meter">{{ __('Meter') }}<span class="required-field">*</span></label>
                                <input type="text" class="form-control @error('meter') is-invalid @enderror" id="meter" name="meter" placeholder="{{ __('Meter') }}" value="{{ $fabrics->meter }}" required>
                                @error('meter')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group mt-3">
                                <label for="FabricGroup">{{ __('FabricGroup Name') }}<span class="required-field">*</span></label>
                                <select class="advance-select-box form-control @error('FabricGroup') is-invalid @enderror" id="FabricGroup" name="fabricgroup_id"  required>
                                    <option value="" selected disabled>{{ __('Select a FabricGroup') }}</option>
                                    @foreach($fabricgroups as $key => $group)
                                        <option value="{{ $group->id }}" {{$fabrics->fabricgroup_id == $group->id ? 'selected' : ''}}>{{ $group->name }}</option>
                                    @endforeach
                                </select>
                                @error('fabricgroup_id')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div> 
                             
                        </div>
                  
                   
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="1" {{ $fabrics->isActive() ? 'selected' : '' }}>{{ __('Active') }}</option>
                                    <option value="0" {{ $fabrics->isActive() ? '' : 'selected' }}>{{ __('Inactive') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-edit"></i> {{ __('Save Changes') }}</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    <!-- /.content -->
@endsection

@section('extra-script')
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
@endsection
