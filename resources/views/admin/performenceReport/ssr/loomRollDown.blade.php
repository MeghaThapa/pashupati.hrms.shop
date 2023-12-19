{{-- <h3>Summary of Tape Production Report for Date: {{ $request->start_date }} To {{ $request->end_date }}</h3> --}}
<table class="table table-bordered">
    <thead class="text-bold">
        <tr>
            {{-- <th style="text-align: center;border: 2px solid black;"></th> --}}
            <th colspan="1" style="text-align: center;border: 2px solid black;">Looms </th>
            <th colspan="3" style="text-align: center;border: 2px solid black;">Production (Roll Down)</th>
            <th colspan="3" style="text-align: center;border: 2px solid black;">Looms Wastage Wastage</th>
            <th colspan="3" style="text-align: center;border: 2px solid black;">Looms Wastage %</th>
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
      @php
       $total_today_netweight_sum  = 0;
       $total_monthly_netweight_sum  = 0;
       $total_yearly_netweight_sum = 0;
       $total_today_waste = 0;
        $total_monthly_total_waste = 0;
        $total_yearly_total_waste = 0;
        $total_today_wastage_perc = 0;
        $total_monthly_wastage_perc = 0;
        $total_yearly_wastage_perc = 0;
      @endphp   
        
        @foreach ($loomRollDown as $date => $item)
            <tr>


                <td>{{ $item['name'] }}</td>
                <td>
                    {{ $item['today_netweight_sum'] }}
                    @php
                    $total_today_netweight_sum += $item['today_netweight_sum'];
                    @endphp
                 </td>
                <td>
                    {{ $item['monthly_netweight_sum']}}
                    @php
                    $total_monthly_netweight_sum += $item['monthly_netweight_sum'];
                    @endphp
                </td>
                <td>
                    {{ $item['yearly_netweight_sum']}}
                    @php
                    $total_yearly_netweight_sum += $item['yearly_netweight_sum'];
                    @endphp
                </td>
                <td>
                    {{ $item['today_waste']}}
                    @php
                    $total_today_waste += $item['today_waste'];
                    @endphp
                </td>
                <td>
                    {{ $item['monthly_total_waste']}}
                    @php
                    $total_monthly_total_waste += $item['monthly_total_waste'];
                    @endphp
                </td>
                <td>
                    {{ $item['yearly_total_waste']}}
                    @php
                    $total_yearly_total_waste += $item['yearly_total_waste'];
                    @endphp
                </td>
                <td>
                   {{ $item['today_wastage_perc']}}
                   @php
                   $total_today_wastage_perc += $item['today_wastage_perc'];
                   @endphp
                </td>
                <td>
                    {{ $item['monthly_waste_perc']}}
                    @php
                    $total_monthly_wastage_perc += $item['monthly_waste_perc'];
                    @endphp
                </td>
                <td>
                    {{ $item['yearly_waste_perc']}}
                    @php
                    $total_yearly_wastage_perc += $item['yearly_waste_perc'];
                    @endphp
                </td>
            </tr>
        @endforeach
        <tfoot>
            <tr style="font-weight: bold">
                <td>Total</td>
                <td>{{ $total_today_netweight_sum }}</td>
                <td>{{ $total_monthly_netweight_sum }}</td>
                <td>{{ $total_yearly_netweight_sum }}</td>
                <td>{{ $total_today_waste }}</td>
                <td>{{ $total_monthly_total_waste }}</td>
                <td>{{ $total_yearly_total_waste }}</td>
                <td>{{number_format($total_today_waste/$total_today_netweight_sum*100,2) }}</td>
                <td>{{number_format($total_monthly_total_waste/$total_monthly_netweight_sum*100,2) }}</td>
                <td>{{number_format($total_yearly_total_waste/$total_yearly_netweight_sum*100,2) }}</td>
            </tr>
        </tfoot>
    </tbody>
</table>
