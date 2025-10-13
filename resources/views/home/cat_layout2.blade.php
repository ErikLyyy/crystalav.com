@extends('layouts.homeLayout')
@section('title', $menu->title)

@section('content')
    <div class="container">
        <h1 class="main-title">{{ $menu->title }}</h1>
        <ul id="layout2" class="list-sub-cat">
            @foreach ($list_category as $category)
                <li>
                    <a href="{{ url('category/' . $menu->slug . '/' . $category->slug) }}" class="img-link"
                        title="{{ $category->title }}">
                        <img src="{{ $category->image ? url('public/' . $category->image->file_path) : url('public/media/images/default-images/default-image.webp') }}"
                            alt="" />
                    </a>
                    <a href="{{ url('category/' . $menu->slug . '/' . $category->slug) }}"
                        class="sub-cat-title">{{ $category->title }}</a>
                </li>
            @endforeach
        </ul>
        @if ($menu->slug == 'conference-meeting')
            <div class="conference-ex-wp">
                <div class="video-wp">
                    <iframe data-ux="Embed" allowfullscreen="" type="text/html" frameborder="0"
                        src="//youtube.com/embed/SLWo9xw6f4E?rel=0&amp;showinfo=0&amp;start=0"
                        data-aid="VIDEO_IFRAME_RENDERED"
                        class="x-el x-el-iframe c2-1 c2-2 c2-v c2-w c2-x c2-y c2-z c2-10 c2-11 c2-12 c2-13 c2-14 c2-q c2-15 c2-3 c2-4 c2-5 c2-6 c2-7 c2-8"></iframe>
                </div>
                <div class="conference-ex-content">
                    <p>Awards Banquet Reception with custom lighting to match the client's colors and an audio / video
                        set up for the evening.</p>
                </div>
            </div>
        @endif
    </div>
@endsection
