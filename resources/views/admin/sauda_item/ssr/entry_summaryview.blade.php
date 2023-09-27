<h3>Summary</h3>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>For Item Type</th>
            <th>Order Qty</th>
        </tr>
    </thead>
    <tbody>
        @php
        $total_order_qty = 0;
        @endphp
        @foreach ($updatedFormattedData as $itemId => $items)
            <tr>
                <td>
                    @foreach($items as $item)
                    {{ $item['order_for'] }}
                    @break
                    @endforeach
                </td>
                <td>
                    @php $order_qty = 0; @endphp
                    @foreach ($items as $item)
                        @php $order_qty+=$item['order_qty'] @endphp
                    @endforeach
                    {{ $order_qty }}<br>
                    @php $total_order_qty+=$order_qty; @endphp
                </td>
            </tr>
        @endforeach

        <tr>
            <td><strong>Total</strong></td>
            <td><strong>{{ $total_order_qty }}</strong></td>
        </tr>
    </tbody>
</table>
