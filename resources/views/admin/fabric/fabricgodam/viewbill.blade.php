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
    {{-- <a href="{{route('tripalsale.pdf',$id)}}"> <button class="btn btn-info btn-sm rounded-0" type="submit" ><i class="fas fa-print"></i> Pdf</button></a> --}}
    {{-- <a href="{{route('tripalsale.excel',$id)}}"> <button class="btn btn-success btn-sm rounded-0"><i class="fas fa-print"></i> Excel</button></a> --}}
   
    
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
                {{-- <strong> FromGodam : {{$find_data->fromgodam->name}}</strong><br> --}}
              
              </address>
            </div>
            <div class="col-sm-12 col-lg-6  text-right">
              {{-- <b>ToGodam : {{$find_data->togodam->name}}</b><br> --}}
              <br>
            </div>
            <div class="row col-lg-12" >
                <div class="col-sm-6 col-lg-6  text-left">
                  <b>Invoice Number: {{$find_data->bill_no}}</b><br>
                  <br>
                </div>

                <div class="col-lg-6 text-right">
                  {{-- <b>Gate Pass: {{$findsale->gp_no}}</b><br> --}}
                  <br>
                </div>
                
            </div>

            <div class="row">

                <div class="col-sm-12 text-right ml-2">
                  <b>Date: {{$find_data->bill_date}}</b><br>
                  <br>
                </div>
                
            </div>
            
            
          </div>
          <div class="row">
            <div class="col-12 table-responsive">
              <table class="table table-bordered" style="padding: 0 30px; ">
                

                <tr>
                    <th width="10px">{{ __('Sr.No') }}</th>
                    <th width="10px">{{ __('Fabric Name') }}</th>
                    <th width="10px">{{ __('Roll No') }}</th>
                    <th width="10px">{{ __('Net Wt') }}</th>
                </tr>

                <tbody>
                    @foreach($fabricdetails as $key=>$fabric)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$fabric->name}}</td>
                        <td>{{$fabric->roll}}</td>
                        <td>{{$fabric->net_wt}}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">GrandTotal</td>
                        <td>{{$total_net}}</td>
                    </tr>
                </tfoot>
             
             
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