@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Fabric') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Fabric') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="col-md-12">
                @include('admin.includes.alert')
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-5 col-6 mb-2">
                    <form action="{{ route('fabrics.index') }}" method="GET" role="search">
                        <div class="input-group">
                            <input type="text" name="term"
                                    placeholder="{{ __('Type name or category name...') }}"
                                    class="form-control" autocomplete="off"
                                    value="{{ request('term') ? request('term') : '' }}" required>
                            <span class="input-group-append">
                                    <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
                                </span>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6 col-md-7 col-6">
                    <div class="card-tools text-md-right">
                       <a href="{{ route('fabrics.create') }}" class="btn btn-primary">
                            {{ __('Add Fabric') }} <i class="fas fa-plus-circle"></i>
                        </a>

                    </div>


                </div>
                <div class="col-lg-3 col-md-7 col-6">
                    <div class="card-tools text-md-right">

                        <form action="{{ route('import.fabric') }}" method="POST" enctype="multipart/form-data">
                          @csrf
                          <div class=" form-group">
                              <label for="size" class="col-form-label">{{ __('To Department') }}
                              </label>
                              <select class="advance-select-box form-control" id="department_id" name="department_id" required>
                                  <option value="" selected disabled>{{ __('Select Department Name') }}</option>
                                 @foreach ($departments as $data)
                                      <option value="{{ $data->id }}">{{ $data->name }}
                                  </option>
                                  @endforeach
                              </select>
                          </div>
                          <div class="form-group mb-2" style="max-width: 500px; margin: 0 auto;">
                              <div class="custom-file text-left">
                                  <input type="file" name="file" class="custom-file-input" id="customFile" class="d-none" >
                                  <label class="custom-file-label" for="customFile">Choose file</label>

                              </div>

                          </div> 
                          <button class="btn btn-primary">Import data</button>
                          
                          @error('file')
                          <span class="text-danger font-italic" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                          @enderror
                        </form> 
                    </div>


                </div>
            </div>

            <div class="p-0 table-responsive table-custom my-3">
                <table class="table">
                    <thead>
                    <tr>
                        <th>@lang('#')</th>
                        <th>{{ __('Roll NO') }}</th>
                        <th>{{ __('Loom NO') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Gross weight') }}</th>
                        <th>{{ __('Net Weight') }}</th>
                        <th>{{ __('Meter') }}</th>
                        <th>{{ __('Average Weight') }}</th>
                        <th>{{ __('Gram Weight') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Created') }}</th>
                        <th class="text-right">{{ __('Action') }}</th>
                    </tr>
                    </thead>
                    <tbody>

                    @if ($fabrics->total() > 0)
                        @foreach ($fabrics as $key => $fabric)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $fabric->roll_no }} </td>
                                <td>{{ $fabric->loom_no }} </td>
                                <td>{{ $fabric->name }} ({{$fabric->fabricgroup->name}}) </td>
                                <td>{{ $fabric->gross_wt}} </td>
                                <td>{{ $fabric->net_wt }}</td>
                                <td>{{ $fabric->meter }}</td>
                                <td>{{round($fabric->average_wt, 2)}}
                                <td>{{round($fabric->gram_wt, 2)}}</td>
                                </td>
                              
                                {{-- <td>{{round(round($fabric->gram, 2) /  (int) filter_var($fabric->name, FILTER_SANITIZE_NUMBER_INT) )}}</td> --}}
                                <td>
                                    @if ($fabric->isActive())
                                        <span class="badge badge-success">{{ __('Active') }}</span>
                                    @else
                                        <span class="badge badge-warning">{{ __('Inactive') }}</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($fabric->created_at)->format('d-M-Y') }}</td>
                                <td class="text-right">
                                    <div class="btn-group">
                                        <button type="button"
                                                class="btn btn-secondary dropdown-toggle action-dropdown-toggle"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            @if ($fabric->isActive())
                                                <a href="{{ route('fabrics.status', $fabric->slug) }}"
                                                    class="dropdown-item"><i class="fas fa-window-close"></i>
                                                    {{ __('Inactive') }}</a>
                                            @else
                                                <a href="{{ route('fabrics.status', $fabric->slug) }}"
                                                    class="dropdown-item"><i class="fas fa-check-square"></i>
                                                    {{ __('Active') }}</a>
                                            @endif
                                            <a href="{{ route('fabrics.edit', $fabric->slug) }}"
                                                class="dropdown-item"><i class="fas fa-edit"></i>
                                                {{ __('Edit') }}</a>
                                            <a href="{{ route('fabrics.delete', $fabric->slug) }}"
                                                class="dropdown-item delete-btn"
                                                data-msg="{{ __('Are you sure you want to delete this sub category?') }}"><i
                                                    class="fas fa-trash"></i> {{ __('Delete') }}</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="10">
                                <div class="data_empty">
                                    <img src="{{ asset('img/result-not-found.svg') }}" alt="" title="">
                                    <p>{{ __('Sorry, no sub category found in the database. Create your very first sub category.') }}
                                    </p>
                                    <a href="{{ route('fabrics.create') }}" class="btn btn-primary">
                                        {{ __('Add Sub Category') }} <i class="fas fa-plus-circle"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endif


                    </tbody>
                </table>
            </div>

            <!-- /.card-body -->

            <!-- pagination start -->
            {{ $fabrics->links() }}

            <div class="card-body p-0">
                <form class="form-horizontal" action="{{ route('fabrics.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="name">{{ __('Pipe  Name') }}<span class="required-field">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="{{ __('Fabric Name') }}" value="{{ old('name') }}" required>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="FabricGroup">{{ __('FabricGroup Name') }}<span class="required-field">*</span></label>
                                <select class="advance-select-box form-control @error('FabricGroup') is-invalid @enderror" id="FabricGroup" name="fabricgroup_id"  required>
                                    <option value="" selected disabled>{{ __('Select a FabricGroup') }}</option>
                                  {{--   @foreach($fabricgroups as $key => $group)
                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach --}}
                                </select>
                                @error('fabricgroup_id')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>

                            <div class="col-md-4 form-group">
                                <label for="FabricGroup">{{ __('Department Name') }}<span class="required-field">*</span></label>
                                <select class="advance-select-box form-control @error('godam_id') is-invalid @enderror" id="godam_id" name="godam_id"  required>
                                    <option value="" selected disabled>{{ __('Select a Godam') }}</option>
                               {{--      @foreach($godams as $key => $group)
                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach --}}
                                </select>
                                @error('godam_id')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>

                        </div>
                 
                        <div class="row">

                            <div class="form-group col-md-6">
                                <label for="meter" class="col-form-label">{{ __('Meter') }} <span class="required-field">*</span></label>
                                <input type="text" class="form-control @error('meter') is-invalid @enderror" id="meter" name="meter" placeholder="{{ __('Meter') }}" value="{{ old('name') }}" required>
                                @error('meter')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div> 
                            <div class="form-group col-md-6">
                                <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                <select class="form-control" id="status" name="status" >
                                    <option value="1">{{ __('Active') }}</option>
                                    <option value="0">{{ __('Inactive') }}</option>
                                </select>
                            </div>
                             
                             
                        </div>
                        
                        <div class="row">
                            
                            
                        </div>
                        <div class="row">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ __('Save Fabric') }}</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </form>
            </div>


            <!-- pagination end -->
        </div>
    </div>

    <!-- /.content -->
@endsection
