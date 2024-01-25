@extends('layouts.admin')

@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
    <style>
        .col-form-label {
            font-size: 12px !important;
        }

        th,
        td {
            text-align: center;
            padding: auto !important;
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
                    <a href="{{ route('fabric-stock.print.view.bill') }}" class="btn btn-primary"
                        id="exportPdfButton">Print/PDF</a>
                </div>
                <div class="col-sm-2">
                    <button type="button" class="btn btn-primary" id="exportExcelButton">Export to Excel</button>
                </div>
            </div>

            <div class="row" id="printTable">
                <div class="col-12">
                    <div class="invoice p-3 mb-3 card  card-outline">

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
                                        @foreach ($summaryData as $key => $data)
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
                // Specify the table to be exported (use an appropriate selector)
                $("#printTable").table2excel({
                    filename: "exported-table.xls" // Specify the file name
                });
            });
        });
    </script>
@endsection
