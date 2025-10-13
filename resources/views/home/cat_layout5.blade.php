@extends('layouts.homeLayout')
@section('title', $menu->title)

@section('content')
    <div class="container">
        <h1 class="main-title">{{ $menu->title }}</h1>
        <div id="layout5">
            <div class="carousel-wp">
                <div style="
                --swiper-navigation-color: #fff;
                --swiper-pagination-color: #fff;
              "
                    class="swiper mySwiper2">
                    <div class="swiper-wrapper">
                        @foreach ($list_category as $category)
                            <div class="swiper-slide">
                                <img
                                    src="{{ $category->image ? url('public/' . $category->image->file_path) : url('public/media/images/default-images/default-image.webp') }}" />
                                <p>{{ $category->title }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
                <div thumbsSlider="" class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        @foreach ($list_category as $category)
                            <div class="swiper-slide">
                                <img
                                    src="{{ $category->image ? url('public/' . $category->image->file_path) : url('public/media/images/default-images/default-image.webp') }}" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
