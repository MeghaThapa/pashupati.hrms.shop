<table class="table" id="replaceTable">
    <thead>
        <tr>
            <th>{{ __('S.No') }}</th>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Roll') }}</th>
            <th>{{ __('Net Weight') }}</th>
            <th>{{ __('Gross Weight') }}</th>
            <th>{{ __('Meter') }}</th>
            <th>{{ __('Average Weight') }}</th>
        </tr>
    </thead>

    <tbody>
        @if ($finaltripal)
            @foreach ($finaltripal as $i => $stock)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $stock->name }}</td>
                    <td>{{ $stock->roll_no }}</td>
                    <td>{{ $stock->net_wt}}</td>
                    <td>{{ $stock->gross_wt }}</td>
                    <td>{{ $stock->meter }}</td>
                    <td>{{ $stock->average_wt }}</td>
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