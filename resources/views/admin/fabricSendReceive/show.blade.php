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
    <div class="card-header">
        <h3>Fabric Send Receive Entry Details</h3>
    </div>

    <div class="card-body">
        <h3 class="text-center">Unlaminated Fabric</h3>
        @php
            $grandCount = 0;
            $grandTotalGrossWt = 0;
            $grandTotalNetWt = 0;
            $grandTotalMeter = 0;
        @endphp
        @foreach ($unLams as $name => $fabrics)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>S.r.</th>
                    <th>Item Name</th>
                    <th>Roll No</th>
                    <th>Meter</th>
                    <th>Gross Wt</th>
                    <th>Net Wt</th>
                    <th>Avg</th>
                    <th>From Godam</th>
                    <th>Shift</th>
                </tr>
            </thead>
            <tbody>
                @php
                $i=0;
                $totalGrossWt = 0;
                $totalNetWt = 0;
                $totalMeter = 0;
                @endphp
                @foreach($fabrics as $fabric)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $fabric['name'] }}</td>
                    <td>{{ $fabric['roll_no'] }}</td>
                    <td>{{ $fabric['meter'] }}</td>
                    <td>{{ $fabric['gross_wt'] }}</td>
                    <td>{{ $fabric['net_wt'] }}</td>
                    <td>{{ $fabric['average_wt'] }}</td>
                    <td>{{ $fabric['from_godam'] }}</td>
                    <td>{{ $fabric['shift'] }}</td>
                </tr>
                @php
                    ++$grandCount;
                    $totalGrossWt += $fabric['gross_wt'];
                    $totalNetWt += $fabric['net_wt'];
                    $totalMeter += $fabric['meter'];
                @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td>Total:</td>
                    <td>{{ $i }}</td>
                    <td>{{ $totalMeter }}</td>
                    <td>{{ $totalGrossWt }}</td>
                    <td>{{ $totalNetWt }}</td>
                </tr>
            </tfoot>
        </table>
        @php
            $grandTotalGrossWt += $totalGrossWt;
            $grandTotalNetWt += $totalNetWt;
            $grandTotalMeter += $totalMeter;
        @endphp
        @endforeach
        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th>No of Rolls</th>
                    <th>Grand Meter Total</th>
                    <th>Grand Gross Wt Total</th>
                    <th>Grand Net Wt Total</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td>Grand Total</td>
                    <td>{{ $grandCount }}</td>
                    <td>{{ $grandTotalMeter }}</td>
                    <td>{{ $grandTotalGrossWt }}</td>
                    <td>{{ $grandTotalNetWt }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <br/><br/>

        <h3 class="text-center">Laminated Fabric</h3>
        @php
            $grandCount = 0;
            $grandTotalGrossWt = 0;
            $grandTotalNetWt = 0;
            $grandTotalMeter = 0;
        @endphp
        @foreach ($lamFabricsData as $name => $fabrics)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>S.r.</th>
                    <th>Item Name</th>
                    <th>Roll No</th>
                    <th>Meter</th>
                    <th>Gross Wt</th>
                    <th>Net Wt</th>
                    <th>Avg</th>
                    <th>To Godam</th>
                    <th>Shift</th>
                </tr>
            </thead>
            <tbody>
                @php
                $i=0;
                $totalGrossWt = 0;
                $totalNetWt = 0;
                $totalMeter = 0;
                @endphp
                @foreach($fabrics as $fabric)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $fabric['name'] }}</td>
                    <td>{{ $fabric['roll_no'] }}</td>
                    <td>{{ $fabric['meter'] }}</td>
                    <td>{{ $fabric['gross_wt'] }}</td>
                    <td>{{ $fabric['net_wt'] }}</td>
                    <td>{{ $fabric['average_wt'] }}</td>
                    <td>{{ $fabric['from_godam'] }}</td>
                    <td>{{ $fabric['shift'] }}</td>
                </tr>
                @php
                    ++$grandCount;
                    $totalGrossWt += $fabric['gross_wt'];
                    $totalNetWt += $fabric['net_wt'];
                    $totalMeter += $fabric['meter'];
                @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td>Total:</td>
                    <td>{{ $i }}</td>
                    <td>{{ $totalMeter }}</td>
                    <td>{{ $totalGrossWt }}</td>
                    <td>{{ $totalNetWt }}</td>
                </tr>
            </tfoot>
        </table>
        @php

            $grandTotalGrossWt += $totalGrossWt;
            $grandTotalNetWt += $totalNetWt;
            $grandTotalMeter += $totalMeter;
        @endphp
        @endforeach
        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th>No of Rolls</th>
                    <th>Grand Meter Total</th>
                    <th>Grand Gross Wt Total</th>
                    <th>Grand Net Wt Total</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td>Grand Total</td>
                    <td>{{ $grandCount }}</td>
                    <td>{{ $grandTotalMeter }}</td>
                    <td>{{ $grandTotalGrossWt }}</td>
                    <td>{{ $grandTotalNetWt }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>


    </div>
</div>

@endsection
