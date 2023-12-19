{{-- <h3>Summary of Tape Production Report for Date: {{ $request->start_date }} To {{ $request->end_date }}</h3> --}}
<table class="table table-bordered">
    <thead class="text-bold">
        <tr>
            <th colspan="1" style="text-align: center;border: 2px solid black;">Looms</th>
            <th colspan="3" style="text-align: center;border: 2px solid black;">Today (Looms Avg Meter) </th>
            <th colspan="3" style="text-align: center;border: 2px solid black;">Monthly (Looms Avg Meter)</th>
            <th colspan="3" style="text-align: center;border: 2px solid black;">Yearly (Looms Avg Meter)</th>
           
        </tr>
        <tr>
            <th style="min-width: 120px;border: 1px solid black;">Production</th>
            <th style="border: 1px solid black;">Loom Run</th>
            <th style="border: 1px solid black;">Meter</th>
            <th style="border: 1px solid black;">Avg Mtr.</th>
            <th style="border: 1px solid black;">Loom Run</th>
            <th style="border: 1px solid black;">Meter</th>
            <th style="border: 1px solid black;">Avg Mtr.</th>
            <th style="border: 1px solid black;">Loom Run</th>
            <th style="border: 1px solid black;">Meter</th>
            <th style="border: 1px solid black;">Avg Mtr.</th>
           
        </tr>
    </thead>
    <tbody>
      @php
       $total_today_run_loom_sum  =0;
        $total_today_total_meter  =0;
        $total_today_loomAvg_meter =0;
        $total_monthly_run_loom_sum =0;
        $total_monthly_total_meter_sum =0;
        $total_monthly_loomAvg_meter =0;
        $total_yearly_run_loom_sum =0;
        $total_yearly_total_meter_sum =0;
        $total_yearly_loomAvg_meter =0;
      @endphp   
        
        @foreach ($loomAvgMeter as $date => $item)
            <tr>


                <td>{{ $item['name'] }}</td>
                <td>
                    {{ $item['today_run_loom_sum'] }}
                    @php
                    $total_today_run_loom_sum += $item['today_run_loom_sum'];
                    @endphp
                 </td>
                <td>
                    {{ $item['today_total_meter']}}
                    @php
                    $total_today_total_meter += $item['today_total_meter'];
                    @endphp
                </td>
                <td>
                    {{ number_format($item['today_loomAvg_meter'],2)}}
                    @php
                    $total_today_loomAvg_meter += $item['today_loomAvg_meter'];
                    @endphp
                </td>
                <td>
                    {{ $item['monthly_run_loom_sum']}}
                    @php
                    $total_monthly_run_loom_sum += $item['monthly_run_loom_sum'];
                    @endphp
                </td>
                <td>
                    {{ $item['monthly_total_meter_sum']}}
                    @php
                    $total_monthly_total_meter_sum += $item['monthly_total_meter_sum'];
                    @endphp
                </td>
                <td>
                    {{ number_format($item['monthly_loomAvg_meter'],2)}}
                    @php
                    $total_monthly_loomAvg_meter += $item['monthly_loomAvg_meter'];
                    @endphp
                </td>
                <td>
                   {{ $item['yearly_run_loom_sum']}}
                   @php
                   $total_yearly_run_loom_sum += $item['yearly_run_loom_sum'];
                   @endphp
                </td>
                <td>
                    {{ $item['yearly_total_meter_sum']}}
                    @php
                    $total_yearly_total_meter_sum += $item['yearly_total_meter_sum'];
                    @endphp
                </td>
                <td>
                    {{ number_format($item['yearly_loomAvg_meter'],2)}}
                    @php
                    $total_yearly_loomAvg_meter += $item['yearly_loomAvg_meter'];
                    @endphp
                </td>
            </tr>
        @endforeach
        <tfoot>
            <tr style="font-weight: bold">
                <td>Total</td>
                <td>{{$total_today_run_loom_sum }}</td>
                <td>{{$total_today_total_meter}}</td>
                <td>{{number_format($total_today_total_meter/$total_today_run_loom_sum,2) }}</td>
                <td>{{$total_monthly_run_loom_sum }}</td>
                <td>{{$total_monthly_total_meter_sum }}</td>
                <td>{{number_format($total_monthly_total_meter_sum/$total_monthly_run_loom_sum,2)}}</td>
                <td>{{$total_yearly_run_loom_sum  }}</td>
                <td>{{$total_yearly_total_meter_sum }}</td>
                <td>{{number_format($total_yearly_total_meter_sum/$total_yearly_run_loom_sum,2) }}</td>
               
            </tr>
        </tfoot>
    </tbody>
</table>
