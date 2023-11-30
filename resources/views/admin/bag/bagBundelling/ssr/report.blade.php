<h3>Printing and Fininshing Report for Date: {{ $request->start_date }} To {{ $request->end_date }}</h3>
<table class="table table-bordered table-responsive">
   
    <table class="table table-bordered" style="padding: 0 30px;">
        <tr>
            <th width="10px">{{ __('Date') }}</th>
            <th width="10px">{{ __('Bag Brand') }}</th>
            <th width="10px">{{ __('Pcs') }}</th>
            <th width="10px">{{ __('Kgs') }}</th>
            <th width="10px">{{ __('Gram Per Bag') }}</th>
        </tr>
        @php
            $totalsAll = [
                'allTotalPcs' => 0,
                'allTotalKgs' => 0,
                'allTotalGramPerBag' => 0,
            ];
        @endphp
        @foreach ($datas['formattedDatas'] as $name => $formattedData)
            <tbody>
                @php
                    $totals = [
                        'totalPcs' => 0,
                        'totalKgs' => 0,
                        'totalGramPerBag' => 0,
                    ];
                @endphp
                @foreach ($formattedData as $key => $data)
                    <tr>
                        <td>{{ $data['receipt_date'] }}</td>
                        <td>{{ $data['bag_brand'] }}</td>
                        <td>{{ $data['qty_pcs'] }}</td>
                        <td>{{ $data['qty_in_kg'] }}</td>
                        <td>{{ $data['gram_per_bag'] }}</td>
                    </tr>
                    @php
                        $totals['totalPcs'] += $data['qty_pcs'];
                        $totals['totalKgs'] += $data['qty_in_kg'];
                        $totals['totalGramPerBag'] += $data['gram_per_bag'];
                    @endphp
                @endforeach
                <tr style="font-weight:bold">
                    <td>Total</td>
                    <td></td>
                    <td>{{ $totals['totalPcs'] }}</td>
                    <td>{{ $totals['totalKgs'] }}</td>
                    <td>{{ $totals['totalGramPerBag'] }}</td>
                </tr>



            </tbody>
            @php
                $totalsAll['allTotalPcs'] += $totals['totalPcs'];
                $totalsAll['allTotalKgs'] += $totals['totalKgs'];
                $totalsAll['allTotalGramPerBag'] += $totals['totalGramPerBag'];

            @endphp
        @endforeach
        <tfoot>
            <tr>
                <td style="font-weight:bold">Grand Total </td>
                <td></td>
                <td>{{ $totalsAll['allTotalPcs'] }}</td>
                <td>{{ $totalsAll['allTotalKgs'] }}</td>
                <td>{{ $totalsAll['allTotalGramPerBag'] }}</td>
            </tr>
        </tfoot>

    </table>
    <h3 class="m-0 text-center mt-4">SUMMARY</h3>

    <div class="row px-4 py-3">
        <div class="col-12 table-responsive">
            <table class="table table-bordered">p


                <tr>
                    <th width="10px">{{ __('Sr.No') }}</th>
                    <th width="10px">{{ __('Bag Brand') }}</th>
                    <th width="10px">{{ __('Pcs') }}</th>
                    <th width="10px">{{ __('Kgs') }}</th>
                    <th width="10px">{{ __('Gram Per Bag') }}</th>
                </tr>

                <tbody>
                    @php
                        $totalOf_total_qty_in_kg = 0;
                        $totalOf_total_qty_pcs = 0;
                        $totalOf_gram_per_bag = 0;

                    @endphp
                    @foreach ($datas['result'] as $key => $resultData)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $resultData->name }}</td>
                            <td>{{ $resultData->total_qty_in_kg }}</td>
                            <td>{{ $resultData->total_qty_pcs }}</td>
                            <td>{{ number_format(($resultData->total_qty_in_kg / $resultData->total_qty_pcs *1000), 2) }}</td>
                        </tr>
                        @php
                            $totalOf_total_qty_in_kg += $resultData->total_qty_in_kg;
                            $totalOf_total_qty_pcs += $resultData->total_qty_pcs;
                            $totalOf_gram_per_bag += number_format(($resultData->total_qty_in_kg / $resultData->total_qty_pcs *1000), 2);
                        @endphp
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td style="font-weight:bold">Total</td>
                        <td></td>
                        <td>{{ $totalOf_total_qty_in_kg }}</td>
                        <td>{{ $totalOf_total_qty_pcs }}</td>
                        <td>{{ $totalOf_gram_per_bag }}</td>
                    </tr>
                </tfoot>


            </table>
        </div>
    </div>
