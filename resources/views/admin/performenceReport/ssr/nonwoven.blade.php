{{-- <h3>Summary of Tape Production Report for Date: {{ $request->start_date }} To {{ $request->end_date }}</h3> --}}
<table class="table table-bordered">
    <thead class="text-bold">
        <tr>
            {{-- <th style="text-align: center;border: 2px solid black;"></th> --}}
            <th colspan="1" style="text-align: center;border: 2px solid black;">Non Woven </th>
            <th colspan="3" style="text-align: center;border: 2px solid black;">production </th>
            <th colspan="3" style="text-align: center;border: 2px solid black;">production Wastage</th>
            <th colspan="3" style="text-align: center;border: 2px solid black;">production Wastage %</th>
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
       $total_today_total_net_weight  = 0;
       $total_monthly_total_net_weight  = 0;
       $total_yearly_total_net_weight = 0;
       $total_today_total_waste = 0;
       $total_monthly_total_waste = 0;
       $total_yearly_total_waste = 0;
       $total_today_wastage_perc = 0;
       $total_monthly_wastage_perc = 0;
       $total_yearly_wastage_perc = 0;
      @endphp     
        @foreach ($nonWovenProduction as $date => $item)
            <tr>
                <td>{{ $item['plant_name'] }}</td>
                <td>
                    {{ $item['today_total_net_weight'] }}
                    @php
                    $total_today_total_net_weight += $item['today_total_net_weight'];
                    @endphp
                 </td>
                <td>
                    {{ $item['monthly_total_net_weight']}}
                    @php
                    $total_monthly_total_net_weight += $item['monthly_total_net_weight'];
                    @endphp
                </td>
                <td>
                    {{ $item['yearly_total_net_weight']}}
                    @php
                    $total_yearly_total_net_weight += $item['yearly_total_net_weight'];
                    @endphp
                </td>
                <td>
                    {{ $item['today_total_waste']}}
                    @php
                    $total_today_total_waste += $item['today_total_waste'];
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
                    {{ $item['monthly_wastage_perc']}}
                    @php
                    $total_monthly_wastage_perc += $item['monthly_wastage_perc'];
                    @endphp
                </td>
                <td>
                    {{ $item['yearly_wastage_perc']}}
                    @php
                    $total_yearly_wastage_perc += $item['yearly_wastage_perc'];
                    @endphp
                </td>
            </tr>
        @endforeach
        <tfoot>
            <tr style="font-weight: bold">
                <td>Total</td>
                <td>{{ $total_today_total_net_weight }}</td>
                <td>{{ $total_monthly_total_net_weight }}</td>
                <td>{{ $total_yearly_total_net_weight }}</td>
                <td>{{ $total_today_total_waste }}</td>
                <td>{{ $total_monthly_total_waste }}</td>
                <td>{{ $total_yearly_total_waste }}</td>
                <td>{{ $total_today_wastage_perc }}</td>
                <td>{{ $total_monthly_wastage_perc }}</td>
                <td>{{ $total_yearly_wastage_perc }}</td>
               
            </tr>
        </tfoot>
    </tbody>
</table>
