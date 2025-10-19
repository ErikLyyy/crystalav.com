@extends('layouts.adminLayout')
@section('title', 'Contact')
@section('content')
    <div id="content" class="container-fluid">
        <style>
            h3 {
                font-size: 20px;
            }
        </style>
        <div class="card">
            <div class="card-header font-weight-bold">
                Contact
            </div>
            <div class="card-body">

                <h3 style="font-weight: 500; margin-bottom:10px">Name: <span
                        style="font-weight: 400;">{{ $contact->name }}</span>
                </h3>
                <h3 style="font-weight: 500; margin-bottom:10px">Email: <span
                        style="font-weight: 400;">{{ $contact->email }}</span>
                </h3>
                <h3 style="font-weight: 500; margin-bottom:10px">Message: <span
                        style="font-weight: 400;">{{ $contact->message }}</span></h3>

                <a href="{{ url('admin/contact') }}" style="margin-top: 20px;display:block">Back to list contact</a>

            </div>
        </div>
    </div>
@endsection
