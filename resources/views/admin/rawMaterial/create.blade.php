@extends('layouts.admin')
@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
@endsection
@section('content')
    @if ($errors->any())
        <div id="error-container" class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card-body p-0 m-0">
        <div class="card">
            <div class="card-header">

                <h3 class="card-title">{{ __('Create Raw Material') }}</h3>

                <div class="card-tools">
                    <a href="{{ route('rawMaterial.index') }}" class="btn btn-block btn-primary">
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

                <form  @if ($rawMaterial) action="{{ route('rawMaterial.update',['rawMaterial_id'=>$rawMaterial->id]) }}"
                    @else action="{{ route('rawMaterial.store') }}"@endif
                    method="POST">
                    @csrf
                    <div class="row p-2">
                        <div class="col-md-3 form-group">
                            <label for="Category" class="col-form-label">{{ __('Date') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="date" step="any" min="0" class="form-control calculator"
                                id="date" data-number="1" name="date" placeholder="{{ __('date') }}"
                                @if ($rawMaterial) value="{{ $rawMaterial->date }}" @endif min="1"
                                required>
                            @error('date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        {{-- item --}}
                        <div class="col-md-4 form-group">
                            <label for="products" class="col-form-label">{{ __('PP No') }}<span
                                    class="required-field">*</span>
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator"
                                id="pp_no" data-number="1" name="pp_no" placeholder="{{ __('PP No') }}"
                                @if ($rawMaterial) value="{{ $rawMaterial->pp_no }}" @endif min="1"
                                required>
                            @error('pp_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        {{-- size --}}
                        <div class="col-md-5 form-group">
                            <label for="size" class="col-form-label">{{ __('Party Name') }}<span
                                    class="required-field">*</span>
                            </label>
                            <select class="advance-select-box form-control @error('size') is-invalid @enderror"
                                id="size_id" name="supplier_id" required>
                                <option value="" selected disabled>{{ __('Select a Size ') }}</option>
                                @foreach ($suppliers as $supplier)
                                    <option
                                        @if ($rawMaterial) {{ $supplier->id == $rawMaterial->supplier_id ? 'selected' : '' }} @endif
                                        value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            @error('size_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                    </div>
                    <div class="row">

                        <div class="col-md-3 form-group">
                            <label for="size" class="col-form-label">{{ __('Type') }}<span
                                    class="required-field">*</span>
                            </label>
                            <select class="advance-select-box form-control" id="Type_id" name="Type_id" required>
                                <option value="" selected disabled>{{ __('Select a type ') }}</option>
                                @foreach ($storeinTypes as $storeinType)
                                    <option value="{{ $storeinType->id }}">{{ $storeinType->name }}</option>
                                @endforeach
                            </select>

                            @error('size_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="size" class="col-form-label">{{ __('From Godam') }}
                            </label>
                            <select class="advance-select-box form-control" id="fromGodam" name="from_godam_id" required>
                                <option value="" selected disabled>{{ __('Select a godam') }}</option>
                                @foreach ($godamAsDept as $department)
                                    <option value="{{ $department->id }}">{{ $department->department }}</option>
                                @endforeach
                            </select>
                            @error('size_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="size" class="col-form-label">{{ __('Challan No') }}
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator"
                                id="challanNo" data-number="1" name="challan_no" placeholder="{{ __('Challan No') }}"
                                min="1" required>
                            @error('size_id')
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
                            <label for="size" class="col-form-label">{{ __('To Godam') }}<span
                                    class="required-field">*</span>
                            </label>
                            <select class="advance-select-box form-control" id="toGodamId" name="to_godam_id" required>
                                <option value="" selected disabled>{{ __('Select a type ') }}</option>
                                @foreach ($godamAsDept as $departments)
                                    <option
                                        @if ($rawMaterial) {{ $rawMaterial->to_godam_id == $departments->id ? 'selected' : '' }} @endif
                                        value="{{ $departments->id }}">{{ $departments->department }}</option>
                                @endforeach
                            </select>
                            @error('size_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-3 form-group">
                            <label for="size" class="col-form-label">{{ __('Receipt No') }}
                            </label>
                            <input type="text" step="any" min="0" class="form-control calculator"
                                id="ReceiptNo" data-number="1" name="Receipt_no"
                                @if ($rawMaterial) value="{{ $rawMaterial->receipt_no }}"@else value= "{{ $receipt_no }}" @endif
                                placeholder="{{ __('Receipt No') }}" min="1" required readonly>
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
                                id="remarks" data-number="1" name="remarks"
                                @if ($rawMaterial) value="{{ $rawMaterial->remark }}" @endif
                                placeholder="{{ __('Remarks') }}" min="1">
                            @error('gp_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div style="margin-bottom:10px;">
                        <center>
                            <button type="submit" class="btn btn-primary">
                                @if ($rawMaterial)
                                    Update
                                @else
                                    Create
                                @endif

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
    <script>
        $(document).ready(function() {

            let from_godam = document.getElementById('fromGodam');
            let challan_no = document.getElementById('challanNo');
            let gp_no = document.getElementById('gpNo');
            from_godam.disabled = true;
            challan_no.disabled = true;
            gp_no.disabled = true;

            let rawMaterial = JSON.parse(`{!! json_encode($rawMaterial) !!}`);

            if (rawMaterial) {
                $('#Type_id').val(rawMaterial.storein_type_id).change();
                if (rawMaterial.storein_type.name.toLowerCase() == 'godam') {
                    $('#fromGodam').val(rawMaterial.from_godam_id).change();
                    $('#challanNo').val(rawMaterial.challan_no);
                    $('#gpNo').val(rawMaterial.gp_no);

                    toggleInputsByStoreInType(rawMaterial.storein_type.name);
                }

            }

            $('#Type_id').on('select2:select', function(e) {
                let selectedName = e.params.data.text.replace(/\s/g, "");
                toggleInputsByStoreInType(selectedName);
            });

            function toggleInputsByStoreInType(selectedName) {
                if (selectedName.toLowerCase() == 'godam') {
                    from_godam.disabled = false;
                    //ppno_input.value = "";
                    challan_no.disabled = false;
                    gp_no.disabled = false;
                    from_godam.required = true;
                    challan_no.required = true;
                    gp_no.required = true;
                } else {
                    from_godam.disabled = true;
                    challan_no.disabled = true;
                    gp_no.disabled = true;
                    from_godam.required = false;
                    challan_no.required = false;
                    gp_no.required = false;
                }
            }

        });
    </script>
@endsection
@section('extra-script')
    <script>
        $(document).ready(function() {
            // Hide the error message after 5 seconds
            setTimeout(function() {
                $('#error-container').fadeOut('fast');
            }, 3000); // 5000 milliseconds = 5 seconds
        });
    </script>
@endsection
