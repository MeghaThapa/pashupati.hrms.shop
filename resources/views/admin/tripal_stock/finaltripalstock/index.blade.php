@extends('layouts.admin')
@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
@endsection
@section('content')
   
    <div style="display: flex;
    justify-content: space-evenly;
    flex-direction: column;
    align-items: center;">

        <img class="lg-logo" src="{{ $settings->logo }}" alt="{{ $settings->companyName }}" width="250" height="50">
        <h3>FinalTripal Stock</h3>
    </div>
  {{--   <form action="{{ route('tapeentry-stock.filterStock') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-3" style="display:flex;gap:10px; width:400px;justify-content: center;align-item:center;">
                <label for="">Godam</label>
                <select class="advance-select-box form-control" id="danaGroupId_model" name="godam_id">
                    <option value="" selected disabled>{{ __('Select Godam') }}</option>
                    <option value="all">All Stocks</option>
                    @foreach ($godams as $godam)
                        <option value="{{ $godam->id }}">{{ $godam->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3" style="display:flex;gap:10px; width:400px;justify-content: center;align-item:center;">
                <label for="">Plant Type</label>
                <select class="advance-select-box form-control" id="planttype_id" name="planttypes_id">
                    <option value="" selected disabled>{{ __('Select Plant Type') }}</option>
                    <option value="all">All Stocks</option>
                    @foreach ($planttypes as $planttype)
                        <option value="{{ $planttype->id }}">{{ $planttype->name }}</option>
                    @endforeach

                </select>

            </div>
            <div class="col-md-3" style="display:flex;gap:10px; width:400px;justify-content: center;align-item:center;">
                <label for="">Plant Name</label>
                <select class="advance-select-box form-control" id="plantname_id" name="plantname_id">
                    <option value="" selected disabled>{{ __('Select Plant Name') }}</option>
                    <option value="all">All Stocks</option>
                    @foreach ($plantnames as $planttype)
                        <option value="{{ $planttype->id }}">{{ $planttype->name }}</option>
                    @endforeach
                </select>

            </div>
            <div class="col-md-3">
                <button class="btn btn-primary" style="width:200px" type="submit">Show Report</button>

            </div>
        </div>
    </form> --}}

    <div class="row">
        <div class="col-md-12">
            <div class="p-0 table-responsive table-custom my-3">
                <table class="table" id="rawMaterialStockTable">
                    <thead>
                        <tr>
                            <th>{{ __('S.No') }}</th>
                            <th>{{ __('Godam') }}</th>
                            <th>{{ __('PlantType') }}</th>
                            <th>{{ __('PlantName') }}</th>
                            <th>{{ __('Net Weight') }}</th>
                            <th>{{ __('Gross Weight') }}</th>
                            <th>{{ __('Meter') }}</th>
                            <th>{{ __('Average Weight') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if ($finaltripal)
                            @foreach ($finaltripal as $i => $stock)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $stock->getGodam->name }}</td>
                                    <td>{{ $stock->getPlantType->name }}</td>
                                    <td>{{ $stock->getPlantName->name }}</td>
                                    <td>{{ $stock->net_wt}}</td>
                                    <td>{{ $stock->gross_wt }}</td>
                                    <td>{{ $stock->meter }}</td>
                                    <td>{{ $stock->average_wt }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>

                </table>
            </div>
            @if ($finaltripal)
                {{ $finaltripal->links() }}
            @endif 
        </div>
    </div>
@endsection
@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
@endsection
