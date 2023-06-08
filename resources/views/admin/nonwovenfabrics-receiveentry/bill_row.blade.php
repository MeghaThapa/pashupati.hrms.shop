<tr id="bill_row">
  <td></td>
  <td>
    <input type="text" name="receive_date" value="{{$request->receive_date}}">
    <input type="text" name="receipt_number" value="{{$request->receipt_number}}">
    <input type="text" name="godam_data" value="{{$request->godam_data}}">
    <input type="text" name="planttype_data" value="{{$request->planttype_data}}">
    <input type="text" name="plantname_data" value="{{$request->plantname_data}}">
    <input type="text" name="shift" value="{{$request->shift}}">
    <label>{{$request->fabric_roll}}</label>
    
    <input type="hidden" name="fabric_roll[]" value="{{$request->fabric_roll}}" id="fabric_roll">
  </td>
  <td class="text-right">
      <label>{{$request->fabric_gsm}}</label>
      <input type="hidden" name="fabric_gsm[]" value="{{$request->fabric_gsm}}" id="fabric_gsm">
  </td>
  <td class="text-right">
      <label>{{$request->fabric_name}}</label>
      <input type="hidden" name="fabric_name[]" value="{{$request->fabric_name}}" id="fabric_name">
  </td>
  <td class="text-right">
      <label>{{$request->fabric_color}}</label>
      <input type="hidden" name="fabric_color[]" value="{{$request->fabric_color}}" id="fabric_color">
  </td>
  <td class="text-right">
      <label>{{$request->fabric_length}}</label>
      <input type="hidden" name="fabric_length[]" value="{{$request->fabric_length}}" id="fabric_length">
  </td>
  <td class="text-right">
      <label>{{$request->gross_weight}}</label>
      <input type="hidden" name="gross_wt[]" value="{{$request->gross_weight}}" id="gross_wt">
  </td>
  <td class="text-right">
      <label>{{$request->net_weight}}</label>
      <input type="hidden" name="net_wt[]" value="{{$request->net_weight}}" id="net_wt">
  </td> 
  <td class="text-center" width="10">
      <i class="text-danger fas fa-times" id="cross"></i>
  </td>
</tr>
