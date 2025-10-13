@extends('layouts.homeLayout')
@section('title', $menu->title)

@section('content')
    <div class="container">
        <h1 class="main-title">{{ $menu->title }}</h1>
        <ul id="layout3" class="list-sub-cat">
            @foreach ($list_category as $category)
                <li>
                    <a href="{{ url('category/' . $menu->slug . '/' . $category->slug) }}" class="img-link"
                        title="{{ $category->title }}">
                        <img src="{{ $category->image ? url('public/' . $category->image->file_path) : url('public/media/images/default-images/default-image.webp') }}"
                            alt="" />
                    </a>
                    <a href="{{ url('category/' . $menu->slug . '/' . $category->slug) }}"
                        class="sub-cat-title">{{ $category->title }}</a>
                    <span>{{ $category->desc }}</span>
                </li>
            @endforeach

        </ul>
    </div>
@endsection
