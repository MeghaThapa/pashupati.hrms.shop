@foreach ($storeouts as $storeout)
    <table class="table table-striped">
        <thead>

            <tr>
                <th colspan="2">
                    Receipt No: {{ $storeout->receipt_no }}
                </th>
                <th colspan="3">
                    Godam: {{ $storeout->godam->name }}
                </th>
                <th colspan="4">
                    Bill Date : {{ $storeout->receipt_date }}
                </th>
            </tr>
            <tr>
                <th>S.N.</th>
                <th>Particulars</th>
                <th>Size</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Placement</th>
                <th>Through</th>
                <th>Rate</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 0;
                $grand_total = 0;
                $currentDepartmentName = null;
            @endphp

            @foreach ($storeout->storeoutItems as $storeoutItem)
                @if ($storeoutItem->department->name != $currentDepartmentName)
                    @if ($currentDepartmentName != null)
                        <tr>
                            <td colspan="8">Pre Total</td>
                            <td>{{ $grand_total }}</td>
                        </tr>
                    @endif
                    @php
                        $i = 0;
                        $grand_total = 0;
                        $currentDepartmentName = $storeoutItem->department->name;
                    @endphp
                @endif

                <tr>
                    <td> {{ ++$i }} </td>
                    <td>{{ $storeoutItem->itemsOfStorein->name }}</td>
                    <td>{{ $storeoutItem->size->name }}</td>
                    <td>{{ $storeoutItem->quantity }}</td>
                    <td>{{ $storeoutItem->unit->name }}</td>
                    <td>{{ $storeoutItem->placement->name }}</td>
                    <td>{{ $storeoutItem->through }}</td>
                    <td>{{ $storeoutItem->rate }}</td>
                    <td>{{ $storeoutItem->total }}</td>
                </tr>
                @php
                    $grand_total += $storeoutItem->total;
                @endphp
            @endforeach

            <!-- Display total for the last fabric -->
            @if ($currentDepartmentName != null)
                <tr>
                    <td colspan="8"> Sub Total</td>
                    <td>{{ $grand_total }}</td>
                </tr>
            @endif


        </tbody>
        <tfoot>
            <tr>
                <td colspan="8"> Grand Total</td>
                <td>{{ $grand_total }}</td>
            </tr>
        </tfoot>

    </table>
@endforeach

<br /><br />
