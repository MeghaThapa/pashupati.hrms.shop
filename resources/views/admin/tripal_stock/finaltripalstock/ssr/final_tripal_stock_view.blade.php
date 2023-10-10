@foreach ($finalTripalArray as $finalTripalStock)
    <table class="table table-bordered table-striped" style="margin-top: 80px;">
        <thead>
            <tr>
                <th>{{ __('S.No') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Roll No') }}</th>
                <th>{{ __('Net Wt') }}</th>
                <th>{{ __('Gross Wt') }}</th>
                <th>{{ __('Meter') }}</th>
                <th>{{ __('Average Weight') }}</th>
            </tr>
        </thead>

        <tbody>
            @php
                $i = 0;
                $totalNetWt = 0;
                $totalGrossWt = 0;
                $totalMeter = 0;
                $totalAverageWeight = 0;
            @endphp
            @foreach ($finalTripalStock as $item)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['roll'] }}</td>
                    <td>{{ $item['net_wt'] }}</td>
                    <td>{{ $item['gross_wt'] }}</td>
                    <td>{{ $item['meter'] }}</td>
                    <td>{{ $item['average_wt'] }}</td>
                @php
                    $totalNetWt         += (float)$item['net_wt'];
                    $totalGrossWt       += (float)$item['gross_wt'];
                    $totalMeter         += (float)$item['meter'];
                    $totalAverageWeight += (float)$item['average_wt'];
                @endphp
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td>Total</td>
                <td></td>
                <td>{{ number_format($totalNetWt, 2) }}</td>
                <td>{{ number_format($totalGrossWt, 2) }}</td>
                <td>{{ number_format($totalMeter, 2) }}</td>
                <td>{{ number_format($totalAverageWeight, 2) }}</td>
            </tr>
        </tfoot>

    </table>
@endforeach


    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th>Tripal Name</th>
                <th>Rolls</th>
                <th>Net Wt</th>
                <th>Gross Weight</th>
                <th>Meter</th>
                <th>Average Wt</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalRolls = 0;
                $totalNetWeight = 0;
                $totalGrossWeight = 0;
                $totalMeter = 0;
                $totalAverageWeight = 0;
            @endphp
            @foreach ($summaryData as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['roll_count'] }}</td>
                    <td>{{ $item['sum_net_weight'] }}</td>
                    <td>{{ $item['sum_gross_weight'] }}</td>
                    <td>{{ $item['sum_meter'] }}</td>
                    <td>{{ $item['sum_average_wt'] }}</td>
                </tr>
                @php
                    $totalRolls += $item['roll_count'];
                    $totalNetWeight += $item['sum_net_weight'];
                    $totalGrossWeight += $item['sum_gross_weight'];
                    $totalMeter += $item['sum_meter'];
                    $totalAverageWeight += $item['sum_average_wt'];
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td><strong>Total</strong></td>
                <td><strong>{{ $totalRolls }}</strong></td>
                <td><strong>{{ $totalNetWeight }}</strong></td>
                <td><strong>{{ $totalGrossWeight }}</strong></td>
                <td><strong>{{ $totalMeter }}</strong></td>
                <td><strong>{{ $totalAverageWeight }}</strong></td>
            </tr>
        </tfoot>
    </table>
