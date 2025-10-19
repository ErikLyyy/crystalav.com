@extends('layouts.adminLayout')
@section('title', 'Request')
@section('content')
    <style>
        h3 {
            font-size: 20px;
        }

        ul.list-quote {
            display: flex;
            flex-wrap: wrap;
            padding: 0 20px;
            list-style: none;
        }

        ul.list-quote li {
            width: 20%;
            margin-right: 10px;
            margin-bottom: 10px;
            border: 1px solid #c9c9c9;
            border-radius: 10px;
            overflow: hidden;
        }

        ul.list-quote li .product-wp {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        ul.list-quote li .product-wp .product-thumb {
            display: flex;
            justify-content: center;
        }

        ul.list-quote li a.thumb-link {
            display: block;
            padding: 0;
        }

        ul.list-quote li a.thumb-link img {
            max-height: 200px;
        }

        .name-qty-wp {
            width: 100%;
            height: auto;
            font-weight: 500;
            text-align: center;
        }

        .name-qty-wp a.product-name {
            color: #000;
        }

        li:hover .name-qty-wp a.product-name {
            text-decoration: underline;
        }

        .qty-wp {
            margin-bottom: 15px;
        }

        @media only screen and (max-width: 1440px) {
            ul.list-quote li {
                width: 30%;
            }

            ul.list-quote li a.thumb-link img {
                max-height: 150px;
            }
        }

        @media only screen and (max-width: 1080px) {
            ul.list-quote li {
                width: 40%;
            }
        }
    </style>
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Request
            </div>
            <div class="card-body">

                <h3 style="font-weight: 500; margin-bottom:10px">First name: <span
                        style="font-weight: 400;">{{ $request->first_name }}</span>
                </h3>
                <h3 style="font-weight: 500; margin-bottom:10px">Last name: <span
                        style="font-weight: 400;">{{ $request->last_name }}</span>
                </h3>
                <h3 style="font-weight: 500; margin-bottom:10px">Email: <span
                        style="font-weight: 400;">{{ $request->email }}</span>
                </h3>
                <h3 style="font-weight: 500; margin-bottom:10px">Company Name: <span
                        style="font-weight: 400;">{{ $request->company_name }}</span></h3>
                <h3 style="font-weight: 500; margin-bottom:10px">Approximate date needed:
                    <span style="font-weight: 400;">{{ $request->approximate_date }}</span>
                </h3>
                <h3 style="font-weight: 500; margin-bottom:10px">Approximate return date:
                    <span style="font-weight: 400;">{{ $request->approximate_return }}</span>
                </h3>
                <h3 style="font-weight: 500; margin-bottom:10px">Phone number:
                    <span style="font-weight: 400;">{{ $request->phone_number }}</span>
                </h3>
                <h3 style="font-weight: 500; margin-bottom:10px">Message: <span
                        style="font-weight: 400;">{{ $request->message }}</span></h3>
                <h3 style="font-weight: 500; margin-bottom:10px">List quote:</h3>
                <ul class="list-quote">
                    @foreach ($list_cart as $cart)
                        <li class="product">
                            <div class="product-wp">
                                <div class="product-thumb">
                                    <div>
                                        <a href="{{ $cart->url }}" class="thumb-link" title="{{ $cart->name }}">
                                            <img src="{{ asset('public/' . $cart->thumbnail) }}" alt="" />
                                        </a>
                                    </div>
                                </div>
                                <div class="name-qty-wp">
                                    <a href="{{ $cart->url }}" class="product-name">{{ $cart->name }}</a>

                                    <!-- qty  -->
                                    <div class="qty-wp">
                                        <p>Qty: <strong>{{ $cart->qty }}</strong></p>

                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <a href="{{ url('admin/request') }}" style="margin-top: 20px;display:block">Back to list request</a>

            </div>
        </div>
    </div>
@endsection
