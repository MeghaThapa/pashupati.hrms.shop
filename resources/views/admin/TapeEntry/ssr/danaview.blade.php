@php $i=0; @endphp
@foreach($tapeDanaItems as $item)
<tr>
    <td>{{ ++$i }}</td>
    <td>{{ $item->godam->name }}</td>
    <td>{{ $item->danaGroup->name }}</td>
    <td>{{ $item->danaName->name }}</td>
    <td>{{ $item->quantity }}</td>
    <td>
        <button class="item_recover btn btn-sm btn-danger" type="button" data-id="{{ $item->id }}" >
            <i class="fa fa-recycle"></i>
        </button>
    </td>
</tr>
@endforeach
