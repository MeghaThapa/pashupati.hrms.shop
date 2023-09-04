@extends('layouts.admin')
@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
@endsection
@section('content')
   
    <div style="display: flex;
    justify-content: space-evenly;
    flex-direction: column;
    align-items: center;">

        <img class="lg-logo" src="{{ $settings->logo }}" alt="{{ $settings->companyName }}" width="250" height="50">
        <h3>FinalTripal Stock</h3>
    </div>
    <div class="row">
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
    </div>

    <div class="row">
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
    </div>
@endsection
@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script>
        $("body").on("click", "#fabricStockSearch", function(event){
          searchFunction();
        });
        function searchFunction(){
          var name = $('#name').val();
          var token = $('meta[name="csrf-token"]').attr('content');
          debugger;


          $.ajax({
            type:"GET",
            dataType:"html",
            url: "{{route('finaltripal-stock.filterStock')}}",
            data: {
              _token: token,
              name: name,
            },
            success:function(response){
              $('#replaceTable').html("");
              $('#replaceTable').html(response);
            },
            error: function (e) {
              alert('Sorry! we cannot load data this time');
              return false;
            }
          });
        }
    </script>
@endsection
