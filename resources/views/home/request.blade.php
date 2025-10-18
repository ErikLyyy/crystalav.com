@extends('layouts.homeLayout')
@section('title', 'Request')

@section('content')
    <div id="head-title-wp" class="list-products-head-wp"
        style="background-image: url({{ asset('public/media/images/rs=w_1920\,m.webp') }}">
        <h1 id="head-title">REQUEST A QUOTE</h1>
    </div>
    <div class="container">
        @if (Cart::count() > 0)

            <div id="quote-wp" class="request-pg">
                <form action="{{ url('request/sendRequestMail') }}" method="POST">
                    @csrf
                    <div class="request-form">
                        <p>YOUR COMPILED QUOTE WILL BE SENT TO ONE OF OUR REPRESENTATIVES, WHO WILL FOLLOW UP WITH
                            YOU. IF YOU REQUIRE IMMEDIATE
                            ASSISTANCE, PLEASE CALL 800-794-1407</p>
                        <span>* INDICATES REQUIRED FIELD</span>
                        <label for="first-name">FIRST NAME *</label>
                        <input type="text" id="first-name" name="first_name" value="{{ old('first_name') }}">
                        @error('first_name')
                            <small style="color:rgb(190, 50, 50)">{{ $message }}</small>
                        @enderror
                        <label for="last-name">LAST NAME *</label>
                        <input type="text" id="last-name" name="last_name" value="{{ old('last_name') }}">
                        @error('last_name')
                            <small style="color:rgb(190, 50, 50)">{{ $message }}</small>
                        @enderror
                        <label for="company-name">COMPANY NAME </label>
                        <input type="text" id="company-name" name="company_name" value="{{ old('company_name') }}">
                        <label for="phone">PHONE NUMBER</label>
                        <input type="tel" id="phone" name="phone" maxlength="20"
                            oninput="this.value = this.value.replace(/[^0-9()+\-\s]/g, '');" value="{{ old('phone') }}">
                        <label for="email">EMAIL *</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}">
                        @error('email')
                            <small style="color:rgb(190, 50, 50)">{{ $message }}</small>
                        @enderror
                        <label for="approximate-date">APPROXIMATE DATE NEEDED *</label>
                        <input type="date" name="approximate_date" id="approximate-date"
                            value="{{ old('approximate_date') }}">
                        @error('approximate_date')
                            <small style="color:rgb(190, 50, 50)">{{ $message }}</small>
                        @enderror
                        <label for="approximate-return">APPROXIMATE RETURN DATE *</label>
                        <input type="date" name="approximate_return" id="approximate-return"
                            value="{{ old('approximate_return') }}">
                        @error('approximate_return')
                            <small style="color:rgb(190, 50, 50)">{{ $message }}</small>
                        @enderror
                        <label for="message">Message:</label>
                        <textarea name="message" id="message" cols="30" rows="10">{{ old('message') }}</textarea>
                        <button type="submit" class="request-btn" name="send"
                            style="display: block;margin-top:10px">SEND
                            YOUR
                            REQUEST</button>
                    </div>
                    <!-- </form> -->
                    <div class="quote">
                        <h2>Your Items</h2>
                        <ul class="list-products">
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
                                            <a href="{{ $row->options->url }}"
                                                class="product-name">{{ $row->name }}</a>
                                            <!-- qty  -->
                                            <div class="qty-wp">
                                                <small>Qty: {{ $row->qty }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                </form>
            </div>
        @else
            <h2 class="quote-h2" style="margin: 15px 0px; text-align: center; ">You have <span style="color:brown">0</span>
                products in your
                quote</h2>
        @endif
    </div>
@endsection
