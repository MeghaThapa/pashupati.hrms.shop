@foreach ($nonWovenStockArray as $nonWovenStock)
    <table class="table table-bordered table-striped" style="margin-top: 80px;">
        <thead>
            <tr>
                <th>{{ __('S.No') }}</th>
                <th>{{ __('Godam') }}</th>
                <th>{{ __('Roll No') }}</th>
                <th>{{ __('Fabric Gsm') }}</th>
                <th>{{ __('Fabric Name') }}</th>
                <th>{{ __('Fabric Color') }}</th>
                <th>{{ __('Length') }}</th>
                <th>{{ __('Gross Weight') }}</th>
                <th>{{ __('Net Weight') }}</th>
            </tr>
        </thead>

        <tbody>
            @php
                $i = 0;
                $totalGrossWt = 0;
                $totalNetWt = 0;
            @endphp
            @foreach ($nonWovenStock as $item)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $item['godam_id'] }}</td>
                    <td>{{ $item['fabric_roll'] }}</td>
                    <td>{{ $item['fabric_gsm'] }}</td>
                    <td>{{ $item['fabric_name'] }}</td>
                    <td>{{ $item['fabric_color'] }}</td>
                    <td>{{ $item['length'] }}</td>
                    <td>
                        @php $totalGrossWt += (float)$item['gross_weight']; @endphp
                        {{ $item['gross_weight'] }}
                    </td>
                    <td>
                        @php $totalNetWt += (float)$item['net_weight']; @endphp
                        {{ $item['net_weight'] }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ number_format($totalGrossWt, 2) }}</td>
                <td>{{ number_format($totalNetWt, 2) }}</td>
            </tr>
        </tfoot>

    </table>
@endforeach


    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th>Fabric Name</th>
                <th>Rolls</th>
                <th>length</th>
                <th>Gross Weight</th>
                <th>Net Weight</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalRolls = 0;
                $totalLength = 0;
                $totalGrossWeight = 0;
                $totalNetWeight = 0;
            @endphp
            @foreach ($summaryData as $item)
                <tr>
                    <td>{{ $item['fabric_name'] }}</td>
                    <td>{{ $item['count(fabric_roll)'] }}</td>
                    <td>{{ $item['sum(length)'] }}</td>
                    <td>{{ $item['sum(gross_weight)'] }}</td>
                    <td>{{ $item['sum(net_weight)'] }}</td>
                </tr>
                @php
                    $totalRolls += $item['count(fabric_roll)'];
                    $totalLength += $item['sum(length)'];
                    $totalGrossWeight += $item['sum(gross_weight)'];
                    $totalNetWeight += $item['sum(net_weight)'];
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td><strong>Total</strong></td>
                <td><strong>{{ $totalRolls }}</strong></td>
                <td><strong>{{ $totalLength }}</strong></td>
                <td><strong>{{ $totalGrossWeight }}</strong></td>
                <td><strong>{{ $totalNetWeight }}</strong></td>
            </tr>
        </tfoot>
    </table>
