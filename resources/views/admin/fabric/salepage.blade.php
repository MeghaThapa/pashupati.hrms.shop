

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
        @foreach ($soldFabrics as  $lamItem)
        <tr>
            <td>{{ $lamItem->id }}</td>
            <td>{{ $lamItem->getsaleentry->bill_no }}</td>
            <td>{{ $lamItem->getfabric->roll_no }}</td>
            <td>{{ $lamItem->getfabric->name }}</td>
            <td>{{ $lamItem->getfabric->gross_wt }}</td>
            <td>{{ $lamItem->getfabric->net_wt }}</td>
            <td>{{ $lamItem->getfabric->meter }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
