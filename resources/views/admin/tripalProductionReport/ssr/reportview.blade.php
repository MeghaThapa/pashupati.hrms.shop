<h3>Summary of Tape Production Report for Date: {{ $request->start_date }} To {{ $request->end_date }}</h3>
<table class="table table-bordered table-responsive">
    <thead class="text-bold">
        <tr>
            <th style="text-align: center;border: 2px solid black;"></th>
            <th colspan="5" style="text-align: center;border: 2px solid black;">Single Sde Production</th>
            <th colspan="5" style="text-align: center;border: 2px solid black;">Double Sde Production</th>
            <th colspan="5" style="text-align: center;border: 2px solid black;">Rewinding Tripal Production</th>
        </tr>
        <tr>
            <th style="min-width: 120px;border: 1px solid black;">Date</th>
            <th style="border: 1px solid black;">Unlam</th>
            <th style="border: 1px solid black;">Single lam</th>
            <th style="border: 1px solid black;">In meter</th>
            <th style="border: 1px solid black;">Wastage</th>
            <th style="border: 1px solid black;">%</th>
            <th style="border: 1px solid black;">Single Side</th>
            <th style="border: 1px solid black;">Both Side</th>
            <th style="border: 1px solid black;">In Meter</th>
            <th style="border: 1px solid black;">Wastage</th>
            <th style="border: 1px solid black;">%</th>
            <th style="border: 1px solid black;">Both Side</th>
            <th style="border: 1px solid black;">Rewinding</th>
            <th style="border: 1px solid black;">In Meter</th>
            <th style="border: 1px solid black;">Wastage</th>
            <th style="border: 1px solid black;">%</th>
        </tr>
    </thead>
    <tbody>
        @php
            $col_total_singleSide_total_waste_sum = 0;
            $col_total_unlam_net_wt_sum = 0;
            $col_total_singleLam_net_wt_sum_singleside = 0;
            $col_total_singleLam_meter_sum = 0;
            $col_total_doubleSide_total_waste_sum = 0;
            $col_total_doubleLam_net_wt_sum = 0;
            $col_total_doubleLam_meter_sum = 0;
            $col_total_triple_total_waste_sum = 0;
            $col_total_tripal_net_wt_sum = 0;
            $col_total_finalTripal_net_wt_sum = 0;
            $col_total_finalTripal_meter_sum = 0;

        @endphp

        @foreach ($dataArray as $date => $item)
            <tr>
                @php
                    $subTotalsundarUnlam = 0;
                    $subTotalsundarLam = 0;
                    $subTotalsundarProdMeter = 0;
                    $subTotalsundarWaste = 0;
                    $subTotalSundarPerc = 0;

                    $subTotaljpUnlam = 0;
                    $subTotaljpLam = 0;
                    $subTotaljpProdMeter = 0;
                    $subTotaljpWaste = 0;
                    $subTotaljpPerc = 0;

                    $subTotalbswUnlam = 0;
                    $subTotalbswLam = 0;
                    $subTotalbswProdMeter = 0;
                    $subTotalbswWaste = 0;
                    $subTotalbswPerc = 0;

                @endphp

                {{-- <td>{{ ++$i }}</td> --}}
                <td>{{ $item['bill_date'] }}</td>

                <td>
                    @if (isset($item['total_unlam_net_wt_sum']))
                        {{ $item['total_unlam_net_wt_sum'] }}
                        @php
                            $col_total_unlam_net_wt_sum += $item['total_unlam_net_wt_sum'];
                        @endphp
                    @else
                        0
                    @endif
                </td>

                <td>
                    @if (isset($item['total_singleLam_meter_sum']))
                        {{ $item['total_singleLam_meter_sum'] }}
                        @php
                            $col_total_unlam_net_wt_sum += $item['total_singleLam_meter_sum'];
                        @endphp
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if (isset($item['total_singleLam_net_wt_sum_singleside']))
                        {{ $item['total_singleLam_net_wt_sum_singleside'] }}
                        @php
                            // $subTotalbswUnlam += $item['bsw_total_unlam'];
                            $col_total_singleLam_net_wt_sum_singleside += $item['total_singleLam_net_wt_sum_singleside'];
                        @endphp
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if (isset($item['total_singleLam_meter_sum']))
                        {{ $item['total_singleLam_meter_sum'] }}
                        @php
                            // $subTotalbswLam += $item['bsw_total_lam'];
                            $col_total_singleLam_meter_sum += $item['total_singleLam_meter_sum'];
                        @endphp
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if (isset($item['total_doubleSide_total_waste_sum']))
                        {{ $item['total_doubleSide_total_waste_sum'] }}
                        @php
                            $col_total_doubleSide_total_waste_sum += $item['total_doubleSide_total_waste_sum'];
                        @endphp
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if (isset($item['total_doubleLam_net_wt_sum']))
                        {{ $item['total_doubleLam_net_wt_sum'] }}
                        @php
                            $col_total_doubleLam_net_wt_sum += $item['total_doubleLam_net_wt_sum'];
                        @endphp
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if (isset($item['total_doubleLam_meter_sum']))
                        {{ $item['total_doubleLam_meter_sum'] }}
                        @php
                            $col_total_doubleLam_meter_sum += $item['total_doubleLam_meter_sum'];
                        @endphp
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if (isset($item['total_triple_total_waste_sum']))
                        {{ $item['total_triple_total_waste_sum'] }}
                        @php
                            $col_total_triple_total_waste_sum += $item['total_triple_total_waste_sum'];
                        @endphp
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if (isset($item['total_tripal_net_wt_sum']))
                        {{ $item['total_tripal_net_wt_sum'] }}
                        @php
                            $col_total_tripal_net_wt_sum += $item['total_tripal_net_wt_sum'];
                        @endphp
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if (isset($item['total_finalTripal_net_wt_sum']))
                        {{ $item['total_finalTripal_net_wt_sum'] }}
                        @php
                            $col_total_finalTripal_net_wt_sum += $item['total_finalTripal_net_wt_sum'];
                        @endphp
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if (isset($item['total_finalTripal_meter_sum']))
                        {{ $item['total_finalTripal_meter_sum'] }}
                        @php
                            $col_total_finalTripal_meter_sum += $item['total_finalTripal_meter_sum'];
                        @endphp
                    @else
                        0
                    @endif
                </td>

            </tr>
        @endforeach
        {{-- <tfoot>
            <tr>
                <td></td>
                <td>Total</td>
                <td>{{ $totalRollIssue }}</td>
                <td>{{ $totalCuttingBag}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ $totalIssueToFinishing }}</td>
                <td>{{ $totalwaste }}</td>
            </tr>
        </tfoot> --}}
    </tbody>

</table>
