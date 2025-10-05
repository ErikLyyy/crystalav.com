$(document).ready(function () {
    var windowWidth = $(window).width();
    $(window).resize(function () {
        windowWidth = $(this).width();
    })

    function preventScrollOutside($element) {
        $element.each(function () {
            const $el = $(this);
            let startY = 0;

            // Block scrolling outside html on desktop devices
            $el.on('wheel', function (e) {
                const scrollTop = $el.scrollTop();
                const scrollHeight = $el[0].scrollHeight;
                const clientHeight = $el.outerHeight();
                const deltaY = e.originalEvent.deltaY;

                const atTop = scrollTop <= 0;
                const atBottom = scrollTop + clientHeight >= scrollHeight - 1;

                if ((deltaY < 0 && atTop) || (deltaY > 0 && atBottom)) {
                    e.preventDefault();
                }
            });

            // Block scrolling outside html on mobile devices (touch screen)
            $el.on('touchstart', function (e) {
                startY = e.originalEvent.touches[0].clientY;
            });

            $el.on('touchmove', function (e) {
                const scrollTop = $el.scrollTop();
                const scrollHeight = $el[0].scrollHeight;
                const clientHeight = $el.outerHeight();
                const currentY = e.originalEvent.touches[0].clientY;
                const diffY = startY - currentY;

                const atTop = scrollTop <= 0;
                const atBottom = scrollTop + clientHeight >= scrollHeight - 1;

                if ((diffY < 0 && atTop) || (diffY > 0 && atBottom)) {
                    e.preventDefault();
                }
            });
        });
    }

    // scroll to hide/showed navbar 
    var headerHeight = $('#header').outerHeight();
    var lastScrollTop = 0;
    var accumulatedScroll = 0;
    $(window).scroll(function () {
        var st = $(this).scrollTop();
        if (st <= 0) {                      //reset value for header when the user scrolls to the top of the page on IOS device
            accumulatedScroll = 0;
            $('#header').css('top', '0px');
            lastScrollTop = 0;
            return;
        }
        if (st > lastScrollTop) {
            // Scroll Down
            accumulatedScroll += (st - lastScrollTop);
            if (accumulatedScroll > headerHeight) {
                accumulatedScroll = headerHeight;            // Set the max value for top
            }
            $('ul#main-menu li a.dropdown-toggle').next('ul.sub-menu').stop().fadeOut(0);
            $('ul.sub-menu').removeClass('showed');
            $('#menu-btn').removeClass('opened');
            $('#main-menu').removeClass('showed-menu');

        } else {
            // Scroll Up
            accumulatedScroll -= (lastScrollTop - st);
            if (accumulatedScroll < 0) {
                accumulatedScroll = 0;              // Set the min value
            }
            $('ul#main-menu li a.dropdown-toggle').next('ul.sub-menu').stop().fadeOut(0);
            $('ul.sub-menu').removeClass('showed');
            $('#menu-btn').removeClass('opened');
            $('#main-menu').removeClass('showed-menu');
        }
        $('#header').css('top', -accumulatedScroll + 'px');
        lastScrollTop = st;
    })

    // Header dropdown menu 
    $('ul#main-menu li a.dropdown-toggle').click(function (e) {
        if ($(this).next('ul.sub-menu').hasClass('showed')) {
            if (windowWidth > 1150) {
                $(this).next('ul.sub-menu.showed').stop().fadeOut(200);
            } else {
                $(this).next('ul.sub-menu.showed').stop().slideUp(200);
            }
            $(this).next('ul.sub-menu').removeClass('showed');
        } else {
            if (windowWidth > 1150) {
                $('ul#main-menu li a.dropdown-toggle').next('ul.sub-menu').stop().fadeOut(200);
            } else {
                $('ul#main-menu li a.dropdown-toggle').next('ul.sub-menu').stop().slideUp(200);
            }
            $('ul#main-menu li a.dropdown-toggle').next('ul.sub-menu').removeClass('showed');     //remove class showed when user open another sub-menu
            $(this).next('ul.sub-menu').addClass('showed');
            if (windowWidth > 1150) {
                $(this).next('ul.sub-menu.showed').stop().fadeIn(200);
            } else {
                $(this).next('ul.sub-menu.showed').stop().slideDown(200);
            }
        }
        e.preventDefault();
        e.stopPropagation();                // sub-menu won fadeOut when user clicks it
    })

    // menu-btn click event
    $("#menu-btn").click(function (e) {
        $("#main-menu").toggleClass('showed-menu')
        $(this).toggleClass('opened');
        $('ul.sub-menu').removeClass('showed');
        $('ul.sub-menu').fadeOut(0);
        e.stopPropagation();
    })
    $("ul.sub-menu").click(function (e) {
        e.stopPropagation();
    })

    // Block scrolling outside html
    preventScrollOutside($('#main-menu'))

    // select form (list-product) event
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
        var select_value = $(this).val();
        $.each(form_select, function (key, value) {
            if (select_value == value.value) {
                $(form_select[select_value]).attr("selected", "selected")
            }
        })
        e.stopPropagation();
    })

    //sidebar click event
    $('.sidebar-cat-title span').click(function () {
        $(this).parent().next().slideToggle(200);
    })
    $('ul#list-cat li span').click(function () {
        $(this).parent().next().slideToggle(200);
    })

    //clear sidebar
    $('.clear-sidebar').click(function () {
        $('.active').removeClass('active')
    })
    $('.clear-filter').click(function () {
        $('.form-checkbox input:checkbox').prop('checked', false)
    })

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

    // product filters click event
    $('button.filter-btn').click(function () {
        $('#oversidebar').show()
        $('#sidebar').show()
    })
    $('.close-btn').click(function () {
        $('#oversidebar').hide()
        $('#sidebar').hide()
    })
    $('#oversidebar').click(function () {
        $('#oversidebar').hide()
        $('#sidebar').hide()
    })

    //pagination
    $('.current-page').click(function (e) {
        e.preventDefault
    })

    // relative carousel btn event
    var carouselTranslateX = 0;
    var liWidth = $('.list-products li:first').width() + 7 // 5px (margin-left) + 2px (border)
    var ulWidth = $('ul.list-products').width()
    var countLi = $('.list-products li').length
    var maxTranslateX = liWidth * countLi - 5 - ulWidth;  // -5px margin-left of last li 
    $('.relative-carousel-btn-next').click(function () {
        $('.relative-carousel-btn-back').css('display', 'flex')
        carouselTranslateX += liWidth * 4;
        if (carouselTranslateX >= maxTranslateX) {
            carouselTranslateX = maxTranslateX
            $('.relative-carousel-btn-next').css('display', 'none')
        }
        $('.detail-product-pg ul.list-products').css({ transform: 'translateX(-' + carouselTranslateX + 'px) translateZ(0px)', transition: 'all 0.5s' });
    })
    $('.relative-carousel-btn-back').click(function () {
        $('.relative-carousel-btn-next').css('display', 'flex')
        carouselTranslateX -= liWidth * 4;
        if (carouselTranslateX <= 0) {
            carouselTranslateX = 0
            $('.relative-carousel-btn-back').css('display', 'none')

        }
        $('.detail-product-pg ul.list-products').css({ transform: 'translateX(-' + carouselTranslateX + 'px) translateZ(0px)', transition: 'all 0.5s' });
    })

    // $('.relative-carousel-wp').scroll(function () {
    //     var scrollVal = $('.relative-carousel-wp li:first').position().left
    //     // console.log($('.relative-carousel-wp li:first').position().left)
    //     $(this).children('.relative-carousel-btn-back').css('left', -scrollVal)
    //     console.log(scrollVal);
    // })


    // code to handle when clicking on document
    $('html').click(function () {               // sub-menu will fadeout when user clicks it
        $('ul.sub-menu').removeClass('showed');
        if (windowWidth > 1150) {
            $('ul.sub-menu').fadeOut(200);
        } else {
            $('ul.sub-menu').fadeOut(0);
        }
        $('#menu-btn').removeClass('opened');
        $('#main-menu').removeClass('showed-menu');
        $('.select-form ul').slideUp(200)
    })
})

