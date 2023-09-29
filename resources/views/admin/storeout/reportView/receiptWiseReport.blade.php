<table class="table table-striped">
    <thead>
        <tr>
            <th colspan="3">
                Purchase Date : {{ $storeout->receipt_date }}
            </th>
            <th colspan="9">
                Receipt No: {{ $storeout->receipt_no }}
            </th>

        </tr>
        <tr>
            <th colspan="3">
                Remark: {{ $storeout->remark }}
            </th>
            <th colspan="9">
            </th>


        </tr>
        <tr>
            <th>Sr No</th>
            <th>Item</th>
            <th>Size</th>
            <th>Quantity</th>
            <th>Unit</th>
            <th>Placement</th>
            <th>Through</th>
            <th>Rate</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i = 0;
        @endphp
        @foreach ($storeout->storeoutItems as $item)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $item->itemsOfStorein->name }}</td>
                <td>{{ $item->size->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->unit->name }}</td>
                <td>{{ $item->placement->name }}</td>
                <td>{{ $item->through }}</td>
                <td>{{ $item->rate }}</td>
                <td>{{ $item->total }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="7" class="text-right text-bold">Sub Total:</td>
            <td class="text-right text-bold">{{ $storeout->total_amount }}</td>
        </tr>
        <tr>
            <td colspan="7" class="text-right text-bold">Grand Total:</td>
            <td class="text-right text-bold">{{ $storeout->total_amount }}</td>
        </tr>
    </tfoot>
</table>
