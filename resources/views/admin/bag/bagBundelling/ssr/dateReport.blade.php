<h3>Bag Production Report Acc Date: {{ $request->start_date }} To {{ $request->end_date }}</h3>
<table class="table table-bordered table-responsive">
   
    <table class="table table-bordered" style="padding: 0 30px;">
        <tr>
            <th width="10px">{{ __('Date') }}</th>
            <th width="10px">{{ __('Pcs') }}</th>
            <th width="10px">{{ __('Kgs') }}</th>
            <th width="10px">{{ __('Wastage') }}</th>
            <th width="10px">{{ __('%') }}</th>
        </tr>
        {{-- @php
            $totalsAll = [
                'allTotalPcs' => 0,
                'allTotalKgs' => 0,
                'allTotalGramPerBag' => 0,
            ];
        @endphp --}}
        @php
        $totalOf_total_wastage = 0;
        $totalOf_total_qty_pcs = 0;
        $totalOf_total_qty_in_kg = 0;
    @endphp
    
    @foreach ($datas as $name => $formattedData)
        <tbody>
            <tr>
                <td>{{ isset($formattedData['receipt_date']) ? $formattedData['receipt_date'] : $formattedData['date'] }}</td>
                <td>{{ $formattedData['total_qty_pcs']?? 0 }}</td>
                <td>{{ $formattedData['total_qty_in_kg'] ?? 0}}</td>
                <td>{{ $formattedData['total_wastage']?? 0 }}</td>
                <td>{{ $formattedData['total_qty_in_kg'] != 0 ?  number_format($formattedData['total_wastage'] / $formattedData['total_qty_in_kg'] * 100,2) : 0 }}</td>
            </tr>
        </tbody>
    
        @php
            $totalOf_total_wastage += $formattedData['total_wastage'];
            $totalOf_total_qty_pcs += $formattedData['total_qty_pcs'];
            $totalOf_total_qty_in_kg += $formattedData['total_qty_in_kg'];
        @endphp
    @endforeach
    
    <tfoot>
        <tr>
            <td style="font-weight:bold">Grand Total </td>
            <td>{{ $totalOf_total_qty_pcs }}</td>
            <td>{{ $totalOf_total_qty_in_kg }}</td>
            <td>{{ $totalOf_total_wastage }}</td>
            <td>{{ $totalOf_total_qty_in_kg != 0 ?  number_format($totalOf_total_wastage / $totalOf_total_qty_in_kg * 100 ,2 ) : 0 }}</td>
        </tr>
    </tfoot>
    

    </table>