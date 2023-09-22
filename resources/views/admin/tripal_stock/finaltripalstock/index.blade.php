@extends('layouts.admin')
@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/nepaliDatePicker/nepali.datepicker.v4.0.1.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
   
    <div style="display: flex;
    justify-content: space-evenly;
    flex-direction: column;
    align-items: center;">

        <img class="lg-logo" src="{{ $settings->logo }}" alt="{{ $settings->companyName }}" width="250" height="50">
        <h3>FinalTripal Stock</h3>
    </div>
    {{-- <div class="row">
        <div class="col-md-5" style="display:flex;gap:10px; width:400px;justify-content: center;align-item:center;">
            <label for="">Name</label>
            <select class="advance-select-box form-control" id="name" name="name">
                <option value="" selected disabled>{{ __('Select Name') }}</option>
                <option value="all">All FinalStocks</option>
                @foreach ($finaltripalname as $data)
                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                @endforeach
            </select>
        </div>
        
        
        <div class="col-md-3">
            <button class="btn btn-primary" style="width:200px" type="submit" id="fabricStockSearch">Show Report</button>

        </div>
    </div> --}}

   {{--  <div class="row">
        <div class="col-md-12">
            <div class="p-0 table-responsive table-custom my-3">
                <table class="table" id="replaceTable">
                    <thead>
                        <tr>
                            <th>{{ __('S.No') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Roll') }}</th>
                            <th>{{ __('Net Weight') }}</th>
                            <th>{{ __('Gross Weight') }}</th>
                            <th>{{ __('Meter') }}</th>
                            <th>{{ __('Average Weight') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if ($finaltripal)
                            @foreach ($finaltripal as $i => $stock)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $stock->name }}</td>
                                    <td>{{ $stock->roll_no }}</td>
                                    <td>{{ $stock->net_wt}}</td>
                                    <td>{{ $stock->gross_wt }}</td>
                                    <td>{{ $stock->meter }}</td>
                                    <td>{{ $stock->average_wt }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>

                    <tfoot>
                      <tr>
                        <th>Total:</th>
                      
                        <td class="text-right">{{ $sum}}</td>
                      </tr>
                      
                    </tfoot>

                </table>
            </div>
            @if ($finaltripal)
                {{ $finaltripal->links() }}
            @endif 
        </div>
    </div> --}}

    <div class="row">
        <div class="col-sm-3">
            <label for="tripalName">Select Name</label>
            <select class="advance-select-box form-control"  id="tripalName">
                <option value="" selected disabled>{{ __('Select Tripal Name') }}</option>
                @foreach ($finaltripalname as $data)
                    <option value="{{ $data->id }}">{{ $data->name }}
                    </option>
                @endforeach 
            </select>
        </div>
        
        <div class="col-sm-3">
            <label for="godamID">Select Godam</label>
            <select class="form-control" id="godamID">
                <option value="" selected disabled>{{ __('Select Godam Name') }}</option>
                @foreach ($godams as $data)
                    <option value="{{ $data->id }}">{{ $data->name }}
                    </option>
                @endforeach 
            </select>
        </div>
    </div>

    <div class="p-0 table-responsive table-custom my-3">
        <table class="table" id="fabricTable">
            <thead>
                <tr>
                    <th>@lang('#')</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Roll') }}</th>
                    <th>{{ __('Net') }}</th>
                    <th>{{ __('Gross') }}</th>
                    <th>{{ __('Meter') }}</th>
                    <th>{{ __('Average Weight') }}</th>

                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>

                </tr>
            </tfoot>
        </table>
    </div> 

    
@endsection
@section('extra-script')
<script src="{{ asset('js/nepaliDatePicker/nepali.datepicker.v4.0.1.min.js') }}"></script>
<script src="{{ asset('js/select2/select2.min.js') }}"></script>


<script type="text/javascript">
      

        $(document).ready(function() {
            var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");

            var table = $("#fabricTable").DataTable({
                serverSide: true,

                processing: true,
                ajax: {
                    url: "{{ route('finaltripal.getFilterList') }}",
                    data: function(data) {
                        data.tripalName = $('#tripalName').val();
                        data.godam_id = $('#godamID').val();
                    },
                },
                columns: [{
                        name: "DT_RowIndex",
                        data: "DT_RowIndex",
                        orderable: false,
                        searchable: false
                    },
                    {
                        name: "name",
                        data: "name"
                    },
                    {
                        name: "roll_no",
                        data: "roll_no",
                         searchable: true,
                    },
                    {
                        name: "net_wt",
                        data: "net_wt"
                    },
                    {
                        name: "gross_wt",
                        data: "gross_wt"
                    },
                    {
                        name: "meter",
                        data: "meter"
                    },
                    {
                        name: "average_wt",
                        data: "average_wt"
                    },


                ],
                footerCallback: function(tfoot, data, start, end, display) {
                    var api = this.api();
                    var totalNetweightSum = api.ajax.json()
                    .total_netweight_sum; // Get the sum from the JSON response
                    $(api.column(4).footer()).html('Total: ' + totalNetweightSum);
                },
                drawCallback: function(settings) {
                    var api = this.api();
                    var totalNetweightSum = api.ajax.json()
                    .total_netweight_sum; // Get the sum from the JSON response
                    $(api.column(4).footer()).html('Total: ' + totalNetweightSum);
                }
            });


            $('#tripalName , #godamID').on('change', function() {
                // debugger;
                table.draw(); // Redraw the table
            });
        });
    </script>
    
@endsection
