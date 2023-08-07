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
            <form action="" method="post">
                @csrf
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
            </form>
        </div>
    </div>
   </div>
@endsection
@section('extra-script')
@endsection