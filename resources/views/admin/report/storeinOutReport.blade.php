@extends('layouts.admin')
@section('extra-style')
    <link href="{{ asset('css/nepaliDatePicker/nepali.datepicker.v4.0.1.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
    <style>
        /* Red background color */
        .bg-red {
            background-color: red;
            color: white;
            /* Optional: Change text color for better visibility */
        }

        /* Yellow background color */
        .bg-yellow {
            background-color: yellow;
        }

        /* Pink background color */
        .bg-pink {
            background-color: pink;
            color: white;
            /* Optional: Change text color for better visibility */
        }

        .table-green {
            background-color: green;
            color: white;
        }
    </style>
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Report') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('report') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <div class="content">
        <form action="{{ route('storeinStock.filter') }}">
            <div class="row m-2">
                <div class="col-md-4">
                    <label for="category">Category</label>
                    <select class="advance-select-box form-control" id="storeinCategory" name="storein_category">
                        <option value="" selected disabled>{{ __('Select Category') }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="department">Department</label>
                    <select class="advance-select-box form-control" id="storeinDepartment" name="storein_department">
                        {{-- <option value="" selected disabled>{{ __('Select Department') }}</option> --}}
                        {{-- @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name}}
                                    </option>
                                @endforeach --}}
                    </select>
                </div>

                <div class="col-md-4 mt-4">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </div>
        </form>
        <div class="container-fluid">
            {{-- <div class="card"> --}}
            {{-- <input type="text" id="nepaliDate" class="date-picker" placeholder="Select Nepali Date" /> --}}
            <div class="table-custom card-body table-responsive">
                <table class="table table-bordered table-hover" id="storeinOutReport">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Item Name</th>
                            <th>Opening Qty</th>
                            <th>Opening Rate</th>
                            <th>Opening Total</th>
                            <th>Purchase Qty</th>
                            <th>Purchase Rate</th>
                            <th>Purchase Total</th>
                            <th>Issued Qty</th>
                            <th>Issued Rate</th>
                            <th>Issued Total</th>
                            <th>Closing Qty</th>
                            <th>Closing Rate</th>
                            <th>Closing Total</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th colspan="2">Total:</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                    <tbody id="storeinOutReportTbody">

                    </tbody>
                </table>
            </div>
            {{-- </div> --}}
        </div>
    </div>
