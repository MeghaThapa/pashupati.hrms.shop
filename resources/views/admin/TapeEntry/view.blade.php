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
        <div class="card">
            <div class="card-header">
                <label for="">Godam</label>
                <select name="" id="">
                    <option value="psi">PSI</option> 
                    <option value="new psi">New PSI</option> 
                    <option value="bsw">BSW</option> 
                </select>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-striped table-hover table-bordered w-100">
                    <thead>
                        <th>SN</th>
                        <th>Godam</th>
                        <th>Receipt Number</th>
                        <th>Receipt Date</th>
                        <th>Wastage</th>
                        <th>Dana Consumption</th>
                        <th>Tape Quantity</th>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <th>SN</th>
                        <th>Godam</th>
                        <th>Receipt Number</th>
                        <th>Receipt Date</th>
                        <th>Wastage</th>
                        <th>Dana Consumption</th>
                        <th>Tape Quantity</th>
                    </tfoot>
                </table>
            </div>
            <div class="card-footer row">
                <div class="col-md-4">
                    <label for="">Dana Consumption</label>
                    <input type="text" class="form-control" value="{{ $dana_consumption }}" readonly>
                </div>
                <div class="col-md-4">
                    <label for="">Wastage</label>
                    <input type="text" class="form-control" value="{{ $wastage }}" readonly>
                </div>
                <div class="col-md-4">
                    <label for="">Tape Quantity</label>
                    <input type="text" class="form-control" value="{{ $tape_quantity }}" readonly>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('extra-script')
<script>
    $(document).ready(function(){
        let table = $(".table").DataTable({
            serverside : true,
            processing : true,
            searchable : true,
            orderable : true,
            ajax : "{{ route('tape.report.ajax') }}",
            columns : [
                { data:"DT_RowIndex" , name: "DT_RowIndex"},
                { data:"godam" , name: "godam"},
                { data:"receipt_number" , name: "receipt_number"},
                { data:"receipt_entry_date" , name: "receipt_entry_date"},
                { data:"wastage" , name: "wastage"},
                { data : "dana_in_kg" , name: "dana_in_kg" },
                { data : "tape_qty_in_kg" , name: "tape_qty_in_kg" },
            ]
        })
    })
</script>
@endsection
