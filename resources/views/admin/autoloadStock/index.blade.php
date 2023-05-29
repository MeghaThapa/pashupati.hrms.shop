@extends('layouts.admin')

@section('content')
<form action="{{route('autoloadStock.filterAccGodam')}}" method="POST">
    @csrf
 <div class="row">
            <div style="display:flex;gap:10px;">
            <label for="">Godam</label>
            <select class="advance-select-box form-control" id="danaGroupId_model" name="godam_id" required>
                 <option value="" selected disabled>{{ __('Select Godam') }}</option>
                    @foreach ($godams as $godam)
                    <option value="{{ $godam->id }}">{{ $godam->department }}</option>
                    @endforeach
            </select>
    <button type="submit" >Show Report</button>
    </div>
</div>
</form>
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
                        <th>{{ __('Plant Type') }}</th>
                        <th>{{ __('Plant Name') }}</th>
                        <th>{{__('dana name')}}</th>
                        <th>{{ __('Shift') }}</th>
                        <th>{{__('total')}}</th>
                    </tr>
                </thead>

                <tbody>
                @if ($autoloadStockDatas)
                    @foreach ($autoloadStockDatas as $i => $stock)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $stock->plantType->name }}</td>
                            <td>{{ $stock->plantName->name }}</td>
                            <td>{{ $stock->danaName->name }}</td>
                            <td>{{ $stock->shift->name }}</td>
                            <td>{{ $stock->quantity}}</td>
                        </tr>
                    @endforeach
                @else
                     @foreach ($autoloadStocks as $i => $stock)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $stock->plantType->name }}</td>
                        <td>{{ $stock->plantName->name }}</td>
                        <td>{{ $stock->danaName->name }}</td>
                        <td>{{ $stock->shift->name }}</td>
                        <td>{{ $stock->quantity}}</td>
                    </tr>
                @endforeach
                @endif



                </tbody>

            </table>
        </div>
    </div>
</div>
@endsection
