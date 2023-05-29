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
                    <label for="categoryName" class="col-form-label">{{ __('Select Category') }}
                    </label>
                    <select class="advance-select-box form-control @error('categoryName') is-invalid @enderror"
                        id="categoryName" name="categoryName" required>
                        <option value="" selected disabled>{{ __('Select a Category ') }}</option>
                        @foreach ($categories as $key => $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- <div class="col-md-3 form-group">
                    <label for="products" class="col-form-label">{{ __('Department') }}
                    </label>
                    <select class="advance-select-box form-control @error('ProductName') is-invalid @enderror"
                        id="ProductName" name="ProductName" required>
                        <option value="" selected disabled>{{ __('Select a department') }}</option>
                        @foreach ($departments as $key => $department)
                            <option value="{{ $department->id }}">{{ $department->department }}</option>
                        @endforeach
                    </select>
                </div> --}}
                {{-- <div class="col-md-3 form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="qunatities" class="col-form-label">{{ __('From Date') }}</label>
                            <input type="number" step="any" min="0"
                                class="form-control @error('qunatities') is-invalid @enderror calculator" id="qunatities-1"
                                data-number="1" name="quantities" placeholder="{{ __('Quantity') }}" min="1">
                        </div>

                        <div class="col-md-6">
                            <label for="units" class="col-form-label">{{ __('To Date') }}
                            </label>
                            <input type="number" step="any" min="0"
                                class="form-control @error('qunatities') is-invalid @enderror calculator" id="qunatities-1"
                                data-number="1" name="quantities" placeholder="{{ __('Quantity') }}" min="1">
                            @error('units')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div> --}}
                {{-- <button type="submit" class="btn btn-primary"
                    style="margin-top: 40px !important; margin-button:60px !important;">
                    Submit
                </button> --}}
            </div>
        </form>
    </div>
    {{-- <div class="d-flex "
        style="font-weight:bold; font-size:25px;justify-content:center; align-items:center;text-decoration:underline;">
        PASHUPATI
        SYNPACK (STORE)</div>
    <div class="d-flex " style="font-size:16px;justify-content:center; align-items:center;">
        Store Items Current Stock</div> --}}
    {{-- table --}}
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
                            <th>{{ __('Part Number')}}</th>
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
        let selectedCatContainer = document.getElementById('categoryName');
        $('#categoryName').on('select2:select', function(e) {
            let category_id = e.params.data.id;
            let catgory_name = e.params.data.text;
            // let click_by=blade;
            console.log(category_id);
            $.ajax({
                url: "{{ route('stock.filterStockAccCategory', ['category_id' => ':Replaced']) }}".replace(
                    ':Replaced',
                    category_id),

                method: 'GET',
                success: function(response) {
                    var tbody = document.querySelector('tbody');
                    tbody.innerHTML = '';
                    //console.log(response);
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
                
                let pnumber = document.createElement('td');
                pnumber.textContent = item.item.pnumber;
                row.appendChild(pnumber);

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
