@extends('layouts.adminLayout')
@section('title', 'Edit User')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Edit User
            </div>
            <div class="card-body">
                <form action="{{ url('admin/user/update', $user->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input class="form-control" type="text" name="name" id="name" value="{{ $user->name }}">
                        @error('name')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input class="form-control" type="text" name="email" id="email" value="{{ $user->email }}"
                            readonly>
                        @error('email')
                            <small style="color:rgb(190, 50, 50)">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input class="form-control" type="password" name="password" id="password">
                        @error('password')
                            <small style="color:rgb(190, 50, 50)">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="form-group">

                        <label for="password_confirmation">Confirm-password</label>
                        <input class="form-control" type="password" name="password_confirmation" id="password_confirmation">
                    </div>
                    @error('confirm-password')
                        <small style="color:rgb(190, 50, 50)">
                            {{ $message }}
                        </small>
                    @enderror
                    <div class="form-group">

                        <label for="">Select Role</label>
                        <select class="form-control" id="" name="role[]" multiple style="width: 500px">
                            @foreach ($roles as $role)
                                <option
                                    @foreach ($user->roles as $item)
                                        @if ($item->id == $role->id)
                                            @selected(true) @endif @endforeach
                                    value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
