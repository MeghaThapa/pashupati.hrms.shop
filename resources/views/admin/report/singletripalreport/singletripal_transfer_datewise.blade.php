<hr>
<br>
<h1>Report Date: {{ $nepaliDate }}</h1>

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>SN</th>
            <th>Item Name</th>
            <th>Roll</th>
            <th>Gross Wt</th>
            <th>Net Wt</th>
            <th>Meter</th>
        </tr>
    </thead>
    <tbody>
        @php  
            $i=1;
            $total_gross_wt = 0; 
            $total_net_wt = 0; 
            $total_meter = 0; 
        @endphp
        @foreach($fabricGodams as $data)
        <tr>
            <td>{{ $i }}</td>
            <td>{{ $data->fabric->name }}</td>
            <td>{{ $data->fabric->roll_no }}</td>
            <td>{{ $data->fabric->gross_wt }}</td>
            <td>{{ $data->fabric->net_wt }}</td>
            <td>{{ $data->fabric->meter }}</td> 
        </tr>
        @php 
        $i++;
        $total_gross_wt = $total_gross_wt + $data->fabric->gross_wt; 
        $total_net_wt = $total_net_wt + $data->fabric->net_wt; 
        $total_meter = $total_meter + $data->fabric->meter; 
        @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Total : {{ $total_gross_wt }}</td>
            <td>Total : {{ $total_net_wt }}</td>
            <td>Total : {{ $total_meter }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>
<br>