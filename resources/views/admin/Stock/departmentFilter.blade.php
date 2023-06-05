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
                    <label for="products" class="col-form-label">{{ __('Department') }}
                    </label>
                    <select class="advance-select-box form-control" id="department" name="departmentName" required>
                        <option value="" selected disabled>{{ __('Select a department') }}</option>
                        @foreach ($departments as $key => $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>
    <div class="row">
        <div class="Ajaxdata col-md-12">
            <div class="p-0 table-responsive table-custom my-3">
                <table class="table" id="stockTable">
                    <thead>
                        <tr>
                            <th id="sno">{{ __('S.No') }}</th>
                            <th>{{ __('Item Name') }}</th>
                            <th>{{ __('Size') }}</th>
                            <th>{{ __('Quantity') }}</th>
                            <th>{{ __('Unit') }}</th>
                            <th>{{ __('Avg Rate') }}</th>
                            <th>{{ __('Total Amt') }}</th>
                            <th>{{ __('Department') }}</th>
                            <th>{{ __('Category') }}</th>
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
        let selectedCatContainer = document.getElementById('department');
        $('#department').on('select2:select', function(e) {
            let department_id = e.params.data.id;
            // console.log(e.params.data);
            let department_name = e.params.data.text;
            // let click_by=blade;
            //console.log(department_id);
            $.ajax({
                url: "{{ route('stock.filterStockAccDepartment', ['department_id' => ':Replaced']) }}"
                    .replace(
                        ':Replaced',
                        department_id),

                method: 'GET',
                success: function(response) {
                    //console.log(response);
                    var tbody = document.querySelector('tbody');
                    tbody.innerHTML = '';
                    setIntoTable(response);
                    //document.getElementById('department_id').value = response.department.department;

                },
                error: function(xhr, status, error) {
                    reject(error);
                }
            });

        });
        //let sn=1;
        function setIntoTable(data) {
            let table = document.getElementById('stockTable');
            const tbody = table.querySelector("tbody");

            for (let i = 0; i < data.length; i++) {
                let item = data[i];
                console.log(item);

                let row = document.createElement('tr');

                let sn = document.createElement('td');
                sn.textContent = i + 1;
                row.appendChild(sn);

                let itemName = document.createElement('td');
                itemName.textContent = item.item.item;
                row.appendChild(itemName);

                let size = document.createElement('td');
                size.textContent = item.size;
                row.appendChild(size);

                let quantity = document.createElement('td');
                quantity.textContent = item.quantity;
                row.appendChild(quantity);

                let unit = document.createElement('td');
                unit.textContent = item.unit;
                row.appendChild(unit);

                let avg_price = document.createElement('td');
                avg_price.textContent = item.avg_price;
                row.appendChild(avg_price);

                let total_amount = document.createElement('td');
                total_amount.textContent = item.total_amount;
                row.appendChild(total_amount);

                let department = document.createElement('td');
                department.textContent = item.department.department;
                row.appendChild(department);

                let category = document.createElement('td');
                category.textContent = item.category.name;
                row.appendChild(category);

                console.log(row);
                tbody.appendChild(row);
            }
        };
    </script>
@endsection
