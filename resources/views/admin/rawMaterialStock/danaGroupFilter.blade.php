@extends('layouts.admin')
@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
@endsection
@section('content')
<div class="card-body p-0">
    <form>
        @csrf
        <div class="row">

            <div class="col-md-3 form-group">
                <label for="categoryName" class="col-form-label">{{ __('Select Dana Group') }}
                </label>
                <select class="advance-select-box form-control @error('danaGroupName') is-invalid @enderror"
                    id="danaGroupName_id" name="danaGroupName" required>
                    <option value="" selected disabled>{{ __('Select a dana group ') }}</option>
                    @foreach ($danaGroups as $danaGroup)
                        <option value="{{ $danaGroup->id }}">{{ $danaGroup->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
</div>
<div class="row">
    <div class="Ajaxdata col-md-12">
        <div class="p-0 table-responsive table-custom my-3">
            <table class="table" id="rawMaterialStockTable">
                <thead>
                    <tr>
                        <th id="sno">{{ __('S.No') }}</th>
                        <th>{{ __('Dana Name') }}</th>
                        <th>{{ __('Dana Group') }}</th>
                        <th>{{ __('Quantity') }}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>

            </table>
        </div>

    </div>
</div>
@endsection
@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/storein.js') }}"></script>
    <script>
        let sn = 1;
       // let danaNameSelectContainer = document.getElementById('danaName_id');
        $('#danaGroupName_id').on('select2:select', function(e) {
            let danaGroup_id = e.params.data.id;
            let danaGroup_name = e.params.data.text;
           $.ajax({
                url: "{{ route('rawMaterialStock.filterAccDanaGroup', ['danaGroup_id' => ':Replaced']) }}".replace(
                    ':Replaced',
                    danaGroup_id),

                method: 'GET',
                success: function(response) {

                    //var tbody = document.querySelector('tbody');
                    //tbody.innerHTML = '';
                    removeAllTableRows();
                    //console.log(response);
                    setIntoTable(response);
                    //document.getElementById('department_id').value = response.department.department;

                },
                error: function(xhr, status, error) {
                    reject(error);
                }
           });
        })
        function setIntoTable(data) {
            let table = document.getElementById('rawMaterialStockTable');
            const tbody = table.querySelector("tbody");

            for (let i = 0; i < data.length; i++) {
                let item = data[i];
                //console.log(item);

                let row = document.createElement('tr');

                let sn = document.createElement('td');
                sn.textContent = i + 1;
                row.appendChild(sn);

                let danaName = document.createElement('td');
                danaName.textContent = item.dana_name.name;
                row.appendChild(danaName);


                let danaGroup = document.createElement('td');
                danaGroup.textContent = item.dana_group.name;
                row.appendChild(danaGroup);

                let quantity = document.createElement('td');
                quantity.textContent = item.quantity;
                row.appendChild(quantity);

               // console.log(row);
                tbody.appendChild(row);
            }
        };
        function removeAllTableRows() {
                // Reseting SN
                sn = 1;
                let tbody = document.querySelector("#rawMaterialStockTable tbody");
                for (var i = tbody.rows.length - 1; i >= 0; i--) {
                    tbody.deleteRow(i);
                }
            }
        </script>

@endsection
