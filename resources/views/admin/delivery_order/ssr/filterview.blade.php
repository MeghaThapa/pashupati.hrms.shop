@foreach($deliveryOrders as $doDate => $deliveryOrderGroup)
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th colspan="9">{{ $doDate }}</th>
            </tr>
            <tr>
                <th>S.N.</th>
                <th>DO No</th>
                <th>New No</th>
                <th>Party Name</th>
                <th>DO For</th>
                <th>Qty</th>
                <th>Bundel</th>
                <th>Rate</th>
                <th>Overdue</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 0; @endphp
            @foreach($deliveryOrderGroup as $dOrder)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $dOrder['do_no'] }}</td>
                    <td>{{ $dOrder['do_no'] }}</td>
                    <td>{{ $dOrder['supplier_name'] }}</td>
                    <td>{{ $dOrder['do_for'] }}</td>
                    <td>{{ $dOrder['qty_in_mt'] }}</td>
                    <td>{{ $dOrder['bundel_pcs'] }}</td>
                    <td>{{ $dOrder['base_rate_per_kg'] }}</td>
                    <td>{{ $dOrder['overdue_amount'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endforeach
