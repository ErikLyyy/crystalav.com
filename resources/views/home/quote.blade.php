@extends('layouts.homeLayout')
@section('title', 'Quote')

@section('content')
    <!-- CONTENT  -->
    <div id="head-title-wp" class="list-products-head-wp"
        style="background-image: url({{ asset('public/media/images/rs=w_1920\,m.webp') }}">
        <h1 id="head-title">BUILD YOUR QUOTE</h1>
    </div>
    <div class="container">
        @if (Cart::count() > 0)
            <div id="quote-wp">
                <div class="quote">
                    @if (session('status'))
                        <div
                            style="padding:10px 15px;color: #155724;background-color: #d4edda;border-color: #c3e6cb;border: 1px solid transparent;border-radius: .25rem;">
                            {{ session('status') }}</div>
                    @endif
                    <h2>You have <span>{{ Cart::count() }}</span> products in your quote</h2>
                    <ul class="list-products">
                        <!-- @csrf -->
                        @foreach (Cart::content() as $row)
                            <li class="product">
                                <div class="product-wp">
                                    <div class="product-thumb">
                                        <div>
                                            <a href="{{ $row->options->url }}" class="thumb-link"
                                                title="{{ $row->name }}">
                                                <img src="{{ asset('public/' . $row->options->thumbnail) }}"
                                                    alt="" />
                                            </a>
                                        </div>
                                    </div>
                                    <div class="name-qty-wp">
                                        <a href="{{ $row->options->url }}" class="product-name">{{ $row->name }}</a>
                                        <small>{{ $row->options->warehouse_status }}</small>

                                        <!-- qty  -->
                                        <div class="qty-wp">
                                            <button class="decrement">-</button>
                                            <input type="number" min="1" value="{{ $row->qty }}" name="qty">
                                            <button class="increment">+</button>
                                            <button class="delete" value="{{ $row->rowId }}"><svg
                                                    xmlns="http://www.w3.org/2000/svg" height="20px"
                                                    viewBox="0 -960 960 960" width="20px" fill="#000">
                                                    <path
                                                        d="M312-144q-29.7 0-50.85-21.15Q240-186.3 240-216v-480h-48v-72h192v-48h192v48h192v72h-48v479.57Q720-186 698.85-165T648-144H312Zm336-552H312v480h336v-480ZM384-288h72v-336h-72v336Zm120 0h72v-336h-72v336ZM312-696v480-480Z" />
                                                </svg></button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="request">
                    <small>Your compiled quote will be sent to one of our representatives, who will follow up with
                        you. If you require immediate
                        assistance, please call <span>800-794-1407</span></small>
                    <a href="{{ url('request') }}" class="request-btn">REQUEST YOUR QUOTE</a>
                </div>
            </div>
        @else
            <h2 class="quote-h2" style="margin: 15px 0px; text-align: center; ">You have <span style="color:brown">0</span>
                products in your
                quote</h2>
        @endif
    </div>
    <script>
        const CART_AJAX_URL = "{{ route('quote') }}"
    </script>
@endsection
