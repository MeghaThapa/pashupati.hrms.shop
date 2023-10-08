<h3>Summary of Tape Production Report for Date: {{ $request->start_date }} To {{ $request->end_date }}</h3>
<table class="table table-bordered table-responsive">
    <thead>
        <tr>
            <th></th>
            <th colspan="7" style="text-align: center;">Tape Plant PSI</th>
            <th colspan="3" style="text-align: center;">Tape Plant Erema</th>
            <th colspan="3" style="text-align: center;">BSW Plant</th>
            <th colspan="3" style="text-align: center;">Total All Tape Plant</th>
        </tr>
        <tr>
            <th style="min-width: 120px;">Date</th>
            <th>Kolsite</th>
            <th>Lohia 1</th>
            <th>Lohia 2</th>
            <th>Lohia 3</th>
            <th>Total</th>
            <th>Wastage</th>
            <th>%</th>
            <th>Lohia 4</th>
            <th>Wastage</th>
            <th>%</th>
            <th>BSW</th>
            <th>Wastage</th>
            <th>%</th>
            <th>Total</th>
            <th>Wastage</th>
            <th>%</th>
        </tr>
    </thead>
    <tbody>
        @php
        $totalKolsite = 0;
        $totalLohia1 = 0;
        $totalLohia2 = 0;
        $totalLohia3 = 0;
        $totalLohia4 = 0;
        $totalBSW = 0;
        $pSITotal = 0;
        $finalPSIWastage = 0;
        $finalPSIPercent = 0;
        $finalNewPSIWastage = 0;
        $finalNewPSIPercent = 0;
        $finalBSWWastage = 0;
        $finalBSWPercent = 0;
        $finalQuantityAllPlant = 0;
        $finalWastageAllPlant = 0;
        $finalPercentAllPlant = 0;
        @endphp

        @foreach ($plantArray as $date => $item)
            <tr>

                @php
                $totalTapePlantPSI = 0;
                $totalTapePlantErema = 0;
                $totalBSWPlant = 0;
                @endphp

                <td>{{ $item['date'] }}</td>
                <td>@if( isset($item['kolsite']))
                    {{ $item['kolsite'] }}
                    @php
                    $totalTapePlantPSI += $item['kolsite'];
                    $totalKolsite += $item['kolsite'];
                    @endphp
                    @else 0 @endif</td>
                <td>@if( isset($item['lohia-i']) )
                    {{ $item['lohia-i'] }}
                    @php
                    $totalTapePlantPSI += $item['lohia-i'];
                    $totalLohia1 += $item['lohia-i'];
                    @endphp
                    @else 0
                    @endif
                </td>
                <td>@if( isset($item['lohia-ii']))
                    {{ $item['lohia-ii'] }}
                    @php
                    $totalTapePlantPSI += $item['lohia-ii'];
                    $totalLohia2 += $item['lohia-ii'];
                    @endphp
                    @else 0
                    @endif
                </td>
                <td>@if( isset($item['lohia-iii']))
                    {{ $item['lohia-iii'] }}
                    @php $totalTapePlantPSI += $item['lohia-iii']; @endphp
                    @else 0
                    @endif
                </td>
                <td>
                    {{ $totalTapePlantPSI }}
                    @php $pSITotal += $totalTapePlantPSI;
                    $finalPSIWastage += $totalTapePlantPSI;
                    @endphp
                </td>
                <td>
                    @if($item['toGodam_id']==1)
                    @php
                    $totalPSIWastage =  $item['total_loading'] + $item['total_loading'] + $item['total_bypass_wastage'];
                    @endphp
                    {{ $totalPSIWastage }}
                    @else
                    @php $totalPSIWastage = 0; @endphp
                    0
                    @endif
                </td>
                <td>
                    @if($totalTapePlantPSI>0)

                    @php
                    $psiPercentage = number_format($totalPSIWastage/$totalTapePlantPSI * 100,2);
                    $finalPSIPercent += $psiPercentage;
                    @endphp
                    {{ $psiPercentage }}
                    @else
                    @php $psiPercentage = 0; @endphp
                    0
                    @endif
                </td>
                <td>
                    @if( isset($item['lohia-iv']))
                    {{ $item['lohia-iv'] }}
                    @php
                    $totalTapePlantErema += $item['lohia-iv'];
                    $totalLohia4 += $item['lohia-iv'];
                    @endphp
                    @else 0
                    @endif
                </td>
                <td>
                    @if($item['toGodam_id']==2)
                    @php
                    $totalNewPSIWastage =  $item['total_loading'] + $item['total_loading'] + $item['total_bypass_wastage'];
                    $finalNewPSIWastage += $totalNewPSIWastage;
                    @endphp
                    {{ $totalNewPSIWastage }}
                    @else
                    @php $totalNewPSIWastage=0; @endphp
                    0
                    @endif
                </td>
                <td>
                    @if($totalTapePlantErema>0)
                    @php $totalEremaPercentage = number_format($totalNewPSIWastage/$totalTapePlantErema * 100 ,2);
                    $finalNewPSIPercent += $totalEremaPercentage;
                    @endphp
                    {{ $totalEremaPercentage }}
                    @else
                    @php $totalEremaPercentage = 0; @endphp
                    0
                    @endif
                </td>
                <td>
                    @if(isset($item['bsw tiratex']))

                    @php
                    $totalBSWPlant += $item['bsw tiratex'];
                    $totalBSW += $item['bsw tiratex'];
                    @endphp

                    {{ $item['bsw tiratex'] }}

                    @else
                    0
                    @endif
                </td>

                <td>
                    @if($item['toGodam_id']==3)
                    @php
                    $totalBSWWastage =  $item['total_loading'] + $item['total_loading'] + $item['total_bypass_wastage'];
                    $finalBSWWastage += $totalBSWWastage;
                    @endphp
                    {{ $totalBSWWastage }}
                    @else
                    @php $totalBSWWastage=0; @endphp
                    0
                    @endif
                </td>
                <td>
                    @if($totalBSWPlant>0)
                    @php
                    $totalBSWPercentage = number_format($totalBSWWastage/$totalBSWPlant * 100 ,2);
                    $finalBSWPercent += $totalBSWPercentage;
                    @endphp
                    {{ $totalBSWPercentage }}
                    @else
                    @php $totalBSWPercentage = 0; @endphp
                    0
                    @endif
                </td>
                <td>
                    @php
                    $totalQtyAllPlant =  $totalTapePlantPSI+$totalTapePlantErema+$totalBSWPlant;
                    $finalQuantityAllPlant += $totalQtyAllPlant;
                    @endphp
                    {{ $totalQtyAllPlant }}
                </td>
                <td>
                    @php
                    $totalWastageAllPlant = $totalPSIWastage + $totalNewPSIWastage + $totalBSWWastage;
                    $finalWastageAllPlant += $totalWastageAllPlant;
                    @endphp
                    {{ $totalWastageAllPlant }}
                </td>
                <td>
                    @php
                    $totalPercentage = number_format($totalWastageAllPlant/$totalQtyAllPlant * 100 ,2);
                    $finalPercentAllPlant += $totalPercentage;
                    @endphp
                    {{ $totalPercentage }}
                </td>
            </tr>
        @endforeach
        <tfoot>
            <tr>
                <td>Total</td>
                <td>{{ $totalKolsite }}</td>
                <td>{{ $totalLohia1 }}</td>
                <td>{{ $totalLohia2 }}</td>
                <td>{{ $totalLohia3 }}</td>
                <td>{{ $pSITotal }}</td>
                <td>{{ $finalPSIWastage }}</td>
                <td>{{ $finalPSIPercent }}</td>
                <td>{{ $totalLohia4 }}</td>
                <td>{{ $finalNewPSIWastage }}</td>
                <td>{{ $finalNewPSIPercent }}</td>
                <td>{{ $totalBSW }}</td>
                <td>{{ $finalBSWWastage }}</td>
                <td>{{ $finalBSWPercent }}</td>
                <td>{{ $finalQuantityAllPlant }}</td>
                <td>{{ $finalWastageAllPlant }}</td>
                <td>{{ $finalPercentAllPlant }}</td>
            </tr>
        </tfoot>
    </tbody>

</table>
