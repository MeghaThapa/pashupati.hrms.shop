<h3>Summary of Tape Production Report for Date: {{ $request->start_date }} To {{ $request->end_date }}</h3>
<table class="table table-bordered table-responsive">
    <thead class="text-bold">
        <tr>
            <th colspan="2" style="text-align: center;border: 2px solid black;"></th>
            <th colspan="6" style="text-align: center;border: 2px solid black;">Old PSI</th>
            <th colspan="6" style="text-align: center;border: 2px solid black;">New PSI</th>
            <th colspan="6" style="text-align: center;border: 2px solid black;">BSW</th>
            <th colspan="3" style="text-align: center;border: 2px solid black;">Total All </th>
        </tr>
        <tr>
            <th style="border: 1px solid black;"> Sr </th>
            <th style="min-width: 120px;border: 1px solid black;">Date</th>
            <th style="border: 1px solid black;">Run Loom</th>
            <th style="border: 1px solid black;">Roll Down</th>
            <th style="border: 1px solid black;">MTR</th>
            <th style="border: 1px solid black;">Avg Mtr</th>
            <th style="border: 1px solid black;">Wast.</th>
            <th style="border: 1px solid black;">%</th>
            <th style="border: 1px solid black;">Run Loom</th>
            <th style="border: 1px solid black;">Roll Down</th>
            <th style="border: 1px solid black;">MTR</th>
            <th style="border: 1px solid black;">Avg Mtr</th>
            <th style="border: 1px solid black;">Wast.</th>
            <th style="border: 1px solid black;">%</th>
            <th style="border: 1px solid black;">Run Loom</th>
            <th style="border: 1px solid black;">Roll Down</th>
            <th style="border: 1px solid black;">MTR</th>
            <th style="border: 1px solid black;">Avg Mtr</th>
            <th style="border: 1px solid black;">Wast.</th>
            <th style="border: 1px solid black;">%</th>
            <th style="border: 1px solid black;">Roll Down</th>
            <th style="border: 1px solid black;">Wast.</th>
            <th style="border: 1px solid black;">%</th>

        </tr>
    </thead>
    <tbody>
        @php
        $i = 0;
        $totalGodamOneRunLoom = 0;
        $totalGodamOneRunDown = 0;
        $totalGodamOneMeter = 0;
        $totalGodamOneAverageMeter = 0;
        $totalGodamOneWastage = 0;
        $totalGodamOnePercentage = 0;

        $totalGodamTwoRunLoom = 0;
        $totalGodamTwoRunDown = 0;
        $totalGodamTwoMeter = 0;
        $totalGodamTwoAverageMeter = 0;
        $totalGodamTwoWastage = 0;
        $totalGodamTwoPercentage = 0;

        $totalGodamThreeRunLoom = 0;
        $totalGodamThreeRunDown = 0;
        $totalGodamThreeMeter = 0;
        $totalGodamThreeAverageMeter = 0;
        $totalGodamThreeWastage = 0;
        $totalGodamThreePercentage = 0;

        $finalTotalRunDown = 0;
        $finalTotalWastage = 0;
        $finalTotalPercentage = 0;

        @endphp

        @foreach ($plantArray as $date => $item)
            <tr>
                @php
                $subTotalGodamOneRunLoom = 0;
                $subTotalGodamOneRunDown = 0;
                $subTotalGodamOneMeter   = 0;
                $subTotalGodamOneWastage = 0;
                $subTotalGodamOnePercentage = 0;

                $subTotalGodamTwoRunLoom = 0;
                $subTotalGodamTwoRunDown = 0;
                $subTotalGodamTwoMeter   = 0;
                $subTotalGodamTwoWastage = 0;
                $subTotalGodamTwoPercentage = 0;

                $subTotalGodamThreeRunLoom = 0;
                $subTotalGodamThreeRunDown = 0;
                $subTotalGodamThreeMeter   = 0;
                $subTotalGodamThreeWastage = 0;
                $subTotalGodamThreePercentage = 0;
                @endphp

                <td>{{ ++$i }}</td>
                <td>{{ $item['date'] }}</td>
                <td>@if( isset($item['godam_one_run_loom']))
                    {{ $item['godam_one_run_loom'] }}
                    @php
                    $subTotalGodamOneRunLoom += $item['godam_one_run_loom'];
                    $totalGodamOneRunLoom += $item['godam_one_run_loom'];
                    @endphp
                    @else 0 @endif
                </td>
                <td>@if( isset($item['godam_one_total_netwt']))
                    {{ $item['godam_one_total_netwt'] }}
                    @php
                    $subTotalGodamOneRunDown += $item['godam_one_total_netwt'];
                    $totalGodamOneRunDown += $item['godam_one_total_netwt'];
                    @endphp
                    @else 0 @endif
                </td>
                <td>@if( isset($item['godam_one_grand_total_meter']))
                    {{ $item['godam_one_grand_total_meter'] }}
                    @php
                    $subTotalGodamOneMeter += $item['godam_one_grand_total_meter'];
                    $totalGodamOneMeter += $item['godam_one_grand_total_meter'];
                    @endphp
                    @else 0 @endif
                </td>
                <td>@if( ($subTotalGodamOneMeter || $subTotalGodamOneRunLoom) && $subTotalGodamOneMeter>0 &&  $subTotalGodamOneRunLoom > 0 )
                    @php
                    $godamOneAverageMeter = $subTotalGodamOneMeter/$subTotalGodamOneRunLoom *1000;
                    $totalGodamOneAverageMeter += $godamOneAverageMeter;
                    @endphp
                    {{ $godamOneAverageMeter }}
                    @else @php $godamOneAverageMeter = 0; @endphp 0 @endif
                </td>
                <td>
                    @php
                    $godamOneWastage = $item['godam_one_total_pipe_cutting'] + $item['godam_one_total_bd_wastage'] + $item['godam_one_total_other_wastage'];
                    $totalGodamOneWastage +=$godamOneWastage;
                    $subTotalGodamOneWastage +=$godamOneWastage;
                    @endphp
                    {{ $godamOneWastage }}
                </td>
                <td>
                    @if($subTotalGodamOneRunDown>0)
                    @php $subTotalGodamOnePercentage = $subTotalGodamOneWastage/ $subTotalGodamOneRunDown * 100; @endphp
                    @endif
                    {{ $subTotalGodamOnePercentage }}
                </td>
                {{-- Godam Two --}}
                <td>@if( isset($item['godam_two_run_loom']))
                    {{ $item['godam_two_run_loom'] }}
                    @php
                    $subTotalGodamTwoRunLoom += $item['godam_two_run_loom'];
                    $totalGodamTwoRunLoom += $item['godam_two_run_loom'];
                    @endphp
                    @else 0 @endif
                </td>
                <td>@if( isset($item['godam_two_total_netwt']))
                    {{ $item['godam_two_total_netwt'] }}
                    @php
                    $subTotalGodamTwoRunDown += $item['godam_two_total_netwt'];
                    $totalGodamTwoRunDown += $item['godam_two_total_netwt'];
                    @endphp
                    @else 0 @endif
                </td>
                <td>@if( isset($item['godam_two_grand_total_meter']))
                    {{ $item['godam_two_grand_total_meter'] }}
                    @php
                    $subTotalGodamTwoMeter += $item['godam_two_grand_total_meter'];
                    $totalGodamTwoMeter += $item['godam_two_grand_total_meter'];
                    @endphp
                    @else 0 @endif
                </td>
                <td>@if( ($subTotalGodamTwoMeter || $subTotalGodamTwoRunLoom) && $subTotalGodamTwoMeter>0 &&  $subTotalGodamTwoRunLoom > 0 )
                    @php
                    $godamTwoAverageMeter = $subTotalGodamTwoMeter/$subTotalGodamTwoRunLoom *1000;
                    $totalGodamTwoAverageMeter += $godamTwoAverageMeter;
                    @endphp
                    {{ $godamTwoAverageMeter }}
                    @else 0 @endif
                </td>
                <td>
                    @php
                    $godamTwoWastage = $item['godam_two_total_pipe_cutting'] + $item['godam_two_total_bd_wastage'] + $item['godam_two_total_other_wastage'];
                    $totalGodamTwoWastage +=$godamTwoWastage;
                    $subTotalGodamTwoWastage +=$godamTwoWastage;
                    @endphp
                    {{ $godamTwoWastage }}
                </td>
                <td>
                    @if($subTotalGodamTwoRunDown>0)
                    @php $subTotalGodamTwoPercentage = $subTotalGodamTwoWastage/ $subTotalGodamTwoRunDown * 100; @endphp
                    @else
                    @php $subTotalGodamTwoPercentage=0; @endphp
                    @endif
                    {{ $subTotalGodamTwoPercentage }}
                </td>
                {{-- Godam 3 --}}
                <td>@if( isset($item['godam_three_run_loom']))
                    {{ $item['godam_three_run_loom'] }}
                    @php
                    $subTotalGodamThreeRunLoom += $item['godam_three_run_loom'];
                    $totalGodamThreeRunLoom += $item['godam_three_run_loom'];
                    @endphp
                    @else 0 @endif
                </td>
                <td>@if( isset($item['godam_three_total_netwt']))
                    {{ $item['godam_three_total_netwt'] }}
                    @php
                    $subTotalGodamThreeRunDown += $item['godam_three_total_netwt'];
                    $totalGodamThreeRunDown += $item['godam_three_total_netwt'];
                    @endphp
                    @else 0 @endif
                </td>
                <td>@if( isset($item['godam_three_grand_total_meter']))
                    {{ $item['godam_three_grand_total_meter'] }}
                    @php
                    $subTotalGodamThreeMeter += $item['godam_three_grand_total_meter'];
                    $totalGodamThreeMeter += $item['godam_three_grand_total_meter'];
                    @endphp
                    @else 0 @endif
                </td>
                <td>@if( ($subTotalGodamThreeMeter || $subTotalGodamThreeRunLoom) && $subTotalGodamThreeMeter>0 &&  $subTotalGodamThreeRunLoom > 0 )
                    @php
                    $godamThreeAverageMeter = $subTotalGodamThreeMeter/$subTotalGodamThreeRunLoom *1000;
                    $totalGodamThreeAverageMeter += $godamThreeAverageMeter;
                    @endphp
                    {{ $godamThreeAverageMeter }}
                    @else 0 @endif
                </td>
                <td>
                    @php
                    $godamThreeWastage = $item['godam_three_total_pipe_cutting'] + $item['godam_three_total_bd_wastage'] + $item['godam_three_total_other_wastage'];
                    $totalGodamThreeWastage +=$godamThreeWastage;
                    $subTotalGodamThreeWastage +=$godamThreeWastage;
                    @endphp
                    {{ $godamTwoWastage }}
                </td>
                <td>
                    @if($subTotalGodamThreeRunDown>0)
                    @php $subTotalGodamThreePercentage = $subTotalGodamThreeWastage/ $subTotalGodamThreeRunDown * 100; @endphp
                    @endif
                    {{ $subTotalGodamThreePercentage }}
                </td>
                {{-- All Total --}}
                <td>
                    @php
                    $totalRunDown = $subTotalGodamOneRunDown + $subTotalGodamTwoRunDown + $subTotalGodamThreeRunDown;
                    $finalTotalRunDown += $totalRunDown;
                    @endphp
                    {{ $totalRunDown }}
                </td>
                <td>
                    @php
                    $totalWastage = $subTotalGodamOneWastage + $subTotalGodamTwoWastage + $subTotalGodamThreeWastage;
                    $finalTotalWastage += $totalWastage;
                    @endphp
                    {{ $totalWastage }}
                </td>
                <td>
                    @php
                    $totalPercentage = $subTotalGodamOnePercentage+ $subTotalGodamTwoPercentage +$subTotalGodamThreePercentage;
                    $finalTotalPercentage += $totalPercentage;
                    @endphp
                    {{ $totalPercentage }}
                </td>
            </tr>
        @endforeach
        <tfoot>
            <tr>
                <td></td>
                <td>Total</td>
                <td>{{ $totalGodamOneRunLoom }}</td>
                <td>{{ $totalGodamOneRunDown }}</td>
                <td>{{ $totalGodamOneMeter }}</td>
                <td>{{ $totalGodamOneAverageMeter }}</td>
                <td>{{ $totalGodamOneWastage }}</td>
                <td>{{ $totalGodamOnePercentage }}</td>

                <td>{{ $totalGodamTwoRunLoom }}</td>
                <td>{{ $totalGodamTwoRunDown }}</td>
                <td>{{ $totalGodamTwoMeter }}</td>
                <td>{{ $totalGodamTwoAverageMeter }}</td>
                <td>{{ $totalGodamTwoWastage }}</td>
                <td>{{ $totalGodamTwoPercentage }}</td>

                <td>{{ $totalGodamThreeRunLoom }}</td>
                <td>{{ $totalGodamThreeRunDown }}</td>
                <td>{{ $totalGodamThreeMeter }}</td>
                <td>{{ $totalGodamThreeAverageMeter }}</td>
                <td>{{ $totalGodamThreeWastage }}</td>
                <td>{{ $totalGodamThreePercentage }}</td>

                <td>
                    {{ $finalTotalRunDown }}
                </td>
                <td>
                    {{ $finalTotalWastage }}
                </td>
                <td>
                    {{ $finalTotalPercentage }}
                </td>
            </tr>
        </tfoot>
    </tbody>

</table>
