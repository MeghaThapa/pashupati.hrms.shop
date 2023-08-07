@extends('layouts.admin')

@section('extra-style')
    <link href="{{ asset('css/nepaliDatePicker/nepali.datepicker.v4.0.1.min.css') }}" rel="stylesheet" type="text/css" />
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
   <div class="card">
    <div class="card-body p-0 m-0">
        <div class="row">
            <div class="col-md-3">
                <label for="">Receipt Number</label>
                <input type="text" value="" class="form-control" readonly/>
            </div>
            <div class="col-md-3">
                <label for="">Godam</label>
                <select name="godam" class="form-control" id="godam">
                    <option value="">PSI</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="">Plant Type</label>
                <select name="plant_type" class="form-control" id="plant_type">
                    <option value="">plant type</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="">Plant Name</label>
                <select name="plant_name" class="form-control" id="plant_name">
                    <option value="">plant name</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="">Shift</label>
                <select name="shift" class="form-control" id="shift">
                    <option value="">Morning</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="">Fabric Type</label>
                <select name="fabric_type" class="form-control" id="fabric_type">
                    <option value="">LAM</option>
                    <option value="">Unlam</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="">Fabric</label>
                <select name="fabric" id="fabric">
                    <option value="">Fabric 1</option>
                </select>
            </div>
        </div>

        <div class="table-responsive card">
            <div class="card-header">
                <h3>Fabric List</h3>
            </div>
            <div class="card-body">
                <table class="table table-hover table-striped w-100">
                    <thead>
                         <tr>
                             <th>Fabric Name</th>
                             <th>Roll No</th>
                             <th>GW</th>
                             <th>NW</th>
                             <th>Meter</th>
                         </tr>
                    </thead>
                    <tbody class="fabnic-list">
                    </tbody>
                 </table>
            </div>
        </div>

        <div class="table-responsive card">
            <div class="card-header">
                <h3>Issued Fabric</h3>
            </div>
            <div class="card-body">
                <table class="table table-hover table-striped w-100">
                    <thead>
                         <tr>
                             <th>Fabric Name</th>
                             <th>Roll No</th>
                             <th>GW</th>
                             <th>NW</th>
                             <th>Meter</th>
                             <th>Action</th>
                         </tr>
                    </thead>
                    <tbody class="fabnic-list">
                    </tbody>
                 </table>
            </div>
        </div>

        <hr>

        <div class="table-responsive card">
            <div class="card-header">
                <h3>Sent Bag</h3>
            </div>
            <div class="card-body">
                <table class="table table-hover table-striped w-100">
                    <thead>
                         <tr>
                             <th>Fabric Name</th>
                             <th>Roll No</th>
                             <th>GW</th>
                             <th>NW</th>
                             <th>Meter</th>
                             <th>Avg</th>
                             <th>Type</th>
                             <th>Bag Brand</th>
                             <th>Brand Category</th>
                             <th>Action</th>
                         </tr>
                    </thead>
                    <tbody class="fabnic-list">
                    </tbody>
                 </table>
            </div>
        </div>

        <div class="form">
           <form action="" method="post">
            @csrf
            <div class="row">
                <div class="col-md-3">
                    <label for="">Total Out Meter</label>
                    <input type="text" name="total_out_meter" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="">Total Valve Meter</label>
                    <input type="text" name="total_out_meter" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="">Total Patch Meter</label>
                    <input type="text" name="total_out_meter" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="">Total In Meter</label>
                    <input type="text" name="total_in_meter" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="">Diff In/Out Meter</label>
                    <input type="text" name="diff_in_out_meter" class="form-control">
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <label for="">Total Out NW</label>
                    <input type="text" name="total_out_nw" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="">Total Valve NW</label>
                    <input type="text" name="total_out_nw" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="">Total Patch NW</label>
                    <input type="text" name="total_out_nw" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="">Total In NW</label>
                    <input type="text" name="total_in_nw" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="">Diff In/Out NW</label>
                    <input type="text" name="diff_in_out_nw" class="form-control">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label for=""></label>
                </div>
            </div>

           </form>
        </div>

    </div>
   </div>
@endsection
@section('extra-script')
@endsection