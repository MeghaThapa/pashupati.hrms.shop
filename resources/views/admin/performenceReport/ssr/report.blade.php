<h3>Performance Report for Date: {{ $request->given_date }} </h3>
<table class="table table-bordered table-responsive">
    <thead class="text-bold">
        <tr>
            {{-- <th style="text-align: center;border: 2px solid black;"></th> --}}
            <th colspan="1" style="text-align: center;border: 2px solid black;">Tape </th>
            <th colspan="3" style="text-align: center;border: 2px solid black;">Production(Kgs)</th>
            <th colspan="3" style="text-align: center;border: 2px solid black;">Plant Wastage (Kgs)</th>
            <th colspan="3" style="text-align: center;border: 2px solid black;">Plant Wastage %</th>
        </tr>
        <tr>
            <th style="min-width: 120px;border: 1px solid black;">Plant</th>
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
        $total_today_tape_quantity = 0;
        $total_monthly_tape_quantity = 0;
        $total_yearly_tape_quantity = 0;
        $total_today_waste = 0;
        $total_monthly_total_waste = 0;
        $total_yearly_total_waste = 0;
        $total_today_wastage_perc = 0;
        $total_monthly_wastage_perc = 0;
        $total_yearly_wastage_perc = 0;
      @endphp   
        
        @foreach ($datas as $date => $item)
            <tr>


                <td>{{ $item['plant_name'] }}</td>
                <td>
                    {{ $item['today_tape_quantity'] }}
                    @php
                    $total_today_tape_quantity += $item['today_tape_quantity'];
                    @endphp
                 </td>
                <td>
                    {{ $item['monthly_tape_quantity']}}
                    @php
                    $total_monthly_tape_quantity += $item['monthly_tape_quantity'];
                    @endphp
                </td>
                <td>
                    {{ $item['yearly_tape_quantity']}}
                    @php
                    $total_yearly_tape_quantity += $item['yearly_tape_quantity'];
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
                   {{number_format( $item['today_wastage_perc'],2)}}
                   @php
                   $total_today_wastage_perc += $item['today_wastage_perc'];
                   @endphp
                </td>
                <td>
                    {{ number_format($item['monthly_wastage_perc'],2)}}
                    @php
                    $total_monthly_wastage_perc += $item['monthly_wastage_perc'];
                    @endphp
                </td>
                <td>
                    {{ number_format($item['yearly_wastage_perc'],2)}}
                    @php
                    $total_yearly_wastage_perc += $item['yearly_wastage_perc'];
                    @endphp
                </td>
            </tr>
        @endforeach
        <tfoot>
            <tr style="font-weight: bold">
                <td>Total</td>
                <td>{{ $total_today_tape_quantity }}</td>
                <td>{{ $total_monthly_tape_quantity }}</td>
                <td>{{ $total_yearly_tape_quantity }}</td>
                <td>{{ $total_today_waste }}</td>
                <td>{{ $total_monthly_total_waste }}</td>
                <td>{{ $total_yearly_total_waste }}</td>
                <td>{{number_format($total_today_wastage_perc ,2) }}</td>
                <td>{{ number_format($total_monthly_wastage_perc,2) }}</td>
                <td>{{ number_format($total_yearly_wastage_perc,2) }}</td>
               
            </tr>
        </tfoot>
    </tbody>
</table>
