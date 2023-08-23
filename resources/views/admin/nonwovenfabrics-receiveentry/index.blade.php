@extends('layouts.admin')
@section('extra-style')
<link href="{{ asset('css/nepaliDatePicker/nepali.datepicker.v4.0.1.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
<style>
    .col-form-label {
        font-size: 12px !important;

    }

    .dynamic-btn {
        height: 18px;
        width: 4px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #storeinSubmitBtn {
        height: 25px;
        width: 70px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 5px !important;
    }

    .fa-plus {
        font-size: 10px;
    }

    .form-control {
        font-size: 12px !important;

    }

    .select2-selection__rendered,
    .select2-container--bootstrap4 .select2-selection {
        font-size: 12px !important;
        display: flex !important;
        align-items: center !important;
        height: calc(1.6em + 0.75rem + 2px) !important;
    }

    .select2-container {
        height: calc(1.6em + 0.75rem + 2px) !important;
    }

    .taxStyle .select2-selection {
        width: 200px !important;
    }

    .form-group {
        margin-bottom: 0px !important;
    }

    .content-wrapper {
        padding-top: 0px !important;
    }

    .card-body {
        padding: 0px 5px !important;
    }

    .card {
        padding: 0px 5px !important;
    }

    .col-md-6 {
        padding: 0px 2px !important;
    }


