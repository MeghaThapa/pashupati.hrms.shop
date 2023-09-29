<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>SN</th>
            <th>Placement</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i = 0;
            $total = 0;
        @endphp
        @foreach ($placements as $placement)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $placement->name }}</td>
                <td>{{ $placement->total }}</td>
            </tr>
            @php
                $total += $placement->total;
            @endphp
        @endforeach

    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" style="font-weight:bold;">Total :</td>
            <td>{{ $total }}</td>
        </tr>
    </tfoot>
</table>
<br>
