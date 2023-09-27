@foreach ($formattedData as $doDate => $saudaItemsByDate)
    <h3>{{ $doDate }}</h3>
    @foreach ($saudaItemsByDate as $itemId => $sOrder)
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>S.N.</th>
                    <th>For</th>
                    <th>Party Name</th>
                    <th>Party Acc</th>
                    <th>Order For</th>
                    <th>Order Qty</th>
                    <th>Dispatch Qty</th>
                    <th>Pending Qty</th>
                    <th>Rate</th>
                </tr>
            </thead>
            <tbody>
                @php
                $i = 0;
                $order_qty = 0;
                $dispatch_qty = 0;
                $pending_qty = 0;
                @endphp
                @foreach ($sOrder as $item)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $item['sauda_for'] }}</td>
                        <td>{{ $item['supplier_name'] }}</td>
                        <td>{{ $item['acc_name'] }}</td>
                        <td>{{ $item['order_for'] }}</td>
                        <td>{{ $item['qty'] }}</td>
                        <td>{{ $item['dispatch_qty'] }}</td>
                        <td>{{ $item['pending_qty'] }}</td>
                        <td>{{ $item['rate'] }}</td>
                    </tr>
                    @php
                        $order_qty += $item['qty'];
                        $dispatch_qty += $item['dispatch_qty'];
                        $pending_qty += $item['pending_qty'];
                    @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{ $order_qty }}</td>
                    <td>{{ $dispatch_qty }}</td>
                    <td>{{ $pending_qty }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    @endforeach
@endforeach
