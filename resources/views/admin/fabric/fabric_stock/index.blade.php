@extends('layouts.admin')

@section('extra-style')

    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />

    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />

@endsection

@section('content')

    <div class="row">

        <div class="col-md-2" style="display:flex;gap:10px; width:400px;justify-content: center;align-item:center;">

            <label for="">Godam</label>

            <select class="advance-select-box form-control" id="godam_id" name="godam_id">

                <option value="" selected disabled>{{ __('Select Godam') }}</option>

                @foreach ($godams as $godam)

                <option value="{{ $godam->id }}">{{ $godam->name }}</option>

                @endforeach

            </select>

        </div>

        <div class="col-md-3" style="display:flex;gap:10px; width:400px;justify-content: center;align-item:center;">

            <label for="">Name</label>

            <select class="advance-select-box form-control" id="fabric_id" name="fabric_id">

                <option value="" selected disabled>{{ __('Select Fabric') }}</option>

            </select>

        </div>

        <div class="col-md-2" style="display:flex;gap:10px; width:400px;justify-content: center;align-item:center;">

            <label for="">Type</label>

            <select class="advance-select-box form-control" id="type" name="type">

                <option value="" selected disabled>{{ __('Select Type') }}</option>

                <option value="true">Lam</option>

                <option value="false">UnLam</option>

                {{-- <option value="3">Opening</option> --}}



            </select>

        </div>

        <div class="col-md-3" style="display:flex;gap:10px; width:400px;justify-content: center;align-item:center;">

            <label for="">Group</label>

            <select class="advance-select-box form-control" id="fabricgroup_id" name="fabricgroup_id">

                <option value="" selected disabled>{{ __('Select Group') }}</option>

                @foreach ($fabricgroups as $group)

                <option value="{{ $group->id }}">{{ $group->name }}</option>

                @endforeach

            </select>

        </div>

        <div class="col-md-1">

            <form action="{{route('fabric-stock.viewBill')}}" method="post">

                @csrf

                <input type="hidden" name="godam_id" id="godamm_id" >

                <input type="hidden" name="name" id="name_data">

                <input type="hidden" name="type" id="type_data">

                <input type="hidden" name="group" id="group_data">

                <button class="btn btn-primary" style="width:150px"  id="">View Bill</button>

            </form>



        </div>







    </div>





    <div class="row" id="replaceTable">

        <div class="col-md-12">

            <div class="p-0 table-responsive table-custom my-3">

                <table class="table" >

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

                                    <td>{{ $stock->name }}</td>

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

        $("#godam_id").change(function(e){



            let department_id =  $(this).val();

            let geturl = "{{ route('fabricSendReceive.get.planttype',['id'=>':id']) }}"

            $("#godam_ids").val(department_id);

            $("#godamm_id").val(department_id);

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



        $("#fabric_id").change(function(e){

            let department_id =  $(this).val();

            $("#name_data").val(department_id);

        });



        $("#type").change(function(e){

            // debugger;

            let type =  $(this).val();

            $("#type_data").val(type);

        });



        $("#fabricgroup_id").change(function(e){

            let group =  $(this).val();

            $("#group_data").val(group);

        });







        function addfabrictype(data){

            $("#fabric_id").empty();

            $('#fabric_id').append(`<option value="" disabled selected>--Select Fabric--</option>`);

            data.godamfabrics.forEach( d => {

                $('#fabric_id').append(`<option value="${d.id}">${d.name}</option>`);

            });

        }



    </script>



    <script type="text/javascript">

      $("body").on("click", "#fabricStockSearch", function(event){

        searchFunction();

      });



      $("body").on("click", "#fabricStockviewBill", function(event){

        viewBill();

      });



      function viewBill(){

        var godam_id = $('#godam_id').val(),

        fabric_id = $('#fabric_id').val(),

        fabricgroup_id = $('#fabricgroup_id').val(),

        type = $('#type').val();

        var token = $('meta[name="csrf-token"]').attr('content');





        $.ajax({

          type:"GET",

          dataType:"html",

          url: "{{route('fabric-stock.viewBill')}}",

          data: {

            _token: token,

            godam_id: godam_id,

            fabric_id: fabric_id,

            type: type,

            fabricgroup_id: fabricgroup_id,

          },

          // success:function(response){

          //   // $('#replaceTable').html("");

          //   // $('#replaceTable').html(response);

          // },

          // error: function (e) {

          //   alert('Sorry! we cannot load data this time');

          //   return false;

          // }

        });

      }

      $("body").on("click", "#replaceTable a.page-link", function(event){

        $.get($(this).attr('href'),function(data){

          $('#replaceTable').html("");

          $('#replaceTable').html(data);

        })

        return false;

      });



      function searchFunction(){

        var godam_id = $('#godam_id').val(),

        fabric_id = $('#fabric_id').val(),

        fabricgroup_id = $('#fabricgroup_id').val(),

        type = $('#type').val();

        var token = $('meta[name="csrf-token"]').attr('content');





        $.ajax({

          type:"GET",

          dataType:"html",

          url: "{{route('fabric-stock.filterStock')}}",

          data: {

            _token: token,

            godam_id: godam_id,

            fabric_id: fabric_id,

            type: type,

            fabricgroup_id: fabricgroup_id,

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

      $("body").on("click", "#replaceTable a.page-link", function(event){

        $.get($(this).attr('href'),function(data){

          $('#replaceTable').html("");

          $('#replaceTable').html(data);

        })

        return false;

      });

      $('#reset').click(function(){

        $('#shift_data').val('');

        $('#class_data').val('');

        $('#section_data').val('');

        $('#search_data').val('');

      });



    </script>



@endsection

