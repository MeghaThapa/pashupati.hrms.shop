@foreach($saleFinalTripals as $saleEntry)
<h4 style="margin-top:100px;">{{ $saleEntry->bill_for }} Sale Final Tripal Details</h4>
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
                Invoice No: {{ $saleEntry->bill_no }}
            </th>
            <th colspan="3">
                Gate Pass No: {{ $saleEntry->gp_no }}
            </th>
            <th colspan="3">
                Bill Date : {{ $saleEntry->bill_date }}
            </th>
        </tr>
        <tr>
            <th>S.N.</th>
            <th>Fabric Name</th>
            <th>Roll No</th>
            <th>Gross Wt</th>
            <th>Net Wt</th>
            <th>Meter</th>
            <th>Avg Wt</th>
            <th>GSM</th>
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
            $currentFabricName = null;

            $billDateTotalGrossWt = 0;
            $billDateTotalNetWt = 0;
            $billDateTotalMeter = 0;


        @endphp

        @foreach($saleEntry->getSaleList as $fabricSaleItem)

            @if($fabricSaleItem->name != $currentFabricName)


                @if($currentFabricName != null)
                    <tr>
                        <td colspan="3">Total</td>
                        <td>{{ $gross_wt }}</td>
                        <td>{{ $net_wt }}</td>
                        <td>{{ $meter }}</td>
                        <td> @if($i != 0) {{ $total_avg/$i }} @endif </td>
                        <td></td>
                    </tr>
                @endif

                <!-- Reset variables and start new fabric -->
                @php
                    $i = 0;
                    $gross_wt = 0;
                    $net_wt = 0;
                    $meter = 0;
                    $total_avg = 0;
                    $total_gram_wt = 0;
                    $currentFabricName = $fabricSaleItem->name;
                @endphp
            @endif

            <!-- Display individual fabric item -->
            <tr>
                <td> {{ ++$i }} </td>
                <td>{{ $fabricSaleItem->name }}</td>
                <td>{{ $fabricSaleItem->roll }}</td>
                <td>{{ $fabricSaleItem->gross }}</td>
                <td>{{ $fabricSaleItem->net }}</td>
                <td>{{ $fabricSaleItem->meter }}</td>
                <td>{{ $fabricSaleItem->average }}</td>
                <td>{{ $fabricSaleItem->gsm }}</td>
            </tr>

            <!-- Update totals for the current fabric -->
            @php
                $gross_wt += (float)$fabricSaleItem->gross;
                $net_wt += (float)$fabricSaleItem->net;
                $meter += (float)$fabricSaleItem->meter;
                $total_avg += (float)$fabricSaleItem->average_wt;
                $total_gram_wt += (float)$fabricSaleItem->gram_wt;
                $billDateTotalGrossWt += (float)$fabricSaleItem->gross;
                $billDateTotalNetWt += (float)$fabricSaleItem->net;
                $billDateTotalMeter += (float)$fabricSaleItem->meter;
            @endphp
        @endforeach

        <!-- Display total for the last fabric -->
        @if($currentFabricName != null)
            <tr>
                <td colspan="3">Total</td>
                <td>{{ $gross_wt }}</td>
                <td>{{ $net_wt }}</td>
                <td>{{ $meter }}</td>
                <td></td>
                <td></td>
            </tr>
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3">Grand Total For no of Rolls: {{ $i }}</td>
            <td>{{ $billDateTotalGrossWt }}</td>
            <td>{{ $billDateTotalNetWt }}</td>
            <td>{{ $billDateTotalMeter }}</td>
        </tr>
    </tfoot>
</table>
@endforeach
<br/>
