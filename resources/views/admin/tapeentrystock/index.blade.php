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
        <h3>TapeEntry Stock</h3>
    </div>
    <form action="{{ route('tapeentry-stock.filterStock') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-3" style="display:flex;gap:10px; width:400px;justify-content: center;align-item:center;">
                <label for="">Godam</label>
                <select class="advance-select-box form-control" id="danaGroupId_model" name="godam_id">
                    <option value="" selected disabled>{{ __('Select Godam') }}</option>
                    {{-- <option value="all">All Stocks</option> --}}
                    @foreach ($godams as $godam)
                        <option value="{{ $godam->id }}">{{ $godam->name }}</option>
                    @endforeach
                </select>
            </div>


            <div class="col-md-3">
                <button class="btn btn-primary" style="width:200px" type="submit">Show Report</button>

            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-md-12">
            <div class="p-0 table-responsive table-custom my-3">
                <table class="table" id="rawMaterialStockTable">
                    <thead>
                        <tr>
                            {{-- <th>{{ __('S.No') }}</th> --}}
                            {{-- <th>{{ __('Godam') }}</th>
                            <th>{{ __('Tape Quantity') }}</th>
                            <th>{{ __('Total') }}</th> --}}
                            <th>{{ __('Tape Stock') }}</th>
                            <th>{{ __('Opening') }}</th>
                            <th>{{ __('Production') }}</th>
                            <th>{{ __('RollDown') }}</th>
                            <th>{{ __('Loom Wast') }}</th>
                            <th>{{ __('Sales') }}</th>
                            <th>{{ __('Closing') }}</th>

                        </tr>
                    </thead>

                    {{-- <tbody>
                        @if ($tableDatas)
                        @php

                        $totalOpening =0;
                        $total_production_total =0;
                        $total_rolldown_total =0;
                        $totalof_wastage_sum  =0;
                        $total_closing_sum =0;
                        @endphp
                            @foreach ($tableDatas as $tableData)
                            @php
                                $closing =0 ;
                                $closing =$tableData->opening +$tableData->production_total- $tableData->rolldown_total - $tableData->total_wastage_sum;
                                $total_closing_sum += $closing;
                           @endphp
                                <tr>
                                    <td>{{ $tableData->name }}</td>
                                    <td>{{ $tableData->opening }}</td>
                                    <td>{{ $tableData->production_total }}</td>
                                    <td>{{ $tableData->rolldown_total }}</td>
                                    <td>{{ $tableData->total_wastage_sum }}</td>
                                    <td>00</td>
                                    <td>{{$closing}}</td>
                                </tr>
                                @php

                                $total_closing_sum = $closing;
                                $totalOpening += $tableData->opening;
                                $total_production_total += $tableData->production_total;
                                $total_rolldown_total += $tableData->rolldown_total;
                                $totalof_wastage_sum += $tableData->total_wastage_sum;


                            @endphp

                            @endforeach


                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>Total</td>
                            <td> {{$totalOpening}} </td>
                            <td>{{$total_production_total}}</td>
                            <td> {{$total_rolldown_total}}</td>
                            <td>{{ $totalof_wastage_sum}}</td>
                            <td>00</td>
                            <td>{{$total_closing_sum}}</td>
                        </tr>
                    </tfoot> --}}
                    <tbody>
                        @if ($tableDatas)
                            @php
                                $totalOpening = 0;
                                $total_production_total = 0;
                                $total_rolldown_total = 0;
                                $totalof_wastage_sum = 0;
                                $total_closing_sum = 0;
                            @endphp
                            @foreach ($tableDatas as $tableData)
                                @php
                                    $closing = $tableData->opening + $tableData->production_total - $tableData->rolldown_total - $tableData->total_wastage_sum;

                                    // Rest of your code remains the same

                                @endphp
                                <tr>
                                    <td>{{ $tableData->name }}</td>
                                    <td>{{ $tableData->opening }}</td>
                                    <td>{{ $tableData->production_total }}</td>
                                    <td>{{ $tableData->rolldown_total }}</td>
                                    <td>{{ $tableData->total_wastage_sum }}</td>
                                    <td>00</td>
                                    <td>{{ $closing }}</td>
                                </tr>
                                @php
                                    $totalOpening += $tableData->opening;
                                    $total_production_total += $tableData->production_total;
                                    $total_rolldown_total += $tableData->rolldown_total;
                                    $totalof_wastage_sum += $tableData->total_wastage_sum;
                                @endphp
                                @php
                                    // Update the total_closing_sum outside of the loop to calculate the sum of closing values
                                    $total_closing_sum = $total_closing_sum + $closing;
                                @endphp
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>Total</td>
                            <td> {{ $totalOpening }} </td>
                            <td>{{ $total_production_total }}</td>
                            <td> {{ $total_rolldown_total }}</td>
                            <td>{{ $totalof_wastage_sum }}</td>
                            <td>00</td>
                            <td>{{ $total_closing_sum }}</td>
                        </tr>
                    </tfoot>

                </table>
            </div>
            {{-- @if ($tapeststocks)
                {{ $tapeststocks->links() }}
            @endif  --}}
        </div>
    </div>
@endsection
@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
@endsection
