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
            <td>{{ $data->name }}</td>
            <td>{{ $data->roll_no }}</td>
            <td>{{ $data->gross_wt }}</td>
            <td>{{ $data->net_wt }}</td>
            <td>{{ $data->meter }}</td> 
        </tr>
        @php 
        $i++;
        $total_gross_wt = $total_gross_wt + $data->gross_wt; 
        $total_net_wt = $total_net_wt + $data->net_wt; 
        $total_meter = $total_meter + $data->meter; 
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