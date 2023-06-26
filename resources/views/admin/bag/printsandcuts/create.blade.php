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

    label {
        font-weight: lighter;
        color: rgba(0, 0, 0, 0.8);
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
            <h1 class="m-0 text-dark">{{ __('Prints and Cuts') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item active">{{ __('Prints and cuts') }}</li>
            </ol>
        </div>
    </div>
</div>
<!-- /.content-header -->
<div class="content">
    <div class="container-fluid">
        <a class='btn btn-primary go-back float-right'>Go back</a>
        <br><br>
        <div class="form">
            <form action="">
                <div class="row">
                    @foreach($data as $d)
                        <div class="col-md-3">
                            <label for="receipt_number">Receipt Number</label>
                            <input type="text" value="{{ $d->receipt_number }}" class="form-control" id='receipt_number' name='receipt_number' readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="Date NP">Date</label>
                            <input type="text" value="{{ $d->date }}"  class="form-control" id='date_np' name='date_np' readonly>
                        </div>
                    @endforeach
                    <div class="col-md-3">
                        <label for="Roll number">Roll Number</label>
                        <input type="text" class="form-control" id='roll_number' name='roll_number' disabled>
                    </div>
                    <div class="col-md-3">
                        <label for="Fabric">Fabric </label>
                        <select name="fabric" class="form-control advance-select-box" id="fabric" >
                            <option value="">Fabric Hre</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="net_weight">Net Weight </label>
                        <input type="text" class="form-control" id="net_weight" name="net_weight">
                    </div>
                    <div class="col-md-3">
                        <label for="gross_weight">Gross Weight </label>
                        <input type="text" class="form-control" id="gross_weight" name="gross_weight">
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <label for="meter">Meter</label>
                        <input type="text" class="form-control" id="meter" name="meter">
                    </div>
                    <div class="col-md-3">
                        <label for="average">Average</label>
                        <input type="text" class="form-control" id="average" name="average">
                    </div>
                    <div class="col-md-3">
                        <label for="cut_length">Cut Length</label>
                        <input type="text" class="form-control" id="cut_length" name="cut_length">
                    </div>
                    <div class="col-md-3">
                        <label for="cut_length">Req Bag</label>
                        <input type="text" class="form-control" id="cut_length" name="cut_length">
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <label for="group">Group <sup><span class="add-group bg-success px-2 py-1" style="border-radius:2px;cursor:pointer"><i class="fa fa-plus" aria-hidden="true"></i></span></sup></label>
                        <input type="text" class="form-control" id="meter" name="meter">
                    </div>
                    <div class="col-md-3">
                        <label for="average">Bag Brand</label>
                        <select id="bag_brand" class="select2 form-control advance-select-box" name="bag_brand">
                            <option value="brand">Brand</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="cut_length">Quantity Piece</label>
                        <input type="text" class="form-control" id="quantity_piece" name="quantity_piece">
                    </div>
                    <div class="col-md-3">
                        <label for="cut_length">AVG</label>
                        <input type="text" class="form-control" id="avg" name="avg">
                    </div>
                </div>
            </form>
            <hr>
            <div class="table-custom table-responsive ">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Brand Group</th>
                            <th>Bag Brand</th>
                            <th>Quantity Piece</th>
                            <th>Average</th>
                            <th>Wastage</th>
                            <th>Roll No</th>
                            <th>Fabric Name</th>
                            <th>NW</th>
                            <th>GW</th>
                            <th>Meter</th>
                            <th>Avg</th>
                            <th>Req Bag</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>SN</td>
                            <td>Brand Group</td>
                            <td>Bag Brand</td>
                            <td>Quantity Piece</td>
                            <td>Average</td>
                            <td>Wastage</td>
                            <td>Roll No</td>
                            <td>Fabric Name</td>
                            <td>NW</td>
                            <td>GW</td>
                            <td>Meter</td>
                            <td>Avg</td>
                            <td>Req Bag</td>
                            <td>
                                <a class="btn btn-danger" href="javascript:void(0)"><i class="fa fa-trash" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            Hello
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Hello</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    Hwello
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mx-1">
                <button class="btn btn-success">Update</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script>
        $(document).ready(function(){
        $(".go-back").click(function(){
            history.back();
        });
    });
    </script>

    <script>
        $(".add-group").click(function(e){
            alert("hello");
        }); 
    </script>
    @if(session()->has('message'))
    <script>
        toastr.success("{{ session()->get('message') }}");
    </script>
    @elseif(session()->has('message_err'))
    <script>
        toastr.error("{{ session()->get('message_err') }}");
    </script>
    @endif
@endsection