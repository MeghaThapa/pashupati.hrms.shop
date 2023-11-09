<h3>Summary of Tape Production Report for Date: {{ $request->start_date }} To {{ $request->end_date }}</h3>
<table class="table table-bordered table-responsive">
    <thead class="text-bold">
        <tr>
            <th colspan="2" style="text-align: center;border: 2px solid black;"></th>
            <th colspan="5" style="text-align: center;border: 2px solid black;">Sunder Lamination Plant</th>
            <th colspan="5" style="text-align: center;border: 2px solid black;">JP Lamination Plant</th>
            <th colspan="5" style="text-align: center;border: 2px solid black;">BSW Lamination Plant</th>
            <th colspan="5" style="text-align: center;border: 2px solid black;">Total All </th>
        </tr>
        <tr>
            <th style="border: 1px solid black;"> Sr </th>
            <th style="min-width: 120px;border: 1px solid black;">Date</th>
            <th style="border: 1px solid black;">Unlam</th>
            <th style="border: 1px solid black;">Prod. In Kg.</th>
            <th style="border: 1px solid black;">Prod. In Mtr</th>
            <th style="border: 1px solid black;">Wast.</th>
            <th style="border: 1px solid black;">%</th>
            <th style="border: 1px solid black;">Unlam</th>
            <th style="border: 1px solid black;">Prod. In Kg.</th>
            <th style="border: 1px solid black;">Prod. In Mtr</th>
            <th style="border: 1px solid black;">Wast.</th>
            <th style="border: 1px solid black;">%</th>
            <th style="border: 1px solid black;">Unlam</th>
            <th style="border: 1px solid black;">Prod. In Kg.</th>
            <th style="border: 1px solid black;">Prod. In Mtr</th>
            <th style="border: 1px solid black;">Wast.</th>
            <th style="border: 1px solid black;">%</th>
            <th style="border: 1px solid black;">Unlam</th>
            <th style="border: 1px solid black;">Prod. In Kg.</th>
            <th style="border: 1px solid black;">Prod. In Mtr</th>
            <th style="border: 1px solid black;">Wast.</th>
            <th style="border: 1px solid black;">%</th>

        </tr>
    </thead>
    <tbody>
        @php
        $i = 0;
        $totalsundarUnlam = 0;
        $totalsundarLam = 0;
        $totalsundarProdMeter = 0;
        $totalsundarWaste = 0;
        $totalSundarPerc = 0;

        $totaljpUnlam = 0;
        $totaljpLam = 0;
        $totaljpProdMeter = 0;
        $totaljpWaste = 0;
        $totaljpPerc = 0;

        $totalbswUnlam = 0;
        $totalbswLam = 0;
        $totalbswProdMeter = 0;
        $totalbswWaste = 0;
        $totalbswPerc = 0;

        $finalTotalunlam = 0;
        $finalTotallam = 0;
        $finalTotalmeter = 0;
        $finalTotalwaste = 0;
        $finalTotalwastePerc = 0;

        @endphp

        @foreach ($plantArray as $date => $item)
            <tr>
                @php
                $subTotalsundarUnlam = 0;
                $subTotalsundarLam = 0;
                $subTotalsundarProdMeter= 0;
                $subTotalsundarWaste = 0;
                $subTotalSundarPerc = 0;

                $subTotaljpUnlam = 0;
                $subTotaljpLam = 0;
                $subTotaljpProdMeter= 0;
                $subTotaljpWaste = 0;
                $subTotaljpPerc = 0;

                $subTotalbswUnlam = 0;
                $subTotalbswLam = 0;
                $subTotalbswProdMeter= 0;
                $subTotalbswWaste = 0;
                $subTotalbswPerc = 0;

                @endphp

                <td>{{ ++$i }}</td>
                <td>{{ $item['date'] }}</td>
                <td>@if( isset($item['sundar_total_unlam']))
                    {{ $item['sundar_total_unlam'] }}
                    @php
                    $subTotalsundarUnlam += $item['sundar_total_unlam'];
                    $totalsundarUnlam += $item['sundar_total_unlam'];
                    @endphp
                    @else 0
                    @endif
                </td>
                <td>@if( isset($item['sundar_total_lam']))
                    {{ $item['sundar_total_lam'] }}
                    @php
                    $subTotalsundarLam += $item['sundar_total_lam'];
                    $totalsundarLam += $item['sundar_total_lam'];
                    @endphp
                    @else 0
                    @endif
                </td>
                <td>@if( isset($item['sundar_total_meter']))
                    {{ $item['sundar_total_meter'] }}
                    @php
                    $subTotalsundarProdMeter += $item['sundar_total_meter'];
                    $totalsundarProdMeter += $item['sundar_total_meter'];
                    @endphp
                    @else 0
                    @endif
                </td>
                <td>@if( isset($item['sundar_total_waste']))
                    {{ $item['sundar_total_waste'] }}
                    @php
                    $subTotalsundarWaste += $item['sundar_total_waste'];
                    $totalsundarWaste += $item['sundar_total_waste'];
                    @endphp
                    @else 0
                    @endif
                </td>
                <td>
                    @if( isset($item['sundar_waste_perc']))
                    {{ $item['sundar_waste_perc'] }}
                    @php
                    $percentage = $item['sundar_total_lam'] > 0 ? $item['sundar_total_waste'] / $item['sundar_total_lam'] * 100 : 0;
                    $subTotalSundarPerc  += $percentage;
                    $totalSundarPerc += $percentage;
                    @endphp
                    @else 0
                    @endif
                </td>
                {{-- JP Lamination Plant --}}
                <td>@if( isset($item['jp_total_unlam']))
                    {{ $item['jp_total_unlam'] }}
                    @php
                    $subTotaljpUnlam += $item['jp_total_unlam'];
                    $totaljpUnlam += $item['jp_total_unlam'];
                    @endphp
                    @else 0
                    @endif
                </td>
                <td>@if( isset($item['jp_total_lam']))
                    {{ $item['jp_total_lam'] }}
                    @php
                    $subTotaljpLam += $item['jp_total_lam'];
                    $totaljpLam += $item['jp_total_lam'];
                    @endphp
                    @else 0
                    @endif
                </td>
                <td>@if( isset($item['jp_total_meter']))
                    {{ $item['jp_total_meter'] }}
                    @php
                    $subTotaljpProdMeter += $item['jp_total_meter'];
                    $totaljpProdMeter += $item['jp_total_meter'];
                    @endphp
                    @else 0
                    @endif
                </td>
                <td>@if( isset($item['jp_total_waste']))
                    {{ $item['jp_total_waste'] }}
                    @php
                    $subTotaljpWaste += $item['jp_total_waste'];
                    $totaljpWaste += $item['jp_total_waste'];
                    @endphp
                    @else 0
                    @endif
                </td>
                <td>
                    @if( isset($item['jp_waste_perc']))
                    {{ $item['jp_waste_perc'] }}
                    @php
                    $percentage = $item['jp_total_lam'] > 0 ? $item['jp_total_waste'] / $item['jp_total_lam'] * 100 : 0;
                    $subTotaljpPerc += $percentage;
                    $totaljpPerc += $percentage;
                    @endphp
                    @else 0
                    @endif
                </td>
{{--
                <td>
                    @if($subTotalGodamTwoRunDown>0)
                    @php $subTotalGodamTwoPercentage = $subTotalGodamTwoWastage/ $subTotalGodamTwoRunDown * 100; @endphp
                    @else
                    @php $subTotalGodamTwoPercentage=0; @endphp
                    @endif
                    {{ $subTotalGodamTwoPercentage }}
                </td> --}}
                {{-- BSW Lamination Plant --}}
                <td>@if( isset($item['bsw_total_unlam']))
                    {{ $item['bsw_total_unlam'] }}
                    @php
                    $subTotalbswUnlam += $item['bsw_total_unlam'];
                    $totalbswUnlam += $item['bsw_total_unlam'];
                    @endphp
                    @else 0
                    @endif
                </td>
                <td>@if( isset($item['bsw_total_lam']))
                    {{ $item['bsw_total_lam'] }}
                    @php
                    $subTotalbswLam += $item['bsw_total_lam'];
                    $totalbswLam += $item['bsw_total_lam'];
                    @endphp
                    @else 0
                    @endif
                </td>
                <td>@if( isset($item['bsw_total_meter']))
                    {{ $item['bsw_total_meter'] }}
                    @php
                    $subTotalbswProdMeter += $item['bsw_total_meter'];
                    $totalbswProdMeter += $item['bsw_total_meter'];
                    @endphp
                    @else 0
                    @endif
                </td>
                <td>@if( isset($item['bsw_total_waste']))
                    {{ $item['bsw_total_waste'] }}
                    @php
                    $subTotalbswWaste += $item['bsw_total_waste'];
                    $totalbswWaste += $item['bsw_total_waste'];
                    @endphp
                    @else 0
                    @endif
                </td>
                <td>
                    @if( isset($item['bsw_waste_perc']))
                    {{ $item['bsw_waste_perc'] }}

                    @php
                    $percentage = $item['bsw_total_lam'] > 0 ? $item['bsw_total_waste'] / $item['bsw_total_lam'] * 100 : 0;
                    $subTotalbswPerc += $percentage;
                    $totalbswPerc += $percentage;
                    @endphp
                    @else 0
                    @endif
                </td>
                {{-- <td>
                    @if($subTotalGodamThreeRunDown>0)
                    @php $subTotalGodamThreePercentage = $subTotalGodamThreeWastage/ $subTotalGodamThreeRunDown * 100; @endphp
                    @endif
                    {{ $subTotalGodamThreePercentage }}
                </td> --}}
                {{-- All Total --}}
                <td>
                    @php
                    $totalunlam = $subTotalsundarUnlam + $subTotaljpUnlam + $subTotalbswUnlam;
                    $finalTotalunlam += $totalunlam;
                    @endphp
                    {{ $totalunlam }}
                </td>
                <td>
                    @php
                    $totallam = $subTotalsundarLam + $subTotaljpLam + $subTotalbswLam;
                    $finalTotallam += $totallam;
                    @endphp
                    {{ $totallam }}
                </td>
                <td>
                    @php
                    $totalmeter = $subTotalsundarProdMeter+ $subTotaljpProdMeter +$subTotalbswProdMeter;
                    $finalTotalmeter +=  $totalmeter;
                    @endphp
                    {{ $totalmeter }}
                </td>
                <td>
                    @php
                    $totalwaste = $subTotalsundarWaste+ $subTotaljpWaste +$subTotalbswWaste;
                    $finalTotalwaste +=  $totalwaste;
                    @endphp
                    {{ $totalwaste }}
                </td>
                <td>
                    @php
                    $totalwastepercent = $subTotalSundarPerc+ $subTotaljpPerc +$subTotalbswPerc;
                    $finalTotalwastePerc +=  $totalwastepercent;
                    @endphp
                    {{ $totalwastepercent }}
                </td>
            </tr>
        @endforeach
        <tfoot>
            <tr>
                <td></td>
                <td>Total</td>
                <td>{{ $totalsundarUnlam }}</td>
                <td>{{ $totalsundarLam }}</td>
                <td>{{ $totalsundarProdMeter }}</td>
                <td>{{ $totalsundarWaste }}</td>
                <td>{{ $totalSundarPerc }}</td>

                <td>{{ $totaljpUnlam }}</td>
                <td>{{ $totaljpLam }}</td>
                <td>{{ $totaljpProdMeter }}</td>
                <td>{{ $totaljpWaste }}</td>
                <td>{{ $totaljpPerc }}</td>

                <td>{{ $totalbswUnlam }}</td>
                <td>{{ $totalbswLam }}</td>
                <td>{{ $totalbswProdMeter }}</td>
                <td>{{ $totalbswWaste }}</td>
                <td>{{ $totalbswPerc }}</td>

                <td>
                    {{ $finalTotalunlam }}
                </td>
                <td>
                    {{ $finalTotallam }}
                </td>
                <td>
                    {{ $finalTotalmeter }}
                </td>
                <td>{{$finalTotalwaste}}</td>
                <td>{{$finalTotalwastePerc}}</td>
            </tr>
        </tfoot>
    </tbody>

</table>
