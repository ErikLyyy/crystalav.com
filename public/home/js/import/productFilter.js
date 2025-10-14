$(document).ready(function () {
    function checkWindowSize() {
        if (window.matchMedia("(min-width: 768px)").matches) {
            $(".list-products-pg #sidebar").show();

        } else {
            $(".list-products-pg #sidebar").hide();
            $("#oversidebar").hide();
        }
    }
    checkWindowSize();

    $(window).resize(function () {
        checkWindowSize();
    })

    function buildAjaxData(page = 1) {
        return {
            s: s,
            sub_cat_slug: sub_cat_slug,
            list_slug: list_slug,
            select_value: select_value,
            ajax: 1,
            category_slug: category_slug,
            menu_slug: menu_slug,
            page: page
        };
    }

    function toggleElement(array, element) {
        var index = array.indexOf(element);
        if (index === -1) {
            // Phần tử không tồn tại trong mảng, thêm vào
            array.push(element);
        } else {
            // Phần tử đã tồn tại trong mảng, xóa nó
            array.splice(index, 1);
        }
        return array;
    }

    // ajax function

    function ajax(data) {
        $.ajax({
            url: AJAX_URL,
            method: 'GET',
            data: data,
            dataType: 'json',
            success: function (data) {
                $("ul.list-products li").remove();
                $(".num-product").text(data['countProduct'] + " Products");
                $.each(data['list_product'].data, function (index, item) {

                    var li = $('<li>', {
                        class: "product"
                    });
                    var product_wp = $('<div>', {
                        class: "product-wp"
                    });
                    var product_thumb = $('<div>', {
                        class: "product-thumb"
                    });
                    var div = $('<div>');
                    var a_img = $('<a>', {
                        href: PRODUCT_BASE_URL + '/' + menu_slug + '/' + category_slug + '/' + item.slug,
                        class: "thumb-link",
                        title: item.name
                    })
                    var img = $('<img>', {
                        src: PRODUCT_THUMBNAIL_BASE_PATH + '/' + item.thumbnail,
                    })
                    var name_qty_wp = $('<div>', {
                        class: "name-qty-wp"
                    });
                    var a_title = $('<a>', {
                        href: PRODUCT_BASE_URL + '/' + menu_slug + '/' + category_slug + '/' + item.slug,
                        class: "product-name",
                        text: item.name
                    })
                    var qty_wp = $('<div>', {
                        class: "qty-wp"
                    });
                    var btn_add = $('<button>', {
                        class: "btn_add",
                        name: "add",
                        value: item.slug,
                        text: "ADD"
                    })
                    var decrement = $('<button>', {
                        class: "decrement",
                        text: "-"
                    })
                    var input_num = $('<input>', {
                        type: "number",
                        min: 1,
                        value: 1,
                        name: "qty"
                    })
                    var increment = $('<button>', {
                        class: "increment",
                        text: "+"
                    })
                    $("ul.list-products").append(li);
                    $(li).append(product_wp);
                    $(product_wp).append(product_thumb);
                    $(product_wp).append(name_qty_wp);
                    $(product_thumb).append(div);
                    $(div).append(a_img);
                    $(a_img).append(img);
                    $(name_qty_wp).append(a_title);
                    $(name_qty_wp).append(qty_wp);

                    $(qty_wp).append(decrement);
                    $(qty_wp).append(input_num);
                    $(qty_wp).append(increment);
                    $(qty_wp).append(btn_add);
                })

                $(".pagination").html(data['pagin']);
                $('#pagination a').on('click', function (e) {
                    e.preventDefault();
                });
                if (data['list_filter'] != undefined) {
                    $(".sidebar_filter").remove();
                    $(".list-products-pg #sidebar").append(data['list_filter']);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr, ajaxOptions);
                alert(thrownError);
            }
        })
    }

    //sidebar click event

    $(document).on('click', '.sidebar-cat-title span', function () {
        $(this).parent().next().slideToggle(200);
    })
    $('ul#list-cat li span').click(function () {
        $(this).parent().next().slideToggle(200);
    })

    // product filters click event
    $('button.filter-btn').click(function (e) {
        console.log('ok');
        e.preventDefault();
        $('#oversidebar').show()
        $('.list-products-pg #sidebar').show()
    })
    $('.close-btn').click(function () {
        $('#oversidebar').hide()
        $('.list-products-pg #sidebar').hide()
    })
    $('#oversidebar').click(function () {
        $('#oversidebar').hide()
        $('.list-products-pg #sidebar').hide()
    })

    //subcategory click event

    let sub_cat_slug = $("ul#list-cat li a.active").attr('href');
    let select_value = 0
    $(document).on('click', 'ul#list-cat li a', function (e) {
        e.preventDefault();
        list_slug = [];
        $("ul#list-cat li a").removeClass('active')
        $(this).addClass("active")
        sub_cat_slug = $(this).attr('href');
        ajax(buildAjaxData(1))
    })

    //clear sidebar

    $(document).on('click', '#subcategory .clear-sidebar', function (e) {
        e.preventDefault();
        sub_cat_slug = "";
        s = ""
        $('ul#list-cat li a.active').removeClass('active')
        ajax(buildAjaxData(1))
    })
    $(document).on('click', '.clear-filter', function () {

        var parentListCatWp = $(this).closest('.list-cat-wp');
        var filter_child = parentListCatWp.find('.form-checkbox input');
        console.log(filter_child)
        filter_child.each(function () {
            if ($(this).is(':checked')) {
                $(this).prop('checked', false)
                list_slug.splice(list_slug.indexOf($(this).val()), 1);
            }
        })
        ajax(buildAjaxData(1))
    })

    //filter change event

    $(document).on("change", ".sidebar_filter .filter input", function () {
        var filter_slug = $(this).val();

        list_slug = toggleElement(list_slug, filter_slug);

        ajax(buildAjaxData(1));
    });

    //pagination

    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();

        var page = $(this).attr('href').split('page=')[1];

        ajax(buildAjaxData(page));
    });

    // select form (sort) event

    $('.select-form').click(function (e) {
        $(this).find('ul').slideToggle(200)
        var selected = $(this).find('li.selected').text().trim();
        if (selected == $(this).find('p').text().trim()) {
            $(this).find('li.selected').hover()
        }
        e.stopPropagation();
    })
    $('.select-form ul li').click(function (e) {

        $('.select-form ul li').removeClass('selected');
        $(this).addClass('selected')
        $('.select-form p').text($(this).text())
        $('.select-form ul').slideUp(0)
        //update value for select form hidden
        var form_select = $('select.options option')
        $(form_select).removeAttr("selected")
        select_value = $(this).val();
        $.each(form_select, function (key, value) {
            if (select_value == value.value) {
                $(form_select[select_value]).attr("selected", "selected")
            }
        })
        e.stopPropagation();

        ajax(buildAjaxData(1));
    })

    // code to handle when clicking on document
    $('html').click(function () {
        $('.select-form ul').slideUp(200)
    })

})
