<h3>Summary Report</h3>
<table class="table table-striped">
    <thead>
        <tr>
            <th>S.N.</th>
            <th>Particulars</th>
            <th>No of Rolls</th>
            <th>Gross Wt</th>
            <th>Net Wt</th>
            <th>Meter</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i = 0;
            $gross_wt = 0;
            $net_wt = 0;
            $meter = 0;

        @endphp

        @foreach($summaryReport as $fabricSaleItem)
        <tr>
            <td> {{ ++$i }} </td>
            <td>{{ $fabricSaleItem->fabric_name }}</td>
            <td>{{ $fabricSaleItem->fabric_count }}</td>
            <td>{{ $fabricSaleItem->total_gross_wt }}</td>
            <td>{{ $fabricSaleItem->total_net_wt }}</td>
            <td>{{ $fabricSaleItem->total_meter }}</td>
        </tr>

        @php
            $gross_wt +=  $fabricSaleItem->total_gross_wt;
            $net_wt +=  $fabricSaleItem->total_net_wt;
            $meter +=  $fabricSaleItem->total_meter;
        @endphp

        @endforeach

    </tbody>
    <tfoot>
        <tr>
            <td colspan="3">Total</td>
            <td>{{ $gross_wt }}</td>
            <td>{{ $net_wt }}</td>
            <td>{{ $meter }}</td>
            <td></td>
            <td></td>
        </tr>
    </tfoot>
</table>

<br/><br/>
