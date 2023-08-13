@extends('layouts.admin')
@section('extra-style')
    <link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
@endsection
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
@endpush
@section('content')
    <a href="javascript:void(0)">
        <button class="btn btn-primary" data-toggle="modal" data-target="#importstock">
            Import Stock
        </button>
    </a>

    {{-- <a href="{{ route('closingStoreinReport.closing') }}">
        <button class="btn btn-primary">
            Closing
        </button>
    </a> --}}
    <div class="d-flex "
        style="font-weight:bold; font-size:25px;justify-content:center; align-items:center;text-decoration:underline;">
        PASHUPATI
        SYNPACK (STORE)</div>
    <div class="d-flex " style="font-size:16px;justify-content:center; align-items:center;">
        Store Items Current Stock</div>
    {{-- table --}}
    <div class="row">

        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ session()->get('message') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif(session()->has('message_err'))
            @foreach (session()->get('message_err') as $error)
            @endforeach
        @endif

        @error('file')
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>{{ $message }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @enderror
        <form action="{{ route('storeinStock.filter') }}">
            <div class="row">
                <div class="col-md-4">
                    <label for="category">Category</label>
                    <select class="advance-select-box form-control" id="storeinCategory" name="storein_category">
                        <option value="" selected disabled>{{ __('Select Category') }}</option>
                        @foreach ($categories as $categorie)
                            <option value="{{ $categorie->id }}">{{ $categorie->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="department">Department</label>
                    <select class="advance-select-box form-control" id="storeinDepartment" name="storein_department">
                        {{-- <option value="" selected disabled>{{ __('Select Department') }}</option> --}}
                        {{-- @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name}}
                                    </option>
                                @endforeach --}}
                    </select>
                </div>

                <div class="col-md-4 mt-4">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </div>
        </form>
        <div class="Ajaxdata col-md-12">
            <div class="p-0 table-responsive table-custom my-3">
                <table class="table" id="storeInItemTable">
                    <thead>
                        <tr>
                            <th>{{ __('S.No') }}</th>
                            <th>{{ __('Item Name') }}</th>
                            <th>{{ __('Department') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Product No') }}</th>
                            <th>{{ __('Size') }}</th>
                            <th>{{ __('Quantity') }}</th>
                            <th>{{ __('Unit') }}</th>
                            <th>{{ __('Avg Rate') }}</th>
                            <th>{{ __('Total Amt') }}</th>
                        </tr>
                    </thead>

                    <tbody></tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="importstock" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Import Stock Here</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('import.stock') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input class="form-control form-input" name="file" id="file" type="file" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenteredPopUpMesage" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Click buttons to create</h1>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <div class="modal-body">
                    <form method="POST" id="import-creates">
                        <div class="form-item">

                            <label for='this' id="label-popup"></label>
                            <input type="text" id="field_name" class="form-control" />
                        </div>
                        <div class="mt-2">
                            <div class="row">
                                <div class="col-md-4 createdepartment d-none">
                                    <input type="submit" value="create department" name="createdepartment"
                                        class="form-control btn btn-success" />
                                </div>
                                <div class="col-md-4 createitem d-none">
                                    <input type="submit" value="create item" name="createitem"
                                        class="form-control btn btn-info" />
                                </div>
                                <div class="col-md-4 createcategory d-none">
                                    <input type="submit" value="Create category" name="createcategory"
                                        class="form-control btn btn-warning" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
@endpush

@section('extra-script')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>

    <script>
        $('#storeinCategory').on('select2:select', function(e) {
            let category_id = e.params.data.id;
            // let click_by=blade;
            getCategoryDepartment(category_id);
        });

        function getCategoryDepartment(category_id) {
            return new Promise(function(resolve, reject) {
                $.ajax({
                    url: "{{ route('storeinStock.getCategoryDepartment', ['category_id' => ':Replaced']) }}"
                        .replace(
                            ':Replaced',
                            category_id),

                    method: 'GET',
                    success: function(response) {
                        console.log('ajax item: ', response);
                        let selectOptions = '';

                        if (response.length == 0) {
                            selectOptions += '<option disabled selected>' + 'no department found' +
                                '</option>';
                        } else {
                            selectOptions += '<option disabled selected>' + 'select department' +
                                '</option>';

                            for (let i = 0; i < response.length; i++) {
                                let optionText = response[i].name;
                                let optionValue = response[i].id;
                                let option = new Option(optionText, optionValue);
                                selectOptions += option.outerHTML;
                            }
                        }
                        $('#storeinDepartment').html(selectOptions);
                        resolve(response);

                    },
                    error: function(xhr, status, error) {
                        reject(error);
                    }
                });
            });
        }
        @if (session()->has('message_err'))
            @foreach (session()->get('message_err') as $data)

                $(document).ready(function() {
                    let type = "{{ $data['type'] }}";
                    // Show the modal when the page is ready
                    $('#exampleModalCenteredPopUpMesage').modal('show');
                    $('#label-popup').addClass("text-danger");
                    $('#label-popup').text("{{ $data['data'] }} missing.");
                    if (type == 'department') {
                        $("#import-creates").attr("action", "{{ route('department.store') }}");
                        $("#field_name").attr('name', 'department');
                        $("#field_name").attr('value', "{!! $data['data'] !!}");
                        $('.createdepartment').removeClass('d-none');
                    }
                    // if(type == 'department'){
                    //     $("#field_name").attr('name','department');
                    //     $("#field_name").attr('value',"{!! $data['data'] !!}");
                    //     $('.createdepartment').removeClass('d-none');
                    // }

                    $('#exampleModalCenteredPopUpMesage').on('hidden.bs.modal', function(e) {
                        $('#label-popup').removeClass("text-danger");
                        $('#label-popup').text("");
                        $("#import-creates").removeAttr("action");
                        $("#import-creates").removeAttr("value");
                        $('.createdepartment').addClass('d-none');
                    });

                });
            @endforeach
        @endif



        var bswDatatable = $('#storeInItemTable').DataTable({
            lengthMenu: [
                [50, 100, 200, -1],
                ['50 rows', '100 rows', '200 rows', 'Show all']
            ],
            style: 'bootstrap',
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('stock.yajra') }}',
                data: function(data) {
                    data.lamFabName = '123';
                },

                error: function(xhr, error, thrown) {
                    console.log("Error fetching data:", error);
                }

            },
            columns: [{
                    data: 'DT_RowIndex'
                },
                {
                    data: 'item_name'
                },
                {
                    data: 'department_name'
                },
                {
                    data: 'category_name'
                },
                {
                    data: 'item_num'
                },
                {
                    data: 'size_name'
                },
                {
                    data: 'quantity'
                },
                {
                    data: 'unit_name'
                },
                {
                    data: 'avg_price'
                },
                {
                    data: 'total_amount'
                },

            ],
        });
    </script>
@endsection
