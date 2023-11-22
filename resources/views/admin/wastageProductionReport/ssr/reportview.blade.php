<h3>Summary of Tape Production Report for Date: {{ $request->start_date }} To {{ $request->end_date }}</h3>
<table class="table table-bordered table-responsive">
    <thead class="text-bold">
        <tr>
            <th style="text-align: center;border: 2px solid black;"></th>
            <th colspan="7" style="text-align: center;border: 2px solid black;">Tape Plant Wastage</th>
            <th colspan="3" style="text-align: center;border: 2px solid black;">Loom Wastage</th>
            <th colspan="3" style="text-align: center;border: 2px solid black;">Lamination Plant</th>
            <th colspan="3" style="text-align: center;border: 2px solid black;">Printing and Finishing</th>
        </tr>
        <tr>
            <th style="min-width: 120px;border: 1px solid black;">Date</th>
            <th style="border: 1px solid black;">Kolsite</th>
            <th style="border: 1px solid black;">Lohia-i</th>
            <th style="border: 1px solid black;">Lohia-ii</th>
            <th style="border: 1px solid black;">Lohia-iii </th>
            <th style="border: 1px solid black;">Lohia-iv</th>

            <th style="border: 1px solid black;">%</th>
            <th style="border: 1px solid black;">%</th>
            <th style="border: 1px solid black;">PSI</th>
            <th style="border: 1px solid black;">New Psi</th>
            <th style="border: 1px solid black;">BSW</th>
            <th style="border: 1px solid black;">Total</th>
            <th style="border: 1px solid black;">Jp lam</th>
            <th style="border: 1px solid black;">Sundaar Lam</th>
            <th style="border: 1px solid black;">Jp Tripal</th>
            <th style="border: 1px solid black;">Wastage</th>
            <th style="border: 1px solid black;">%</th>
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

                <td>{{ $item['bill_date'] }}</td>
                <td>
                    {{-- {{ $item['total_loomwastage'] }} --}}
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    {{ $item['psigodam'] }}
                </td>
                <td>
                    {{ $item['newpsigodam'] }}
                </td>
                <td>
                    {{ $item['bswgodam'] }}
                </td>
                <td>
                    {{-- {{ $item->getSingleSideData->sum('meter') }} --}}
                </td>
                {{-- <td>@if( isset($item['lohia-iii']))
                    {{ $item['lohia-iii'] }}
                    @php $totalTapePlantPSI += $item['lohia-iii']; @endphp
                    @else 0
                    @endif
                </td> --}}
                {{-- <td>
                    {{ $totalTapePlantPSI }}
                    @php $pSITotal += $totalTapePlantPSI;
                    $finalPSIWastage += $totalTapePlantPSI;
                    @endphp
                </td> --}}
                {{-- <td>
                    @if(isset($item['psi']))
                    @php
                    $totalPSIWastage =  $item['godam_one_total_loading'] + $item['godam_one_total_running'] + $item['godam_one_total_bypass_wastage'];
                    @endphp
                    {{ $totalPSIWastage }}
                    @else
                    @php $totalPSIWastage = 0; @endphp
                    0
                    @endif
                </td> --}}
                {{-- <td>
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
                </td> --}}
                {{-- <td>
                    @if( isset($item['lohia-iv']))
                    {{ $item['lohia-iv'] }}
                    @php
                    $totalTapePlantErema += $item['lohia-iv'];
                    $totalLohia4 += $item['lohia-iv'];
                    @endphp
                    @else 0
                    @endif
                </td> --}}
                {{-- <td>
                    @if( isset($item['newpsi']))
                    @php
                    $totalNewPSIWastage =  $item['godam_two_total_loading'] + $item['godam_two_total_running'] + $item['godam_two_total_bypass_wastage'];
                    $finalNewPSIWastage += $totalNewPSIWastage;
                    @endphp
                    {{ $totalNewPSIWastage }}
                    @else
                    @php $totalNewPSIWastage=0; @endphp
                    0
                    @endif
                </td> --}}
                {{-- <td>
                    @if($totalTapePlantErema>0)
                    @php $totalEremaPercentage = number_format($totalNewPSIWastage/$totalTapePlantErema * 100 ,2);
                    $finalNewPSIPercent += $totalEremaPercentage;
                    @endphp
                    {{ $totalEremaPercentage }}
                    @else
                    @php $totalEremaPercentage = 0; @endphp
                    0
                    @endif
                </td> --}}
                {{-- <td>
                    @if(isset($item['bsw tiratex']))

                    @php
                    $totalBSWPlant += $item['bsw tiratex'];
                    $totalBSW += $item['bsw tiratex'];
                    @endphp

                    {{ $item['bsw tiratex'] }}

                    @else
                    0
                    @endif
                </td> --}}

                {{-- <td>
                    @if(isset($item['bsw']))
                    @php
                    $totalBSWWastage =  $item['godam_three_total_loading'] + $item['godam_three_total_running'] + $item['godam_three_total_bypass_wastage'];
                    $finalBSWWastage += $totalBSWWastage;
                    @endphp
                    {{ $totalBSWWastage }}
                    @else
                    @php $totalBSWWastage=0; @endphp
                    0
                    @endif
                </td> --}}
                {{-- <td>
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
                </td> --}}
                {{-- <td>
                    @php
                    $totalQtyAllPlant =  $totalTapePlantPSI+$totalTapePlantErema+$totalBSWPlant;
                    $finalQuantityAllPlant += $totalQtyAllPlant;
                    @endphp
                    {{ $totalQtyAllPlant }}
                </td> --}}
                {{-- <td>
                    @php
                    $totalWastageAllPlant = $totalPSIWastage + $totalNewPSIWastage + $totalBSWWastage;
                    $finalWastageAllPlant += $totalWastageAllPlant;
                    @endphp
                    {{ $totalWastageAllPlant }}
                </td> --}}
                {{-- <td>
                    @php
                    $totalPercentage = number_format($totalWastageAllPlant/$totalQtyAllPlant * 100 ,2);
                    $finalPercentAllPlant += $totalPercentage;
                    @endphp
                    {{ $totalPercentage }}
                </td> --}}
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
