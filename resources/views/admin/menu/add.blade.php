@extends('layouts.adminLayout')
@section('title', 'Add Menu')
@section('content')
    <style>
        .form-layout {
            display: flex;
            align-items: center
        }

        .form-layout .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-layout .form-group img {
            height: 250px;
            width: auto;
            margin-left: 15px;
            border: 1px solid #000;
        }
    </style>
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                ADD NEW MENU
            </div>

            @if (session('success'))
                <div style="padding:10px 15px; margin-bottom:0px" class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="card-body">
                <form action="{{ url('admin/menu/store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-layout">
                        <label for="">Layout</label>
                        <div class="form-group">
                            <label for="layout1">
                                <img src="{{ url('public/media/images/default-images/layout3.png') }}" alt="">
                            </label>
                            <input type="radio" name="layout" value="1" id="layout1"
                                @if (old('layout') == 1) checked="checked" @endif>
                        </div>
                        <div class="form-group">
                            <label for="layout2">
                                <img src="{{ url('public/media/images/default-images/layout2.png') }}" alt="">
                            </label>
                            <input type="radio" name="layout" value="2"
                                id="layout2"@if (old('layout') == 2) checked="checked" @endif>
                        </div>
                        <div class="form-group">
                            <label for="layout3">
                                <img src="{{ url('public/media/images/default-images/layout1.png') }}" alt="">
                            </label>
                            <input type="radio" name="layout" value="3"
                                id="layout3"@if (old('layout') == 3) checked="checked" @endif>
                        </div>
                        <div class="form-group">
                            <label for="layout4">
                                <img src="{{ url('public/media/images/default-images/layout4.png') }}" alt="">
                            </label>
                            <input type="radio" name="layout" value="4"
                                id="layout4"@if (old('layout') == 4) checked="checked" @endif>
                        </div>
                        <div class="form-group">
                            <label for="layout5">
                                <img src="{{ url('public/media/images/default-images/layout5.png') }}" alt="">
                            </label>
                            <input type="radio" name="layout" value="5"
                                id="layout5"@if (old('layout') == 5) checked="checked" @endif>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input class="form-control" type="text" name="title" id="title"
                            value="{{ old('title') }}">
                        @error('title')
                            <small style="color:rgb(190, 50, 50)">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="content">Description</label>
                        <textarea name="desc" class="form-control" id="desc" cols="30" rows="5">{{ old('desc') }}</textarea>
                    </div>
                    <div class="form-group">
                        <select name="parent_id" id="" class="form-control">
                            <option>Select parent category</option>
                            <option value="0">Set as parent menu</option>
                            @foreach ($list_menu as $menu)
                                <option
                                    value="{{ $menu->id }}"@if (old('parent_id') == $menu->id) selected="selected" @endif>
                                    {{ $menu->title }}</option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <small style="color:rgb(190, 50, 50)">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" name="btn_submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </div>
@endsection
