@foreach ($formattedData as $doDate => $saudaItemsByDate)
    <h3>{{ $doDate }}</h3>
    @foreach ($saudaItemsByDate as $itemId => $sOrder)
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>S.N.</th>
                    <th>For</th>
                    <th>Receipt</th>
                    <th>Party Name</th>
                    <th>Party Acc</th>
                    <th>Dispatch For</th>
                    <th>Dispatch Qty</th>
                    <th>Rate</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @php
                $i = 0;
                $dispatch_qty = 0;
                @endphp
                @foreach ($sOrder as $item)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $item['for'] }}</td>
                        <td>{{ $item['receipt_no'] }}</td>
                        <td>{{ $item['supplier_name'] }}</td>
                        <td>{{ $item['party_acc'] }}</td>
                        <td>{{ $item['dispatch_for'] }}</td>
                        <td>{{ $item['dispatch_qty'] }}</td>
                        <td>{{ $item['rate'] }}</td>
                        <td>{{ $item['remarks'] }}</td>
                    </tr>
                    @php

                        $dispatch_qty += $item['dispatch_qty'];
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
                    <td>Total</td>
                    <td>{{ $dispatch_qty }}</td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    @endforeach
@endforeach
