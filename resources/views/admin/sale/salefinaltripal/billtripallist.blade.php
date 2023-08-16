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

<div class="card-body p-0 m-0">
      <p class="text-center">PASHUPATI SYNPACK INDUSTRIES PVTV. LTD. SONAPUR,SUNSARI</p>
       

    <div class="row">
        <div class="col-md-6 form-group">
            <label for="size" class="col-form-label">{{ __('Party Name') }} : {{$findtripal->getParty->name}}
            </label>
           
        </div>

        <div class="col-md-6 form-group">
            <label for="size" class="col-form-label">{{ __('A/c') }} : hello
            </label>
           
        </div>

    </div>

    <div class="row">
        <div class="col-md-6 form-group">
            <label for="size" class="col-form-label">{{ __('Packing List/Invoice No') }} : {{$findtripal->bill_no}}
            </label>
           
        </div>

        <div class="col-md-6 form-group">
            <label for="size" class="col-form-label">{{ __('Gate Pass no') }} : {{$findtripal->gp_no}}
            </label>
           
        </div>

    </div>
    <div class="row">
        <div class="col-md-4 form-group">
            <label for="size" class="col-form-label">{{ __('Date') }} : {{$findtripal->bill_date}}
            </label>
           
        </div>
        
    </div>
</div>

<div class="row">
    <div class="table-responsive table-custom my-3">
        <table class="table table-hover table-striped" id="sameFabricsTable">
            <thead class="table-info">
                <tr>
                    <th>{{ __('Sr.No') }}</th>
                    <th>{{ __('Fabric Name') }}</th>
                    <th>{{ __('Roll No') }}</th>
                    <th>{{ __('G.W') }}</th>
                    <th>{{ __('N.W') }}</th>
                    <th>{{ __('Meter') }}</th>
                    <th>{{ __('Avg') }}</th>
                    <th>{{ __('Gram') }}</th>
                </tr>
            </thead>
            <tbody id="same-fabrics">
                @foreach($salefinaltripals as $tripal)
                <tr>
                    <td>#</td>
                    <td>{{$tripal->name}}</td>
                    <td>{{$tripal->roll}}</td>
                    <td>{{$tripal->gross}}</td>
                    <td>{{$tripal->net}}</td>
                    <td>{{$tripal->meter}}</td>
                    <td>{{$tripal->average}}</td>
                    <td>{{$tripal->gram}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<!-- /.card-body -->

<!-- pagination start -->

@endsection
@section('extra-script')
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
<script src="{{ asset('js/storein.js') }}"></script>

    




@endsection