(function(){
    "use strict";

    // dynamic add product fileds
    $(document).ready(function(){
        let maxField = 10;
        let x;
        let length = $("#product-count").val();
        let type = 'edit-calculator';
        if(length === undefined){
            x = 1;
            type = 'calculator';
        }else{
            x = length;
        }
        //Once add button is clicked
        $(".add_button").on("click", function () {
            if(x < maxField){
                x++;
                var unitNames = $('#unitInfo').data("names");
                var unitCodes = $('#unitInfo').data("codes");
                let names = unitNames.split(',');
                let codes = unitCodes.split(',');
                let options = '';
                for(let row = 0; row < names.length; row++ ){
                    options += '<option value="'+codes[row]+'">'+names[row]+'</option>';
                }
                $(".field_wrapper").append('<div class="row"> <div class="col-md-3 form-group"> <label for="products" class="col-form-label">Product Name<span class="required-field">*</span></label> <input type="text" class="form-control" id="products" name="products[]" placeholder="Product name" required> </div><div class="col-md-3 form-group"> <div class="row"> <div class="col-md-6"> <label for="qunatities" class="col-form-label">Quantity<span class="required-field">*</span></label> <input type="number" step="0.01" class="form-control '+type+'" id="qunatities-'+x+'" data-number="'+x+'" name="quantities[]" placeholder="Quantity" min="1" required> </div><div class="col-md-6"> <label for="units" class="col-form-label">Unit<span class="required-field">*</span></label> <select class="form-control" name="units[]" id="units" required> <option value="" disabled>Select a unit</option> '+ options +' </select> </div></div></div><div class="col-md-3 form-group"> <div class="row"> <div class="col-md-6"> <label for="unitPrices[]" class="col-form-label">Unit Price<span class="required-field">*</span> </label> <input type="number" step="0.01" class="form-control '+type+'" id="unitPrices-'+x+'" data-number="'+x+'" name="unitPrices[]" placeholder="Unit price" min="1" required> </div><div class="col-md-6"> <label for="discounts[]" class="col-form-label">Discount</label> <input type="number" step="any" min="0" class="form-control '+type+'" id="discounts-'+x+'" data-number="'+x+'" name="discounts[]" placeholder="Discount"> </div></div></div><div class="col-md-2 form-group"> <label for="singleTotal[]" class="col-form-label">Total<span class="required-field">*</span></label> <input type="text" class="form-control" id="singleTotal-'+x+'" name="singleTotal[]" placeholder="Total" readonly> </div><a class="col-md-1 remove_button btn btn-danger dynamic-btn updateTotalBtn" data-number="'+x+'" title="Remove" href="#" ><i class="fas fa-backspace"></i></a></div>');
            }
        });
        //Once remove button is clicked
        $(".field_wrapper").on('click', '.remove_button', function(e){
            e.preventDefault();
            $(this).parent('div').remove();
            x--;
        });
    });

    // calculate single total, sub total, total for purchase create
    var items =  [];
    $(document).ready(function(){
        $('body').on('change', 'input.calculator', function(){
            let id =  $(this).data("number");
            let quantity = $("#qunatities-"+id).val() > 0 ?  $("#qunatities-"+id).val() :  0;
            let price = $("#unitPrices-"+id).val() > 0 ? $("#unitPrices-"+id).val() : 0;
            let discount = $("#discounts-"+id).val() > 0 ? $("#discounts-"+id).val() : 0;
            let subTotal = 0;
            let totalDiscount = 0;
            let singleTotal = quantity * price;
            $("#singleTotal-"+id).val(singleTotal - (discount / 100) * singleTotal);
            if(items.length == 0)
            {
                items.push({
                    id: id,
                    quantity:  quantity,
                    price:  price,
                    discount: discount
                });
            }
            else
            {
                if(items.some(e => e.id === id)) {
                    items[id-1].quantity = quantity;
                    items[id-1].price = price;
                    items[id-1].discount = discount;
                }
                else
                {
                    items.push({
                        id: id,
                        quantity:  quantity,
                        price:  price,
                        discount:  discount
                    });
                }
            }
            if(items.length > 0){
                subTotal = getTotal(items);
                totalDiscount = getTotalDiscount(items);
            }
            $("#subTotal").val(subTotal);
            $("#totalDiscount").val(totalDiscount);
            // calculate total with and transport cost
            let deliveryCharge = isNaN(parseFloat($("#transportCost").val())) ? 0 : parseFloat($("#transportCost").val());
            let total = subTotal + deliveryCharge;
            $("#total").val(total);
        });
    });

    // calculate single total, sub total, total for purchase edit
    $(document).ready(function(){
        $('body').on('change', 'input.edit-calculator', function(){
            let id =  $(this).data("number");
            let quantity = $("#qunatities-"+id).val() > 0 ?  $("#qunatities-"+id).val() :  0;
            let price = $("#unitPrices-"+id).val() > 0 ? $("#unitPrices-"+id).val() : 0;
            let discount = $("#discounts-"+id).val() > 0 ? $("#discounts-"+id).val() : 0;
            let subTotal = 0;
            let singleTotal = quantity * price;
            let totalDiscount = 0;
            $("#singleTotal-"+id).val(singleTotal - (discount / 100) * singleTotal);
            let length = $("#product-count").val();
            if(items.length == 0)
            {
                for(var i = 1; i <= length; i++)
                {
                    items.push({
                        id: i,
                        quantity: $("#qunatities-"+i).val(),
                        price:  $("#unitPrices-"+i).val(),
                        discount:  $("#discounts-"+i).val()
                    });
                }
            }
            else
            {
                if(items.some(e => e.id === id)) {
                    items[id-1].quantity = quantity;
                    items[id-1].price = price;
                    items[id-1].discount = discount;

                }
                else
                {
                    items.push({
                        id: id,
                        quantity:  quantity,
                        price:  price,
                        discount:  discount,
                    });
                }
            }
            if(items.length > 0){
                subTotal = getTotal(items);
                totalDiscount = getTotalDiscount(items);
            }
            $("#subTotal").val(subTotal);
            $("#totalDiscount").val(totalDiscount);
            let deliveryCharge = isNaN(parseFloat($("#transportCost").val())) ? 0 : parseFloat($("#transportCost").val());
            let total = subTotal + deliveryCharge;
            $("#total").val(total)
        });
    });

    // calculate sub total amount
    function getTotal(items)
    {

        let subTotal = 0;
        for(let i = 0; i < items.length; i++)
        {
            let singleTotal = parseFloat(items[i].quantity) * parseFloat(items[i].price);
            let discount =  (items[i].discount / 100) * singleTotal;
            subTotal += singleTotal -  discount;
        }
        return subTotal;
    }

    // calculate total discount
    function getTotalDiscount(items)
    {
        let totalDiscount = 0;
        for(let i = 0; i < items.length; i++)
        {
            let singleTotal = parseFloat(items[i].quantity) * parseFloat(items[i].price);
            let discount =  (items[i].discount / 100) * singleTotal;
            totalDiscount +=  discount;
        }
        return totalDiscount;
    }

    // calculate total when remove a row
    $(document).ready(function(){
        $(".field_wrapper").on('click', '.remove_button', function(e){
            let num = $(this).data("number") - 1;
            let subTotal = $("#subTotal").val();
            let totalDiscount = $("#totalDiscount").val();
            if (num > -1) {
                items.splice(num, 1);
            }
            subTotal = getTotal(items);
            totalDiscount = getTotalDiscount(items);
            $("#totalDiscount").val(totalDiscount);
            $("#subTotal").val(subTotal);

            // calculate total with discuont and transport cost
            let deliveryCharge = isNaN(parseFloat($("#transportCost").val())) ? 0 : parseFloat($("#transportCost").val());
            let total = subTotal;
            total = total + deliveryCharge;
            $("#total").val(total);
        });

    });

    // calculate final amount
    $(document).ready(function(){
        $("#totalDiscount, #transportCost").on("change", function () {
            let subTotal = isNaN(parseFloat($("#subTotal").val())) ? 0 : parseFloat($("#subTotal").val());
            let deliveryCharge = isNaN(parseFloat($("#transportCost").val())) ? 0 : parseFloat($("#transportCost").val());
            let total = subTotal + deliveryCharge;
            $("#total").val(total)
        });
    });
})();
