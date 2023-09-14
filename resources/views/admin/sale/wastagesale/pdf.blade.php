<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TripalSaleList</title>
</head>
<body>
    <div class="row invoice-info p-4">
      <div class="col-sm-12 text-center mb-2">
        <div><small class="text-center">PASHUPATI SYNPACK INDUSTRIES PVT. LTD.</small></div>
        {{-- <h3 class="m-0">SONAPUR,SUNSARI</h3> --}}
        <div><small><b>SONAPUR,SUNSARI</b></small> </div>
       
      </div> 
      <div class="col-sm-6 invoice-col">
        
        <address>
          <strong> PartyName : {{$findtripal->getParty->name}}</strong><br>
        
        </address>
      </div>
      <div class="col-sm-12 col-lg-6  text-right">
        <b>A/C : {{$findtripal->getParty->name}}</b><br>
        <br>
      </div>
      <div class="row col-lg-12" >
          <div class="col-sm-6 col-lg-6  text-left">
            <b>Invoice Number: {{$findtripal->bill_no}}</b><br>
            <br>
          </div>

          <div class="col-lg-6 text-right">
            <b>Gate Pass: {{$findtripal->gp_no}}</b><br>
            <br>
          </div>
          
      </div>

      <div class="row">

          <div class="col-sm-12 text-right ml-2">
            <b>Date: {{$findtripal->bill_date}}</b><br>
            <br>
          </div>
          
      </div>
      
      
    </div>
    <table class="table table-striped table-sm">
      

      <tr>
          <th width="10px">{{ __('Sr.No') }}</th>
          <th width="10px">{{ __('Fabric Name') }}</th>
          <th width="10px">{{ __('Roll No') }}</th>
          <th width="10px">{{ __('Gross Wght') }}</th>
          <th width="10px">{{ __('Net Wght') }}</th>
          <th width="10px">{{ __('Meter') }}</th>
          <th width="10px">{{ __('Avg Wght') }}</th>
          <th width="10px">{{ __('GSM') }}</th>
      </tr>

      <tbody>
          @foreach($data as $tripal)
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

    <h3 class="m-0 text-center mt-2">SUMMARY</h3>

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
            </tr>

            <tbody>
                @foreach($totaltripals as $tripal)
                <tr>
                    <td>#</td>
                    <td>{{$tripal->name}}</td>
                    <td>{{$tripal->total_count}}</td>
                    <td>{{$tripal->total_gross}}</td>
                    <td>{{$tripal->total_net}}</td>
                    <td>{{$tripal->total_meter}}</td>
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

</body>
</html>