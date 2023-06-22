@extends('layouts.admin')
@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
@endsection
@section('content')
    <a href="{{ route('rawMaterialStock.danaNameFilter') }}">
        <button class="btn btn-primary">
            Dana Name filter
        </button>
    </a>
    <a href="{{ route('rawMaterialStock.danaGroupFilter') }}">
        <button class="btn btn-primary">
            Dana Group filter
        </button>
    </a>
    <div style="display: flex;
    justify-content: space-evenly;
    flex-direction: column;
    align-items: center;">

        <img class="lg-logo" src="{{ $settings->logo }}" alt="{{ $settings->companyName }}" width="250" height="50">
        <h3>Raw Material Stock</h3>
    </div>
    <form action="{{ route('rawmaterial.filterAccGodam') }}" method="POST">
        @csrf
        <div style="display:flex;gap:10px; width:400px;justify-content: center;align-item:center;">
            <label for="">Godam</label>
            <select class="advance-select-box form-control" id="danaGroupId_model" name="godam_id" required>
                <option value="" selected disabled>{{ __('Select Godam') }}</option>
                <option value="all">All Stocks</option>
                @foreach ($godams as $godam)
                    <option value="{{ $godam->id }}">{{ $godam->name }}</option>
                @endforeach
            </select>
            <button class="btn btn-primary" style="width:200px" type="submit">Show Report</button>
        </div>
    </form>

    <div class="row">
        <div class="col-md-12">
            <div class="p-0 table-responsive table-custom my-3">
                <table class="table" id="rawMaterialStockTable">
                    <thead>
                        <tr>
                            <th>{{ __('S.No') }}</th>
                            <th>{{ __('Godam') }}</th>
                            <th>{{ __('Dana Group Name') }}</th>
                            <th>{{ __('Dana Name') }}</th>
                            <th>{{ __('Quantity') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if ($rawMaterialStocks)
                            @foreach ($rawMaterialStocks as $i => $stock)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $stock->godamName }}</td>
                                    <td>{{ $stock->danaGroup }}</td>
                                    <td>{{ $stock->danaName }}</td>
                                    <td>{{ $stock->quantity }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>

                </table>
            </div>
            @if ($rawMaterialStocks)
                {{ $rawMaterialStocks->links() }}
            @endif
        </div>
    </div>
@endsection
@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
@endsection
