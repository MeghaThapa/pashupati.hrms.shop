@foreach($nonWovenSales as $saleEntry)
<h4 style="margin-top:100px;">{{ $saleEntry->bill_for }} Sale Non Woven Details</h4>
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
            <th>Color</th>
            <th>GSM</th>
            <th>Length</th>
            <th>Gross Wt</th>
            <th>Net Wt</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i = 0;
            $length = 0;
            $gross_wt = 0;
            $net_wt = 0;
            $meter = 0;
            $total_avg = 0;
            $total_gram_wt = 0;
            $currentFabricName = null;

            $billDateTotalGrossWt = 0;
            $billDateTotalNetWt = 0;
            $billDateTotalLength = 0;


        @endphp

        @foreach($saleEntry->nonwovenSaleEntry as $fabricSaleItem)

            @if($fabricSaleItem->name != $currentFabricName)


                @if($currentFabricName != null)
                    <tr>
                        <td></td>
                        <td colspan="4">Total</td>
                        <td>{{ $length }}</td>
                        <td>{{ $gross_wt }}</td>
                        <td>{{ $net_wt }}</td>
                    </tr>
                @endif

                <!-- Reset variables and start new fabric -->
                @php
                    $i = 0;
                    $length = 0;
                    $gross_wt = 0;
                    $net_wt = 0;
                    $currentFabricName = $fabricSaleItem->name;
                @endphp
            @endif

            <!-- Display individual fabric item -->
            <tr>
                <td> {{ ++$i }} </td>
                <td>{{ $fabricSaleItem->fabric_name }}</td>
                <td>{{ $fabricSaleItem->fabric_roll }}</td>
                <td>{{ $fabricSaleItem->fabric_color }}</td>
                <td>{{ $fabricSaleItem->fabric_gsm }}</td>
                <td>{{ $fabricSaleItem->length }}</td>
                <td>{{ $fabricSaleItem->gross_weight }}</td>
                <td>{{ $fabricSaleItem->net_weight }}</td>
            </tr>

            <!-- Update totals for the current fabric -->
            @php
                $gross_wt += (float)$fabricSaleItem->gross_weight;
                $net_wt += (float)$fabricSaleItem->net_weight;
                $length += (float)$fabricSaleItem->length;
                $billDateTotalLength += (float)$fabricSaleItem->length;
                $billDateTotalGrossWt += (float)$fabricSaleItem->gross_weight;
                $billDateTotalNetWt += (float)$fabricSaleItem->net_weight;
            @endphp
        @endforeach

        <!-- Display total for the last fabric -->
        @if($currentFabricName != null)
            <tr>
                <td colspan="3">Total</td>
                <td>{{ $length }}</td>
                <td>{{ $gross_wt }}</td>
                <td>{{ $net_wt }}</td>
                <td> @if($meter != 0) {{ ($net_wt/$meter) * 1000  }} @endif </td>
                <td></td>
            </tr>
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5">Grand Total For no of Rolls: {{ $i }}</td>
            <td>{{ $length }}</td>
            <td>{{ $billDateTotalGrossWt }}</td>
            <td>{{ $billDateTotalNetWt }}</td>
        </tr>
    </tfoot>
</table>
@endforeach

<br/>
