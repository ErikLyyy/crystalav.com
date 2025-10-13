@extends('layouts.homeLayout')
@if (isset($category))
    @section('title', $category->title)
@else
    @section('title', 'Rental Request')
@endif

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

@section('content')
    <!-- head-title  -->
    <div id="head-title-wp" class="list-products-head-wp"
        style="
          background-image: url({{ asset('public/media/images/A;mbient&Theatrical-lighting.webp') }});
        ">
        <h1 id="head-title">Custom Design Speaker Array at Work</h1>
    </div>
    <div id="breadcrumb-contact-wp">
        <p class="link-contact">
            If you cannot locate a specific product,<a href="{{ url('contact') }}">contact us</a>.
        </p>
        @if (isset($menu) && isset($category))
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ url('category', $menu->slug) }}">{{ $menu->title }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('category/' . $menu->slug . '/' . $category->slug) }}">{{ $category->title }}</a>
                </li>
            </ol>
        @endif
    </div>
    <div id="oversidebar"></div>
    <div id="list-products-wp" class="list-products-pg">
        <!-- sidebar  -->
        <div id="sidebar">
            @if ($list_subcategory !== "<ul id='list-cat'>")
                <div class="close-btn">
                    <button>Close</button>
                </div>
                <div id="subcategory">
                    <h3 class="sidebar-cat-title">
                        SUBCATEGORIES
                        <span><svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px"
                                fill="#fff">
                                <path d="M232-444v-72h496v72H232Z" />
                            </svg></span>
                    </h3>


                    <div class="list-cat-wp">
                        <div class="btn-clear">
                            <button class="clear-sidebar">CLEAR SELECTION</button>
                        </div>
                        {!! $list_subcategory !!}
                    </div>
                </div>
            @endif
            {!! $list_filter !!}
        </div>
        <!-- list products-wrapper  -->
        <div id="wrapper">
            <form action="" method="GET">
                <div class="product-filters-options-wp">
                    <div class="count-product-wp">
                        <h1 class="num-product">{{ $countProduct }} Products</h1>
                    </div>
                    <!-- filter button  -->
                    <div class="product-filters-options">
                        <div class="subcategories-filters">
                            <button class="filter-btn"><svg xmlns="http://www.w3.org/2000/svg" height="16px"
                                    viewBox="0 -960 960 960" width="16px" fill="#fff">
                                    <path
                                        d="M456-144v-240h72v84h288v72H528v84h-72Zm-312-84v-72h240v72H144Zm144-132v-84H144v-72h144v-84h72v240h-72Zm144-84v-72h384v72H432Zm144-132v-240h72v84h168v72H648v84h-72Zm-432-84v-72h384v72H144Z" />
                                </svg>SUBCATEGORIES & FILTERS</button>
                        </div>
                        <!-- form search  -->
                        <div class="form-s">
                            <input type="text" id="s" name="s" placeholder="Search" />

                            <button type="submit" class="btn-search">
                                <span><svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960"
                                        width="20px" fill="#000">
                                        <path
                                            d="M765-144 526-383q-30 22-65.79 34.5-35.79 12.5-76.18 12.5Q284-336 214-406t-70-170q0-100 70-170t170-70q100 0 170 70t70 170.03q0 40.39-12.5 76.18Q599-464 577-434l239 239-51 51ZM384-408q70 0 119-49t49-119q0-70-49-119t-119-49q-70 0-119 49t-49 119q0 70 49 119t119 49Z" />
                                    </svg></span>
                            </button>
                            <ul id="search-results"></ul>
                        </div>
                        <!-- form select  -->
                        <div class="select-form">
                            <p>Sort your search by:</p>
                            <ul>
                                <li class="options-form selected" value="0">Sort your serch by:</li>
                                <li class="options-form" value="1">Title (A-Z)</li>
                                <li class="options-form" value="2">Title (Z-A)</li>
                            </ul>
                        </div>
                        <select class="options" name="" id="">
                            <option value="0">Sort your search by:</option>
                            <option value="1">Title(A-Z)</option>
                            <option value="2">Title(Z-A)</option>
                        </select>
                    </div>
                </div>
            </form>
            <!-- list products  -->
            <ul class="list-products">
                @foreach ($list_product as $product)
                    <li class="product">
                        <div class="product-wp">
                            <div class="product-thumb">
                                <div>
                                    <a href="{{ url('category/' . $product->category->menu->slug . '/' . $product->category->slug . '/' . $product->slug) }}"
                                        title="{{ $product->name }}" class="thumb-link">
                                        <img src="{{ asset('public/' . $product->thumbnail) }}" alt="" />
                                    </a>
                                </div>
                            </div>
                            <div class="name-qty-wp">
                                <a href="{{ url('category/' . $product->category->menu->slug . '/' . $product->category->slug . '/' . $product->slug) }}"
                                    class="product-name">{{ $product->name }}</a>
                                <!-- qty  -->
                                <div class="qty-wp">
                                    <button class="decrement">-</button>
                                    <input type="number" min="1" value="1" name="qty">
                                    <button class="increment">+</button>
                                    <button class="btn_add" value="{{ $product->id }}">ADD</button>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach

            </ul>
            {{ $list_product->onEachSide(1)->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}

        </div>
    </div>
    <script>
        const SEARCH_SUGGESTIONS_URL = "{{ route('search.suggestions') }}";
        const ADD_CART_AJAX_URL = "{{ route('add_cart_ajax') }}";
        const PRODUCT_THUMBNAIL_BASE_PATH = "{{ asset('public') }}";
        const PRODUCT_BASE_URL =
            "{{ url('category') }}";
        const AJAX_URL = "{{ url()->full() }}";
        let category_id = "";
        let menu_slug = "";
        let category_slug = "";
        let s = "";
        @if (isset($s))
            s = "{{ $s }}"
        @endif
        let list_slug = ['api'];
        @if (isset($list_slug))
            list_slug = @json($list_slug);
        @endif
        @if (isset($category))
            category_id = {{ $category->id }};
            category_slug = "{{ $category->slug }}";
            menu_slug = "{{ $menu->slug }}";
        @endif
    </script>
@endsection
