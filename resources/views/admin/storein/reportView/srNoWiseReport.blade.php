<table class="table table-striped">
    <thead>
        <tr>
            <th colspan="3">
                Purchase Date : {{ $storein->purchase_date }}
            </th>
            <th colspan="2">
                Bill No: {{ $storein->gp_no }}
            </th>
            <th colspan="2">
                Sr No: {{ $storein->sr_no ? $storein->sr_no : 'EMPTY' }}
            </th>

            <th colspan="5" style="text-align: center !important;">
                PP : {{ $storein->pp_no ? $storein->pp_no : 'EMPTY' }}
            </th>
        </tr>
        <tr>
            <th colspan="3">
                Party Name : {{ $storein->supplier->name }}
            </th>
            <th colspan="2">
                Pan No: EMPTY
            </th>
            <th colspan="3">
                Type: {{ $storein->storeinType->name }}
            </th>

        </tr>
        <tr>
            <th>Sr No</th>
            <th>Department</th>
            <th>Item Name</th>
            <th>Size</th>
            <th>Unit</th>
            <th>Quantity</th>
            <th>Rate </th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i = 0;
        @endphp
        @foreach ($storein->storeinItems as $item)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $item->storeinDepartment->name }}</td>
                <td>{{ $item->itemsOfStorein->name }}</td>
                <td>{{ $item->size->name }}</td>
                <td>{{ $item->unit->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->price }}</td>
                <td>{{ $item->total_amount }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="7" class="text-right text-bold">Sub Total:</td>
            <td class="text-right text-bold">{{ $storein->sub_total }}</td>
        </tr>
        @foreach ($Extra_charges as $charge)
            <tr>
                <td colspan="7" class="text-right text-bold">
                    @if (strtolower($charge->charge_name) == 'vat' && $charge->charge_operator == '%')
                        {{ $charge->charge_name }} @ {{ $charge->charge_amount }}% (+)
                    @else
                        {{ $charge->charge_name }} ({{ $charge->charge_operator }})
                    @endif
                </td>
                <td class="text-right text-bold">
                    Rs.{{ $charge->charge_total }}
                </td>
            </tr>
        @endforeach
        <tr>
            <td colspan="7" class="text-right text-bold">Grand Total:</td>
            <td class="text-right text-bold">{{ $storein->grand_total }}</td>
        </tr>
    </tfoot>
</table>

