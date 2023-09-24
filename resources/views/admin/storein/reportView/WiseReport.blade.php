<hr>
<br>
<h1>Report Date: {{ $date }}</h1>


<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>SN</th>
            <th>Item Name</th>
            <th>category</th>
            <th>Department</th>
            <th>Size</th>
            <th>Unit</th>
            <th>quantity</th>
            <th>Price</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i = 1;
            $total_quantity = 0;
            $total_price = 0;
            $total_amount = 0;
        @endphp
        @foreach ($storeiItems as $storeiItem)
            <tr>
                <td>{{ $i }}</td>
                <td>{{ $storeiItem->item_name }}</td>
                <td>{{ $storeiItem->storein_categories_name }}</td>
                <td>{{ $storeiItem->storein_departments_name }}</td>
                <td>{{ $storeiItem->sizes_name }}</td>
                <td>{{ $storeiItem->units_name }}</td>
                <td>{{ $storeiItem->quantity }}</td>
                <td>{{ $storeiItem->price }}</td>
                <td>{{ $storeiItem->total_amount }}</td>
            </tr>
            @php
                $i++;
                $total_quantity += $storeiItem->quantity;
                $total_price += $storeiItem->price;
                $total_amount += $storeiItem->total_amount;
            @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6">Total :</td>
            <td> {{ $total_quantity }}</td>
            <td>{{ $total_price }}</td>
            <td>{{ $total_amount }}</td>
        </tr>
    </tfoot>
</table>
<br>
