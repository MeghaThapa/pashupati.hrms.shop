{{-- <h3>Summary of Tape Production Report for Date: {{ $request->start_date }} To {{ $request->end_date }}</h3> --}}
<table class="table table-bordered table-responsive">
    <thead class="text-bold">
        <tr>
            {{-- <th style="text-align: center;border: 2px solid black;"></th> --}}
            <th colspan="1" style="text-align: center;border: 2px solid black;">PP Bags </th>
            <th colspan="3" style="text-align: center;border: 2px solid black;">production </th>
            <th colspan="3" style="text-align: center;border: 2px solid black;">Plant Wastage</th>
            <th colspan="3" style="text-align: center;border: 2px solid black;">Plant Wastage %</th>
        </tr>
        <tr>
            <th style="min-width: 120px;border: 1px solid black;">Production</th>
            <th style="border: 1px solid black;">Today</th>
            <th style="border: 1px solid black;">Monthly</th>
            <th style="border: 1px solid black;">Yearly</th>
            <th style="border: 1px solid black;">Today</th>
            <th style="border: 1px solid black;">Monthly</th>
            <th style="border: 1px solid black;">Yearly</th>
            <th style="border: 1px solid black;">Today</th>
            <th style="border: 1px solid black;">Monthly</th>
            <th style="border: 1px solid black;">Yearly</th>
           
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $ppBags['mergeData']['pcs']['type'] }}</td>
            <td>{{ $ppBags['mergeData']['pcs']['today_total_qty_pcs'] ?? 0 }}</td>
            <td>{{ $ppBags['mergeData']['pcs']['monthly_total_qty_pcs'] ?? 0 }}</td>
            <td>{{ $ppBags['mergeData']['pcs']['yearly_total_qty_pcs'] ?? 0 }}</td>
            @php
            $todayWaste = $ppBags['todayWaste'][0] ?? [];
            $today_total_wastage = isset($todayWaste['today_total_wastage']) ? $todayWaste['today_total_wastage'] : 0;
        @endphp
        
            <td>{{ $today_total_wastage }}</td>
            @php
            $monthlyWaste = $ppBags['monthlyWaste'][0] ?? [];
            $monthly_total_wastage = isset($monthlyWaste['monthly_total_wastage']) ? $monthlyWaste['monthly_total_wastage'] : 0;
        @endphp
        
            <td>{{ $monthly_total_wastage}}</td>


            @php
            $yearlyWaste = $ppBags['yearlyWaste'][0] ?? [];
            $yearly_total_wastage = isset($yearlyWaste['yearly_total_wastage']) ? $yearlyWaste['yearly_total_wastage'] : 0;
        @endphp
        
            <td>{{ $yearly_total_wastage}}</td>
            <td>{{ $ppBags['today_waste_per']['today_waste_perc'] ?? 0 }}</td>
            <td>{{ $ppBags['monthly_waste_perc']['monthly_waste_perc'] ?? 0 }}</td>
            <td>{{ $ppBags['yearly_waste_perc']['yearly_waste_perc'] ?? 0 }}</td>
        </tr>
        <tr>
            <td>{{ $ppBags['mergeData']['kg']['type'] }}</td>
            <td>{{ $ppBags['mergeData']['kg']['today_total_qty_in_kg'] ?? 0 }}</td>
            <td>{{ $ppBags['mergeData']['kg']['monthly_total_qty_in_kg'] ?? 0 }}</td>
            <td>{{ $ppBags['mergeData']['kg']['yearly_total_qty_in_kg'] ?? 0 }}</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        
       
        {{-- <tfoot>
            <tr style="font-weight: bold">
                <td>Total</td>
                <td>{{ $total_today_total_quantity }}</td>
                <td>{{ $total_monthly_total_quantity }}</td>
                <td>{{ $total_yearly_total_quantity }}</td>
                <td>{{ $total_today_total_wastages }}</td>
                <td>{{ $total_monthly_total_wastages }}</td>
                <td>{{ $total_yearly_total_wastages }}</td>
                <td>{{ $total_today_wastage_perc }}</td>
                <td>{{ $total_monthly_wastage_perc }}</td>
                <td>{{ $total_yearly_wastage_perc }}</td>
            </tr>
        </tfoot> --}}
    </tbody>
</table>
