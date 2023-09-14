@extends('layouts.admin')

@section('extra-style')
<link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
<style>
    .col-form-label {
        font-size: 12px !important;

    }

    .dynamic-btn {
        height: 18px;
        width: 4px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #storeinSubmitBtn {
        height: 25px;
        width: 70px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 5px !important;
    }

    .fa-plus {
        font-size: 10px;
    }

    .form-control {
        font-size: 12px !important;

    }

    .select2-selection__rendered,
    .select2-container--bootstrap4 .select2-selection {
        font-size: 12px !important;
        display: flex !important;
        align-items: center !important;
        height: calc(1.6em + 0.75rem + 2px) !important;
    }

    .select2-container {
        height: calc(1.6em + 0.75rem + 2px) !important;
    }

    .taxStyle .select2-selection {
        width: 200px !important;
    }

    .form-group {
        margin-bottom: 0px !important;
    }

    .content-wrapper {
        padding-top: 0px !important;
    }

    .card-body {
        padding: 0px 5px !important;
    }

    .card {
        padding: 0px 5px !important;
    }

    .col-md-6 {
        padding: 0px 2px !important;
    }


</style>
@endsection

@section('content')

<div class="row">
    <input type="hidden" name="saletripal_id" value="{{$id}}" id="saletripal_id">
    <div class="table-responsive table-custom my-3">
        <table class="table table-hover table-striped" id="sameFabricsTable">
            <thead class="table-info">
                <tr>
                    <th>{{ __('Sr.No') }}</th>
                    <th>{{ __('Fabric Name') }}</th>
                    <th>{{ __('Roll No') }}</th>
                    <th>{{ __('G.W') }}</th>
                    <th>{{ __('N.W') }}</th>
                    <th>{{ __('Meter') }}</th>
                    <th>{{ __('Avg') }}</th>
                    <th>{{ __('Gram') }}</th>
                </tr>
            </thead>
            <tbody id="same-fabrics"></tbody>
        </table>
    </div>
</div>


<!-- /.card-body -->

<!-- pagination start -->
{{ $salefinaltripals->links() }}

@endsection
@section('extra-script')
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
<script src="{{ asset('js/storein.js') }}"></script>
<script src="{{ asset('js/storein.js') }}"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"> --}}

 <script>
     $(document).ready(function(){
         let fabricTable = null;

         if (fabricTable !== null) {
             fabricTable.destroy();
         }

         let saletripal_id = $('#saletripal_id').val();
         // debugger;

         fabricTable = $("#sameFabricsTable").DataTable({
             serverside : true,
             processing : true,
             ajax : {
                 url : "{{ route('getSaleTripalList') }}",
                 method : "post",
                 data : function(data){
                     data._token = $("meta[name='csrf-token']").attr("content"),
                     data.saletripal_id = saletripal_id
                 }
             },
             columns:[
                 { data : "DT_RowIndex" , name : "DT_RowIndex" },
                 { data : "name" , name : "name" },
                 { data : "roll" , name : "roll" },
                 { data : "gross" , name : "gross" },
                 { data : "net" , name : "net" },
                 { data : "meter" , name : "meter" },
                 { data : "average" , name : "average" },
                 { data : "gram" , name : "gram" },
             ]
         });
       
     });


 </script>   




@endsection