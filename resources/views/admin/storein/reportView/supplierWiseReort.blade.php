<hr>
<br>
<p style="text-align: center; font-weight: bold;">Date: {{ $date }}</p>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>SN</th>
            <th>Sr.No</th>
            <th>Item Name</th>
            <th>Size</th>
            <th>Unit</th>
            <th>Price</th>
            <th>quantity</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i = 1;
            $total_quantity = 0;
            $total_amount = 0;
        @endphp
        @foreach ($storeiItems as $storeiItem)
            <tr>
                <td>{{ $i }}</td>
                <td>{{ $storeiItem->sr_no }}</td>
                <td>{{ $storeiItem->item_name }}</td>
                <td>{{ $storeiItem->sizes_name }}</td>
                <td>{{ $storeiItem->units_name }}</td>
                <td>{{ $storeiItem->price }}</td>
                <td>{{ $storeiItem->quantity }}</td>
                <td>{{ $storeiItem->total_amount }}</td>
            </tr>
            @php
                $i++;
                $total_quantity += $storeiItem->quantity;
                $total_amount += $storeiItem->total_amount;
            @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6" style="font-weight:bold;">Total :</td>
            <td style="font-weight:bold;"> {{ $total_quantity }}</td>
            <td style="font-weight:bold;">{{ $total_amount }}</td>
        </tr>
    </tfoot>
</table>
<br>
