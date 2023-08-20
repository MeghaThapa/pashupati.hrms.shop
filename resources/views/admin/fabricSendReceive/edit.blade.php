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
<div class="card-body p-0 m-0">
    <div class="row">
        <div class="table-responsive table-custom my-3">
            <table class="table table-hover table-striped" id="table">
                <thead class="table-info">
                    <tr>
                        <th>{{ __('Sr.No') }}</th>
                        <th>{{ __('Fabric Name') }}</th>
                        <th>{{ __('Roll No') }}</th>
                        <th>{{__('Action')}}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<?php
    $fabrics = \App\Models\Fabric::where("is_laminated","true")->get();
?>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop1" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h4>
                <button type="button" class="btn-rounded btn-primary" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body">

                <label for="">Search Roll</label>
                <input type="text" class="form-control" id="roll_no"/>
                <button class="search">Search</button>

                <hr>

                <form action="" method="post"> {{-- route('update.lam.sent.fsr') --}}
                    <label for="">Selected Fabric</label>
                    <input type="text" class="form-control" id="searched_name" readonly/>
                    <label for="">ROll No</label>
                    <input type="text" class="form-control" id="searched_roll_no" readonly/>
                    <br>
                    <label for="">Fabric ID</label>
                    <input type="text" name="fabric_id" id="fabric_id" class="form-control" readonly>
                    <br>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


@endsection
@section('extra-script')
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
<script>
    $(".advance-select-box").select2()
</script>
<script>
    $(document).ready(function(){
        $(".search").click(function(){
            $.ajax({
                url : "{{ route('get/fabric/details/fsr') }}",
                method : "post",
                data : {
                    "_token" : $("meta[name='csrf-token']").attr("content"),
                    "roll_no" : $("#roll_no").val()
                },
                success:function(response){
                    console.log(response)
                    $("#searched_name").val(response.name)
                    $("#searched_roll_no").val(response.roll_no)
                    $("#fabric_id").val(response.id)
                },error:function(error){
                    console.log(error)
                }
            })
        })

        $("#table").DataTable({
            serverside : true,
            processing : true,
            lengthMenu : [
                [100 ,200, 400,600],
                [100 , 200 ,400 ,600]
            ],
            ajax : "{{ route('edit.laminated.fabric.ajax') }}",
            columns : [
                { name : "DT_RowIndex" , data : "DT_RowIndex" },
                { name : "name" , data : "name" },
                { name : "roll_no" , data : "roll_no" },
                { name : "action" , data : "action" },
            ]
        })

        $(document).on("click",".edit-data",function(){
            $("#laminated_id").val($(this).data('id'));
            $("#staticBackdrop1").modal("show")
        })
        $("#staticBackdrop1").on("hidden.bs.modal",function(){
            $("#searched_name").val("")
            $("#searched_roll_no").val("")
            $("#fabric_id").val("")
        })
    })
</script>
@endsection