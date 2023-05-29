@extends('layouts.admin')

@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
@endsection
@section('content')

<div style="display: flex;
    justify-content: space-evenly;
    flex-direction: column;
    align-items: center;" >

  <img class="lg-logo" src="{{ $settings->logo }}" alt="{{ $settings->companyName}}" width="250" height="50">
  <h3>Auto Load Stock</h3>
</div>
<form action="{{route('autoloadStock.filterAccGodam')}}" method="POST">
    @csrf
 <div style="display:flex;gap:10px; width:400px;justify-content: center;align-item:center;">
    <label for="">Godam</label>
            <select class="advance-select-box form-control" id="danaGroupId_model" name="godam_id" required>
                 <option value="" selected disabled>{{ __('Select Godam') }}</option>
                 <option value="all">All Stocks</option>
                    @foreach ($godams as $godam)
                    <option value="{{ $godam->id }}">{{ $godam->department }}</option>
                    @endforeach
            </select>
    <button class="btn btn-primary" style="width:200px" type="submit" >Show Report</button>
</div>
</form>
<div class="row">
    <div class="col-md-12">
        <div class="p-0 table-responsive table-custom my-3">
            <table class="table" id="rawMaterialStockTable">
                <thead>
                    <tr>
                        <th>{{ __('S.No') }}</th>
                        <th>{{ __('Plant Type') }}</th>
                        <th>{{ __('Plant Name') }}</th>
                        <th>{{__('dana name')}}</th>
                        <th>{{ __('Shift') }}</th>
                        <th>{{__('total')}}</th>
                    </tr>
                </thead>

                <tbody>
                @if ($autoloadStocks)
                    @foreach ($autoloadStocks as $i => $stock)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $stock->plantTypeName }}</td>
                        <td>{{ $stock->plantName}}</td>
                        <td>{{ $stock->danaName}}</td>
                        <td>{{ $stock->shiftName }}</td>
                        <td>{{ $stock->quantity}}</td>
                    </tr>
                    @endforeach
                @endif
                </tbody>
            </table>

        </div>
        @if ($autoloadStocks)
            {{$autoloadStocks->links()}}
        @endif
    </div>
</div>
@endsection
@section('extra-script')
  <script src="{{ asset('js/select2/select2.min.js') }}"></script>

@endsection
