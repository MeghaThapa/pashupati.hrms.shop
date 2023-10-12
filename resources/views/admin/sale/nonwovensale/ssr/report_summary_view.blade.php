<div class="container">
    <h1>Summary Report</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Fabric Name</th>
                <th>Fabric GSM</th>
                <th>Fabric Color</th>
                <th>Fabric Count</th>
                <th>Total Length</th>
                <th>Total Gross Weight</th>
                <th>Total Net Weight</th>
            </tr>
        </thead>
        <tbody>
            @php
            $countTotal = 0;
            $totalLength = 0;
            $totalGrossWt = 0;
            $totalNetWt = 0;
            @endphp
            @foreach ($summaryReport as $row)
                <tr>
                    <td>{{ $row->fabric_name }}</td>
                    <td>{{ $row->fabric_gsm }}</td>
                    <td>{{ $row->fabric_color }}</td>
                    <td>{{ $row->fabric_count }}</td>
                    <td>{{ $row->total_length }}</td>
                    <td>{{ $row->total_gross_wt }}</td>
                    <td>{{ $row->total_net_wt }}</td>
                </tr>
                @php
                $countTotal += $row->fabric_count;
                $totalLength += $row->total_length;
                $totalGrossWt += $row->total_gross_wt;
                $totalNetWt += $row->total_net_wt;
                @endphp
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ $countTotal }}</td>
                <td>{{ $totalLength }}</td>
                <td>{{ $totalGrossWt }}</td>
                <td>{{ $totalNetWt }}</td>
            </tr>
        </tbody>
    </table>
</div>
