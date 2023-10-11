@foreach ($nonWovenStockArray as $fabricName => $fabricGSMs)
    @foreach ($fabricGSMs as $fabricGSM => $fabricColors)
        @foreach ($fabricColors as $fabricColor => $data)
            <table class="table table-bordered table-striped">
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
                    @foreach ($data as $row)
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
                </tbody>
            </table>
        @endforeach
    @endforeach
@endforeach

<h3>Summary Report</h3>
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
        @foreach ($summary as $fabricName => $fabricGSMs)
            @foreach ($fabricGSMs as $fabricGSM => $fabricColors)
                @foreach ($fabricColors as $fabricColor => $summaryData)
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
    </tbody>
</table>
