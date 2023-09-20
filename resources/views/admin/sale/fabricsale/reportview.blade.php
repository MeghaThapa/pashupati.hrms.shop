@foreach($fabricSalesEntries as $saleEntry)
<h4 style="margin-top:100px;">{{ $saleEntry->bill_for }} PP Woven Packing List Details</h4>
<table class="table table-striped">
    <thead>
        <tr>
            <th colspan="3">
                Party Name : {{ $saleEntry->getParty->name }}
            </th>
            <th colspan="5" style="text-align: center !important;">
                A/C No:
            </th>
        </tr>
        <tr>
            <th colspan="2">
                Bill No: {{ $saleEntry->bill_no }}
            </th>
            <th colspan="3">
                Gate Pass No: {{ $saleEntry->gp_no }}
            </th>
            <th colspan="3">
                Bill Date : {{ $saleEntry->bill_date }}
            </th>
        </tr>
        <tr>
            <th>No of Rolls</th>
            <th>Roll No</th>
            <th>Particulars</th>
            <th>Gross Wt</th>
            <th>Net Wt</th>
            <th>Meter</th>
            <th>Avg Wt</th>
            <th>Avg Gram</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i = 0;
            $gross_wt = 0;
            $net_wt = 0;
            $meter = 0;
            $total_avg = 0;
            $total_gram_wt = 0;

        @endphp

        @foreach($saleEntry->fabricSaleItems as $fabricSaleItem)
        <tr>
            <td> {{ ++$i }} </td>
            <td>{{ $fabricSaleItem->fabric->roll_no }}</td>
            <td>{{ $fabricSaleItem->fabric->name }}</td>
            <td>{{ $fabricSaleItem->fabric->gross_wt }}</td>
            <td>{{ $fabricSaleItem->fabric->net_wt }}</td>
            <td>{{ $fabricSaleItem->fabric->meter }}</td>
            <td>{{ $fabricSaleItem->fabric->average_wt }}</td>
            <td>{{ $fabricSaleItem->fabric->gram_wt }}</td>
        </tr>

        @php
            $gross_wt +=  $fabricSaleItem->fabric->gross_wt;
            $net_wt +=  $fabricSaleItem->fabric->net_wt;
            $meter +=  $fabricSaleItem->fabric->meter;
            $total_avg += $fabricSaleItem->fabric->average_wt;
            $total_gram_wt += $fabricSaleItem->fabric->gram_wt;
        @endphp

        @endforeach

    </tbody>
    <tfoot>
        <tr>
            <td colspan="3">Total</td>
            <td>{{ $gross_wt }}</td>
            <td>{{ $net_wt }}</td>
            <td>{{ $meter }}</td>
            <td> {{ $total_avg/$i }} </td>
            <td> {{ $total_gram_wt/$i }} </td>
        </tr>
        <tr>
            <td colspan="3">Grand Total For no of Rolls: {{ $i }}</td>
            <td>{{ $gross_wt }}</td>
            <td>{{ $net_wt }}</td>
            <td>{{ $meter }}</td>
        </tr>
    </tfoot>
</table>
@endforeach

<br/><br/>
