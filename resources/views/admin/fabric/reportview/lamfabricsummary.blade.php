<hr>
<br>
<h1>Report Summary: {{ $nepaliStartDate }} to {{ $nepaliEndDate }}</h1>

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
            $total_roll_count = 0; 
            $total_gross_wt = 0; 
            $total_net_wt = 0; 
            $total_meter = 0; 
        @endphp
        @foreach($summaryFabrics as $fabric)
        <tr>
            <td>{{ $i }}</td>
            <td>{{ $fabric->fabric_name }}</td>
            <td>{{ $fabric->roll_count }}</td>
            <td>{{ $fabric->gross_wt }}</td>
            <td>{{ $fabric->net_wt }}</td>
            <td>{{ $fabric->meter }}</td>
        </tr>
        @php 
        $i++;
        $total_roll_count = $total_roll_count + $fabric->roll_count; 
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
            <td>Total : {{ $total_roll_count }}</td>
            <td>Total : {{ $total_gross_wt }}</td>
            <td>Total : {{ $total_net_wt }}</td>
            <td>Total : {{ $total_meter }}</td>
        </tr>
    </tfoot>
</table>
<br>