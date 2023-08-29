@extends('layouts.admin')
@section('extra-style')
    <link href="{{ asset('css/nepaliDatePicker/nepali.datepicker.v4.0.1.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Fabric') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Fabric') }}</li>

                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="col-md-12">
                @include('admin.includes.alert')
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-5 col-6 mb-2">
                    <form action="{{ route('fabrics.index') }}" method="GET" role="search">
                        <div class="input-group">
                            <input type="text" name="term" placeholder="{{ __('Type name or category name...') }}"
                                class="form-control" autocomplete="off" value="{{ request('term') ? request('term') : '' }}"
                                required>
                            <span class="input-group-append">
                                <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
                            </span>
                        </div>
                    </form>
                </div>

                <div class="col-lg-6 col-md-7 col-6">
                    <div class="card-tools text-md-right">
                        <a href="{{ route('fabrics.create') }}" class="btn btn-primary">
                            {{ __('Add Fabric') }} <i class="fas fa-plus-circle"></i>
                        </a>

                    </div>


                </div>
                <div class="col-lg-3 col-md-7 col-6">
                    <div class="card-tools text-md-right">

                        <form action="{{ route('import.fabric') }}" method="POST" enctype="multipart/form-data"
                            id="fabricimport">
                            @csrf

                            <div class=" form-group">

                                <label for="receipt_number">Date Np</label>
                                <input type="text" name="date_np" class="form-control" id="date_np">
                            </div>

                            <div class=" form-group">
                                <label for="size" class="col-form-label">{{ __('To Godam') }}
                                </label>
                                <select class="advance-select-box form-control" id="godam_id" name="godam_id" required>
                                    <option value="" selected disabled>{{ __('Select Godam Name') }}</option>
                                    @foreach ($departments as $data)
                                        <option value="{{ $data->id }}">{{ $data->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-2" style="max-width: 500px; margin: 0 auto;">
                                <div class="custom-file text-left">
                                    <input type="file" name="file" class="custom-file-input" id="customFile"
                                        class="d-none">
                                    <label class="custom-file-label" for="customFile">Choose file</label>

                                </div>

                            </div>
                            <button id="registerimport" class="btn btn-primary">Import data</button>

                            @error('file')
                                <span class="text-danger font-italic" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </form>
                    </div>


                </div>
            </div>



            {{-- <form action="{{ route('fabrics.discard') }}" method="POST" role="search">
                @csrf
                <input type="submit" name="button" value="discard">
            </form> --}}
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="start_date">Start Date:</label>
                        <input type="text" class="form-control ndp-nepali-calendar" id="start_date" name="start_date" value="">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="end_date">End Date:</label>
                        <input type="text" class="form-control ndp-nepali-calendar" id="end_date" name="end_date" value="">
                    </div>
                </div>
                <div class="col-sm-3">
                    <label for="godamID">Select Godam</label>
                    <select class="form-control" id="godamID">
                        <option value="" selected disabled>{{ __('Select Godam Name') }}</option>
                        @foreach ($departments as $data)
                            <option value="{{ $data->id }}">{{ $data->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="p-0 table-responsive table-custom my-3">
                <table class="table" id="fabricTable">
                    <thead>
                        <tr>
                            <th>@lang('#')</th>
                            <th>{{ __('Godam') }}</th>
                            <th>{{ __('BillNO') }}</th>
                            <th>{{ __('Date Np') }}</th>
                            <th>{{ __('Net Weight') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th> 
                            <th></th> 
                            <th></th> 
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>

    <!-- /.content -->
@endsection

@section('extra-script')
    <script src="{{ asset('js/nepaliDatePicker/nepali.datepicker.v4.0.1.min.js') }}"></script>
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/storein.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>

    <script type="text/javascript">
        $('#fabricimport').on('submit', function() {
            $('#registerimport').attr('disabled', 'true');
        });

        $(document).ready(function() {
            var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");

            var table = $("#fabricTable").DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('fabrics.index') }}",
                    data: function(data) {
                        data.start_date = $('#start_date').val();
                        data.end_date = $('#end_date').val();
                        data.godam_id = $('#godamID').val();
                    },
                },
                columns: [
                    {
                        name: "DT_RowIndex",
                        data: "DT_RowIndex",
                        orderable: false
                    },
                    {
                        name: "godam",
                        data: "godam"
                    },
                    {
                        name: "bill_number",
                        data: "bill_number"
                    },
                    {
                        name: "bill_date",
                        data: "bill_date"
                    },
                    {
                        name: "total_netweight",
                        data: "total_netweight"
                    },
                    {
                        name: "action",
                        data: "action"
                    },
                ],
                footerCallback: function (tfoot, data, start, end, display) {
                    var api = this.api();
                    var totalNetweightSum = api.ajax.json().total_netweight_sum; // Get the sum from the JSON response
                    $(api.column(4).footer()).html('Total: ' + totalNetweightSum);
                },
                drawCallback: function (settings) {
                    var api = this.api();
                    var totalNetweightSum = api.ajax.json().total_netweight_sum; // Get the sum from the JSON response
                    $(api.column(4).footer()).html('Total: ' + totalNetweightSum);
                }
            });

            $('#date_np').val(currentDate);
            $('#date_np').nepaliDatePicker({
                ndpYear: true,
                ndpMonth: true,
                disableAfter: currentDate,
            });
            
            $('#start_date').val(currentDate);
            $('#start_date').nepaliDatePicker({
                ndpYear: true,
                ndpMonth: true,
                disableAfter: currentDate,
                onChange(){
                    table.draw();
                }
            
            });

            $('#end_date').val(currentDate);
            $('#end_date').nepaliDatePicker({
                ndpYear: true,
                ndpMonth: true,
                disableAfter: currentDate,
                onChange(){
                    table.draw();
                }
            });

            

            $('#start_date, #end_date, #godamID').on('change', function () {
                table.draw(); // Redraw the table
            });
        });




        $('body').on('click', '#rawMaterialDeleteBtn', function(e) {
            let fabricDetail_id = this.getAttribute('data-id');
            // console.log('js', fabricDetail_id);
            new swal({
                title: "Are you sure?",
                text: "Once deleted, data will deleted completely!!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true

            }).then((willDelete) => {
                if (willDelete.isConfirmed) {

                    $.ajax({
                        type: "POST",
                        url: "{{ route('fabricdetail.destroy', ['fabricDetail_id' => ':id']) }}"
                            .replace(':id', fabricDetail_id),
                        data: {
                            '_token': $('meta[name=csrf-token]').attr("content"),
                        },
                        success: function(data) {
                            console.log('controller:', data);
                            new swal
                                ({
                                    text: "Poof! Your data has been deleted!",
                                    title: "Deleted",
                                    icon: "success",
                                });
                            location.reload();
                        },
                        error: function(xhr) {
                            setMessage('RawMaterialError', xhr.responseJSON.message)

                            //console.log(xhr.responseJSON.message);
                        }
                    })

                }
            })
        });
    </script>
@endsection
