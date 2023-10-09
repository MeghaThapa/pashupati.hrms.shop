@extends('layouts.admin')

@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"
        integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ asset('css/nepaliDatePicker/nepali.datepicker.v4.0.1.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .update_status {
            cursor: pointer;
        }

        .invalid {
            border-color: red;
        }

        .valid {
            border-color: green;
        }
    </style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6 mt-2">
                <h4><strong>Bag Fabric Receive Item Sent Stock</strong></h4>
            </div>

        </div>
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">


            <div class="card">

                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="myTable">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Fabric</th>
                                <th>Gram</th>
                                <th>Gross Weight</th>
                                <th>Net Weight</th>
                                <th>Meter</th>
                                <th>roll_no</th>
                                <th>loom_no</th>
                                <th>average</th>
                                <th>status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-script')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/nepaliDatePicker/nepali.datepicker.v4.0.1.min.js') }}"></script>
    <script>
        $(".select2").select2()
    </script>
    @if (session()->has('message'))
        <script>
            toastr.success('{{ session()->get('message') }}');
        </script>
    @endif
    <script>
        $(document).ready(function() {

            let table = $("#myTable").DataTable({
                serverSide: true,
                processing: true,
                lengthMenu: [
                    [10, 25, 50, 100, 250, 500],
                    [10, 25, 50, 100, 250, 500]
                ],
                ajax: {
                    url: "{{ route('bagFabricReceiveItemSentStock.index') }}",
                    data: function(data) {
                        // data.start_date = $('#start_date').val();
                        // data.end_date = $('#end_date').val();
                    },
                },
                columns: [{
                        name: "DT_RowIndex",
                        data: "DT_RowIndex",
                        orderable: false,
                    },
                    {
                        name: "fabric_id",
                        data: "fabric_id"
                    },
                    {
                        name: "gram",
                        data: "gram"
                    },
                    {
                        name: "gross_wt",
                        data: "gross_wt"
                    },
                    {
                        name: "net_wt",
                        data: "net_wt"
                    },
                    {
                        name: "meter",
                        data: "meter"
                    },
                    {
                        name: "roll_no",
                        data: "roll_no"
                    },
                    {
                        name: "loom_no",
                        data: "loom_no"
                    },
                    {
                        name: "average",
                        data: "average"
                    },
                    {
                        name: "status",
                        data: "status"
                    },

                ]
            });

            // $(document).ready(function() {
            //     $('#delivery_order_for_item_id').on('change', function() {
            //         var selectedOptionText = $(this).find('option:selected').text().trim();
            //         var unitNameInput = $('#unit_name');

            //         if (['PP Woven', 'PP Non Woven', 'PP/HDPE Tripal', 'RP Granuels',
            //                 'PP/CC/Other Granuels', 'Wastage'
            //             ].includes(selectedOptionText)) {
            //             unitNameInput.val('Kgs');
            //         } else if (['PP Bags (Unlam)', 'PP Bags (Lam)'].includes(selectedOptionText)) {
            //             unitNameInput.val('Pcs');
            //         }
            //     });
            // });

        })
        // function isValidDecimal(input) {
        //     const decimalPattern = /^\d+(\.\d+)?$/;
        //     return decimalPattern.test(input);
        // }

        // $(document).on('keyup', '.decimal_number', function() {
        //     const inputValue = $(this).val();
        //     if (isValidDecimal(inputValue)) {
        //         $(this).removeClass('invalid').addClass('valid');
        //     } else {
        //         $(this).removeClass('valid').addClass('invalid');
        //     }
        // });
    </script>
@endsection
