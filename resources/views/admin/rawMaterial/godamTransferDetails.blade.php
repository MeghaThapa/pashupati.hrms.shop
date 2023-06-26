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

        {{-- <img class="lg-logo" src="{{ $settings->logo }}" alt="{{ $settings->companyName }}" width="250" height="50"> --}}
        <h3>Godam Transfer Details</h3>
    </div>
    <form action="{{ route('rawMaterial.filterGodamTransferAccGodam') }}" method="POST">
        @csrf
        <div style="display:flex;gap:10px;align-item:center;">
            <div>
                <label for="">From</label>
                <select class="advance-select-box form-control" style="width: 250px!important" id="fromGodamId"
                    name="from_godam_id">
                    <option value="" selected disabled>{{ __('Select Godam') }}</option>
                    {{-- <option value="all">All Stocks</option> --}}
                    @foreach ($godams as $godam)
                        <option value="{{ $godam->id }}">{{ $godam->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="toGodamId">To</label>
                <select class="advance-select-box form-control" style="width: 250px !important" name="to_godam_id"
                    id="toGodamId">
                    <option value="" selected disabled>{{ __('Select Godam') }}</option>
                    {{-- <option value="all">All Stocks</option> --}}
                    @foreach ($godams as $godam)
                        <option value="{{ $godam->id }}">{{ $godam->name }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-primary" style="width:100px" type="submit">Show Report</button>
        </div>
    </form>

    <div class="row">
        <div class="col-md-12">
            <div class="p-0 table-responsive table-custom my-3">
                <table class="table" id="rawMaterialStockTable">
                    <thead>
                        <tr>
                            <th>{{ __('S.No') }}</th>
                            <th>{{ __('Dana Group') }}</th>
                            <th>{{ __('Dana Name') }}</th>
                            <th>{{ __('Quantity') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($rawMaterialFilter as $index => $data)
                            <tr>
                                <td>{{ ++$index }}</td>
                                <td>{{ $data->danaGroup }}</td>
                                <td>{{ $data->danaName }}</td>
                                <td>{{ $data->quantity }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- @if ($danaDataJson)
                {{ $rawMaterialStocks->links() }}
            @endif --}}
        </div>
    </div>
@endsection
@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
@endsection