</style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Non Woven Factory Receive Entry') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('NonWovwn Factory Receive Entry') }}</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="card-body p-0 m-0">
        <form class="form-horizontal" action="{{ route('nonwovenbill.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('Bill No') }}<span class="required-field">*</span>
                    </label>
                    <input type="text" class="form-control" id="billnumber" value="{{ $bill_no }}" name="bill_number"
                        required />
                </div>

                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('Bill Date') }}
                    </label>
                    <input  step="any" min="0" class="form-control calculator"
                        id="billDate" data-number="1" name="bill_date" placeholder="{{ __('Remarks') }}" min="1" required>

                    @error('bill_date')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('To Godam') }}
                    </label>
                    <select class="advance-select-box form-control" id="toGodam" name="to_godam_id" required>
                        <option value="" selected>{{ __('Select Godam Name') }}</option>
                        @foreach ($godams as $data)
                        <option value="{{ $data->id }}">{{ $data->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('to_godam_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('Plant Type') }}
                    </label>
                    <select class="advance-select-box form-control" id="plantType" name="plant_type_id" required>
                        <option value="" selected>{{ __('Select Plant Name') }}</option>
                        {{-- @foreach ($danaNames as $danaName)
                        <option value="{{ $danaName->id }}">{{ $danaName->name }}
                        </option>
                        @endforeach --}}
                    </select>
                    @error('plant_type_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('Plant Name') }}
                    </label>
                    <select class="advance-select-box form-control" id="plantName" name="plant_name_id" required>
                        <option value="" selected>{{ __('Select Plant Name') }}</option>
                        {{-- @foreach ($danaNames as $danaName)
                        <option value="{{ $danaName->id }}">{{ $danaName->name }}
                        </option>
                        @endforeach --}}
                    </select>
                    @error('gp_no')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('Shift') }}
                    </label>
                    <select class="advance-select-box form-control" id="shiftName" name="shift_name_id"  required>
                        <option value="" selected>{{ __('Select Shift Name') }}</option>
                        @foreach ($shifts as $shift)
                        <option value="{{ $shift->id }}">{{ $shift->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('shift_name_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-md-2 form-group">
                    <button type="submit" class="btn btn-sm btn-primary" style="margin-top:35px;">
                        Add
                    </button>
                </div> 

            </div>
         
        </form>
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
                    {{-- <form action="{{ route('fabric-groups.index') }}" method="GET" role="search">
                        <div class="input-group">
                            <input type="text" name="term" placeholder="{{ __('Type name or code...') }}"
                                    class="form-control" autocomplete="off"
                                    value="{{ request('term') ? request('term') : '' }}" required>
                            <span class="input-group-append">
                                    <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
                                </span>
                        </div>
                    </form> --}}
                </div>
                <div class="col-lg-9 col-md-7 col-6">
                    <div class="card-tools text-md-right">
                        <a class="btn btn-secondary" href="{{ route('fabric-groups.pdf') }}">
                            <i class="fas fa-download"></i> @lang('Export')
                        </a>
                        <a href="{{ route('nonwovenfabrics-receiveentry.create') }}" class="btn btn-primary">
                            {{ __('Add NonWoven Recive Entry') }} <i class="fas fa-plus-circle"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="p-0 table-responsive table-custom my-3">
                <table class="table">
                    <thead>
                    <tr>
                        <th>{{ __('Sr.No') }}</th>
                        <th>{{ __('Bill No') }}</th>
                        <th>{{ __('Bill Date') }}</th>
                        <th>{{ __('Plantype') }}</th>
                        <th>{{ __('Plantname') }}</th>
                        <th>{{ __('Shift') }}</th>
                        <th>{{ __('Godam') }}</th>
                        <th>{{__('Action')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($datas as $data)
                        <tr>
                            <td>#</td>
                            <td>{{$data->bill_no}}</td>
                            <td>{{$data->bill_date}}</td>
                            <td>{{$data->getPlantType->name}}</td>
                            <td>{{$data->getPlantName->name}}</td>
                            <td>{{$data->getShift->name}}</td>
                            <td>{{$data->getGodam->name}}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('nonwovenentry.create', $data->id) }}"
                                        class="btn btn-info" target="_blank"><i class="fas fa-plus"></i>
                                    </a>

                                    {{-- <a href="{{ route('addsingletripal.edit', $data->id) }}"
                                            class="btn btn-primary" target="_blank"><i class="fas fa-edit"></i>
                                    </a> --}}
                                    
                                </div>
                            </td>
                            
                        </tr>
                        @endforeach
                    </tbody>
              
                </table>
            </div>
            <!-- /.card-body -->


            <!-- pagination start -->
            {{-- {{ $fabricgroups->links() }} --}}
            <!-- pagination end -->
        </div>
    </div>
@endsection
@section('extra-script')
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
<script src="{{ asset('js/storein.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
<script src="{{ asset('js/nepaliDatePicker/nepali.datepicker.v4.0.1.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
  var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
  $('#billDate').val(currentDate);
  $('#billDate').nepaliDatePicker({
    ndpYear: true,
    ndpMonth: true,
    disableAfter: currentDate,
    });
  
  });
</script>

<script>
    $(document).ready(function(){

        $("#toGodam").change(function(e){
            let department_id =  $(this).val();
            let geturl = "{{ route('fabricSendReceive.get.planttype',['id'=>':id']) }}"
            $.ajax({
                url:geturl.replace(':id',department_id),
                beforeSend:function(){
                    console.log('Getting Plant type');
                },
                success:function(response){
                    addplanttype(response);
                },
                error:function(error){
                    console.log(error);
                }
            });
        });

        $("#plantType").change(function(e){
            let department_id =  $(this).val();
            let geturl = "{{ route('fabricSendReceive.get.plantname',['id'=>':id']) }}";
            $.ajax({
                url:geturl.replace(':id',department_id),
                beforeSend:function(){
                    console.log('Getting Plant Name');
                },
                success:function(response){
                    addplantname(response);
                },
                error:function(error){
                    console.log(error);
                }
            });
        });

        $("#shiftName").change(function(e){
            let department_id =  $(this).val();
            let geturl = "{{ route('fabricSendReceive.get.fabrics') }}";
            $.ajax({
                url:geturl.replace(':id',department_id),
                beforeSend:function(){
                    console.log('Getting Fabrics');
                },
                success:function(response){
                    getfabrics(response);
                },
                error:function(error){
                    console.log(error);
                }
            });
        });
  
    });

    function addplanttype(data){
        $("#plantType").empty();
        $('#plantType').append(`<option value="" disabled selected>Select Planttype</option>`);
        data.planttype.forEach( d => {
            $('#plantType').append(`<option value="${d.id}">${d.name}</option>`);
        });
    }

    function addplantname(data){
        console.log(data);
        $("#plantName").empty();
        $('#plantName').append(`<option value="" disabled selected>Select Plantname</option>`);
        data.plantname.forEach( d => {
            // if(d.name == '')
            $('#plantName').append(`<option value="${d.id}">${d.name}</option>`);
        });
    }

</script>
@endsection 
