@extends('layouts.admin')
@section('extra-style')
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
        }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              } */
    </style>
@endsection
@section('content')
 {{-- message for success --}}
 <div id="success_msg" class="alert alert-success mt-2" hidden>
</div>
<div id="error_msg" class="alert alert-danger mt-2" hidden>
</div>
<div class="card-body p-0 m-0">
    <form id="createAutoLoadItem">
        @csrf
        <div class="row">
            <div class="col-md-3 form-group">

                <input type="text" step="any" min="0" class="form-control calculator"  value="{{$autoload->id}}"
                    data-number="1" name="autoload_id"  id="autoloadId" min="1" hidden>
                <label for="size" class="col-form-label">{{ __('From Godam') }}
                </label>
                <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                    data-target="#addDanaNameModel" style="margin-top:0 !important; top:0;float:right;">
                    <i class="fas fa-plus" style="display:flex;align-items: center;justify-content: center;"></i>
                </a>
                <select class="advance-select-box form-control" id="fromGodamId" name="from_godam_id" required>
                    <option value="" selected disabled>{{ __('Select From Godam') }}</option>
                    @foreach ($fromGodams as $fromGodam)
                        <option value="{{ $fromGodam->department_id }}">{{ $fromGodam->department->department}}
                        </option>
                    @endforeach
                </select>
                @error('from_godam_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-3 form-group">
                <label for="size" class="col-form-label">{{ __('Plant Type') }}
                </label>
                <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                    data-target="#addDanaNameModel" style="margin-top:0 !important; top:0;float:right;">
                    <i class="fas fa-plus" style="display:flex;align-items: center;justify-content: center;"></i>
                </a>
                <select class="advance-select-box form-control" id="plantTypeId" name="plant_type_id" required>
                    <option value="" selected disabled>{{ __('Select Plant Type') }}</option>

                </select>
                @error('plant_type_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-3 form-group">
                <label for="size" class="col-form-label">{{ __('Plant Name') }}
                </label>
                <select class="advance-select-box form-control" id="plantNameId" name="plant_name_id" required>
                    <option value="" selected disabled>{{ __('Select Plant Name') }}</option>

                </select>
                @error('plant_name_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-3 form-group">
                <label for="size" class="col-form-label">{{ __('Shift') }}
                </label>
                <select class="advance-select-box form-control" id="shiftId" name="shift_id" required>
                    <option value="" selected disabled>{{ __('Select Shift') }}</option>
                    @foreach ($shifts as $shift)
                        <option value="{{ $shift->id }}">{{ $shift->name }}
                        </option>
                    @endforeach
                </select>
                @error('shift_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

        </div>
        <div class="row">
            <div class="col-md-3 form-group">
                <label for="size" class="col-form-label">{{ __('Dana Group') }}
                </label>
                <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                    data-target="#addDanaGroupModel" style="margin-top:0 !important; top:0;float:right;">
                    <i class="fas fa-plus" style="display:flex;align-items: center;justify-content: center;"></i>
                </a>
                {{-- <select class="advance-select-box form-control" id="danaGroup" name="dana_group" required>
                    <option value="" selected disabled>{{ __('Select dana Group') }}</option>
                    @foreach ($danaGroups as $danaGroup)
                        <option value="{{ $danaGroup->id }}">{{ $danaGroup->name }}
                        </option>
                    @endforeach
                </select> --}}
                <select class="advance-select-box form-control" id="danaGroupId" name="dana_group_id" required>
                    <option value="" selected disabled>{{ __('Select dana Group') }}</option>
                </select>
                @error('Receipt_no')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-3 form-group">
                <label for="size" class="col-form-label">{{ __('Dana Name') }}
                </label>
                <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                    data-target="#addDanaNameModel" style="margin-top:0 !important; top:0;float:right;">
                    <i class="fas fa-plus" style="display:flex;align-items: center;justify-content: center;"></i>
                </a>
                <select class="advance-select-box form-control" id="danaName" name="dana_name_id" required>
                    <option value="" selected disabled>{{ __('Select dana Name') }}</option>
                </select>
                @error('gp_no')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-2 form-group">
                <label for="size" class="col-form-label">{{ __('Qty in Kg') }}
                </label>
                <input type="text" step="any" min="0" class="form-control calculator" id="quantityInKg"
                    data-number="1" name="quantity_in_kg" placeholder="{{ __('Quantity') }}" min="1" required>
                @error('gp_no')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div>
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
                <table class="table" id="autoLoadItemTable">
                    <thead>
                        <tr>
                            <th>{{ __('S.No') }}</th>
                            <th>{{ __('From Godam') }}</th>
                            <th>{{ __('Plant Type') }}</th>
                            <th>{{ __('Plant Name') }}</th>
                            <th>{{ __('Shift') }}</th>
                            <th>{{__('Dana Group')}}</th>
                            <th>{{__('Dana Name')}}</th>
                            <th>{{__('Quantity')}}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>

                    <tbody id="autoLoadItemTbody">
                    </tbody>

                </table>

                {{-- for sub total --}}
                {{-- <input type="text" id="subTotal"> --}}
            </div>

        </div>
        <!-- Modal -->
    <div class="modal fade" id="editAutoloadItemModel" tabindex="-1" role="dialog" aria-labelledby="tryModelLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tryModelLabel">Modal Title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editAutoloadItemModelUpdate">
                    <div class="modal-body">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                {{-- recent --}}
                                 <div class="col-md-12 form-group">
                                    <input type="text" step="any" min="0" class="form-control calculator"
                                        data-number="1" name="autoload_item_id_model"  id="autoloadItemIdModel" min="1" >
                                    <label for="size" class="col-form-label">{{ __('From Godam') }}
                                    </label>
                                    <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                                        data-target="#addDanaNameModel" style="margin-top:0 !important; top:0;float:right;">
                                        <i class="fas fa-plus" style="display:flex;align-items: center;justify-content: center;"></i>
                                    </a>
                                    <select class="advance-select-box form-control" id="fromGodamIdModel" name="from_godam_id_model" required>
                                        <option value="" selected disabled>{{ __('Select From Godam') }}</option>
                                        @foreach ($fromGodams as $fromGodam)
                                            <option value="{{ $fromGodam->department->id }}">{{ $fromGodam->department->department}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('from_godam_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                 <div class="col-md-12 form-group">
                                    <label for="size" class="col-form-label">{{ __('Plant Type') }}
                                    </label>
                                    <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                                        data-target="#addDanaNameModel" style="margin-top:0 !important; top:0;float:right;">
                                        <i class="fas fa-plus" style="display:flex;align-items: center;justify-content: center;"></i>
                                    </a>
                                    <select class="advance-select-box form-control" id="plantTypeIdModel" name="plant_type_id_model" required>
                                        <option value="" selected disabled>{{ __('Select Plant Type') }}</option>
                                        @foreach ($plantTypes as $plantType)
                                            <option value="{{ $plantType->id }}">{{ $plantType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('plant_type_id_model')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                  <div class="col-md-12 form-group">
                                    <label for="size" class="col-form-label">{{ __('Plant Name') }}
                                    </label>
                                    <select class="advance-select-box form-control" id="plantNameIdModel" name="plant_name_id_model" required>
                                        <option value="" selected disabled>{{ __('Select Plant Name') }}</option>

                                    </select>
                                    @error('plant_name_id_model')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-12 form-group">
                                    <label for="size" class="col-form-label">{{ __('Shift') }}
                                    </label>
                                    <select class="advance-select-box form-control" id="shiftIdModel" name="shift_id_model" required>
                                        <option value="" selected disabled>{{ __('Select Shift') }}</option>
                                        @foreach ($shifts as $shift)
                                            <option value="{{ $shift->id }}">{{ $shift->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('shift_id_model')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-12 form-group">
                                    {{-- <input type="text" step="any" min="0" class="form-control calculator"
                                        id="rawMaterialItemIdModel" data-number="1" name="rawMaterial_item_id_model"
                                        min="1" required hidden> --}}
                                    <label for="size" class="col-form-label">{{ __('Dana Group') }}
                                    </label>
                                    <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                                        data-target="#addDanaGroupModel"
                                        style="margin-top:0 !important; top:0;float:right;">
                                        <i class="fas fa-plus"
                                            style="display:flex;align-items: center;justify-content: center;"></i>
                                    </a>
                                    <select class="advance-select-box form-control" id="danaGroupIdModel"
                                        name="dana_group_id_model" required>
                                        <option value="" selected disabled>{{ __('Select dana Group') }}</option>
                                        {{-- @foreach ($danaGroups as $danaGroup)
                                            <option value="{{ $danaGroup->id }}">{{ $danaGroup->name }}
                                            </option>
                                        @endforeach --}}
                                    </select>

                                    @error('Receipt_no')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-12 form-group">
                                    <label for="size" class="col-form-label">{{ __('Dana Name') }}
                                    </label>
                                    <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal"
                                        data-target="#addDanaNameModel"
                                        style="margin-top:0 !important; top:0;float:right;">
                                        <i class="fas fa-plus"
                                            style="display:flex;align-items: center;justify-content: center;"></i>
                                    </a>
                                    {{-- <input type="text" step="any" min="0" class="form-control calculator" id="remarks"
                                    data-number="1" name="remarks" placeholder="{{ __('Remarks') }}" min="1" required> --}}
                                    <select class="advance-select-box form-control" id="danaNameModel"
                                        name="dana_name_id_model" required>
                                        <option value="" selected disabled>{{ __('Select dana Name') }}</option>
                                    </select>
                                    @error('gp_no')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-12 form-group">
                                    <label for="size" class="col-form-label">{{ __('Qty in Kg') }}
                                    </label>
                                    <input type="text" step="any" min="0" class="form-control calculator"
                                        id="quantityInKgModel" data-number="1" name="quantity_in_kg_model"
                                        placeholder="{{ __('Remarks') }}" min="1" required>
                                    @error('gp_no')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    </div>
@endsection
@section('extra-script')
  <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/storein.js') }}"></script>
    <script>
        $(document).ready(function(){
            getAutoloadItemsData();
            //create raw material

            document.getElementById('createAutoLoadItem').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = event.target;
                let autoload_id= form.elements['autoload_id'];
               let from_godam_id = form.elements['from_godam_id'];
                let plant_type_id = form.elements['plant_type_id'];
                let plant_name_id = form.elements['plant_name_id'];
                let shift_id = form.elements['shift_id'];
                let dana_group_id = form.elements['dana_group_id'];
                let dana_name_id = form.elements['dana_name_id'];
                let quantity =form.elements['quantity_in_kg'];

               // console.log(quantity);
                $.ajax({
                    url: "{{ route('autoloadItem.store') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        autoload_id: autoload_id.value,
                        from_godam_id: from_godam_id.value,
                        plant_type_id: plant_type_id.value,
                        plant_name_id: plant_name_id.value,
                        shift_id: shift_id.value,
                        dana_group_id: dana_group_id.value,
                        dana_name_id: dana_name_id.value,
                        quantity:quantity.value,

                    },
                    success: function(response) {
                        setSuccessMessage(response.message);
                        clearInputFields();
                        setIntoTable(response.autoloadItems);
                        deleteEventBtn();

                        //calculateTotalQuantity();
                    },
                     error: function(xhr, status, error) {
                    setErrorMessage(xhr.responseJSON.message);
                }
                });


            });
        document.getElementById('editAutoloadItemModelUpdate').addEventListener('submit', function(e){
                e.preventDefault();
                const form = event.target;
                let autoLoad_item_id= form.elements['autoload_item_id_model'];
                let from_godam_id = form.elements['from_godam_id_model'];
                let plant_type_id = form.elements['plant_type_id_model'];
                let plant_name_id = form.elements['plant_name_id_model'];
                let shift_id = form.elements['shift_id_model'];
                let dana_group_id = form.elements['dana_group_id_model'];
                let dana_name_id = form.elements['dana_name_id_model'];
                let quantity =form.elements['quantity_in_kg_model'];
                  $.ajax({
                    url: "{{ route('autoloadItem.update') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        autoload_item_id: autoLoad_item_id.value,
                        from_godam_id: from_godam_id.value,
                        plant_type_id: plant_type_id.value,
                        plant_name_id: plant_name_id.value,
                        shift_id: shift_id.value,
                        dana_group_id: dana_group_id.value,
                        dana_name_id: dana_name_id.value,
                        quantity:quantity.value,

                    },
                    success: function(response) {
                        console.log(response);
                       // return response;
                        // setSuccessMessage(response.message);
                        // clearInputFields();
                        // setIntoTable(response.autoloadItems);

                        //calculateTotalQuantity();
                    },
                     error: function(xhr, status, error) {
                    setErrorMessage(xhr.responseJSON.message);
                }
                });

        })
        function setErrorMessage(message) {
            let errorContainer = document.getElementById('error_msg');
            errorContainer.hidden = false;
            errorContainer.innerHTML = message;
            setTimeout(function() {
                errorContainer.hidden = true;
            }, 2000); // 5000 milliseconds = 5 second
        }

            //Set autoloaditem  data when page refreshed
            function getAutoloadItemsData() {
                removeAllTableRows();
                $.ajax({
                    url: '{{ route('autoloadItem.getAutoloadItemsData', ['autoload_id' => $autoload->id]) }}',
                    method: 'GET',
                    success: function(response) {
                      console.log(response);
                        if (response.count <= 0) {
                            return false;
                        }
                        response.autoloadItems.forEach(function(autoloadItem) {
                           // console.log(autoloadItem);
                            setIntoTable(autoloadItem);

                        });
                        deleteEventBtn();
                       // calculateTotalQuantity();
                    },
                    error: function(xhr, status, error) {
                        reject(error);
                    }
                });
            }
               function removeAllTableRows() {
                // Reseting SN
                sn = 1;
                let tbody = document.querySelector("#autoLoadItemTable tbody");
                for (var i = tbody.rows.length - 1; i >= 0; i--) {
                    tbody.deleteRow(i);
                }
            }


            function setIntoTable(res) {
                // console.log(res);
                            var html = "";

                            html = "<tr  id=editRowId-" + res.id + "><td>" + sn +
                                "</td><td class='rowFromGodam'>" + res.from_godam.department +
                                "</td><td class='rowPlantType'>" + res.plant_type.name +
                                "</td><td class='rowPlantName'>" + res.plant_name.name +
                                "</td><td class='rowShift'>" + res.shift.name +
                                "</td><td class='rowDanaGroup'>" + res.dana_group.name +
                                "</td><td class='rowDanaName'>" + res.dana_name.name +
                                "</td><td class='rowQuantity'>" + res.quantity +
                                "</td><td>" +
                                // "<button class='btn btn-success editAutoladItemBtn' data-id=" +
                                // res.id + "><i class='fas fa-edit'></i></button>" +
                                "  " +
                                "<button class='btn btn-danger dltAutoloadItemBtn' data-id=" +
                                res.id + " ><i class='fas fa-trash-alt'></i> </button>" + "</td ></tr>";

                            document.getElementById('autoLoadItemTbody').innerHTML += html;
                            sn++;
                            // Clearing the input fields
                            clearInputFields();
                            editEventBtn();
                            //deleteEventBtn()
             }
             function deleteEventBtn(){
                let deleteButtons = document.getElementsByClassName('dltAutoloadItemBtn');
                 for (var i = 0; i < deleteButtons.length; i++) {
                    deleteButtons[i].addEventListener('click', function(event) {
                        let autoloadItem_id = this.getAttribute('data-id');

                        $.ajax({
                            url: '{{ route('autoLoadItem.delete', ['autoloadItem_id' => ':lol']) }}'
                                .replace(':lol', autoloadItem_id),
                              method: 'DELETE',
                                data:{
                                    _token: "{{ csrf_token() }}"
                                },
                              success: async function(response) {
                                // Refesh table
                                // console.log(response);
                                getAutoloadItemsData();
                              }
                        });

                    })
                }
             }
              function editEventBtn() {
                // Assign event listener to buttons with class 'editItemBtn'
                let editButtons = document.getElementsByClassName('editAutoladItemBtn');
                for (var i = 0; i < editButtons.length; i++) {
                    editButtons[i].addEventListener('click', function(event) {
                        let autoloadItem_id = this.getAttribute('data-id');

                        $.ajax({
                            url: '{{ route('autoLoadItem.getEditAutoloadItemData', ['autoloadItem_id' => ':lol']) }}'
                                .replace(':lol', autoloadItem_id),
                            method: 'GET',
                              success: async function(response) {
                                $('#fromGodamIdModel').val(response.autoLoadItem.from_godam_id).trigger('change');
                                $('#plantTypeIdModel').val(response.autoLoadItem.plant_type_id).trigger('change');
                                $('#shiftIdModel').val(response.autoLoadItem.shift_id).trigger('change');
                                $('#quantityInKgModel').val(response.autoLoadItem.quantity);
                                $('#autoloadItemIdModel').val(autoloadItem_id);

                                await getPlantName(response.autoLoadItem.plant_type_id,'model');
                                $('#plantNameIdModel').val(response.autoLoadItem.plant_name_id).trigger('change');

                               await getEditDanaGroupName(response.autoLoadItem.from_godam_id,'model');
                               $('#danaGroupIdModel').val(response.autoLoadItem.dana_group_id).trigger('change');

                               await getEditDanaName(response.autoLoadItem.from_godam_id,response.autoLoadItem.dana_group_id,'model');
                                $('#danaNameModel').val(response.autoLoadItem.dana_name_id).trigger('change');

                                $('#editAutoloadItemModel').modal('show');
                              }
                        });

                    })
                }
            }
            //clear input fields
            function clearInputFields() {
            document.getElementById('quantityInKg').value = "";
            $('#fromGodamId').val($('#fromGodamId option:first').val()).change();
            $('#plantTypeId').val($('#plantTypeId option:first').val()).change();
            $('#plantNameId').val($('#plantNameId option:first').val()).change();
            $('#shiftId').val($('#shiftId option:first').val()).change();
            $('#danaGroupId').val($('#danaGroupId option:first').val()).change();
            $('#danaName').val($('#danaName option:first').val()).change();
        }


             $('#plantTypeId').on('select2:select', function(e) {
                let plantType_id = e.params.data.id;
               getPlantName(plantType_id, 'blade');

            });
              $('#fromGodamId').on('select2:select', function(e) {
                let godam_id = e.params.data.id;
                getPlantTypeAccGodam(godam_id, 'blade');
                getDanaGroupName(godam_id, 'blade');
                $('#plantNameId').val($('#plantNameId option:first').val()).change();
                $('#danaName').val($('#danaName option:first').val()).change();
            });

            $('#fromGodamIdModel').on('select2:select', function(e) {
                let godam_id = e.params.data.id;
                getPlantTypeAccGodam(godam_id, 'model');
                getDanaGroupName(godam_id, 'model');
                $('#plantNameIdModel').val($('#plantNameIdModel option:first').val()).change();
                $('#danaNameModel').val($('#danaNameModel option:first').val()).change();
            });

            function getPlantTypeAccGodam(godam_id,selectFrom){
                  //let godam_id =godam_id;
                   return new Promise(function(resolve, reject) {
                      $.ajax({
                        url: "{{ route('autoload.getPlantTypeAccGodam', ['department_id' => ':Replaced']) }}"
                            .replace(
                                ':Replaced',
                                godam_id),
                        method: 'GET',
                         success: function(response) {
                          console.log(response);
                            let selectOptions = '';
                            if (response.length == 0) {
                                selectOptions +=
                                "<option disabled selected value =''>" +
                                    'no groups found' + '</option>';
                            } else {
                                selectOptions +=
                                "<option disabled selected value =''>" +
                                    'select a group' + '</option>';
                                for (var i = 0; i < response.length; i++) {
                                    selectOptions += '<option value="' +
                                        response[i].id +
                                        '">' +
                                        response[i].name + '</option>';
                                }
                            }
                            if (selectFrom == 'blade') {
                                $('#plantTypeId').html(selectOptions);
                                resolve(response);
                            } else {
                                $('#plantTypeIdModel').html(selectOptions);
                                resolve(response);
                            }

                        }
                      });
                 })
            }
            function clearSelect2Fields(elementId){
                let select2Dropdown = $('#'+elementId);
                select2Dropdown.empty();
                // Add the default option to the dropdown.
                select2Dropdown.append(new Option('Select an option', null, true));
                //select2Dropdown.prop('disabled', true);
                // Trigger the `change` event on the dropdown.
                select2Dropdown.trigger('change');
            }
            function getDanaGroupName($godam_id,selectFrom){
                    let godam_id =$godam_id;
                     return new Promise(function(resolve, reject) {
                      $.ajax({
                        url: "{{ route('autoload.getDanaGroupAccToGodam', ['department_id' => ':Replaced']) }}"
                            .replace(
                                ':Replaced',
                                godam_id),
                        method: 'GET',
                         success: function(response) {
                          // console.log(response);
                            let selectOptions = '';
                            if (response.length == 0) {
                                selectOptions +=
                                    "<option disabled selected value=''>" +
                                    'no groups found' + '</option>';
                            } else {
                                selectOptions +=
                                    "<option disabled selected value=''>" +
                                    'select a group' + '</option>';
                                for (var i = 0; i < response.length; i++) {
                                    selectOptions += '<option value="' +
                                        response[i].dana_group.id +
                                        '">' +
                                        response[i].dana_group.name + '</option>';
                                }
                            }
                            if (selectFrom == 'blade') {
                                $('#danaGroupId').html(selectOptions);
                                resolve(response);
                            } else {
                                $('#danaGroupIdModel').html(selectOptions);
                                resolve(response);
                            }

                        }
                      });
                 })
            }
            // For Edit
            function getEditDanaGroupName($godam_id,selectFrom){
                    let godam_id =$godam_id;
                     return new Promise(function(resolve, reject) {
                      $.ajax({
                        url: "{{ route('autoLoad.getEditDanaGroupAccToGodam', ['department_id' => ':Replaced']) }}"
                            .replace(
                                ':Replaced',
                                godam_id),
                        method: 'GET',
                         success: function(response) {
                          // console.log(response);
                            let selectOptions = '';
                            if (response.length == 0) {
                                selectOptions +=
                                    "<option disabled selected value=''>" +
                                    'no groups found' + '</option>';
                            } else {
                                selectOptions +=
                                    "<option disabled selected value=''>" +
                                    'select a group' + '</option>';
                                for (var i = 0; i < response.length; i++) {
                                    selectOptions += '<option value="' +
                                        response[i].dana_group.id +
                                        '">' +
                                        response[i].dana_group.name + '</option>';
                                }
                            }
                            if (selectFrom == 'blade') {
                                $('#danaGroupId').html(selectOptions);
                                resolve(response);
                            } else {
                                $('#danaGroupIdModel').html(selectOptions);
                                resolve(response);
                            }

                        }
                      });
                 })
            }
            function getPlantName($plantType_id,selectFrom){
                let plantType_id=$plantType_id;
                 return new Promise(function(resolve, reject) {
                      $.ajax({
                        url: "{{ route('autoload.getPlantTypePlantName', ['plantType_id' => ':Replaced']) }}"
                            .replace(
                                ':Replaced',
                                plantType_id),
                        method: 'GET',
                         success: function(response) {
                           //console.log(response);
                            let selectOptions = '';
                            if (response.length == 0) {
                            selectOptions += "<option disabled selected value =''>" +
                                'no plant name found' + '</option>';
                            } else {
                                selectOptions += "<option disabled selected value =''>" +
                                    'select plant name' + '</option>';
                                for (var i = 0; i < response.length; i++) {
                                    selectOptions += '<option value="' + response[i].id + '">' +
                                        response[i].name + '</option>';
                                }
                            }
                            if (selectFrom == 'blade') {
                            $('#plantNameId').html(selectOptions);
                            resolve(response);
                            } else {
                                $('#plantNameIdModel').html(selectOptions);
                                resolve(response);
                            }
                        }
                      });
                 })
            }
             //to get dana name according to dana group
            $('#danaGroupId').on('select2:select', function(e) {
                let danaGroup_id = e.params.data.id;
                let fromGodam_id= document.getElementById('fromGodamId').value;
                getDanaName(danaGroup_id,fromGodam_id,'blade');

            });
             $('#danaGroupIdModel').on('select2:select', function(e) {
                let danaGroup_id = e.params.data.id;
                let fromGodam_id= document.getElementById('fromGodamIdModel').value;

                 getDanaName(danaGroup_id,fromGodam_id,'model');

            });
             function getDanaName(danaGroup_id,fromGodam_id, selectFrom) {
                return new Promise(function(resolve, reject) {

                    $.ajax({
                        url: "{{ route('autoload.getDanaGroupDanaName', ['danaGroup_id' => ':Replaced','fromGodam_id' =>':fromGodam_id']) }}"
                            .replace(':Replaced', danaGroup_id)
                            .replace(':fromGodam_id', fromGodam_id),
                        method: 'GET',
                        success: function(response) {
                             console.log('dana-name:',response);
                            let selectOptions = '';
                            if (response.length == 0) {
                                selectOptions +=
                                "<option disabled selected value =''>" +
                                    'no items found' + '</option>';
                            } else {
                                selectOptions +=
                                "<option disabled selected value =''>" +
                                    'select an item' + '</option>';
                                for (var i = 0; i < response.length; i++) {
                                    selectOptions += '<option value="' +
                                        response[i].dana_name.id +
                                        '">' +
                                        response[i].dana_name.name + '</option>';
                                }
                            }
                            if (selectFrom == 'blade') {
                                $('#danaName').html(selectOptions);
                                resolve(response);
                            } else {
                                $('#danaNameModel').html(selectOptions);
                                resolve(response);
                            }

                        },
                        error: function(xhr, status, error) {
                            reject(error);
                        }
                    });
                });
            }
            //For  Edit
            function getEditDanaName(danaGroup_id,fromGodam_id, selectFrom) {
                return new Promise(function(resolve, reject) {

                    $.ajax({
                        url: "{{ route('autoLoad.getEditDanaGroupDanaName', ['danaGroup_id' => ':Replaced','fromGodam_id' =>':fromGodam_id']) }}"
                            .replace(':Replaced', danaGroup_id)
                            .replace(':fromGodam_id', fromGodam_id),
                        method: 'GET',
                        success: function(response) {
                             console.log('dana-name:',response);
                            let selectOptions = '';
                            if (response.length == 0) {
                                selectOptions +=
                                "<option disabled selected value =''>" +
                                    'no items found' + '</option>';
                            } else {
                                selectOptions +=
                                "<option disabled selected value =''>" +
                                    'select an item' + '</option>';
                                for (var i = 0; i < response.length; i++) {
                                    selectOptions += '<option value="' +
                                        response[i].dana_name.id +
                                        '">' +
                                        response[i].dana_name.name + '</option>';
                                }
                            }
                            if (selectFrom == 'blade') {
                                $('#danaName').html(selectOptions);
                                resolve(response);
                            } else {
                                $('#danaNameModel').html(selectOptions);
                                resolve(response);
                            }

                        },
                        error: function(xhr, status, error) {
                            reject(error);
                        }
                    });
                });
            }
  //success message
            function setSuccessMessage(message) {
                let successContainer = document.getElementById('success_msg');
                //console.log(successContainer);
                successContainer.hidden = false;
                successContainer.innerHTML = message;
                setTimeout(function() {
                    successContainer.hidden = true;
                }, 2000); // 5000 milliseconds = 5 seconds
            }

        })

    </script>
@endsection
