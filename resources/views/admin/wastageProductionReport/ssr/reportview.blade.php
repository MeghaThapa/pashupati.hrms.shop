<h3>Summary of Wastage Production Report for Date: {{ $request->start_date }} To {{ $request->end_date }}</h3>
<table class="table table-bordered table-responsive">
    <thead class="text-bold">
        <tr>
            <th style="text-align: center;border: 2px solid black;"></th>
            <th colspan="7" style="text-align: center;border: 2px solid black;">Tape Plant Wastage</th>
            <th colspan="4" style="text-align: center;border: 2px solid black;">Loom Wastage</th>
            <th colspan="5" style="text-align: center;border: 2px solid black;">Lamination Plant</th>
            <th colspan="1" style="text-align: center;border: 2px solid black;">Nonwoven</th>
            <th colspan="2" style="text-align: center;border: 2px solid black;">Printing and Finishing</th>
            <th colspan="2" style="text-align: center;border: 2px solid black;">Other</th>

        </tr>
        <tr>
            <th style="min-width: 120px;border: 1px solid black;">Date</th>
            <th style="border: 1px solid black;">Kolsite</th>
            <th style="border: 1px solid black;">Lohia-i</th>
            <th style="border: 1px solid black;">Lohia-ii</th>
            <th style="border: 1px solid black;">Lohia-iii </th>
            <th style="border: 1px solid black;">Lohia-iv</th>
            <th style="border: 1px solid black;">Bsw Tiratex</th>
            <th style="border: 1px solid black;">Total</th>

            <th style="border: 1px solid black;">PSI</th>
            <th style="border: 1px solid black;">New Psi</th>
            <th style="border: 1px solid black;">BSW</th>
            <th style="border: 1px solid black;">Total</th>

            <th style="border: 1px solid black;">Jp lam</th>
            <th style="border: 1px solid black;">Sundaar Lam</th>
            <th style="border: 1px solid black;">Jp Tripal</th>
            <th style="border: 1px solid black;">Ecotex</th>
            <th style="border: 1px solid black;">Total</th>

            <th style="border: 1px solid black;">Nonwoven</th>
            <th style="border: 1px solid black;">PSI</th>
            <th style="border: 1px solid black;">Bsw</th>
            <th style="border: 1px solid black;">Erema</th>
            <th style="border: 1px solid black;">CCplant</th>

        </tr>
    </thead>
    <tbody>
        @php
        $totalplant_wastage = 0;
        $total_godam = 0;
        $total_lamwastage = 0;
        @endphp

        @foreach ($plantArray as $date => $item)
            <tr>

                <td>{{ $item['bill_date'] }}</td>
                <td>
                    @if (isset($item['tapeplant_wastage1']))
                    {{ $item['tapeplant_wastage1'] }}
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if (isset($item['tapeplant_wastage2']))
                    {{ $item['tapeplant_wastage2'] }}
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if (isset($item['tapeplant_wastage3']))
                    {{ $item['tapeplant_wastage3'] }}
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if (isset($item['tapeplant_wastage4']))
                    {{ $item['tapeplant_wastage4'] }}
                    @else
                        0
                    @endif

                </td>
                <td>
                    @if (isset($item['tapeplant_wastage9']))
                    {{ $item['tapeplant_wastage9'] }}
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if (isset($item['tapeplant_wastage13']))
                    {{ $item['tapeplant_wastage13'] }}
                    @else
                        0
                    @endif

                </td>
                <td>
                    @php
                    $totalplant_wastage = $item['tapeplant_wastage1'] +$item['tapeplant_wastage2']+$item['tapeplant_wastage3']+$item['tapeplant_wastage4']+$item['tapeplant_wastage9']+$item['tapeplant_wastage13'];
                    @endphp
                    {{$totalplant_wastage}}
                </td>
                <td>
                    @if (isset($item['godam1']))
                    {{ $item['godam1'] }}
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if (isset($item['godam2']))
                    {{ $item['godam2'] }}
                    @else
                        0
                    @endif

                </td>
                <td>
                    @if (isset($item['godam3']))
                    {{ $item['godam3'] }}
                    @else
                        0
                    @endif
                </td>
                <td>
                    @php
                    $total_godam = $item['godam1'] +$item['godam2']+$item['godam3'];
                    @endphp
                    {{$total_godam}}
                </td>
                <td>
                    @if (isset($item['laminationplant_wastage1']))
                    {{ $item['laminationplant_wastage1'] }}
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if (isset($item['laminationplant_wastage2']))
                    {{ $item['laminationplant_wastage2'] }}
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if (isset($item['laminationplant_wastage3']))
                    {{ $item['laminationplant_wastage3'] }}
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if (isset($item['laminationplant_wastage4']))
                    {{ $item['laminationplant_wastage4'] }}
                    @else
                        0
                    @endif
                </td>
                <td>
                    @php
                    $total_lamwastage = $item['laminationplant_wastage1'] +$item['laminationplant_wastage2']+$item['laminationplant_wastage3']+$item['laminationplant_wastage4'];
                    @endphp
                    {{$total_lamwastage}}
                </td>
                <td>
                    @if (isset($item['nonwoven_wastage']))
                    {{ $item['nonwoven_wastage'] }}
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if (isset($item['printfinish_wastage1']))
                    {{ $item['printfinish_wastage1'] }}
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if (isset($item['printfinish_wastage3']))
                    {{ $item['printfinish_wastage3'] }}
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if (isset($item['erema_wastage']))
                    {{ $item['erema_wastage'] }}
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if (isset($item['total_ccplant_wastage']))
                    {{ $item['total_ccplant_wastage'] }}
                    @else
                        0
                    @endif
                </td>

            </tr>
        @endforeach
    </tbody>

</table>
