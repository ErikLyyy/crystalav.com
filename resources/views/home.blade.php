@extends('layouts.homeLayout')
@section('title', 'Home')

@section('content')
    <div id="head-title-wp" style="background-image: url({{ asset('public/media/images/rs=w_1920\,m.webp') }}">
        <h1 id="head-title">Crystal Audio Visual Services, Inc.</h1>
        <span>A full production company</span>
        <small>(866) 441-6468</small>
    </div>

    <div class="container">
        <div id="about-us">
            <h1 class="main-title">About us</h1>
            <div id="content-card-wp">
                @foreach ($about_us as $about)
                    @php
                        $path = $about->image
                            ? 'public/' . $about->image->file_path
                            : 'public/media/images/default-images/default-image.webp';

                        // Escape các ký tự đặc biệt cho CSS
                        $escapedPath = str_replace([' ', '(', ')', ','], ['\ ', '\(', '\)', '\,'], $path);
                    @endphp
                    <div>
                        <div class="content-cart-text"
                            style="
                    background-image: url({{ $escapedPath }});
                  ">
                            <h2>{{ $about->title }}</h2>
                            <p>{{ $about->content }}</p>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

        <div id="rental-support">
            <h1 class="main-title">PROFESSIONAL RENTAL EQUIPMENT AND SUPPORT SERVICE</h1>
            <div id="section-wp">
                @foreach ($list_service as $service)
                    <div class="section-content">
                        <div>
                            <h2>{{ $service->title }}</h2>
                            <p>
                                {{ $service->desc }}
                            </p>
                        </div>
                        <a href="{{ url('category', $service->slug) }}">Find out more</a>
                    </div>
                @endforeach
            </div>
        </div>

        <div id="reseller-wp">
            <h1 class="main-title">Reseller For</h1>
            <ul id="list-reseller">
                @foreach ($resellers as $reseller)
                    <li>
                        <a href="{{ $reseller->url }}"><img
                                src="{{ $reseller->image ? url('public/' . $reseller->image->file_path) : url('public/media/images/default-images/default-image.webp') }}"
                                alt="" /></a>
                    </li>
                @endforeach

            </ul>
        </div>
    </div>
@endsection
