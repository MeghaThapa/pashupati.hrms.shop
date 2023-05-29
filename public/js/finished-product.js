(function () {
    "use strict";

    // get products for selected purchase
    $(document).ready(function () {
        $(".purchase-select").on("change", function () {
            let processingProduct = $("#processingProduct option:selected").val();
            $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: location.origin + "/admin/finished-purchase-products",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        id: processingProduct
                    }
                })
                .done(function (data) {
                    let inputHtml = '';
                    for (let i in data) {
                        inputHtml += '<div class="col-md-3"> <label for="productName" class="col-form-label">Product Name</label> <input type="text" class="form-control" id="products-' + i + '" name="products[]" placeholder="Product Name" value="' + data[i].product_name + '" readonly></div><div class="col-md-3"> <label for="purchasedQuantites" class="col-form-label">Purchased Quantity</label> <input type="text" class="form-control" name="purchasedQuantites[]" placeholder="Purchased Quantity" value="' + data[i].quantity + ' ' + data[i].unit + '" readonly></div><div class="col-md-3"> <label for="availableQuantites" class="col-form-label">Available Quantity</label> <input type="text" class="form-control" name="availableQuantites[]" placeholder="Available Quantity" value="' + data[i].available_qty + ' ' + data[i].unit + '" readonly></div><div class="col-md-3"> <label for="usedQuantity" class="col-form-label">Used Quantity<span class="required-field">*</span></label> <input type="number" value="' + data[i].used_qty + '" step="any" min="1" max="' + data[i].available_qty + '" class="form-control" id="usedQuantities-' + i + '" name="usedQuantities[]" placeholder="Used Quantity" required><small id="usedQtyHelp" class="form-text text-muted">Will be added with current used qty</small></div>';
                    }
                    if ($(".existing-products").length > 0) {
                        $(".existing-products").remove();
                    }
                    $('#dynamic-products').html(inputHtml);
                })
                .fail(function () {
                    console.log('Ajax Failed')
                });
        });
    });


    // get sizes for each category
    $(document).ready(function () {
        $(".size-select").on("change", function () {
            let category = $("#productType option:selected").val();
            $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: location.origin + "/admin/sizes",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        id: category
                    }
                })
                .done(function (data) {
                    let html = '<label for="productSizes" class="col-form-label">Quantities for sizes<span class="required-field">*</span></label><div class="row">';
                    let rejectedSizeHtml = '<label for="productSizes" class="col-form-label">Rejected quantities for sizes<span class="required-field">*</span></label><div class="row">';

                    let sizes = data.length;
                    let column = 4;
                    for (var i in data) {
                        if (sizes == 1) {
                            column = 12;
                        }
                        else if(sizes == 2){
                            column = 6;
                        }else if(sizes == 3){
                            column = 4;
                        }else  if(sizes == 4){
                            column = 3;
                        }

                        html += '<div class="col-md-'+column+' form-group"> <div class="input-group mb-2"> <div class="input-group-prepend"> <div class="input-group-text">Size:' + data[i] + '</div></div><input type="number" step="1" min="0" class="form-control" id="size-' + data[i] + '" name="quantities[]" placeholder="Finished Qty" required> </div></div>';

                        rejectedSizeHtml += '<div class="col-md-'+column+' form-group"> <div class="input-group mb-2"> <div class="input-group-prepend"> <div class="input-group-text">Size:' + data[i] + '</div></div><input type="number" step="1" min="0" class="form-control" id="size-' + data[i] + '" name="rejectedQuantities[]" placeholder="Rejected Qty" > </div></div>'
                    }
                    html += '</div>';

                    if ($(".existing-sizes").length > 0) {
                        $(".existing-sizes").remove();
                    }

                    $('#dynamic-sizes').html(html);
                    $('#dynamic-rejected-sizes').html(rejectedSizeHtml);
                })
                .fail(function () {
                    console.log('Ajax Failed')
                });
        });
    });

})();
