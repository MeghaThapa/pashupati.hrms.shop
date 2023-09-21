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
<div id="error_msg" class="alert alert-danger mt-2" hidden>

</div>
<div class="card-body p-0 m-0">
    <form class="form-horizontal" action="{{ route('singletripalbill.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Bill No') }}<span class="required-field">*</span>
                </label>
                <input type="text" class="form-control" id="billnumber" value="{{ $bill_no }}" name="bill_number"
                    required /> {{-- value="FSR-{{ getNepalidate(date('Y-m-d')).'-'.rand(0,9999)}}" --}}
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
                    @foreach ($godam as $data)
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
<div class="row">
    <div class="Ajaxdata col-md-12">
        <div class="p-0 table-responsive table-custom my-3">
            <table class="table" id="tripalListTable" >
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
                  {{--   @foreach($datas as $data)
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
                                <a href="{{ route('addsingletripal.create', $data->id) }}"
                                    class="btn btn-info" target="_blank"><i class="fas fa-plus"></i>
                                </a>

                                <a href="{{ route('addsingletripal.edit', $data->id) }}"
                                        class="btn btn-primary" target="_blank"><i class="fas fa-edit"></i>
                                </a>
                                
                            </div>
                        </td>
                        
                    </tr>
                    @endforeach --}}
                </tbody>

            </table>
        </div>

    </div>
</div>
<hr>
{{-- <h1 class='text-center'>Compare Lam and Unlam</h1> --}}

<hr>




<!-- Modal -->



@endsection
@section('extra-script')
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
<script src="{{ asset('js/storein.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
</script>
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
            $("#godam_ids").val(department_id);
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
            $("#planttype_id").val(department_id);
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

        $("#plantName").change(function(e){
            let department_id =  $(this).val();
            $("#plantname_id").val(department_id);
        
        });

    });


    function addplanttype(data){
        $("#plantType").empty();
        $('#plantType').append(`<option value="" disabled selected>--Select Plant Type--</option>`);
        data.planttype.forEach( d => {
            $('#plantType').append(`<option value="${d.id}">${d.name}</option>`);
        });
    }


    function addplantname(data){
        $("#plantName").empty();
        $('#plantName').append(`<option value="" disabled selected>--Select Plant Name--</option>`);
        data.plantname.forEach( d => {
            // if(d.name == '')
            $('#plantName').append(`<option value="${d.id}">${d.name}</option>`);

        });
    }
 
</script>

<script>
    $('document').ready(function() {
        var table = $('#tripalListTable').DataTable({
            lengthMenu: [
                [30, 40, 50, -1],
                ['30 rows', '40 rows', '50 rows', 'Show all']
            ],
            style: 'bootstrap4',
            processing: true,
            serverSide: true,
            ajax: "{{ route('singleTripal.dataTable') }}",
            columns: [{
                    data: 'DT_RowIndex'
                },
                {
                    data: 'bill_no'
                },
                {
                    data: 'bill_date'
                },
                {
                    data: 'planttype'
                },
                {
                    data: 'plantname'
                },
                {
                    data: 'shift'
                },
                {
                    data: 'godam'
                },

                {
                    data: 'action',
                    orderable: true,
                    searchable: true,
                },
            ]
        });
        $('body').on('click', '#rawMaterialDeleteBtn', function(e) {
            let rawMaterial_id = this.getAttribute('data-id');
            console.log('js', rawMaterial_id);
            new swal({
                title: "Are you sure?",
                text: "Once deleted, data will deleted completely!!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true

            }).then((willDelete) => {
                if (willDelete.isConfirmed) {

                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('rawMaterial.delete', ['rawMaterial_id' => ':id']) }}"
                            .replace(':id', rawMaterial_id),
                        data: {
                            '_token': $('meta[name=csrf-token]').attr("content"),
                        },
                        success: function(data) {
                            console.log('controller:', data);
                            new swal
                                ({
                                    text: "Poof! Your data has been deleted!",
                                    title: "Deleted",
                                    icon: "success",
                                });
                            location.reload();
                        },
                        error: function(xhr) {
                            setMessage('RawMaterialError', xhr.responseJSON.message)

                            //console.log(xhr.responseJSON.message);
                        }
                    })

                }
            })
        });

        function setMessage(element_id, message) {
            let errorContainer = document.getElementById(element_id);
            errorContainer.hidden = false;
            errorContainer.innerHTML = message;
            setTimeout(function() {
                errorContainer.hidden = true;
            }, 2000);
        }

    });
</script>
@endsection