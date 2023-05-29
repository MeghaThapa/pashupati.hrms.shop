(function(){
    "use strict";

    // get the sizes and for a selected finished product
    $(document).ready(function(){
        $(".finished-select").on("change", function () {
            let finishedProduct =  $("#finishedProduct option:selected").val();
            $.ajax({
                type: "POST",
                dataType : 'json',
                url: location.origin+"/admin/finished-product-sizes",
                data: {_token: $('meta[name="csrf-token"]').attr('content'), id: finishedProduct}
            })
            .done(function(data){
                let sizes = data['finishedProduct'].sizes.split(", ");
                let qunatities = data['finishedProduct'].quantities.split(", ");
                let rmQuantities = data['transferredQty'].split(", ");
                let htmlFields = '';
                for(let i in sizes)
                {
                    let size = sizes[i];
                    let qty = isNaN(qunatities[i]) ? 0 : parseFloat(qunatities[i]);
                    let rmQty = isNaN(rmQuantities[i]) || rmQuantities[i] == '' ? qty : qty - parseFloat(rmQuantities[i]);
                    htmlFields += '<div class="col-md-3 form-group"> <label for="productSizes" class="col-form-label">Product Size</label> <input type="text" class="form-control" id="productSizes-'+i+'" name="productSizes[]" placeholder="Product Size" value="'+size+'" readonly> </div><div class="col-md-3 form-group"> <label for="finishedProducts" class="col-form-label">Finished Quantity</label> <input type="text" class="form-control" id="finishedProducts-'+i+'" name="finishedProducts[]" placeholder="Finished Quantity" value="'+qty+'" readonly> </div><div class="col-md-3 form-group"> <label for="remainingQuantities" class="col-form-label">Remaining Quantity</label> <input type="text" class="form-control" id="remainingQuantities-'+i+'" name="remainingQuantities[]" placeholder="Remaining Quantity" value="'+rmQty+'" readonly> </div><div class="col-md-3 form-group"> <label for="transferredQuantities" class="col-form-label">Transferred Quantity<span class="required-field">*</span></label> <input type="number" min="0" step="any" max="'+rmQty+'" class="form-control" id="transferredQuantities-'+i+'" name="transferredQuantities[]" placeholder="Transferred Quantity" ></div>'
                }

                if($("#existing-columns").length  > 0)
                {
                    $("#existing-columns").remove();
                }
                $('#dynamic-sizes').html(htmlFields);
            })
            .fail(function(){
                console.log('Ajax Failed')
            });
        });
    });


})();
