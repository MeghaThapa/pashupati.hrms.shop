<h3>Summary</h3>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>For Item Type</th>
            <th>Dispatch Qty</th>
        </tr>
    </thead>
    <tbody>
        @php $totalQty = 0; @endphp
        @foreach ($updatedFormattedData as $dispatchFor => $item)
            <tr>
                <td>{{ $item['dispatch_for'] }}</td>
                <td>{{ $item['total_dispatch_qty'] }}</td>
            </tr>
            @php $totalQty+=$item['total_dispatch_qty']; @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td>Total</td>
            <td>{{ $totalQty }}</td>
        </tr>
    </tfoot>
</table>
