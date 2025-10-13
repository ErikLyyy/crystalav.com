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
    })
})
