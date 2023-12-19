<h3>Printing and Fininshing Report for Date: {{ $request->start_date }} To {{ $request->end_date }}</h3>
<table class="table table-bordered table-responsive">
    <thead class="text-bold">
        <tr>
            <th colspan="4" style="text-align: center;border: 2px solid black;"></th>
            <th colspan="7" style="text-align: center;border: 2px solid black;">Ink</th>
            <th colspan="6" style="text-align: center;border: 2px solid black;"></th>
            {{-- <th colspan="5" style="text-align: center;border: 2px solid black;">BSW Lamination Plant</th>
            <th colspan="5" style="text-align: center;border: 2px solid black;">Total All </th>  --}}
        </tr>
        <tr>
            <th style="border: 1px solid black;"> Sr </th>
            <th style="min-width: 120px;border: 1px solid black;">Date</th>
            <th style="border: 1px solid black;">Roll Isuue</th>
            <th style="border: 1px solid black;">Cutting Bags</th>
            <th style="border: 1px solid black;">Black</th>
            <th style="border: 1px solid black;">Red.</th>
            <th style="border: 1px solid black;">Green</th>
            <th style="border: 1px solid black;">Blue</th>
            <th style="border: 1px solid black;">Orange</th>
            <th style="border: 1px solid black;">Yellow</th>
            <th style="border: 1px solid black;">Total Ink</th>
            <th style="border: 1px solid black;">Diesel</th>
            <th style="border: 1px solid black;">Tape</th>
            <th style="border: 1px solid black;">NBA</th>
            <th style="border: 1px solid black;">PP Yarn</th>
            <th style="border: 1px solid black;">Issue to finishing</th>
            <th style="border: 1px solid black;">Wastage</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i = 0;
            $totalRollIssue = 0;
            $totalCuttingBag = 0;
            $totalIssueToFinishing = 0;
            $totalwaste = 0;
        @endphp

        @foreach ($datas as $date => $item)
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

                <td>{{ ++$i }}</td>
                <td>{{ $item['date'] }}</td>

                <td>
                    @if (isset($item['rollIssue_total_net_wt']))
                        {{ $item['rollIssue_total_net_wt'] }}
                        @php
                            $totalRollIssue += $item['rollIssue_total_net_wt'];
                        @endphp
                    @else
                        0
                    @endif
                </td>

                <td>
                    @if (isset($item['cuttingBag_total_quantity_piece']))
                        {{ $item['cuttingBag_total_quantity_piece'] }}
                        @php
                            $totalCuttingBag += $item['cuttingBag_total_quantity_piece'];
                        @endphp
                    @else
                        0
                    @endif
                </td>


                @foreach ($item['dana_quantities'] as $color => $quantity)
                @php
                    $totalInk=0;
                @endphp
                    @if (strtolower($color) == 'black')
                        <td class="black-column">{{ $quantity }}</td>
                        @php
                           $totalInk += $quantity;
                        @endphp
                    @else
                        <td class="black-column">0</td>
                    @endif

                    @if (strtolower($color) == 'red')
                        <td class="red-column">{{ $quantity }}</td>
                        @php
                        $totalInk += $quantity;
                     @endphp
                    @else
                        <td class="red-column">0</td>
                    @endif

                    @if (strtolower($color) == 'green')
                        <td class="green-column">{{ $quantity }}</td>
                        @php
                        $totalInk += $quantity;
                     @endphp
                    @else
                        <td class="green-column">0</td>
                    @endif

                    @if (strtolower($color) == 'blue')
                        <td class="blue-column">{{ $quantity }}</td>
                        @php
                        $totalInk += $quantity;
                     @endphp
                    @else
                        <td class="blue-column">0</td>
                    @endif
                    @if (strtolower($color) == 'orange')
                        <td class="orange-column">{{ $quantity }}</td>
                        @php
                        $totalInk += $quantity;
                     @endphp
                    @else
                        <td class="orange-column">0</td>
                    @endif

                    @if (strtolower($color) == 'yellow')
                        <td class="yellow-column">{{ $quantity }}</td>
                    @else
                        <td class="yellow-column">0</td>
                    @endif

                  
                    <td class="totalInk-column">{{ $totalInk }}</td>
                

                    @if (strtolower($color) == 'diesel')
                        <td class="diesel-column">{{ $quantity }}</td>
                    @else
                        <td class="diesel-column">0</td>
                    @endif

                    @if (strtolower($color) == 'tape')
                        <td class="tape-column">{{ $quantity }}</td>
                    @else
                        <td class="tape-column">0</td>
                    @endif

                    @if (strtolower($color) == 'nba')
                        <td class="nba-column">{{ $quantity }}</td>
                    @else
                        <td class="nba-column">0</td>
                    @endif

                    @if (strtolower($color) == 'pp yarn')
                        <td class="pp yarn-column">{{ $quantity }}</td>
                    @else
                        <td class="pp yarn-column">0</td>
                    @endif
                @endforeach

                <td>
                    @if (isset($item['tot_total_quantity']))
                        {{ $item['tot_total_quantity'] }}
                        @php
                            $totalIssueToFinishing += $item['tot_total_quantity'];
                        @endphp
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if (isset($item['tot_total_wastage']))
                        {{ $item['tot_total_wastage'] }}
                        @php
                            $totalwaste += $item['tot_total_wastage'];
                        @endphp
                    @else
                        0
                    @endif
                </td>

                {{-- <td>
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
                </td> --}}
            </tr>
        @endforeach
    <tfoot>
        <tr>
            <td></td>
            <td>Total</td>
            <td>{{ $totalRollIssue }}</td>
            <td>{{ $totalCuttingBag }}</td>
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
    </tfoot>
    </tbody>

</table>
