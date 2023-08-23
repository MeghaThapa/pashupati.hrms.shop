@extends('layouts.admin')

@section('extra-style')
<link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
<style>
    .col-form-label {
        font-size: 12px !important;

    }

    th, td {
        text-align: center;
        padding: auto !important;
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
              <div><h5 style="font-weight:bold;">PASHUPATI SYNPACK INDUSTRIES PVT. LTD.</h5></div>
              {{-- <h3 class="m-0">SONAPUR,SUNSARI</h3> --}}
              <div><h6><b>SONAPUR,SUNSARI</b></h6> </div>
             
            </div> 
            <div class="col-sm-6 invoice-col">
              
              <address>
                <strong> Godam : {{$find_godam == null ? 'All' : $find_godam }}</strong><br>
              
              </address>
            </div>
            <div class="col-sm-12 col-lg-6  text-right">
              <b>Name : {{$find_name == null ? 'All' : $find_name }}</b><br>
              <br>
            </div>
            <div class="row col-lg-12" >
                <div class="col-sm-6 col-lg-6  text-left">
                  <b>Type: {{$find_type == null ? 'All' : $find_type }}</b><br>
                  <br>
                </div>

                <div class="col-lg-6 text-right">
                  <b>Group: {{$find_group == null ? 'All' : $find_group }}</b><br>
                  <br>
                </div>
                
            </div>

            <div class="row">

                <div class="col-sm-12 text-right ml-2">
                  {{-- <b>Date: {{$findtripal->bill_date}}</b><br> --}}
                  <br>
                </div>
                
            </div>
            
            
          </div>
          <div class="row">
            <div class="col-12 table-responsive" style="padding: 0 30px; ">
              <table class="table table-bordered ">
                

                <tr>
                    <th width="10px">{{ __('Sr.No') }}</th>
                    <th width="10px">{{ __('Fabric Name') }}</th>
                    <th width="10px">{{ __('Roll No') }}</th>
                    <th width="10px">{{ __('Gross Wght') }}</th>
                    <th width="10px">{{ __('Net Wght') }}</th>
                    <th width="10px">{{ __('Meter') }}</th>
                    <th width="10px">{{ __('Avg Wght') }}</th>
                </tr>

                <tbody>
                    @foreach($datas as $key=>$data)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$data->name}}</td>
                        <td>{{$data->roll_no}}</td>
                        <td>{{$data->gross_wt}}</td>
                        <td>{{$data->net_wt}}</td>
                        <td>{{$data->meter}}</td>
                        <td>{{$data->average_wt}}</td>
                    </tr>
                    @endforeach
                </tbody>
             
             
              </table>
          </div>
        </div>

        <h3 class="m-0 text-center mt-4">SUMMARY</h3>

          <div class="row px-4 py-3">
            <div class="col-12 table-responsive">
              <table class="table table-bordered">
                

                <tr>
                    <th width="10px">{{ __('Sr.No') }}</th>
                    <th width="10px">{{ __('Fabric Name') }}</th>
                    <th width="10px">{{ __('Roll No') }}</th>
                    <th width="10px">{{ __('Gross Wght') }}</th>
                    <th width="10px">{{ __('Net Wght') }}</th>
                    <th width="10px">{{ __('Meter') }}</th>
                </tr>

                <tbody>
                    @foreach($totaldatas as $key=>$data)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$data->name}}</td>
                        <td>{{$data->total_count}}</td>
                        <td>{{$data->total_gross}}</td>
                        <td>{{$data->total_net}}</td>
                        <td>{{$data->total_meter}}</td>
                    </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="3">GrandTotal</td>
                        <td>{{$total_gross}}</td>
                        <td>{{$total_net}}</td>
                        <td>{{$total_meter}}</td>
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






<!-- /.card-body -->

<!-- pagination start -->

@endsection
@section('extra-script')
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
<script src="{{ asset('js/storein.js') }}"></script>

    




@endsection