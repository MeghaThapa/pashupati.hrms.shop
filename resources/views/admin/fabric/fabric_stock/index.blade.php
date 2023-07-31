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
        <h3>Fabric Stock</h3>
    </div>
 <form action="{{ route('fabric-stock.filterStock') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-3" style="display:flex;gap:10px; width:400px;justify-content: center;align-item:center;">
                <label for="">Godam</label>
                <select class="advance-select-box form-control" id="toGodam" name="godam_id">
                    <option value="" selected disabled>{{ __('Select Godam') }}</option>
                    @foreach ($godams as $godam)
                        <option value="{{ $godam->id }}">{{ $godam->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3" style="display:flex;gap:10px; width:400px;justify-content: center;align-item:center;">
                <label for="">Type</label>
                <select class="advance-select-box form-control" id="type" name="type">
                    <option value="" selected disabled>{{ __('Select Type') }}</option>
                    <option value="true">Lam</option>
                    <option value="false">UnLam</option>
                    {{-- <option value="3">Opening</option> --}}

                </select>

            </div>

            <div class="col-md-3" style="display:flex;gap:10px; width:400px;justify-content: center;align-item:center;">
                <label for="">Name</label>

                <select class="advance-select-box form-control" id="fabricNameId" name="name">
                    <option value="" selected disabled>{{ __('Select Fabric') }}</option>

                </select>


            </div>
            
            <div class="col-md-3">
                <button class="btn btn-primary" style="width:200px" type="submit">Show Report</button>

            </div>
        </div>
    </form> 

    <div class="row">
        <div class="col-md-12">
            <div class="p-0 table-responsive table-custom my-3">
                <table class="table" id="rawMaterialStockTable">
                    <thead>
                        <tr>
                            <th>{{ __('S.No') }}</th>
                            <th>{{ __('Roll Number') }}</th>
                            <th>{{ __('Loom Number') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Gross Weight') }}</th>
                            <th>{{ __('Net Weight') }}</th>
                            <th>{{ __('Meter') }}</th>
                            <th>{{ __('Average Weight') }}</th>
                            <th>{{ __('Gram Weight') }}</th>
                            <th>{{ __('Bill Number') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if ($fabric_stock)
                            @foreach ($fabric_stock as $i => $stock)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                     <td>{{ $stock->roll_no }}</td>
                                     <td>{{ $stock->loom_no }}</td>
                                    <td>{{ $stock->name }} ({{$stock->fabricgroup->name}})</td>
                                     <td>{{ $stock->gross_wt }}</td>
                                     <td>{{ $stock->net_wt }}</td>
                                     <td>{{ $stock->meter }}</td>
                                     <td>{{round($stock->average_wt, 2)}}</td>

                                     <td>{{ round((round($stock->average_wt, 2) /  (int) filter_var($stock->name, FILTER_SANITIZE_NUMBER_INT) ),2) }}</td>

                                   
                                     <td>{{ $stock->bill_no }}</td>
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
            @if ($fabric_stock)
                {{ $fabric_stock->links() }}
            @endif 
        </div>
    </div>
@endsection
@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script>
        $("#toGodam").change(function(e){

            let department_id =  $(this).val();
            let geturl = "{{ route('fabricSendReceive.get.planttype',['id'=>':id']) }}"
            $("#godam_ids").val(department_id);
            $.ajax({
                url:geturl.replace(':id',department_id),
                beforeSend:function(){
                    console.log('Getting Plant type');
                },
                success:function(response){
                    addfabrictype(response);
                },
                error:function(error){
                    console.log(error);
                }
            });
        });

        function addfabrictype(data){
            $("#fabricNameId").empty();
            $('#fabricNameId').append(`<option value="" disabled selected>--Select Fabric--</option>`);
            data.godamfabrics.forEach( d => {
                $('#fabricNameId').append(`<option value="${d.id}">${d.name}</option>`);
            });
        }

    </script>

@endsection
