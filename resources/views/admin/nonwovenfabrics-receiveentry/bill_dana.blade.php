<tr id="bill_row">
  <td></td>
  <td>
    <label>{{$request->dana}}</label>
    
    <input type="hidden" name="dana" value="{{$request->dana}}" id="dana">
  </td>
  <td class="text-right">
      <label>{{$request->quantity}}</label>
      <input type="hidden" name="quantity" value="{{$request->quantity}}" id="quantity">
  </td>
 
  <td class="text-center" width="10">
      <i class="text-danger fas fa-times" id="cross"></i>
  </td>
</tr>
