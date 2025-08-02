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

    //add sidebar ajax event
    $("#category").change(function () {
        var category_id = $(this).val();
        var subcategory = $("#subcategory").val();
        var edit_value = $("#edit_value").val();        //get sidebar_id when edit
        var data = {
            category_id: category_id,
            subcategory: subcategory,
            edit_value: edit_value
        };
        $.ajax({
            url: "../ajax",
            method: 'GET',
            data: data,
            dataType: 'json',
            success: function (data) {
                //dump subcategory data by category
                if (data['list_subcategory']) {
                    var subSelect = $("#subcategory");
                    subSelect.empty();
                    subSelect.append('<option value="">Select parent subcategory</option>');
                    if (data['subcategory'] == 0) {
                        subSelect.append('<option value="0" selected>Set as parent subcategory</option>');
                    } else {
                        subSelect.append('<option value="0">Set as parent subcategory</option>');
                    }
                    data['list_subcategory'].forEach(function (item) {
                        subSelect.append('<option value="' + item.id + '">' + item.title + '</option>');
                    });
                } else {
                    return;
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr, ajaxOptions);
                alert(thrownError);
            }
        })
    })
    $("#subcategory").change(function () {
        var subcategory_id = $(this).val();
        var category_id = $('#category').val()
        var data = {
            subcategory_id: subcategory_id
        };
        $.ajax({
            url: "../ajax",
            method: 'GET',
            data: data,
            dataType: 'json',
            success: function (data) {
                //select subcategory, the main category of this subcategory will seclect
                if (data) {
                    $("#category").val(data.id);
                } else {
                    $("#category").val('');
                }
                if (subcategory_id == 0) {
                    $("#category").val(category_id);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr, ajaxOptions);
                alert(thrownError);
            }
        })
    })
});

