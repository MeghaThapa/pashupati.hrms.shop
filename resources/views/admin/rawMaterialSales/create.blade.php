@extends('layouts.admin')
@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/nepaliDatePicker/nepali.datepicker.v4.0.1.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @if ($errors->any())
        <div id="error-container">
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    <div class="card-body p-0 m-0">
        <div class="card">
            <div class="card-header">

                <h3 class="card-title">{{ __('Create Raw Material Sales') }}</h3>

                <div class="card-tools">
                    <a href="{{ route('rawMaterialSalesEntry.index') }}" class="btn btn-block btn-primary">
                        <i class="fas fa-long-arrow-alt-left"></i> {{ __('Go Back') }}
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                {{-- <a href="{{ route('storein.storeinItemCreateLayout', ['storein_id' => 11]) }}">
                    <button class="btn btn-primary">
                        Layout
                    </button>
                </a> --}}

                <form action="{{ route('rawMaterialSalesEntry.store') }}" method="POST">
                    @csrf
                    <div class="row p-2">
                        <div class="col-md-3 form-group">
                            <label for="Category" class="col-form-label">{{ __('Bill Date') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" name="bill_date" class="form-control" id="nepali-date-picker">

                            @error('date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        {{-- bill no --}}
                        <div class="col-md-3 form-group">
                            <label for="Category" class="col-form-label">{{ __('Bill No') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator"
                                id="billNo" data-number="1" name="bill_no" min="1" required>
                            @error('date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        {{-- supplier --}}
                        <div class="col-md-6 form-group">
                            <label for="size" class="col-form-label">{{ __('Party Name') }}<span
                                    class="required-field">*</span>
                            </label>
                            {{-- <a href="#" class="col-md-1 btn btn-primary dynamic-btn" data-toggle="modal" tabindex="-1"
                                        data-target="#addSupplierModel" style="margin-top:0 !important; top:8px;float:right;">
                                        <i class="fas fa-plus" style="display:flex;align-items: center;justify-content: center;"></i>
                            </a> --}}
                            <select class="advance-select-box form-control @error('size') is-invalid @enderror"
                                id="supplierId" name="supplier_id" required>
                                <option value="" selected disabled>{{ __('Select a Supplier ') }}</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        {{-- bill no --}}
                        <div class="col-md-3 form-group">
                            <label for="products" class="col-form-label">{{ __('Godam') }}<span
                                    class="required-field">*</span>
                            </label>
                            <select class="advance-select-box form-control @error('size') is-invalid @enderror"
                                id="godamId" name="godam_id" required>
                                <option value="" selected disabled>{{ __('Select a godam ') }}</option>
                                @foreach ($godams as $godam)
                                    <option value="{{ $godam->id }}">{{ $godam->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="size" class="col-form-label">{{ __('Challan No') }}
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator"
                                id="challanNo" data-number="1" name="challan_no" placeholder="{{ __('Challan No') }}"
                                min="1" required>
                            @error('challan_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="size" class="col-form-label">{{ __('Do No') }}
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator"
                                id="doNo" data-number="1" name="do_no" placeholder="{{ __('Do No') }}"
                                min="1" required>
                            @error('do_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="size" class="col-form-label">{{ __('GP No') }}
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator"
                                id="gpNo" data-number="1" name="gp_no" placeholder="{{ __('GP No') }}"
                                min="1" required>
                            @error('gp_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label for="size" class="col-form-label">{{ __('Through') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator"
                                id="through" data-number="1" name="through" min="1" required>
                            @error('to_godam_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-3 form-group">
                            <label for="size" class="col-form-label">{{ __('Sale For') }}
                            </label>
                            <select class="advance-select-box form-control" id="saleFor" name="sale_for" required>
                                <option value="" selected disabled>{{ __('Select sales for ') }}</option>
                                <option value="loan">Loan</option>
                                <option value="sale">Sale</option>
                            </select>
                            @error('Receipt_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="size" class="col-form-label">{{ __('Remarks') }}
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator"
                                id="remarks" data-number="1" name="remarks" placeholder="{{ __('Remarks') }}"
                                min="1">
                            @error('remarks')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div style="margin-bottom:10px;">
                        <center>
                            <button type="submit" class="btn btn-primary">

                                Create
                            </button>
                        </center>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/storein.js') }}"></script>
    <script src="{{ asset('js/nepaliDatePicker/nepali.datepicker.v4.0.1.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            /**************************** Ajax Calls **************************/
            // for nepali datepicker
            let todayNepaliDate = {!! isset($nepaliDate) ? json_encode($nepaliDate) : 'null' !!};

            $("#nepali-date-picker").nepaliDatePicker({});
            if (todayNepaliDate !== null) {
                $("#nepali-date-picker").val(todayNepaliDate);
            }
            $('#danaGroup').on('select2:select', function(e) {
                let danaGroup_id = e.target.value;

                getDanaName(danaGroup_id);
            });

            function getDanaName(danaGroup_id) {
                return new Promise(function(resolve, reject) {
                    let url =
                        "{{ route('rawMaterial.getDanaGroupDanaName', ['danaGroup_id' => ':Replaced']) }}"
                        .replace(':Replaced', danaGroup_id);
                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function(response) {
                            let selectOptions = '';
                            if (response.length == 0) {
                                selectOptions +=
                                    '<option disabled selected>' +
                                    'no items found' + '</option>';
                            } else {
                                selectOptions +=
                                    '<option disabled selected>' +
                                    'select an item' + '</option>';
                                for (var i = 0; i < response.length; i++) {
                                    selectOptions += '<option value="' +
                                        response[i].id +
                                        '">' +
                                        response[i].name + '</option>';
                                }
                            }
                            $('#danaName').html(selectOptions);
                            resolve(response);
                        },
                        error: function(xhr, status, error) {
                            reject(error);
                        }
                    });
                });
            }

        });
    </script>
@endsection
