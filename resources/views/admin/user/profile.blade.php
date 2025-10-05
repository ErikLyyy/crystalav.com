@extends('layouts.adminLayout')
@section('title', 'Profile')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Profile
            </div>
            <div class="card-body">
                <p>Full Name: {{ $user->name }}</p>
                <p>Email: {{ $user->email }}</p>
                <p>Role: @if (isset($user->roles) && $user->roles->count())
                        @foreach ($user->roles as $role)
                            {{ $role->name }}@if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    @else
                        None
                    @endif
                </p>
                <a href="{{ url('admin/user/edit', Auth::id()) }}" class="btn btn-primary">Edit your profile</a>
            </div>
        </div>
    </div>
@endsection
