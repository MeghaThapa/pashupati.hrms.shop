<hr>
<br>
<h1>Report Date: {{ $nepaliDate }}</h1>

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>SN</th>
            <th>Item Name</th>
            <th>No of Rolls</th>
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
        @foreach($fabrics as $fabric)
        <tr>
            <td>{{ $i }}</td>
            <td>{{ $fabric->name }}</td>
            <td>{{ $fabric->roll_no }}</td>
            <td>{{ $fabric->gross_wt }}</td>
            <td>{{ $fabric->net_wt }}</td>
            <td>{{ $fabric->meter }}</td>
        </tr>
        @php 
        $i++;
        $total_gross_wt = $total_gross_wt + $fabric->gross_wt; 
        $total_net_wt = $total_net_wt + $fabric->net_wt; 
        $total_meter = $total_meter + $fabric->meter; 
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
        </tr>
    </tfoot>
</table>
<br>