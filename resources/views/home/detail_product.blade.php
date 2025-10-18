@extends('layouts.homeLayout')
@section('title', $product->name)

@section('content')
    <!-- CONTENT  -->
    <style>
        .swiper-slide {
            height: auto !important;
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: #a3a3a3 !important;
        }


        .swiper.mySwiper .swiper-slide {
            margin: auto 1px !important;
        }

        .swiper {
            width: 100%;
            height: auto;
        }

        .swiper-slide img,
        .swiper-slide video {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 10px;
        }

        .mySwiper {
            margin-top: 10px;
            height: 100px;
        }

        .mySwiper .swiper-slide {
            opacity: 0.5;
        }

        .mySwiper .swiper-slide-thumb-active {
            opacity: 1;
        }

        .mySwiper .swiper-slide {
            position: relative;
            cursor: pointer;
        }

        .mySwiper .thumb-video {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 8px;
            display: block;
        }

        .mySwiper .video-slide::after {
            content: "▶";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 40px;
            color: rgb(164, 164, 164);
            opacity: 0.8;
            pointer-events: none;
        }
    </style>
    <div id="head-title-wp" class="list-products-head-wp"
        style="
          background-image: url({{ asset('public/media/images/A;mbient&Theatrical-lighting.webp') }});
                ">
        <h1 id="head-title">Ambient & Theatrical Lighting for Private Events</h1>
    </div>
    <div class="container detail-product-pg">
        <div id="breadcrumb-contact-wp">
            <ol class="breadcrumb mg-t">
                <li class="breadcrumb-item">
                    <a href="{{ url('category', $product->category->menu->slug) }}">{{ $product->category->menu->title }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a
                        href="{{ url('category/' . $product->category->menu->slug . '/' . $product->category->slug) }}">{{ $product->category->title }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a
                        href="{{ url('category/' . $product->category->menu->slug . '/' . $product->category->slug . '/' . $product->slug) }}">{{ $product->name }}</a>
                </li>
            </ol>
        </div>
        <div id="list-products-wp">
            <!-- sidebar  -->
            @if (count($relative_products)>4)

                <div id="sidebar">
                    <h2>Products related to this item</h2>
                    <div class="relative-carousel-wp">
                        <!-- <div class="relative-carousel-btn-back">
                            <button>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                height="20px" viewBox="0 -960 960 960" width="20px" fill="#000">
                                <path d="M624-96 240-480l384-384 68 68-316 316 316 316-68 68Z" />
                            </svg>
                        </button>
                        </div> -->
                        <ul class="list-products flex-coluum">
                            @foreach ($relative_products as $relative_product)
                                <li class="product">
                                    <div class="product-wp">
                                        <div class="product-thumb">
                                            <div><a href="{{ url('category/' . $relative_product->category->menu->slug . '/' . $relative_product->category->slug . '/' . $relative_product->slug) }}"
                                                    class="thumb-link"><img
                                                        src="{{ $relative_product->thumbnail ? asset('public/' . $relative_product->thumbnail) : url('public/media/images/default-images/default-image.webp') }}"
                                                        alt=""></a>
                                            </div>
                                        </div>
                                        <div class="name-qty-wp">
                                            <a href="{{ url('category/' . $relative_product->category->menu->slug . '/' . $relative_product->category->slug . '/' . $relative_product->slug) }}"
                                                class="product-name">{{ $relative_product->name }}</a>
                                            <div class="qty-wp">
                                                <button class="btn_add" value="{{ $relative_product->id }}">ADD TO
                                                    QUOTE</button>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach

                            </ul>
                            <!-- <div class="relative-carousel  -->
                        </div>
                    </div>
                @endif
                    <!-- product  -->
            <div id="wrapper">
                <div id="detail-product-wp">
                    <div id="product-sumary">
                        <div class="carousel-wp">
                    <div class="swiper-wp">
                        {{-- Swiper main --}}
                        <div class="swiper mySwiper2" style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff;">
                            <div class="swiper-wrapper">
                                @foreach ($product->media as $media)
                                    <div class="swiper-slide">
                                        <div class="flex-column">
                                            <div class="flex-row">
                                                @if ($media->media_type === 'thumbnail')
                                                    <img src="{{  asset('public/' . $media->file_path)  }}" alt="Product Image" />
                                                @elseif ($media->media_type === 'image')
                                                    <img src="{{  asset('public/' . $media->file_path)  }}" alt="Product Image" />
                                                @elseif ($media->media_type === 'video')
                                                    <video controls preload="metadata">
                                                        <source src="{{  asset('public/' . $media->file_path)  }}" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                                </div>

                                {{-- Swiper thumbnail --}}
                                <div class="swiper mySwiper" thumbsSlider>
                                    <div class="swiper-wrapper">
                                        @foreach ($product->media as $media)
                                            <div class="swiper-slide {{ $media->media_type === 'video' ? 'video-slide' : '' }}">
                                                <div class="flex-column">
                                                    <div class="flex-row">
                                                        @if ($media->media_type === 'thumbnail' && count($product->media) > 1)
                                                            <img src="{{ $media->file_path ? asset('public/' . $media->file_path) : url('public/media/images/default-images/default-image.webp') }}" alt="Thumbnail" />
                                                        @elseif ($media->media_type === 'image')
                                                            <img src="{{ $media->file_path ? asset('public/' . $media->file_path) : url('public/media/images/default-images/default-image.webp') }}" alt="Thumbnail" />
                                                        @elseif ($media->media_type === 'video')
                                                            <video preload="metadata" muted class="thumb-video">
                                                                <source src="{{ $media->file_path ? asset('public/' . $media->file_path) : url('public/media/images/default-images/default-image.webp') }}" type="video/mp4">
                                                            </video>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="sumary">
                            <div class="product-content">
                                <div class="product-title">
                                    <h1>{{ $product->name }}</h1>

                                </div>
                                <div class="desc">
                                    {!! $product->short_desc !!}
                                </div>
                            </div>
                            <div class="qty-wp">
                                <button class="decrement">-</button>
                                <input type="number" min="1" value="1" name="qty">
                                <button class="increment">+</button>
                                <button class="btn_add" value="{{ $product->id }}">ADD</button>
                            </div>
                        </div>
                    </div>
                    <div class="product-detail">
                        @if($product->detail)
                            <h1 style="width: 100%; text-align: center;">Product Details</h1>
                            {!! $product->detail !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const ADD_CART_AJAX_URL = "{{ route('add_cart_ajax') }}";
    </script>
 @endsection