@endsection
@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>

    <script>
        // $(document).ready(function() {
        //     var table = $('#storeinOutReport').DataTable({
        //         lengthMenu: [
        //             [30, 40, 50, -1],
        //             ['30 rows', '40 rows', '50 rows', 'Show all']
        //         ],
        //         style: 'bootstrap', // Corrected 'style' option
        //         processing: true,
        //         serverSide: true,
        //         ajax: "{{ route('closingStoreinReport.yajraReport') }}",
        //         error: function(xhr, error, thrown) {
        //             console.log("Error fetching data:", error);
        //         },
        //         columns: [{
        //                 data: 'DT_RowIndex'
        //             },
        //             {
        //                 data: 'item_name'
        //             },
        //             {
        //                 data: 'opening_qty'
        //             },
        //             {
        //                 data: 'opening_rate'
        //             },
        //             {
        //                 data: 'opening_total'
        //             },
        //             {
        //                 data: 'purchase_qty'
        //             },
        //             {
        //                 data: 'purchase_rate'
        //             },
        //             {
        //                 data: 'purchase_total'
        //             },
        //             {
        //                 data: 'issue_qty'
        //             },
        //             {
        //                 data: 'issue_rate'
        //             },
        //             {
        //                 data: 'issue_total'
        //             },
        //             {
        //                 data: 'closing_qty'
        //             },
        //             {
        //                 data: 'closing_rate'
        //             },
        //             {
        //                 data: 'closing_total'
        //             }
        //         ],



        //     });
        // });

        $('#storeinCategory').on('select2:select', function(e) {
            let category_id = e.params.data.id;
            // let click_by=blade;
            getCategoryDepartment(category_id);
        });

        function getCategoryDepartment(category_id) {
            return new Promise(function(resolve, reject) {
                $.ajax({
                    url: "{{ route('storeinStock.getCategoryDepartment', ['category_id' => ':Replaced']) }}"
                        .replace(
                            ':Replaced',
                            category_id),

                    method: 'GET',
                    success: function(response) {
                        console.log('ajax item: ', response);
                        let selectOptions = '';

                        if (response.length == 0) {
                            selectOptions += '<option disabled selected>' + 'no department found' +
                                '</option>';
                        } else {
                            selectOptions += '<option disabled selected>' + 'select department' +
                                '</option>';

                            for (let i = 0; i < response.length; i++) {
                                let optionText = response[i].name;
                                let optionValue = response[i].id;
                                let option = new Option(optionText, optionValue);
                                selectOptions += option.outerHTML;
                            }
                        }
                        $('#storeinDepartment').html(selectOptions);
                        resolve(response);

                    },
                    error: function(xhr, status, error) {
                        reject(error);
                    }
                });
            });
        }
        $(document).ready(function() {
            let openingTotal = 0;
            let purchaseTotal = 0;
            let issueTotal = 0;
            let closingTotal = 0;

            let table = $('#storeinOutReport').DataTable({
                lengthMenu: [
                    [30, 40, 50, -1],
                    ['30 rows', '40 rows', '50 rows', 'Show all']
                ],
                style: 'bootstrap',
                processing: true,
                serverSide: true,
                ajax: "{{ route('closingStoreinReport.yajraReport') }}",
                error: function(xhr, error, thrown) {
                    console.log("Error fetching data:", error);
                },
                columns: [{
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'item_name'
                    },
                    {
                        data: 'opening_qty'
                    },
                    {
                        data: 'opening_rate'
                    },
                    {
                        data: 'opening_total'
                    },
                    {
                        data: 'purchase_qty'
                    },
                    {
                        data: 'purchase_rate'
                    },
                    {
                        data: 'purchase_total'
                    },
                    {
                        data: 'issue_qty'
                    },
                    {
                        data: 'issue_rate'
                    },
                    {
                        data: 'issue_total'
                    },
                    {
                        data: 'closing_qty'
                    },
                    {
                        data: 'closing_rate'
                    },
                    {
                        data: 'closing_total'
                    }
                ],
                drawCallback: function(settings) {
                    openingTotal = 0;
                    purchaseTotal = 0;
                    closingTotal = 0;
                    issueTotal = 0;

                    var api = this.api();
                    api.rows({
                        page: 'current'
                    }).every(function() {
                        var data = this.data();
                        openingTotal += parseFloat(data.opening_total);
                        purchaseTotal += parseFloat(data.purchase_total);
                        issueTotal += parseFloat(data.issue_total);
                        closingTotal += parseFloat(data.closing_total);
                    });

                    $(this).find('tfoot th').eq(3).html(openingTotal.toFixed(2));
                    $(this).find('tfoot th').eq(6).html(purchaseTotal.toFixed(2));
                    $(this).find('tfoot th').eq(9).html(issueTotal.toFixed(2));
                    $(this).find('tfoot th').eq(12).html(closingTotal.toFixed(2));
                },
                createdRow: function(row, data, dataIndex) {
                    // Apply red color to Opening Qty, Opening Rate, and Opening Total columns

                    $('td', row).eq(1).addClass('table-green'); // Opening Qty
                    $('td', row).eq(0).addClass('table-green'); // Opening Qty

                    $('td', row).eq(2).addClass('bg-red'); // Opening Qty
                    $('td', row).eq(3).addClass('bg-red'); // Opening Rate
                    $('td', row).eq(4).addClass('bg-red'); // Opening Total

                    // Apply yellow color to Purchase Qty, Purchase Rate, and Purchase Total columns
                    $('td', row).eq(5).addClass('bg-yellow'); // Purchase Qty
                    $('td', row).eq(6).addClass('bg-yellow'); // Purchase Rate
                    $('td', row).eq(7).addClass('bg-yellow'); // Purchase Total

                    // Apply pink color to Issued Qty, Issued Rate, and Issued Total columns
                    $('td', row).eq(8).addClass('bg-pink'); // Issued Qty
                    $('td', row).eq(9).addClass('bg-pink'); // Issued Rate
                    $('td', row).eq(10).addClass('bg-pink'); // Issued Total

                    $('td', row).eq(11).addClass('bg-success'); // Issued Qty
                    $('td', row).eq(12).addClass('bg-success'); // Issued Rate
                    $('td', row).eq(13).addClass('bg-success'); // Issued Total
                }
            });
        });
    </script>
@endsection
