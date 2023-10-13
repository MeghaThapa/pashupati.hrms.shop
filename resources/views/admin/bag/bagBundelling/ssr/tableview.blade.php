@php
    $i = 0;
    $totalKg = 0;
    $toalPcs = 0;
@endphp
@foreach ($bagBundelItems as $item)
    <tr id="editRow-1">
        <td>{{ ++$i }}</td>
        <td class="rowGroupName">{{ $item->group->name }}</td>
        <td class="rowBrandBagName">{{ $item->bagBrand->name }}</td>
        <td class="rowQuantity_piece">{{ $item->qty_in_kg }}</td>
        <td class="rowAverage">{{ $item->qty_pcs }}</td>
        <td class="rowWastage">{{ $item->average_weight }}</td>
        <td class="rowRollno">{{ $item->bundel_no }}</td>
        <td>
            <button class="btn btn-danger dltBagBundelItem" data-url="{{ route('bagBundelItem.deleteBagBundelItem',$item->id) }}" data-id="{{ $item->id }}">
                <i class="fas fa-trash-alt"></i>
            </button>
        </td>
    </tr>
    @php
        $totalKg += $item->qty_in_kg;
        $toalPcs += $item->qty_pcs;
    @endphp
@endforeach
<tr style="font-weight: 600;">
    <td></td>
    <td></td>
    <td></td>
    <td>{{ $totalKg }}</td>
    <td>{{ $toalPcs }}</td>
    <td></td>
    <td></td>
    <td></td>
</tr>
