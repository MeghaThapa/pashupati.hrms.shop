@extends('layouts.admin')

@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
    <style>
        .col-form-label {
            font-size: 12px !important;

        }

        .dynamic-btn {
            height: 18px;
            width: 4px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #storeinSubmitBtn {
            height: 25px;
            width: 70px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 5px !important;
        }

        .fa-plus {
            font-size: 10px;
        }

        .form-control {
            font-size: 12px !important;

        }

        .select2-selection__rendered,
        .select2-container--bootstrap4 .select2-selection {
            font-size: 12px !important;
            display: flex !important;
            align-items: center !important;
            height: calc(1.6em + 0.75rem + 2px) !important;
        }

        .select2-container {
            height: calc(1.6em + 0.75rem + 2px) !important;
        }

        .taxStyle .select2-selection {
            width: 200px !important;
        }

        .form-group {
            margin-bottom: 0px !important;
        }

        .content-wrapper {
            padding-top: 0px !important;
        }

        .card-body {
            padding: 0px 5px !important;
        }

        .card {
            padding: 0px 5px !important;
        }

        .col-md-6 {
            padding: 0px 2px !important;
        }
    </style>
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">



            <div class="row" id="printTable">
                <div class="col-12">
                    <div class="invoice p-3 mb-3 card  card-outline">
                        {{-- <div class="row col-12">
            <div class="col-12">
              <h4>
                <small class="float-right">Date: </small>
              </h4>
            </div>
          </div> --}}
                        <div class="row invoice-info p-4">
                            <div class="col-sm-12 text-center mb-2">
                                <div><small>PASHUPATI SYNPACK INDUSTRIES PVT. LTD.</small></div>
                                {{-- <h3 class="m-0">SONAPUR,SUNSARI</h3> --}}
                                <div><small><b>SONAPUR,SUNSARI</b></small> </div>

                            </div>
                            <div class="col-sm-6 invoice-col">

                                <address>
                                    {{-- <strong> PartyName : {{$findtripal->getParty->name}}</strong><br> --}}

                                </address>
                            </div>
                            <div class="col-sm-12 col-lg-6  text-right">
                                {{-- <b>A/C : {{$findtripal->getParty->name}}</b><br> --}}
                                <br>
                            </div>
                            <div class="row col-lg-12">
                                <div class="col-sm-6 col-lg-6  text-left">
                                    {{-- <b>Invoice Number: {{$findtripal->bill_no}}</b><br> --}}
                                    <br>
                                </div>

                                <div class="col-lg-6 text-right">
                                    {{-- <b>Gate Pass: {{$findtripal->gp_no}}</b><br> --}}
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
                            <div class="col-12 table-responsive">
                                @foreach ($dataArray as $fabricName => $fabricItems)
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>S.r.</th>
                                                <th>Fabric Name</th>
                                                <th>Roll No</th>
                                                <th>Net Wt</th>
                                                <th>Meter</th>
                                                <th>Gross Wt</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 0;
                                                $netWt = 0;
                                                $meter = 0;
                                                $grossWt = 0;
                                            @endphp
                                            @foreach ($fabricItems as $item)
                                                <tr>
                                                    <td>{{ ++$i }}</td>
                                                    <td>{{ $item['fabric_name'] }}</td>
                                                    <td>{{ $item['roll_no'] }}</td>
                                                    <td>{{ $item['net_wt'] }}</td>
                                                    <td>{{ $item['meter'] }}</td>
                                                    <td>{{ $item['gross_wt'] }}</td>
                                                </tr>
                                                @php
                                                    $netWt += (float) $item['net_wt'];
                                                    $meter += (float) $item['meter'];
                                                    $grossWt += (float) $item['gross_wt'];
                                                @endphp
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td></td>
                                                <td>Total</td>
                                                <td></td>
                                                <td>{{ $netWt }}</td>
                                                <td>{{ $meter }}</td>
                                                <td>{{ $grossWt }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                @endforeach
                            </div>
                        </div>

                        <h3 class="m-0 text-center mt-2">SUMMARY</h3>

                        <div class="row p-4">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>S.r.</th>
                                            <th>Fabric Name</th>
                                            <th>Count(Roll No)</th>
                                            <th>Sum(Net Wt)</th>
                                            <th>Sum(Meter)</th>
                                            <th>Sum(Gross Wt)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 0;
                                            $netWt_sum = 0;
                                            $meter_sum = 0;
                                            $grossWt_sum = 0;
                                        @endphp
                                        @foreach ($summaryArray as $summary)
                                            <tr>
                                                <td>{{ ++$i }}</td>
                                                <td>{{ $summary['fabric_name'] }}</td>
                                                <td>{{ $summary['roll_no_count'] }}</td>
                                                <td>{{ $summary['net_wt_sum'] }}</td>
                                                <td>{{ $summary['meter_sum'] }}</td>
                                                <td>{{ $summary['gross_wt_sum'] }}</td>
                                            </tr>
                                            @php
                                                $netWt_sum += (float) $summary['net_wt_sum'];
                                                $meter_sum += (float) $summary['meter_sum'];
                                                $grossWt_sum += (float) $summary['gross_wt_sum'];
                                            @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td>Total</td>
                                            <td></td>
                                            <td>{{ $netWt_sum }}</td>
                                            <td>{{ $meter_sum }}</td>
                                            <td>{{ $grossWt_sum }}</td>
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
        </div>
    </section>






    <!-- /.card-body -->

    <!-- pagination start -->
@endsection
@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/storein.js') }}"></script>
@endsection
