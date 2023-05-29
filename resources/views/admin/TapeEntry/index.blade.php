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
            <div class="col-sm-6">
                <h4 ><strong>Modify Tape Receive Entry</strong></h4>
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
                    <form>
                        @csrf
                       <div class='row mt-2'>
                          <div class="col-md-4">
                               <label for="tape_receive_date">Tape Receive Date</label>
                               <input type="date" name="tape_receive_date" class="form-control" value="{{ date("Y-m-d") }}"/>
                          </div>
                          <div class="col-md-4">
                               <label for="receipt_no">Receipt Number</label>
                               <input type="text" name="receipt_number" id="receipt_number_1" class="form-control" value="{{ 'TR'.'-'.getNepaliDate(date('Y-m-d')) }}"/> {{-- value 'TR'."-".rand(0,999).'-'.rand(0,99999)  --}}
                          </div>  
                          <div class="col-md-4">
                               <label for="receipt_no"></label>
                               <button class="btn btn-primary">Search For Edit</button>
                          </div>
                       </div>
                       <hr>
                       <div class='row mt-2'>
                          <div class="col-md-4">
                               <label for="tape_receive_date">Tape Date</label>
                               <input type="date" name="tape_receive_date" class="form-control" value="{{ date("Y-m-d") }}"/>
                          </div>
                          <div class="col-md-4">
                               <label for="receipt_no">Receipt Number</label>
                               <input type="text" id="receipt_number_1_repeat" name="receipt_number" class="form-control" />
                          </div>
                          <div class="col-md-4">
                               <label for="receipt_no">To Godam</label>
                                <select class="form-control select2 advance-select-box" id="togodam"> 
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
                                <select class="form-control advance-select-box" id="planttype">
                                    {{-- @foreach($planttype as $data)
                                        <option value="{{ $data->id }}">{{ $data->name }}</option>
                                    @endforeach --}}
                                </select>
                            </div>
                            <div class="col-md-4">
                               <label for="receipt_no">Plant Name</label>
                               <select class="form-control select2 advance-select-box" id="plantname">
                                    {{-- @foreach($plantname as $data)
                                        <option value="{{ $data->id }}">{{ $data->name }}</option>
                                    @endforeach --}}
                                </select>
                            </div>
                            <div class="col-md-4">
                               <label for="receipt_no">Plant Shift</label>
                               <select class="form-control select2 advance-select-box" id="shift">
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
                        </div>
                        
                        <div class='row mt-2'>
                            <div class="col-md-3">
                                <label for="receipt_no">Total In Kgs</label>
                                <input type="text" class="form-control" name="total_in_kg"/>
                            </div>
                            <div class="col-md-3">
                                <label for="receipt_no">Loading</label>
                                <input type="text" class="form-control" name="loading"/>
                            </div>
                            <div class="col-md-3">
                                <label for="receipt_no">Running</label>
                                <input type="text" class="form-control" name="running"/>
                            </div>
                            <div class="col-md-3">
                                <label for="receipt_no">Bypass wast</label>
                                <input type="text" class="form-control" name="bypass_wast"/>
                            </div>
                        </div>
                        <hr>
                        <div class='row mt-3'>
                            <div class="col-md-4">
                                <div>
                                    <label for="receipt_no">Dana Name</label>
                                    <select class="form-control select2 advance-select-box">
                                        {{-- @foreach($dananame as $data)
                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                                        @endforeach --}}
                                    </select>
                                </div>
                                <div class="mt-2">
                                    <label for="receipt_no">Quantity in Kg</label>
                                    <input type="text" class="form-control" name="qty_in_kg"/>
                                    <button class="btn btn-primary float-right mt-2">Add</button>
                            </div>
                                </div>
                            <div class="col-md-8">
                                <div class="card">
                                    <table class="table table-bordered"  style="background:rgba(241, 214, 147,0.2)">
                                        <thead class="table-warning">
                                            <tr>
                                                <th style="width:100px">Sr No</th>
                                                <th>Dana Name</th>
                                                <th>Dana Qty</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>1</th>
                                                <th>dana name</th>
                                                <th>dana qty</th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row text-center d-flex justify-content-center">
                            <div class="col-md-3">
                                <label>Total Row/Dana in Kg</label>
                                <input type="text" class="form-control" name="dana_in_kg" id="dana_in_kg"/>
                            </div>
                            <div class="col-md-3">
                                <button id="btn-update" class="btn btn-info">Update</button>
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
            let receipt_number = $('#receipt_number_1').val();
           let receipt_number_repeat = $('#receipt_number_1_repeat').val(receipt_number);
        });
    </script>
    <script>
        $(document).ready(function(){
            //godam
            // getgodam();
            // $("#togodam").change(function(){
            //     getgodam();
            //     $('#plantname').empty();
            // });
            
            //godam
            $(document).on('change click','#togodam',function(){
                getgodam();
                $('#plantname').empty();
                $('#shift').empty();
            });
            
            //for plant name
            $(document).on("change click",'#planttype',function(e){
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
                        shift(response);
                    },
                    error:function(error){
                        console.log(error);
                    }
                });
            });
            
            
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
                response.planttype.forEach(data => {
                    $('<option></option>').attr('value', `${data.id}`).text(`${data.name}`).appendTo('#planttype');
                });
            }
            
            //for plant name
            function plantname(data){
                $('#plantname').empty();
                data.plantname.forEach(resp => {
                    $('<option></option>').attr('value', `${resp.id}`).text(`${resp.name}`).appendTo('#plantname');
                });
            }
            
            function shift(data){
                console.log(data);
                // data.shift(d => {
                //     $('<option></option>').attr('value', `${d.id}`).text(`${d.id}`).appendTo('#shift');
                // });
            }
        });
    </script>
@endpush
@endsection

@section('extra-script')
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
@endsection
