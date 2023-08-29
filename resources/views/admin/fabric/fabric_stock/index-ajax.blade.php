 <div class="row" >
        <div class="col-md-12">
            <div class="p-0 table-responsive table-custom my-3">
                <table class="table" id="replaceTable">
                    <thead>
                        <tr>
                            <th>{{ __('S.No') }}</th>
                            <th>{{ __('Roll Number') }}</th>
                            <th>{{ __('Loom Number') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Gross Weight') }}</th>
                            <th>{{ __('Net Weight') }}</th>
                            <th>{{ __('Meter') }}</th>
                            <th>{{ __('Average Weight') }}</th>
                            <th>{{ __('Gram Weight') }}</th>
                            <th>{{ __('Bill Number') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if ($fabric_stock)
                            @foreach ($fabric_stock as $i => $stock)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                     <td>{{ $stock->roll_no }}</td>
                                     <td>{{ $stock->loom_no }}</td>
                                    <td>{{ $stock->name }} </td>
                                     <td>{{ $stock->gross_wt }}</td>
                                     <td>{{ $stock->net_wt }}</td>
                                     <td>{{ $stock->meter }}</td>
                                     <td>{{round($stock->average_wt, 2)}}</td>

                                     <td>{{ round((round($stock->average_wt, 2) /  (int) filter_var($stock->name, FILTER_SANITIZE_NUMBER_INT) ),2) }}</td>

                                   
                                     <td>{{ $stock->bill_no }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Total:</th>
                      
                        <td class="text-right">{{ $sum}}</td>
                      </tr>
                      
                    </tfoot>

                </table>
            </div>
            {{-- @if ($fabric_stock)
                {{ $fabric_stock->links() }}
                
            @endif  --}}
        </div>
</div>
