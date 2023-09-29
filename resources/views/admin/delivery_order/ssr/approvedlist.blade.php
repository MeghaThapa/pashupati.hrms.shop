<table>
    <thead>
        <tr>
            <th>S.N</th>
            <th>DO. Number</th>
            <th>DO. Date</th>
            <th>Supplier</th>
            <th>Overdue Amount</th>
            <th>Total Due</th>
            <th>Party Limit</th>
            <th>For Item</th>
            <th>Qty in MT</th>
            <th>Bundel PCs</th>
            <th>Base Rate/KG </th>
            <th>Collection</th>
            <th>Pending Sauda</th>
            <th>Copy DO Number</th>
        </tr>
    </thead>
    <tbody>
        @php $i=0; @endphp
        @foreach($deliveryOrders as $deliveryOrder)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $deliveryOrder->do_no }}</td>
            <td>{{ $deliveryOrder->do_date }}</td>
            <td>{{ $deliveryOrder->supplier->name }}</td>
            <td>{{ $deliveryOrder->overdue_amount }}</td>
            <td>{{ $deliveryOrder->total_due }}</td>
            <td>{{ $deliveryOrder->party_limit }}</td>
            <td>{{ $deliveryOrder->deliveryOrderForItem->name }}</td>
            <td>{{ $deliveryOrder->qty_in_mt }}</td>
            <td>{{ $deliveryOrder->bundel_pcs }}</td>
            <td>{{ $deliveryOrder->base_rate_per_kg }}</td>
            <td>{{ $deliveryOrder->collection }}</td>
            <td>{{ $deliveryOrder->pending_sauda }}</td>
            <td>
                <button type="button" class="btn btn-primary copy_do_no" data-do_no="{{ $deliveryOrder->do_no }}">Copy</button>
            </td>

        </tr>
        @endforeach
    </tbody>
</table>
