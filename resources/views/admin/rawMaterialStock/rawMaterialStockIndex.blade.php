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
<center>
  <img class="lg-logo" src="{{ $settings->logo }}" alt="{{ $settings->companyName}}" width="350" height="80">
</center>
<div class="row">
    <div class="col-md-12">
        <div class="p-0 table-responsive table-custom my-3">
            <table class="table" id="rawMaterialStockTable">
                <thead>
                    <tr>
                        <th>{{ __('S.No') }}</th>
                        <th>{{ __('Dana Group Name') }}</th>
                        <th>{{ __('Dana Name') }}</th>
                        <th>{{ __('Quantity') }}</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($rawMaterialStocks as $i => $stock)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $stock->danaGroup->name }}</td>
                        <td>{{ $stock->danaName->name }}</td>
                        <td>{{ $stock->quantity }}</td>
                    </tr>
                @endforeach

                </tbody>

            </table>
        </div>
    </div>
</div>
@endsection
@section('extra-script')
@endsection
