@extends('layouts.homeLayout')
@section('title', $category_parent->title)

@section('content')
    <div class="container">
        <h1 class="main-title">FULL EVENT TECHNICAL SUPPORT SERVICES</h1>
        <div id="full-technical-support">
            <div id="full-event-technical-thumb">
                <img src="{{ asset('public/media/images/full-event-technical.webp') }}" alt=""
                    style="width: 100%; height: auto; max-height: 100%" />
            </div>
            <div id="full-event-technical-content">
                <a class="sub-cat-title">Audio Technicians</a>
                <span>Our Audio Technicians are masters in their craft and work
                    diligently to deliver clear and crisp audio to your event</span>
                <a class="sub-cat-title">Lighting Technicians</a>
                <span>When it comes to lighting we demonstrate the importance of laying
                    a foundational platform to accommodate any event. Events ranging
                    from meetings with public speaker to concerts with different
                    bands</span>
                <a class="sub-cat-title">Video Technicians</a>
                <span>Skilled Video Technicians will ensure that you create a lasting
                    impression, share your brand message and share your story with
                    your audience</span>
                <a class="sub-cat-title">Utility Technicians</a>
                <span>Our skilled Utility Technicians are available to help with any
                    event setup or any other incidental event support we can help
                    with</span>
            </div>
        </div>
    </div>
@endsection
