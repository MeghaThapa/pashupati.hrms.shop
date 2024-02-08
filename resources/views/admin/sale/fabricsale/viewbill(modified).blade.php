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
            {{-- <a href="{{route('tripalsale.pdf',$id)}}"> <button class="btn btn-info btn-sm rounded-0" type="submit" ><i class="fas fa-print"></i> Pdf</button></a> --}}
            {{-- <a href="{{route('tripalsale.excel',$id)}}"> <button class="btn btn-success btn-sm rounded-0"><i class="fas fa-print"></i> Excel</button></a> --}}

            <div class="row">
                <div class="col-sm-2">
                    <a href="{{ route('fabric.sale.print.view.bill',$findsale->id) }}" class="btn btn-primary" id="exportPdfButton">Print/PDF</a>
                </div>
                <div class="col-sm-2">
                    <button type="button" class="btn btn-primary" id="exportExcelButton">Export to Excel</button>
                </div>
            </div>

            <div class="row" id="printTable">
                <div class="col-12">
                    <div class="invoice p-3 mb-3 card  card-outline">

                        {{-- <div class="row invoice-info p-4" id="invoice-info">
                            <div class="col-sm-12 text-center mb-2">
                                <div><small>PASHUPATI SYNPACK INDUSTRIES PVT. LTD.</small></div>
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
                        </div> --}}
                        {{-- <div class="row" id="invoice-info">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td colspan="2" class="text-center"><small>PASHUPATI SYNPACK INDUSTRIES PVT. LTD.</small></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center"><small><b>SONAPUR,SUNSARI</b></small></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Party Name:</strong> {{ $findsale->getParty->name }}</td>
                                        <td><strong>A/C:</strong> {{ $findsale->getParty->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Invoice Number:</strong>  {{ $findsale->bill_no }} </td>
                                        <td><strong>Gate Pass:</strong> {{ $findsale->gp_no }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date:</strong> {{ $findsale->bill_date }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div> --}}
                        <div class="row" id="invoice-info">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered table-borderless">
                                    <tr>
                                        <td colspan="2" class="text-center"><small>PASHUPATI SYNPACK INDUSTRIES PVT. LTD.</small></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center"><small><b>SONAPUR,SUNSARI</b></small></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Party Name:</strong> {{ $findsale->getParty->name }}</td>
                                        <td><strong>A/C:</strong> {{ $findsale->getParty->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Invoice Number:</strong>  {{ $findsale->bill_no }} </td>
                                        <td><strong>Gate Pass:</strong> {{ $findsale->gp_no }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date:</strong> {{ $findsale->bill_date }}</td>
                                    </tr>
                                </table>
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
    <script src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>

    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/storein.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Attach a click event to the export button
            $("#exportExcelButton").click(function() {
                // Create a new div to hold the concatenated content
                var exportContent = $("<div>");

                // Append the content of the .invoice-info div to the exportContent
                exportContent.append($(".invoice-info").html());

                // Append the content of the #printTable div to the exportContent
                exportContent.append($("#printTable").html());

                // Specify the table to be exported (use an appropriate selector)
                exportContent.table2excel({
                    filename: "exported-table.xls" // Specify the file name
                });
            });
        });
    </script>
@endsection
