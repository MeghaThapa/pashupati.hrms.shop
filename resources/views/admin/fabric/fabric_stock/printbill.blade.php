<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fabric Stock</title>
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
</head>
<body>
    <div class="row" id="printTable">
        <div class="col-12">
            <div class="invoice p-3 mb-3 card  card-outline">

                <div class="row invoice-info p-4">
                    <div class="col-sm-12 text-center mb-2">
                        <div>
                            <h5 style="font-weight:bold;">PASHUPATI SYNPACK INDUSTRIES PVT. LTD.</h5>
                        </div>
                        {{-- <h3 class="m-0">SONAPUR,SUNSARI</h3> --}}
                        <div>
                            <h6><b>SONAPUR,SUNSARI</b></h6>
                        </div>

                    </div>
                    <div class="col-sm-6 invoice-col">

                        <address>
                            <strong> Godam : {{ $find_godam == null ? 'All' : $find_godam }}</strong><br>

                        </address>
                    </div>
                    <div class="col-sm-12 col-lg-6  text-right">
                        <b>Name : {{ $find_name == null ? 'All' : $find_name }}</b><br>
                        <br>
                    </div>
                    <div class="row col-lg-12">
                        <div class="col-sm-6 col-lg-6  text-left">
                            <b>Type: {{ $find_type == null ? 'All' : $find_type }}</b><br>
                            <br>
                        </div>

                        <div class="col-lg-6 text-right">
                            <b>Group: {{ $find_group == null ? 'All' : $find_group }}</b><br>
                            <br>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-sm-12 text-right ml-2">
                            {{-- <b>Date: {{$findtripal->bill_date}}</b><br> --}}
                            <br>
                        </div>

                    </div>


                </div>
                <div class="row">
                    <div class="col-12 table-responsive" style="padding: 0 30px; ">
                        @php
                            $grandCount = 0;
                            $grandTotalGrossWt = 0;
                            $grandTotalNetWt = 0;
                            $grandTotalMeter = 0;
                        @endphp
                        @foreach ($fabricsData as $name => $fabrics)
                            <table class="table table-bordered">
                                <tr>
                                    <th width="10px">{{ __('Sr.No') }}</th>
                                    <th width="10px">{{ __('Fabric Name') }}</th>
                                    <th width="10px">{{ __('Roll No') }}</th>
                                    <th width="10px">{{ __('Gross Wght') }}</th>
                                    <th width="10px">{{ __('Net Wght') }}</th>
                                    <th width="10px">{{ __('Meter') }}</th>
                                    <th width="10px">{{ __('Avg Wght') }}</th>
                                </tr>

                                <tbody>
                                    @php
                                        $i = 0;
                                        $totalGrossWt = 0;
                                        $totalNetWt = 0;
                                        $totalMeter = 0;
                                    @endphp
                                    @foreach ($fabrics as $fabric)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $fabric['name'] }}</td>
                                            <td>{{ $fabric['roll_no'] }}</td>
                                            <td>{{ $fabric['gross_wt'] }}</td>
                                            <td>{{ $fabric['net_wt'] }}</td>
                                            <td>{{ $fabric['meter'] }}</td>
                                            <td>{{ $fabric['average_wt'] }}</td>
                                        </tr>
                                        @php
                                            ++$grandCount;
                                            $totalGrossWt += $fabric['gross_wt'];
                                            $totalNetWt += $fabric['net_wt'];
                                            $totalMeter += $fabric['meter'];
                                        @endphp
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td>Total:</td>
                                        <td>{{ $i }}</td>
                                        <td>{{ $totalGrossWt }}</td>
                                        <td>{{ $totalNetWt }}</td>
                                        <td>{{ $totalMeter }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                            @php
                                $grandTotalGrossWt += $totalGrossWt;
                                $grandTotalNetWt += $totalNetWt;
                                $grandTotalMeter += $totalMeter;
                            @endphp
                        @endforeach

                        <table class="table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>No of Rolls</th>
                                    <th>Grand Gross Wt Total</th>
                                    <th>Grand Net Wt Total</th>
                                    <th>Grand Meter Total</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td>Grand Total</td>
                                    <td>{{ $grandCount }}</td>
                                    <td>{{ $grandTotalGrossWt }}</td>
                                    <td>{{ $grandTotalNetWt }}</td>
                                    <td>{{ $grandTotalMeter }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>


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
                                @foreach ($totaldatas as $key => $data)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $data->name }}</td>
                                        <td>{{ $data->total_count }}</td>
                                        <td>{{ $data->total_gross }}</td>
                                        <td>{{ $data->total_net }}</td>
                                        <td>{{ $data->total_meter }}</td>
                                    </tr>
                                @endforeach
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td colspan="3">GrandTotal</td>
                                    <td>{{ $total_gross }}</td>
                                    <td>{{ $total_net }}</td>
                                    <td>{{ $total_meter }}</td>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>

                <div class="row no-print">
                    <div class="col-12">
                        {{-- <span>Bill Printed By : {{$bill_total_student->getUser->name}}</span> --}}
                        {{-- <span class="float-right">Bill Date: {{$bill_total_student->created_at_np}}</span> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    window.addEventListener("load", window.print());
</script>
</html>
