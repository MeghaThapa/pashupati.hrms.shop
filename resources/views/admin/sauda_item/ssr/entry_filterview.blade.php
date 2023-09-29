@foreach ($formattedData as $doDate => $saudaItemsByDate)
    <h3>{{ $doDate }}</h3>
    @foreach ($saudaItemsByDate as $itemId => $sOrder)
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>S.N.</th>
                    <th>For</th>
                    <th>Order NO</th>
                    <th>Party Name</th>
                    <th>Party Acc</th>
                    <th>Order For</th>
                    <th>Order Qty</th>
                    <th>Rate</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @php
                $i = 0;
                $order_qty = 0;
                @endphp
                @foreach ($sOrder as $item)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $item['sauda_for'] }}</td>
                        <td>{{ $item['sauda_no'] }}</td>
                        <td>{{ $item['supplier_name'] }}</td>
                        <td>{{ $item['acc_name'] }}</td>
                        <td>{{ $item['order_for'] }}</td>
                        <td>{{ $item['qty'] }}</td>
                        <td>{{ $item['rate'] }}</td>
                        <td>{{ $item['remarks'] }}</td>
                    </tr>
                    @php
                        $order_qty += $item['qty'];
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
                    <td></td>
                    <td>{{ $order_qty }}</td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    @endforeach
@endforeach
