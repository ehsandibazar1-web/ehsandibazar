function setShippingCost(route,type,totalWeight) {
    var postType = $('input[name=postType]').val();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        type: "post",
        url: route,
        data: {
            type: type,
            postType: postType,
            totalWeight: totalWeight,
            output: 1,
            _token: CSRF_TOKEN
        },

        success: function (data) {
            if (data.status == 200) {
                var totalPrice = $('.total-price').html();
                totalPrice = totalPrice.replace("تومان", "");
                totalPrice = totalPrice.replace(",", "") == "رایگان" ? 0 : totalPrice.replace(",", "");
                $('input[name="shipping-method"]').val(type);
                if (data.shippingCost == 0) {
                    $('.shipping-value').html("هزینه پیک به عهده مشتری");

                } else {
                    $('.shipping-value').html(data.shippingCost.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+ " تومان ");
                }
                var totalAll = parseInt(data.shippingCost) + parseInt(totalPrice);
                $('.price-for-pay').html(totalAll.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+ " تومان ");
            } else {
                Swal.fire({
                    title: "خطا!",
                    text: "خطا در دریافت هزینه ارسال...",
                    icon: "error",
                    button: "تایید",
                });
            }
        },
        error: function (error) {
            //alert(error);
        }
    });
}

function setPaymentType(value){
    $('input[name="payment"]').val(value);
}