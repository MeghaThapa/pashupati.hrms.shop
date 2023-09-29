{{-- <hr> --}}
<br>
<p style="text-align: center; font-weight: bold;">Date: {{ $date }}</p>

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>SN</th>
            <th>Sr.No</th>
            <th>Party Name</th>
            <th>quantity</th>
            <th>Unit</th>
            <th>Rate</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i = 1;
            $total_amount = 0;
        @endphp
        <tr>{{ $storeoutItems[0]->store_out_departments_name }}</tr>

        @foreach ($storeoutItems as $storeoutItem)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ $storeoutItem->receipt_no }}</td>
                <td>{{ $storeoutItem->item_name }}</td>
                <td>{{ $storeoutItem->quantity }}</td>
                <td>{{ $storeoutItem->units_name }}</td>
                <td>{{ $storeoutItem->rate }}</td>
                <td>{{ $storeoutItem->total }}</td>
            </tr>
            @php
                $i++;
                $total_amount += $storeoutItem->total;
            @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6" style="font-weight:bold;">Total :</td>
            <td style="font-weight:bold;">{{ $total_amount }}</td>
        </tr>
    </tfoot>
</table>
<br>
