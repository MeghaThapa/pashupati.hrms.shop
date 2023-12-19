{{-- <h3>Summary of Tape Production Report for Date: {{ $request->start_date }} To {{ $request->end_date }}</h3> --}}
<table class="table table-bordered">
    <thead class="text-bold">
        <tr>
            {{-- <th style="text-align: center;border: 2px solid black;"></th> --}}
            <th colspan="1" style="text-align: center;border: 2px solid black;">Erema Plant </th>
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
      @php
       $total_today_total_quantity  = 0;
       $total_monthly_total_quantity  = 0;
       $total_yearly_total_quantity = 0;
       $total_today_total_waste = 0;
       $total_monthly_total_waste = 0;
       $total_yearly_total_waste = 0;
       $total_today_waste_perc = 0;
       $total_monthly_waste_perc = 0;
       $total_yearly_waste_perc = 0;
      @endphp     
        @foreach ($erimaPlantProd as $date => $item)
            <tr>
                <td>{{ $item['wastage_key'] }}</td>
                <td>
                    {{ $item['today_total_quantity'] }}
                    @php
                    $total_today_total_quantity += $item['today_total_quantity'];
                    @endphp
                 </td>
                <td>
                    {{ $item['monthly_total_quantity']}}
                    @php
                    $total_monthly_total_quantity += $item['monthly_total_quantity'];
                    @endphp
                </td>
                <td>
                    {{ $item['yearly_total_quantity']}}
                    @php
                    $total_yearly_total_quantity += $item['yearly_total_quantity'];
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
                   {{ $item['today_waste_perc']}}
                   @php
                   $total_today_waste_perc += $item['today_waste_perc'];
                   @endphp
                </td>
                <td>
                    {{ $item['monthly_waste_perc']}}
                    @php
                    $total_monthly_waste_perc += $item['monthly_waste_perc'];
                    @endphp
                </td>
                <td>
                    {{ $item['yearly_waste_perc']}}
                    @php
                    $total_yearly_waste_perc += $item['yearly_waste_perc'];
                    @endphp
                </td>
            </tr>
        @endforeach
        <tfoot>
            <tr style="font-weight: bold">
                <td>Total</td>
                <td>{{ $total_today_total_quantity }}</td>
                <td>{{ $total_monthly_total_quantity }}</td>
                <td>{{ $total_yearly_total_quantity }}</td>
                <td>{{ $total_today_total_waste }}</td>
                <td>{{ $total_monthly_total_waste }}</td>
                <td>{{ $total_yearly_total_waste }}</td>
                <td>
                    @if ($total_today_total_quantity != 0)
                    {{ number_format($total_today_total_waste / $total_today_total_quantity * 100, 2) }}
                    @else
                    0
                    @endif
                </td>
                <td>
                    @if ($total_monthly_total_quantity != 0)
                    {{ number_format($total_monthly_total_waste / $total_monthly_total_quantity * 100, 2) }}
                    @else
                    0
                    @endif
                </td>
                <td>
                    @if ($total_yearly_total_quantity != 0) 
                    {{ number_format($total_yearly_total_waste / $total_yearly_total_quantity * 100, 2) }}
                    @else
                    0
                    @endif
                </td>
                </tr>
        </tfoot>
    </tbody>
</table>
