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
          
            <div class="content-header mb-4">
                <div class="row align-items-center">
                    <div class="col-sm-6 mt-2">
                     <h4><strong>Printing And Cutting Bag Stock</strong></h4>
                    </div>
        
                </div>
            </div>
          
           
            <div class="row" id="printTable">
                <div class="col-12">
                    <div class="invoice p-3 mb-3 card  card-outline">


                        <div class="row">
                            <div class="col-12 table-responsive">
                                @foreach ($formattedDatas as $name => $formattedData)
                                    <table class="table table-bordered" style="padding: 0 30px;">
                                        <tr>
                                            <th width="10px">{{ __('Sr.No') }}</th>
                                            <th width="10px">{{ __('Group') }}</th>
                                            <th width="10px">{{ __('Bag Brand') }}</th>
                                            <th width="10px">{{ __('Quantity Pieces') }}</th>
                                        </tr>
                                        <tbody>
                                            @php
                                                $totals = [
                                                    'qty_pieces' => 0,
                                                ]
                                            @endphp
                                            @foreach ($formattedData as $key => $data)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $data['group'] }}</td>
                                                    <td>{{ $data['bag_brand'] }}</td>
                                                    <td>{{ $data['quantity_piece'] }}</td>
                                                </tr>
                                                @php
                                                    $totals['qty_pieces'] += $data['quantity_piece'];
                                                @endphp
                                            @endforeach
                                            <tr style="font-weight:bold">
                                                <td>Total</td>
                                                <td></td>
                                                <td></td>
                                                <td>{{ $totals['qty_pieces'] }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endforeach
                        
                                <!-- Grand Total row outside the loop -->
                                <table class="table table-bordered" style="padding: 0 30px;">
                                    <tbody>
                                        <tr style="font-weight:bold">
                                            <td colspan="3">Grand Total</td>
                                            <td>{{ $totalQuantity }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        

                        {{-- <h3 class="m-0 text-center mt-4">SUMMARY</h3>

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
                        </div> --}}

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
                // Specify the table to be exported (use an appropriate selector)
                $("#printTable").table2excel({
                    filename: "exported-table.xls" // Specify the file name
                });
            });
        });

    </script>
@endsection
