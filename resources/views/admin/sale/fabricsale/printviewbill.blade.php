<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sales</title>
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
</head>
<body>
    <div class="row" id="printTable">
        <div class="col-12">
            <div class="invoice p-3 mb-3 card  card-outline">

                <div class="row invoice-info p-4">
                    <div class="col-sm-12 text-center mb-2">
                        <div><small>PASHUPATI SYNPACK INDUSTRIES PVT. LTD.</small></div>
                        {{-- <h3 class="m-0">SONAPUR,SUNSARI</h3> --}}
                        <div><small><b>SONAPUR,SUNSARI</b></small> </div>

                    </div>
                    <div class="col-sm-6 invoice-col">

                        <address>
                            <strong> PartyName : {{ $findsale->getParty->name }}</strong><br>

                        </address>
                    </div>
                    <div class="col-sm-12 col-lg-6  text-right">
                        <b>A/C : {{ $findsale->getParty->name }}</b><br>
                        <br>
                    </div>
                    <div class="row col-lg-12">
                        <div class="col-sm-6 col-lg-6  text-left">
                            <b>Invoice Number: {{ $findsale->bill_no }}</b><br>
                            <br>
                        </div>

                        <div class="col-lg-6 text-right">
                            <b>Gate Pass: {{ $findsale->gp_no }}</b><br>
                            <br>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-sm-12 text-right ml-2">
                            <b>Date: {{ $findsale->bill_date }}</b><br>
                            <br>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-12 table-responsive">
                        @foreach ($formattedData as $name => $fabricItems)
                            <table class="table table-bordered" style="padding: 0 30px;">
                                <tr>
                                    <th width="10px">{{ __('Sr.No') }}</th>
                                    <th width="10px">{{ __('Fabric Name') }}</th>
                                    <th width="10px">{{ __('Roll No') }}</th>
                                    <th width="10px">{{ __('Gross Wght') }}</th>
                                    <th width="10px">{{ __('Net Wght') }}</th>
                                    <th width="10px">{{ __('Meter') }}</th>
                                    <th width="10px">{{ __('Avg Wght') }}</th>
                                    <th width="10px">{{ __('Avg Gram') }}</th>
                                </tr>
                                <tbody>
                                    @php
                                        $totals = [
                                            'gross_wt' => 0,
                                            'net_wt' => 0,
                                            'meter' => 0,
                                            'average_wt' => 0,
                                            'gram_wt' => 0,
                                        ];
                                    @endphp
                                    @foreach ($fabricItems as $key => $fabric)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $fabric['name'] }}</td>
                                            <td>{{ $fabric['roll_no'] }}</td>
                                            <td>{{ $fabric['gross_wt'] }}</td>
                                            <td>{{ $fabric['net_wt'] }}</td>
                                            <td>{{ $fabric['meter'] }}</td>
                                            <td>{{ $fabric['average_wt'] }}</td>
                                            <td>{{ $fabric['gram_wt'] }}</td>
                                        </tr>
                                        @foreach ($totals as $key => $value)
                                            @if ($key !== 'name')
                                                @php
                                                    $totals[$key] += $fabric[$key];
                                                @endphp
                                            @endif
                                        @endforeach
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td>Total</td>
                                        <td></td>
                                        <td>{{ $totals['gross_wt'] }}</td>
                                        <td>{{ $totals['net_wt'] }}</td>
                                        <td>{{ $totals['meter'] }}</td>
                                        <td>{{ $totals['average_wt'] }}</td>
                                        <td>{{ $totals['gram_wt'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        @endforeach

                    </div>
                </div>

                <h3 class="m-0 text-center mt-4">SUMMARY</h3>

                <div class="row px-4 py-3">
                    <div class="col-12 table-responsive">
                        <table class="table table-bordered">


                            <tr>
                                <th width="10px">{{ __('Sr.No') }}</th>
                                <th width="10px">{{ __('Fabric Name') }}</th>
                                <th width="10px">{{ __('Roll No') }}</th>
                                <th width="10px">{{ __('Gross Wght') }}</th>
                                <th width="10px">{{ __('Net Wght') }}</th>
                                <th width="10px">{{ __('Meter') }}</th>
                            </tr>

                            <tbody>
                                @foreach ($totalstocks as $key => $stock)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $stock->name }}</td>
                                        <td>{{ $stock->total_count }}</td>
                                        <td>{{ $stock->total_gross }}</td>
                                        <td>{{ $stock->total_net }}</td>
                                        <td>{{ $stock->total_meter }}</td>
                                    </tr>
                                @endforeach
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>{{ $total_gross }}</td>
                                    <td>{{ $total_net }}</td>
                                    <td>{{ $total_meter }}</td>
                                </tr>
                            </tfoot>


                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener("load", window.print());
    </script>
</body>
</html>
