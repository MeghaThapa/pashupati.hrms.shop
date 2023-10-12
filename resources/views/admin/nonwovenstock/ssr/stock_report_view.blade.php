@foreach ($nonWovenStockArray as $fabricName => $fabricGSMs)
    @foreach ($fabricGSMs as $fabricGSM => $fabricColors)
        @foreach ($fabricColors as $fabricColor => $data)
            <table class="table table-bordered table-striped" style="margin-top: 60px;">
                <thead>
                    <tr>
                        <th>Fabric Name</th>
                        <th>Fabric GSM</th>
                        <th>Fabric Color</th>
                        <th>Godam ID</th>
                        <th>Fabric Roll</th>
                        <th>Length</th>
                        <th>Gross Weight</th>
                        <th>Net Weight</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalLength = 0;
                        $totalGrossWeight = 0;
                        $totalNetWeight = 0;
                    @endphp
                    @foreach ($data as $row)
                        @php
                            $totalLength += (float)$row['length'];
                            $totalGrossWeight += (float)$row['gross_weight'];
                            $totalNetWeight += (float)$row['net_weight'];
                        @endphp
                        <tr>
                            <td>{{ $fabricName }}</td>
                            <td>{{ $fabricGSM }}</td>
                            <td>{{ $fabricColor }}</td>
                            <td>{{ $row['godam_id'] }}</td>
                            <td>{{ $row['fabric_roll'] }}</td>
                            <td>{{ $row['length'] }}</td>
                            <td>{{ $row['gross_weight'] }}</td>
                            <td>{{ $row['net_weight'] }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="5">Total</td>
                        <td>{{ $totalLength }}</td>
                        <td>{{ $totalGrossWeight }}</td>
                        <td>{{ $totalNetWeight }}</td>
                    </tr>
                </tbody>
            </table>
        @endforeach
    @endforeach
@endforeach

<h3 style="margin-top: 60px;">Summary Report</h3>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Fabric Name</th>
            <th>Fabric GSM</th>
            <th>Fabric Color</th>
            <th>Total Godam ID</th>
            <th>Total Fabric Roll Count</th>
            <th>Total Length</th>
            <th>Total Gross Weight</th>
            <th>Total Net Weight</th>
        </tr>
    </thead>
    <tbody>
        @php
        $totalFabricRollCount = 0;
        $totalLengthTotal = 0;
        $totalGrossWeightTotal = 0;
        $totalNetWeightTotal = 0;
        @endphp
        @foreach ($summary as $fabricName => $fabricGSMs)
            @foreach ($fabricGSMs as $fabricGSM => $fabricColors)
                @foreach ($fabricColors as $fabricColor => $summaryData)
                    @php
                        $totalFabricRollCount += (float)$summaryData['fabric_roll_count'];
                        $totalLengthTotal += (float)$summaryData['length_total'];
                        $totalGrossWeightTotal += (float)$summaryData['gross_weight_total'];
                        $totalNetWeightTotal += (float)$summaryData['net_weight_total'];
                    @endphp
                    <tr>
                        <td>{{ $fabricName }}</td>
                        <td>{{ $fabricGSM }}</td>
                        <td>{{ $fabricColor }}</td>
                        <td>{{ $summaryData['godam_id'] }}</td>
                        <td>{{ $summaryData['fabric_roll_count'] }}</td>
                        <td>{{ $summaryData['length_total'] }}</td>
                        <td>{{ $summaryData['gross_weight_total'] }}</td>
                        <td>{{ $summaryData['net_weight_total'] }}</td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach
        <tr>
            <td>Total</td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ $totalFabricRollCount }}</td>
            <td>{{ $totalLengthTotal }}</td>
            <td>{{ $totalGrossWeightTotal }}</td>
            <td>{{ $totalNetWeightTotal }}</td>
        </tr>
    </tbody>
</table>
