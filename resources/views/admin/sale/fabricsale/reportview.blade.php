
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
            <th>S.N.</th>
            <th>Particulars</th>
            <th>No of Rolls</th>
            <th>Roll No</th>
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
            $currentFabricName = null;

            $billDateTotalGrossWt = 0;
            $billDateTotalNetWt = 0;
            $billDateTotalMeter = 0;


            @foreach ($saleEntry->fabricSaleItems as $fabricSaleItem)
                @if ($fabricSaleItem->fabric->name != $currentFabricName)
                    <!-- Display total for the previous fabric -->
                    @if ($currentFabricName != null)
                        <tr>
                            <td colspan="3">Total</td>
                            <td>{{ $gross_wt }}</td>
                            <td>{{ $net_wt }}</td>
                            <td>{{ $meter }}</td>
                            <td>
                                @if ($i != 0)
                                    {{ $total_avg / $i }}
                                @endif
                            </td>
                            <td>
                                @if ($i != 0)
                                    {{ $total_gram_wt / $i }}
                                @endif
                            </td>
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
                        $currentFabricName = $fabricSaleItem->fabric->name;
                    @endphp
                @endif

                <!-- Display individual fabric item -->
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

                <!-- Update totals for the current fabric -->
                @php
                    $gross_wt += (float) $fabricSaleItem->fabric->gross_wt;
                    $net_wt += (float) $fabricSaleItem->fabric->net_wt;
                    $meter += (float) $fabricSaleItem->fabric->meter;
                    $total_avg += (float) $fabricSaleItem->fabric->average_wt;
                    $total_gram_wt += (float) $fabricSaleItem->fabric->gram_wt;
                    $billDateTotalGrossWt += (float) $fabricSaleItem->fabric->gross_wt;
                    $billDateTotalNetWt += (float) $fabricSaleItem->fabric->net_wt;
                    $billDateTotalMeter += (float) $fabricSaleItem->fabric->meter;
                @endphp

            @endforeach

            <!-- Display individual fabric item -->
            <tr>
                <td>{{ $fabricSaleItem->fabric->name }}</td>
                <td> {{ ++$i }} </td>
                <td>{{ $fabricSaleItem->fabric->roll_no }}</td>
                <td>{{ $fabricSaleItem->fabric->gross_wt }}</td>
                <td>{{ $fabricSaleItem->fabric->net_wt }}</td>
                <td>{{ $fabricSaleItem->fabric->meter }}</td>
                <td>{{ $fabricSaleItem->fabric->average_wt }}</td>
                <td>{{ $fabricSaleItem->fabric->gram_wt }}</td>
            </tr>


            <!-- Display total for the last fabric -->
            @if ($currentFabricName != null)
                <tr>
                    <td colspan="3">Total</td>
                    <td>{{ $gross_wt }}</td>
                    <td>{{ $net_wt }}</td>
                    <td>{{ $meter }}</td>
                    <td>
                        @if ($i != 0)
                            {{ $total_avg / $i }}
                        @endif
                    </td>
                    <td>
                        @if ($i != 0)
                            {{ $total_gram_wt / $i }}
                        @endif
                    </td>
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Total</td>
                <td>{{ $gross_wt }}</td>
                <td>{{ $net_wt }}</td>
                <td>{{ $meter }}</td>
                <td> @if($meter != 0) {{ ($net_wt/$meter) * 1000  }} @endif </td>
                <td> @if($i != 0) {{ $total_gram_wt/$i }} @endif </td>
            </tr>
        </tfoot>
    </table>
@endforeach

<br /><br />

