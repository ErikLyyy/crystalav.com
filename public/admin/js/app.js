$(document).ready(function () {
    $('.nav-link.active .sub-menu').slideDown();
    // $("p").slideUp();

    $('#sidebar-menu .arrow').click(function () {
        $(this).parents('li').children('.sub-menu').slideToggle();
        $(this).toggleClass('fa-angle-right fa-angle-down');
    });

    $("input[name='checkall']").click(function () {
        var checked = $(this).is(':checked');
        $('.table-checkall tbody tr td input:checkbox').prop('checked', checked);
    });


    // Sự kiện change của input file
    $("#image").change(function () {
        // alert('ok');
        var input = this;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                // Hiển thị ảnh trên container
                // $(".thumb").remove();
                $("#uploadedImage").attr("src", e.target.result);
                $("#uploadedImage").css({
                    'display': 'block',
                    'height': '150px'
                });
            };
            reader.readAsDataURL(input.files[0]);
        }
    });


    $("#sub_image").change(function () {
        var input = this;
        if (input.files && input.files.length > 0) {
            // Lặp qua mảng các tệp được chọn
            $(".product_sub_image .sub_thumb img").remove();
            for (var i = 0; i < input.files.length; i++) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    // Hiển thị ảnh trên container
                    // $(".product_sub_image").remove();
                    $(".sub_thumb").append(
                        '<img style="height:150px;" class="selected-image mr-2" src="' + e
                            .target
                            .result + '" alt="Selected Image">');
                };
                reader.readAsDataURL(input.files[i]);
            }
        }
    });

    $('.btn_upload').click(function () {
        $(this).next('input').click();
    })
});

