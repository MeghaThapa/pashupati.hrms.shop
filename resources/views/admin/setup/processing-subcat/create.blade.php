@extends('layouts.admin')

@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    @if ($errors->any())
        <div id="error-container" class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Create Processing Subcategory') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.setup') }}">{{ __('Setup') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('processing-subcat.index') }}">{{ __('Processing Subcat') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Create Processing Subcat') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Add a new processing subcategory') }}</h3>
                <div class="card-tools">
                    <a href="{{ route('processing-subcat.index') }}" class="btn btn-block btn-primary">
                        <i class="fas fa-long-arrow-alt-left"></i> {{ __('Go Back') }}
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <form class="form-horizontal"
                @if ($processingSubCat)
                action="{{ route('processing-subcat.update',['processingSubCatId'=>$processingSubCat->id]) }}"
                @else
                action="{{ route('processing-subcat.store') }}"
                @endif
                method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="name">{{ __('Processing Subcategory Name') }}<span class="required-field">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="{{ __('Processing Subcat Name') }}"
                                @if ($processingSubCat)
                                    value="{{$processingSubCat->name}}"
                                @endif
                                required>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="name">{{ __('Select Department') }}<span class="required-field">*</span></label>
                                <select
                                    class="advance-select-box select-2 form-control @error('department_id') is-invalid @enderror"
                                    id="departmentId"  required> {{-- name="department_id" --}}
                                    <option value="" selected disabled>{{ __('Select Processing Steps') }}</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}"
                                            @if ($processingSubCat)
                                           {{$department->id == $processingSubCat->department_id ? 'selected':''}}
                                        @endif
                                            >{{ $department->department }}</option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="processingSteps">{{ __('Processing Steps') }}<span
                                        class="required-field">*</span>
                                </label>
                                <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal" data-target="#exampleModal" style="margin-top:0 !important; top:0;float:right;">
                                    <i class="fas fa-plus" style="display:flex;align-items: center;justify-content: center;"></i>
                                </a>
                                <select
                                    class="advance-select-box select-2 form-control @error('department') is-invalid @enderror"
                                    id="processingStepsId" name="processing_steps_id" required>
                                    <option value="" selected disabled>{{ __('Select Processing Steps') }}</option>

                                </select>
                                @error('department')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                 </span>
                                @enderror

                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="form-group col-md-12">
                                <label for="note" class="col-form-label">{{ __('Processing Step Note') }}</label>
                                <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" placeholder="{{ __('Processing Step Note') }}">{{ old('note') }}</textarea>
                                @error('note')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div> --}}
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="status" class="col-form-label">{{ __('Status') }}</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="active">{{ __('Active') }}</option>
                                    <option value="inactive">{{ __('Inactive') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> @if ($processingSubCat) {{ __('Update Processing Subcategory') }} @else {{ __('Save Processing Subcategory') }} @endif</button>
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
    <script src="{{ asset('js/storein.js') }}"></script>
    <script>
$(document).ready(function(){

        let processingSubCatObj =JSON.parse(`{!! json_encode($processingSubCat) !!}`);

        if(processingSubCatObj){
            update();
        }
        async function update(){
            await getProcessingStepsAccDept(processingSubCatObj.department_id);
            $('#processingStepsId').val(processingSubCatObj.processing_steps_id).trigger('change');
        }

        $('#departmentId').on('select2:select',function(e){
           let department_id= e.params.data.id;
            getProcessingStepsAccDept(department_id);
        });


        function getProcessingStepsAccDept(department_id){
            return new Promise(function(resolve, reject) {
            $.ajax({
                url:"{{route('processing-subcat.getProcessingStepsAccDept',['department_id'=>':dpt_id'])}}"
                .replace(':dpt_id',department_id),
                method:"GET",
                 success:  function(response) {
                            let selectOptions = '';
                            if (response.processingSteps.length == 0) {
                                selectOptions +=
                                    '<option disabled selected>' +
                                    'no processing step found' + '</option>';
                            } else {
                                selectOptions +=
                                    '<option disabled selected>' +
                                    'select a processing step' + '</option>';
                                for (var i = 0; i < response.processingSteps.length; i++) {
                                    selectOptions += '<option value="' +
                                        response.processingSteps[i].id +
                                        '">' +
                                        response.processingSteps[i].name + '</option>';
                                }
                            }
                            $('#processingStepsId').html(selectOptions);
                            resolve(response)
                        }
            });
        });

        }
});

    </script>
@endsection


