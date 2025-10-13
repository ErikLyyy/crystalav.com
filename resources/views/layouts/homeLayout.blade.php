<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title')</title>

    <link rel="icon" href="{{ asset('public/media/images/default-images/logo-1.jpg') }}" />
    <link rel="stylesheet" href="{{ asset('public/home/css/reset.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/home/css/style.css') }}" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=keyboard_arrow_down" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>

<body>
    <!-- HEADER  -->
    <div id="header">
        <div id="nav-wp">
            <a href="{{ url('/') }}" id="logo"><img
                    src="{{ asset('public/media/images/default-images/logo.png') }}"
                    alt="Crystal Audio Visual Services, Inc." title="Crystal Audio Visual Services, Inc." /></a>
            <div id="menu-wp">
                <div id="info">
                    <p>Service - Support: <span>866.411.6468</span></p>
                </div>
                <div id="main-menu-wp">
                    <ul id="main-menu">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ url('/rental_request') }}">Rental Request</a></li>
                        {!! $menu_navbar !!}
                        <li><a href="?page=contact">Contact Us</a></li>
                    </ul>
                    <div id="toggle-menu-wp">
                        <div id="cart">
                            <a href="?page=quote">
                                <svg xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960"
                                    width="22px" fill="#6f6f6f">
                                    <path
                                        d="M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z" />
                                </svg>
                                @if (Cart::count() > 0)
                                    <span>{{ Cart::count() }}</span>
                                @endif
                            </a>
                        </div>
                        <div id="menu-btn">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="head-blank"></div>
    <div id="content">
        @section('content')

        @show
    </div>
    <!-- FOOTER  -->
    <div id="footer">
        <div class="container">
            <h1 class="main-title">
                COPYRIGHT © 2021 CRYSTAL AUDIO VISUAL SERVICES, INC. - ALL RIGHTS
                RESERVED
            </h1>
        </div>
    </div>
    <script src="{{ asset('public/home/js/jquery.js') }}"></script>
    <script type="module" src="{{ asset('public/home/js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Initialize Swiper -->
    <script>
        var swiper = new Swiper(".mySwiper", {
            loop: true,
            spaceBetween: 10,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesProgress: true,
        });
        var swiper2 = new Swiper(".mySwiper2", {
            loop: true,
            spaceBetween: 10,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            thumbs: {
                swiper: swiper,
            },
        });
    </script>
</body>

</html>
