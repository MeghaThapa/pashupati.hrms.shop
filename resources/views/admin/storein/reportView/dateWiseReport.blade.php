<hr>
<br>
<h1>Report Date: {{ $nepaliDate }}</h1>

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>SN</th>
            <th>Item Name</th>
            <th>category</th>
            {{-- <th>Gross Wt</th>
            <th>Net Wt</th>
            <th>Meter</th>
            <th>From Godam</th> --}}
        </tr>
    </thead>
    <tbody>
        @php
            $i = 1;
            $total_gross_wt = 0;
            $total_net_wt = 0;
            $total_meter = 0;
        @endphp
        @foreach ($fabrics as $fabric)
            <tr>
                <td>{{ $i }}</td>
                <td>{{ $fabric->item_name }}</td>
                <td>{{ $fabric->storein_categories_name }}</td>

            </tr>

        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            {{-- <td>Total : {{ $total_gross_wt }}</td>
            <td>Total : {{ $total_net_wt }}</td>
            <td>Total : {{ $total_meter }}</td> --}}
        </tr>
    </tfoot>
</table>
<br>
