<hr>
<br>
<h1>Report Summary: {{ $startDate }} to {{ $endDate }}</h1>

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>SN</th>
            <th>Item Name</th>
            <th>Size</th>
            <th>Unit</th>
            <th>Category</th>
            <th>total quantity</th>
            <th>grand total</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i = 1;
            $total_quantity = 0;
            $grand_total = 0;
        @endphp
        @foreach ($summaryStoreIn as $storeIn)
            <tr>
                <td>{{ $i }}</td>
                <td>{{ $storeIn->item_name }}</td>
                <td>{{ $storeIn->size_name }}</td>
                <td>{{ $storeIn->unit_name }}</td>
                <td>{{ $storeIn->storein_category_name }}</td>
                <td>{{ $storeIn->total_quantity }}</td>
                <td>{{ $storeIn->grand_total }}</td>
            </tr>
            @php
                $i++;
                
                $total_quantity += $storeIn->total_quantity;
                $grand_total += $storeIn->grand_total;
            @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5">Total :</td>
            <td>{{ $total_quantity }}</td>
            <td> {{ $grand_total }}</td>
        </tr>
    </tfoot>
</table>
<br>
