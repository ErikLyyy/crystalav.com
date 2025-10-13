@extends('layouts.homeLayout')
@section('title', $menu->title)

@section('content')
    <div class="container">
        <h1 class="main-title">{{ $menu->title }}</h1>
        <ul id="layout4" class="list-sub-cat">
            @foreach ($list_category as $category)
                <li>
                    <a href="{{ url('category/' . $menu->slug . '/' . $category->slug) }}" title="{{ $category->title }}">
                        <img src="{{ $category->image ? url('public/' . $category->image->file_path) : url('public/media/images/default-images/default-image.webp') }}"
                            alt="" />
                    </a>
                    <div>
                        <a href="{{ url('category/' . $menu->slug . '/' . $category->slug) }}"
                            class="sub-cat-title">{{ $category->title }}</a>
                        <span>{{ $category->desc }}</span>
                    </div>
                </li>
            @endforeach

        </ul>
    </div>
@endsection
