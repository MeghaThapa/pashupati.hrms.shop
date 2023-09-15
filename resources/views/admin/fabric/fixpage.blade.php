

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Bill no</th>
            <th>roll no</th>
            <th>fabric name</th>
            <th>gross wt</th>
            <th>net wt</th>
            <th>meter</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($laminatedFabricsFrom as  $lamItem)
        <tr>
            <td>{{ $lamItem->id }}</td>
            <td></td>
            <td>{{ $lamItem->roll_no }}</td>
            <td>{{ $lamItem->fabric_name }}</td>
            <td>{{ $lamItem->gross_wt }}</td>
            <td>{{ $lamItem->net_wt }}</td>
            <td>{{ $lamItem->meter }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@foreach($laminatedFabricsFrom as $laminated)




@endforeach
