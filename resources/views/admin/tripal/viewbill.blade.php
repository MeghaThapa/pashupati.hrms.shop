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
<section class="content">
  <div class="container-fluid">
   
   
    
    <div class="row" id="printTable">
      <div class="col-12">
        <div class="invoice p-3 mb-3 card  card-outline">
          {{-- <div class="row col-12">
            <div class="col-12">
              <h4>
                <small class="float-right">Date: </small>
              </h4>
            </div>
          </div> --}}
          <div class="row invoice-info p-4">
            <div class="col-sm-12 text-center mb-2">
              <div><small>PASHUPATI SYNPACK INDUSTRIES PVT. LTD.</small></div>
              {{-- <h3 class="m-0">SONAPUR,SUNSARI</h3> --}}
              <div><small><b>SONAPUR,SUNSARI</b></small> </div>
             
            </div> 
            <div class="col-sm-6 invoice-col">
              
              <address>
                {{-- <strong> FromGodam : {{$find_data->getFromGodam->name}}</strong><br> --}}
              
              </address>
            </div>
            <div class="col-sm-12 col-lg-6  text-right">
              {{-- <b>ToGodam : {{$find_data->getToGodam->name}}</b><br> --}}
              <br>
            </div>
            <div class="row col-lg-12" >
                <div class="col-sm-6 col-lg-6  text-left">
                    
                  {{-- <b>Invoice Number: {{$find_data->bill_no}}</b><br> --}}
                  <br>
                </div>

                {{-- <div class="col-lg-6 text-right">
                  <b>FromGodam : {{$find_data->getFromGodam->name}}</b><br>
                  <br>
                </div> --}}
                
            </div>

            <div class="row">

                <div class="col-sm-12 text-right ml-2">
                  {{-- <b>Date: {{$findtripal->bill_date}}</b><br> --}}
                  <br>
                </div>
                
            </div>
            
            
          </div>
          <h3 class="m-0 text-center mt-2">Unlaminated</h3>
          <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-striped table-sm">
                  

                  <tr>
                      <th width="10px">{{ __('Sr.No') }}</th>
                      <th width="10px">{{ __('Fabric Name') }}</th>
                      <th width="10px">{{ __('Roll No') }}</th>
                      <th width="10px">{{ __('Gross Wght') }}</th>
                      <th width="10px">{{ __('Net Wght') }}</th>
                      <th width="10px">{{ __('Meter') }}</th>
                      <th width="10px">{{ __('Avg Wght') }}</th>
                      <th width="10px">{{ __('Gram') }}</th>
                  </tr>

                  <tbody>
                      @foreach($unlam_datas as $key => $unlam)
                      <tr>
                          <td>{{$key+1}}</td>
                          <td>{{$unlam->fabric->name}}</td>
                          <td>{{$unlam->roll_no}}</td>
                          <td>{{$unlam->gross_wt}}</td>
                          <td>{{$unlam->net_wt}}</td>
                          <td>{{$unlam->meter}}</td>
                          <td>{{$unlam->average}}</td>
                          <td>{{$unlam->gram}}</td>
                      </tr>
                      @endforeach
                  </tbody> 
                
                
                </table>
              
          </div>
        </div>

        <h3 class="m-0 text-center mt-2">SingleSideLaminatedStock</h3>

        <div class="row p-4">
            <div class="col-12 table-responsive">
              

              <table class="table table-striped table-sm">
                

                <tr>
                    <th width="10px">{{ __('Sr.No') }}</th>
                    <th width="10px">{{ __('Fabric Name') }}</th>
                    <th width="10px">{{ __('Roll No') }}</th>
                    <th width="10px">{{ __('Gross Wght') }}</th>
                    <th width="10px">{{ __('Net Wght') }}</th>
                    <th width="10px">{{ __('Meter') }}</th>
                    <th width="10px">{{ __('Avg Wght') }}</th>
                    <th width="10px">{{ __('Gram') }}</th>
                </tr>

                <tbody>
                    @foreach($stocks as $key => $tripal)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$tripal->name}}</td>
                        <td>{{$tripal->roll_no}}</td>
                        <td>{{$tripal->gross_wt}}</td>
                        <td>{{$tripal->net_wt}}</td>
                        <td>{{$tripal->meter}}</td>
                        <td>{{$tripal->average_wt}}</td>
                        <td>{{$tripal->gram}}</td>
                    </tr>
                    @endforeach
                </tbody> 
              
              
              </table>
          </div>
        </div>
        
        <div class="row no-print">
          <div class="col-12">
            {{-- <span>Bill Printed By : {{$bill_total_student->getUser->name}}</span> --}}
            {{-- <span class="float-right">Bill Date: {{$bill_total_student->created_at_np}}</span> --}}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</section>

@endsection
@section('extra-script')
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
<script src="{{ asset('js/storein.js') }}"></script>

@endsection