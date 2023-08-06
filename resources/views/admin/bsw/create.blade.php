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
    <div class="card-body p-0 m-0">
        <form action="{{ route('BswLamFabSendForPrinting.saveEntry') }}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('Receipt No') }}<span class="required-field">*</span>
                    </label>
                    <input type="text" class="form-control" id="receiptNo" name="receipt_no"
                        @if ($bswLamFabForPrintingEntryData) value="{{ $bswLamFabForPrintingEntryData->receipt_no }}"
                    @else
                         value="{{ $receipt_no }}" @endif
                        required />
                    {{-- value="FSR-{{ getNepalidate(date('Y-m-d')).'-'.rand(0,9999)}}" --}}
                </div>

                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('Date') }}
                    </label>
                    <input type="text" name="date_np" class="form-control" id="nepali-date-picker">

                    @error('bill_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('Plant Type') }}
                    </label>
                    <select class="advance-select-box form-control" id="plantTypeId" name="plant_type_id" required>
                        <option value="" selected disabled>{{ __('Select Plant Type') }}</option>

                        @foreach ($plantTypes as $plantType)
                            <option
                                @if ($bswLamFabForPrintingEntryData) {{ $bswLamFabForPrintingEntryData->plant_type_id == $plantType->id ? 'selected' : 'null' }} @endif
                                value="{{ $plantType->id }}">{{ $plantType->name }}
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
                    <label for="size" class="col-form-label">{{ __('Plant Name') }}
                    </label>
                    <select class="advance-select-box form-control" id="plantNameId" name="plant_name_id" required>
                        <option value="" selected disabled>{{ __('Select Plant Name') }}</option>
                        @if ($bswLamFabForPrintingEntryData)
                            @foreach ($plantNames as $plantName)
                                <option
                                    {{ $bswLamFabForPrintingEntryData->plant_name_id == $plantName->id ? ' selected' : 'null' }}
                                    value="{{ $plantName->id }}">{{ $plantName->name }}
                                </option>
                            @endforeach
                        @endif

                    </select>
                    @error('plant_type_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-2 form-group">
                    <label for="shift" class="col-form-label">{{ __('Shift') }}
                    </label>
                    <select class="advance-select-box form-control" id="shift" name="shift_id" required>
                        <option value="" selected disabled>{{ __('Select Shift') }}</option>
                        @foreach ($shifts as $shift)
                            <option value="{{ $shift->id }}">{{ $shift->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('gp_no')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-2 form-group">
                    <label for="size" class="col-form-label">{{ __('To godam') }}
                    </label>
                    <select class="advance-select-box form-control" id="godam" name="godam_id" required>
                        <option value="" selected disabled>{{ __('Select Godam Name') }}</option>
                        @foreach ($godams as $godam)
                            <option value="{{ $godam->id }}">{{ $godam->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('shift_name_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                {{-- <div>
                <button type="submit" class="btn btn-sm btn-primary" style="margin-top:35px;">
                    Add
                </button>
            </div> --}}

            </div>
            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="size" class="col-form-label">{{ __('Group Name') }}<span
                            class="required-field">*</span>
                    </label>
                    <select class="advance-select-box form-control" id="groupId" name="group_name_id">
                        <option value="" disabled selected>{{ __('Select group Name') }}</option>
                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('fabric_name_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-2">
                    <label for="Fabric">Brand Bag</label>
                    <select name="brand_bag_id" class="form-control advance-select-box" id="brandBagId">
                        <option value="" selected disabled>Select Brand Bag</option>
                    </select>
                </div>
                <div>
                    <button type="submit" id="getfabricsrelated" class="btn btn-primary mt-4">
                        Add
                    </button>
                </div>
            </div>
        </form>
        <hr>

    </div>
    {{-- <h1 class='text-center'>Compare Lam and Unlam</h1> --}}
@endsection
@section('extra-script')
    <script src="{{ asset('js/nepaliDatePicker/nepali.datepicker.v4.0.1.min.js') }}"></script>
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/storein.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
    <script>
        $(document).ready(function() {
            /**************************** Ajax Calls **************************/
            // for nepali datepicker
            $("#nepali-date-picker").nepaliDatePicker({});
            let todayNepaliDate = {!! isset($nepaliDate) ? json_encode($nepaliDate) : 'null' !!};
            let neplaiDateFromEdit = {!! isset($bswLamFabForPrintingEntryData) ? json_encode($bswLamFabForPrintingEntryData->date) : 'null' !!};
            if (todayNepaliDate !== null) {
                $("#nepali-date-picker").val(todayNepaliDate);
            } else {
                $("#nepali-date-picker").val(neplaiDateFromEdit);
            }

            $('#groupId').on('select2:select', function(e) {
                let group_id = e.params.data.id;
                getBagBrand(group_id);
            });

            function getBagBrand(group_id) {
                $.ajax({

                    url: "{{ route('BswLamFabSendForPrinting.getBrandBag') }}",
                    method: 'get',
                    data: {
                        group_id: group_id,
                    },
                    success: function(response) {
                        $('#brandBagId').empty().trigger('change');
                        $('#brandBagId').prepend(
                            "<option value='' disabled selected>Select required data</option>");
                        response.forEach(function(item) {
                            setOptionInSelect('brandBagId', item.id, item.name);
                        });
                    },
                    error: function(xhr, status, error) {
                        setErrorMsg(xhr.responseJSON.message);
                    }
                });
            }

            function setOptionInSelect(elementId, optionId, optionText) {
                let selectElement = $('#' + elementId);
                let newOption = $('<option>');
                newOption.val(optionId).text(optionText);
                selectElement.append(newOption);
                selectElement.trigger('change.select2');

            }

            $('#plantTypeId').on('select2:select', function(e) {
                let plantType_id = e.params.data.id;
                getPlantName(plantType_id);

            });

            function getPlantName($plantType_id) {
                let plantType_id = $plantType_id;
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
                            $('#plantNameId').html(selectOptions);
                            resolve(response);

                        }
                    });
                })
            }


        });
    </script>
@endsection
