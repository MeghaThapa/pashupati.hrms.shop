@extends('layouts.admin')
@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
@endsection
@section('content')
    <div style="display: flex;
    justify-content: space-evenly;
    flex-direction: column;
    align-items: center;">

        <img class="lg-logo" src="{{ $settings->logo }}" alt="{{ $settings->companyName }}" width="250" height="50">
        <h3>Bag Bundel Stock</h3>
    </div>
    {{-- <form action="{{ route('rawmaterial.filterStocks') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-3" style="display:flex;gap:10px; width:400px;justify-content: center;align-item:center;">
                <label for="">Godam</label>
                <select class="advance-select-box form-control" id="danaGroupId_model" name="godam_id">
                    <option value="" selected disabled>{{ __('Select Godam') }}</option>
                    <option value="all">All Stocks</option>
                    @foreach ($godams as $godam)
                        <option value="{{ $godam->id }}">{{ $godam->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3" style="display:flex;gap:10px; width:400px;justify-content: center;align-item:center;">
                <label for="">Dana Group</label>
                <select class="advance-select-box form-control" id="danaGroupId" name="danaGroup_id">
                    <option value="" selected disabled>{{ __('Select Dana Group') }}</option>
                    <option value="all">All Stocks</option>
                    @foreach ($danaGroups as $danaGroup)
                        <option value="{{ $danaGroup->id }}">{{ $danaGroup->name }}</option>
                    @endforeach

                </select>

            </div>
            <div class="col-md-3" style="display:flex;gap:10px; width:400px;justify-content: center;align-item:center;">
                <label for="">Dana Name</label>
                <select class="advance-select-box form-control" id="danaNameId" name="danaName_id">
                    <option value="" selected disabled>{{ __('Select Dana Name') }}</option>
                    <option value="all">All Stocks</option>
                    @foreach ($danaNames as $danaName)
                        <option value="{{ $danaName->id }}">{{ $danaName->name }}</option>
                    @endforeach
                </select>

            </div>
            <div class="col-md-3">
                <button class="btn btn-primary" style="width:200px" type="submit">Show Report</button>

            </div>
        </div>
    </form> --}}

    <div class="row">
        <div class="col-md-12">
            <div class="p-0 table-responsive table-custom my-3">
                <table class="table" id="bagBundelStockTable">
                    <thead>
                        <tr>
                            <th>{{ __('S.No') }}</th>
                            <th>{{ __('Group') }}</th>
                            <th>{{ __('Bag Brand') }}</th>
                            <th>{{ __('Bundel No') }}</th>
                            <th>{{ __('Qty Pcs') }}</th>
                            <th>{{ __('Qty Kg') }}</th>
                            <th>{{ __('Average') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        {{-- @if ($rawMaterialStocks)
                            @foreach ($rawMaterialStocks as $i => $stock)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $stock->danaGroup }}</td>
                                    <td>{{ $stock->danaName }}</td>
                                    <td>{{ $stock->quantity }}</td>
                                </tr>
                            @endforeach
                        @endif --}}
                    </tbody>

                </table>
            </div>
            {{-- @if ($rawMaterialStocks)
                {{ $rawMaterialStocks->links() }}
            @endif --}}
        </div>
    </div>
@endsection
@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var table = $('#bagBundelStockTable').DataTable({
                lengthMenu: [
                    [30, 40, 50, -1],
                    ['30 rows', '40 rows', '50 rows', 'Show all']
                ],
                style: 'bootstrap4',
                processing: true,
                serverSide: true,
                ajax: "{{ route('bagBundelStock.bagBundellingYajraDatatables') }}",
                columns: [{
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'group.name'
                    },
                    {
                        data: 'bag_brand.name'
                    },
                    {
                        data: 'bundle_no'
                    },
                    {
                        data: 'qty_pcs'
                    },
                    {
                        data: 'qty_in_kg'
                    },
                    {
                        data: 'average_weight'
                    },
                ]
            });
        });
    </script>
@endsection
