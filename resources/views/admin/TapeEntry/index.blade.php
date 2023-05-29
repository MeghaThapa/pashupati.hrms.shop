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

        label{
            font-weight:lighter;
            color:rgba(0,0,0,0.8);
        }

        /* .select2-selection {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            width:150px !important;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        } */
    </style>
@endsection

@section('content')
 <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6 mt-2">
                <h4 ><strong>Tape Receive Entry</strong></h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Tape Entry') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            {{-- <div class="card">  --}}

                {{-- <div class="card-body" > --}}
                    <form id="tape_entry_form" action="{{ route('tape.entry.stock.store') }}" method="post">
                        @csrf
                       {{--<div class='row mt-2'>
                          <div class="col-md-4">
                               <label for="tape_receive_date">Tape Receive Date</label>
                               <input type="date" name="tape_receive_date" class="form-control" value="{{ date("Y-m-d") }}"/>
                          </div>
                          <div class="col-md-4">
                               <label for="receipt_no">Receipt Number</label>
                               <input type="text" name="receipt_number" id="receipt_number_1" class="form-control" value="{{ 'TR'.'-'.getNepaliDate(date('Y-m-d')) }}"/> {{-- value 'TR'."-".rand(0,999).'-'.rand(0,99999)  
                                                      </div>  
                          <div class="col-md-4">
                               <label for="receipt_no"></label>
                               <button class="btn btn-primary">Search For Edit</button>
                          </div>
                       </div> --}}
                       <hr>
                       <div class='row mt-2'>
                          <div class="col-md-4">
                               <label for="tape_receive_date">Tape Date</label>
                               <input type="date" name="tape_receive_date" class="form-control" value="{{ date("Y-m-d") }}" required/>
                          </div>
                          <div class="col-md-4">
                               <label for="receipt_no">Receipt Number</label>
                               <input type="text" value="{{ 'TR'.'-'.getNepaliDate(date('Y-m-d')) }}" id="receipt_number_1_repeat" name="receipt_number" class="form-control" required/>
                          </div>
                          <div class="col-md-4">
                               <label for="receipt_no">To Godam</label>
                                <select class="form-control select2 advance-select-box" name="togodam" id="togodam" required>  {{-- select2 advance-select-box --}}
                                    <option>Select Godam/Department</option>
                                    @foreach($department as $data)
                                        @php
                                            $currentDepartment = $data->fromGodam->department;
                                        @endphp
                                
                                        @if ($loop->first || $currentDepartment !== $previousDepartment)
                                            <option value="{{ $data->from_godam_id }}">{{ $currentDepartment }}</option>
                                        @endif
                                
                                        @php
                                            $previousDepartment = $currentDepartment;
                                        @endphp
                                    @endforeach
                                </select>
                          </div>
                       </div>
                       
                       <div class='row mt-2'>
                            <div class="col-md-4">
                                <label for="receipt_no">Plant Type</label>
                                <select class="form-control select2 advance-select-box" name="planttype" id="planttype" required> {{--  advance-select-box --}}
                                    {{-- @foreach($planttype as $data)
                                        <option value="{{ $data->id }}">{{ $data->name }}</option>
                                    @endforeach --}}
                                </select>
                            </div>
                            <div class="col-md-4">
                               <label for="receipt_no">Plant Name</label>
                               <select class="form-control select2 advance-select-box" name="plantname" id="plantname" required> {{-- advance-select-box --}}
                                    {{-- @foreach($plantname as $data)
                                        <option value="{{ $data->id }}">{{ $data->name }}</option>
                                    @endforeach --}}
                                </select>
                            </div>
                            <div class="col-md-4">
                               <label for="receipt_no">Plant Shift</label>
                               <select class="form-control select2 advance-select-box" name="shift" id="shift" required> {{--   select2 advance-select-box --}}
                                    @foreach($shift as $data)
                                        @php
                                            $currentDepartment = $data->shift->name;
                                        @endphp
                                
                                        @if ($loop->first || $currentDepartment !== $previousDepartment)
                                            <option value="{{ $data->shift_id }}">{{ $currentDepartment }}</option>
                                        @endif
                                
                                        @php
                                            $previousDepartment = $currentDepartment;
                                        @endphp
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label for="tape type">Tape Type</label>
                                <select name="tapetype" id="tapetype" name="tapetype" class='advance-select-box select2' required>
                                    <option> select type</option>
                                    <option value="tape1">Tape1</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="">Tape Quantity In Kg</label>
                                <input type="text" name='tape_qty_in_kg' id='tape_qty_in_kg' class="form-control" placeholder="Enter Amount In Kg" required>
                            </div>
                        </div>
                        
                        <div class='row mt-2'>
                            <div class="col-md-3">
                                <label for="receipt_no">Total In Kgs</label>
                                <input type="text" class="form-control" name="total_in_kg" id="total_in_kg" disabled required/>
                            </div>
                            <div class="col-md-3">
                                <label for="receipt_no">Loading</label>
                                <input type="text" class="form-control" name="loading" id="loading" disabled required/>
                            </div>
                            <div class="col-md-3">
                                <label for="receipt_no">Running</label>
                                <input type="text" class="form-control" name="running" id="running" disabled required/>
                            </div>
                            <div class="col-md-3">
                                <label for="receipt_no">Bypass wast</label>
                                <input type="text" class="form-control" name="bypass_wast" id="bypass_wast" disabled required/>
                            </div>
                        </div>
                        <hr>
                        <div class='row mt-3'>
                            <div class="col-md-4">
                                <div>
                                    <label for="receipt_no">Dana Name</label>
                                    <select class="form-control select2 advance-select-box" id="dana"> {{-- select2 advance-select-box --}}
                                         {{-- @foreach($dananame as $data)
                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                                        @endforeach --}}
                                    </select>
                                </div>
                                <div class="mt-2">
                                    <label for="receipt_no">Quantity in Kg</label>
                                    <input type="text" class="form-control" name="qty_in_kg" id='dana_quantity'/>
                                    <button class="btn btn-primary float-right mt-2" id="add_new_dana_to_autoloader_stock">Add</button>
                            </div>
                                </div>
                            <div class="col-md-8">
                                <div class="card">
                                    <table class="table table-bordered" id="tape_entry_dana_table" style="background:rgba(241, 214, 147,0.2)">
                                        <thead class="table-warning">
                                            <tr>
                                                <th style="width:100px">Sr No</th>
                                                <th>Dana Name</th>
                                                <th>Dana Qty</th>
                                            </tr>
                                        </thead>
                                        {{-- <tbody>
                                            <tr>
                                                <th>1</th>
                                                <th>dana name</th>
                                                <th>dana qty</th>
                                            </tr>
                                        </tbody> --}}
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row text-center d-flex justify-content-center">
                            <div class="col-md-3">
                                <label>Total Row/Dana in Kg</label>
                                <input type="text" class="form-control" name="dana_in_kg" id="dana_in_kg" disabled required/>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" id="btn-update" class="btn btn-info" disabled>Update</button>
                                <button id="btn-view" class=" btn btn-primary">View</button>
                                <button id="btn-close" class=" btn btn-danger">close</button>
                            </div>
                        </div>
                        <hr>
                    </form>
                {{--</div>--}}  <!-- card-body -->
             {{--</div>--}} <!-- card -->
        </div>
    </div>
@push('scripts')
    <script>
        $(document).ready(function(){
            // let receipt_number = $('#receipt_number_1').val();
            // let receipt_number_repeat = $('#receipt_number_1_repeat').val(receipt_number);
        });
    </script>
    <script>
        $(document).ready(function(){
            //godam
            $(document).on('change click','#togodam',function(){
                getgodam();
                $('#plantname').empty();
                $('#shift').empty();
                $('#tape_dana_tbody').empty();
                $('#dana').empty();
                revertdisables();
            });

            /***************** AJAX *******************************/
            //for plant name
            $(document).on("change click",'#planttype',function(e){ 
                // $("#planttype").on("select2:select",function(e){ 
                $.ajax({ 
                    url:"{{ route('tape.entry.ajax.plantname',['planttype_id'=>':id']) }}".replace(':id',e.target.value),
                    beforeSend:function(){
                        console.log("Getting Plant Name");
                    },
                    success:function(response){
                        // console.log(response);
                        plantname(response);
                    },
                    error:function(error){
                        console.log(error);
                    }
                });
            });
            
            $(document).on('change click','#plantname', function(e){
                $.ajax({
                    url : "{{ route('tape.entry.ajax.shift',['plantname_id'=>':id']) }}".replace(':id',e.target.value),
                    beforeSend:function(){
                        console.log("Getting Plant Name");
                    },
                    success:function(response){
                        console.log(response);
                        // plantname(response);
                        $('#shift').empty();
                        shift(response);
                    },
                    error:function(error){
                        console.log(error);
                    }
                });
            });


            $(document).on("change click",'#shift',function(e){
                $("#dana").empty();
                let department = $('#togodam').val();
                let planttype = $('#planttype').val();
                let plantname = $('#plantname').val();
                let shift = $('#shift').val();
                let csrftoken = $('meta[name="csrf-token"]').attr('content');
                console.log(department,planttype,plantname,shift);
                $.ajax({
                    url:"{{ route('tape.entry.ajax.get.danainfo') }}",
                    method : 'POST',
                    data:{
                        '_token' : csrftoken,
                        'shift' : shift,
                        'department' : department,
                        'plantname' : plantname,
                        'planttype' : planttype
                    },
                    beforeSend:function(){
                        console.log('getting dana info');
                    },
                    success:function(response){
                        console.log(response);
                        danaandquantity(response);
                        disabledstuffs();
                    },
                    error:function(error){
                        console.log(error);
                    }
                });
            });
            
            /***************** AJAX *******************************/

            /********************* ajax functions *********************/
            //for godam and planttype
            function getgodam(){
                let geturl = "{{ route('tape.entry.ajax.planttype',['department_id'=>':department_id']) }}";
                finalurl = geturl.replace(':department_id',$('#togodam').val());
                $.ajax({
                    url:finalurl,
                    method:"GET",
                    beforeSend:function(){
                        console.log("ajax fired");
                    },
                    success:function(response){
                        planttype(response);
                    },
                    error:function(error){
                        console.log(response);
                    }
                });
            }

            //planttype
            function planttype(response){
                // console.log(response);
                $('#planttype').empty();
                    $('<option></option>').text("Select Plant Type").appendTo('#planttype');
                response.planttype.forEach(data => {
                    if(data.name == "Tape Plant"){
                        $('<option></option>').attr('value', `${data.id}`).text(`${data.name}`).appendTo('#planttype');
                    }
                });
            }
            
            //for plant name
            function plantname(data){
                $('#plantname').empty();
                $('<option></option>').text("Select Plant Name").appendTo('#plantname');
                data.plantname.forEach(resp => {
                    $('<option></option>').attr('value', `${resp.id}`).text(`${resp.name}`).appendTo('#plantname');
                });
            }
            
            //for shift
            function shift(data){
                console.log(data);
                $('<option></option>').text("Select Type").appendTo('#shift');
                data.shift.forEach(d => {
                    $('<option></option>').attr('value', `${d.id}`).text(`${d.name}`).appendTo('#shift');
                });
            }

            //for dana and quantity
            function danaandquantity(response){
                let tbody = $('<tbody></tbody>').appendTo('#tape_entry_dana_table');
                tbody.attr({
                    'id':'tape_dana_tbody',
                })
                let a = 1;
                response.data.forEach(d=>{
                    let tr = $('<tr></tr>').appendTo(tbody);
                    $('<option></option>').attr('value', `${d.id}`).text(`${d.dana_name.name}`).appendTo('#dana');
                    $('<td></td>').text(a++).appendTo(tr);
                    $('<td></td>').text(`${d.dana_name.name}`).appendTo(tr);
                    $('<td></td>').text(`${d.quantity}`).appendTo(tr);
                });
                $('#dana_in_kg').attr({
                    'value': response.total_quantity
                })
            }

            function disabledstuffs(){
                $('#total_in_kg').prop('disabled',false);
                $('#loading').prop('disabled',false);
                $('#running').prop('disabled',false);
                $('#bypass_wast').prop('disabled',false);
                $('#btn-update').prop('disabled',false);
                $('#dana_in_kg').prop('disabled',false);
            }

            function revertdisables(){
                $('#total_in_kg').prop('disabled',true);
                $('#loading').prop('disabled',true);
                $('#running').prop('disabled',true);
                $('#bypass_wast').prop('disabled',true);
                $('#dana_in_kg').prop('disabled',true);
            }

            /********************* ajax functions *********************/

            $("#add_new_dana_to_autoloader_stock").click(function(e){
                e.preventDefault();
            });

            //form condition checks
            $('#tape_entry_form').submit(function(e){
                let total = parseFloat($('#total_in_kg').val());
                let loading = parseFloat($('#loading').val());
                let running = parseFloat($('#running').val());
                let bypass = parseFloat($('#bypass_wast').val());
                let dana_in_kg = parseFloat($('#dana_in_kg').val());

                $('#btn-update').prop('disabled',false);

                let final = total + loading + running + bypass;
                if(final != dana_in_kg){
                    e.preventDefault();
                    alert('not equal');
                }
                else{
                    $('#tape_entry_form').submit();
                }
            });

        });
    </script>

@endpush
@endsection

@section('extra-script')
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
@endsection
