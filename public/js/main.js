(function(){
    "use strict";

    // define advance select box
    $(document).ready(function(){
        if ($('.advance-select-box').val() !== undefined)
        {
            $('.advance-select-box').select2({
                theme: 'bootstrap4',
            });
        }
    });

    // initialize datepicker plugin
    $(document).ready(function(){
        if ($('.datepicker').length > 0)
        {
            $('.datepicker').datepicker({
                clearBtn: true,
                format: "yyyy-m-d"
            });
        }
    });

    // delete btn confirmation
    $(document).ready(function(){
        if ($(".delete-btn").length > 0)
        {
            $(".delete-btn").on("click", function () {
                let msg = $(".delete-btn").data("msg")
                return confirm(msg);
            });
        }
    });

    // attached image preview
    $(document).ready(function(){
        $("#attached-image").change(function(){
            readURL(this);
        });
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#attached-preview-img').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    });

    // print button
    $(document).ready(function(){
        $(".print-btn").on("click", function () {
            "use strict";
            window.print();
        });
    });


    // admin logout form
    $(document).ready(function(){
        if ($(".admin-logout").length > 0)
        {
            $(".admin-logout").on("click", function (e) {
                e.preventDefault();
                $(".logout-form").submit();
            });
        }
    });
})();
