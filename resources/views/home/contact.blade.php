@extends('layouts.homeLayout')
@section('title', 'Contact Us')

@section('content')
    <!-- CONTENT  -->

    <div class="container">
        <h1 class="main-title">CONTACT US</h1>

        <div id="contact-wp">
            <div id="contact-form">
                <form action="{{ url('contact/store') }}" method="POST">
                    @csrf
                    @if (session('status'))
                        <div
                            style="padding:10px 15px;color: #155724;background-color: #d4edda;border-color: #c3e6cb;border: 1px solid transparent;border-radius: .25rem;">
                            {{ session('status') }}</div>
                    @endif
                    <h2>Drop us a line!</h2>
                    <input type="text" name="name" id="name" class="form-input-contact" value="{{ old('name') }}"
                        placeholder="Name*" />
                    @error('name')
                        <small style="color:rgb(190, 50, 50);display:block; margin-bottom: 15px;">{{ $message }}</small>
                    @enderror
                    <input type="email" name="email" id="email" class="form-input-contact"
                        value="{{ old('email') }}" placeholder="Email*" />
                    @error('email')
                        <small style="color:rgb(190, 50, 50);display:block; margin-bottom: 15px;">{{ $message }}</small>
                    @enderror
                    <textarea name="message" id="message" placeholder="Message*" rows="8">{{ old('massage') }}</textarea>
                    @error('message')
                        <small style="color:rgb(190, 50, 50);display:block; margin-bottom: 15px;">{{ $message }}</small>
                    @enderror
                    <button type="submit" id="button-submit">SEND</button>
                    <span>This site is protected by reCAPTCHA and the Google Privacy
                        Policy and Terms of Service apply.</span>
                </form>
            </div>
            <div id="contact-info">
                <h2>Crystal Audio Visual Services, Inc.</h2>
                <span>(866) 441-6468</span>
                <span>Please contact us for any request at info@crystalav.com</span>
            </div>
        </div>
    </div>
@endsection
