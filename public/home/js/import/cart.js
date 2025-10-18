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

    $('.qty-wp input').on('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
        if ($(this).val() == 0) {
            $(this).val(1)
        }
    });

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
                $("#cart a span").text(data['cartCount']);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr, ajaxOptions);
                alert(thrownError);
            }
        })
    })

    //delete cart
    $(document).on('click', ".quote .delete", function () {
        var rowId = $(this).val();
        var parent = $(this).parents('li.product')
        var data = {
            rowId: rowId,
            action: "delete"
        };

        $.ajax({
            url: CART_AJAX_URL,
            method: 'GET',
            data: data,
            dataType: 'json',
            success: function (data) {
                if (data['cartCount'] == 0) {
                    $("#cart a span").remove();
                } else {
                    $("#cart a span").text(data['cartCount']);
                    $(".quote h2 span").text(data['cartCount']);
                }
                parent.remove()
                if ($(".quote ul.list-products li").length == 0) {
                    $('#content .container #quote-wp').remove()
                    $('#content .container').append(`<h2 style="margin: 15px 0px; text-align: center; ">You have <span style="color:brown"> ${data['cartCount']}</span> products in your quote</h2>`)
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr, ajaxOptions);
                alert(thrownError);
            }
        })
    })

    //update cart
    function updateCart(data) {
        $.ajax({
            url: CART_AJAX_URL,
            method: 'GET',
            data: data,
            dataType: 'json',
            success: function (data) {
                $("#cart a span").text(data['cartCount']);
                $(".quote h2 span").text(data['cartCount']);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr, ajaxOptions);
                alert(thrownError);
            }
        })
    }

    $(document).on('change', ".quote .qty-wp input", function () {
        var inputValue = $(this).val();
        var rowId = $(this).parents('.qty-wp').find('.delete').val()
        var data = {
            rowId: rowId,
            inputValue: inputValue,
            action: 'update'
        };
        updateCart(data)
    })

    $(document).on('click', ".quote .qty-wp .decrement", function () {
        var inputValue = $(this).parents('.qty-wp').find('input').val();
        var rowId = $(this).parents('.qty-wp').find('.delete').val()
        var data = {
            rowId: rowId,
            inputValue: inputValue,
            action: 'update'
        };
        updateCart(data)
    })

    $(document).on('click', ".quote .qty-wp .increment", function () {
        var inputValue = $(this).parents('.qty-wp').find('input').val();
        var rowId = $(this).parents('.qty-wp').find('.delete').val()
        var data = {
            rowId: rowId,
            inputValue: inputValue,
            action: 'update'
        };
        updateCart(data)
    })
})
