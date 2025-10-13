$(document).ready(function () {
    //decrement, increment qty product event
    $('.decrement').click(function () {
        var current_value = +$(this).next().val();
        var new_value = 1;
        if (current_value > 1) {
            var new_value = current_value - 1;
        }
        $(this).next().val(new_value)
    })
    $('.increment').click(function () {
        var current_value = +$(this).prev().val();
        var new_value = current_value + 1;
        $(this).prev().val(new_value)
    })

    //add product event
    $('.btn_add').mousedown(function () {
        $(this).css({ backgroundColor: "#8b0128", color: "#fff" });
    })
    $('.btn_add').mouseup(function () {
        $(this).css({ backgroundColor: "#cf1047", color: "#fff" });
    })
    $('.btn_add').mouseleave(function () {
        $(this).css({ backgroundColor: "#fff", color: "#000", transition: "all 0.3s ease" });
    })
    $('.btn_add').mouseenter(function () {
        $(this).css({ backgroundColor: "#cf1047", color: "#fff", transition: "all 0.3s ease" });
    })

    $(document).on('click', ".btn_add", function () {
        var id = $(this).val();
        var parent = $(this).closest('.qty-wp');
        var qty = parent.find("input[type='number']").val();

        if (qty == undefined) {
            qty = 1;
        }
        var data = {
            id: id,
            qty: qty
        };
        $(this).text("ADDED");
        $("#cart a span").remove();
        $("#cart a").append("<span></span>");
        $.ajax({
            url: ADD_CART_AJAX_URL,
            method: 'GET',
            data: data,
            dataType: 'json',
            success: function (data) {
                console.log(data)
                $("#cart a span").text(data['cartCount']);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr, ajaxOptions);
                alert(thrownError);
            }
        })
    })
})
