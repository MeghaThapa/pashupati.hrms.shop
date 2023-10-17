@foreach ($formattedReports as $key => $result)
    <h5>{{ $key }}</h5>
    <table class="table table-bordered" id="rawMaterialTable" style="margin-bottom: 30px;">
        <thead>
            <tr>
                <th></th>
                <th colspan="5">Received</th>
                <th colspan="4">Issue</th>
                <th></th>
            </tr>
        </thead>
        <thead>
            <tr>
                <th>Date</th>
                <th>Opening</th>
                <th>Import</th>
                <th>Local</th>
                <th>CC Plant</th>
                <th>From Godam</th>
                <th>Tape</th>
                <th>Lam</th>
                <th>PSI Godam</th>
                <th>BSW Godam</th>
                <th>Closing</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($result as $dateKey => $reportRow)
                <tr>
                    <td>{{ $dateKey }}</td>
                    <td> {{ $reportRow['opening_amount'] }} </td>
                    <td> {{ $reportRow['import'] }} </td>
                    <td> {{ $reportRow['local'] }} </td>
                    <td> {{ $reportRow['cc_plant_quantity'] }}</td>
                    <td> {{ $reportRow['from_godam'] }} </td>
                    <td> {{ $reportRow['tape'] }} </td>
                    <td> {{ $reportRow['lam'] }} </td>
                    <td> @if($reportRow['to_godam']==1) {{ $reportRow['to_godam_quantity'] }} @else 0 @endif </td>
                    <td> @if($reportRow['to_godam']==3) {{ $reportRow['to_godam_quantity'] }} @else 0 @endif </td>
                    <td> {{ $reportRow['closing'] }} </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endforeach
