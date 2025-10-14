@extends('layouts.adminLayout')
@section('title', 'Edit Menu')
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
                EDIT MENU
            </div>

            @if (session('success'))
                <div style="padding:10px 15px; margin-bottom:0px" class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="card-body">
                <form action="{{ url('admin/menu/update/' . $menu->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-layout">
                        <label for="">Layout</label>
                        <div class="form-group">
                            <label for="layout1">
                                <img src="{{ url('public/media/images/default-images/layout1.png') }}" alt="">
                            </label>
                            <input type="radio" name="layout" value="1" id="layout1"
                                @if ($menu->layout == 1) checked="checked" @endif>
                        </div>
                        <div class="form-group">
                            <label for="layout2">
                                <img src="{{ url('public/media/images/default-images/layout2.png') }}" alt="">
                            </label>
                            <input type="radio" name="layout" value="2"
                                id="layout2"@if ($menu->layout == 2) checked="checked" @endif>
                        </div>
                        <div class="form-group">
                            <label for="layout3">
                                <img src="{{ url('public/media/images/default-images/layout3.png') }}" alt="">
                            </label>
                            <input type="radio" name="layout" value="3"
                                id="layout3"@if ($menu->layout == 3) checked="checked" @endif>
                        </div>
                        <div class="form-group">
                            <label for="layout4">
                                <img src="{{ url('public/media/images/default-images/layout4.png') }}" alt="">
                            </label>
                            <input type="radio" name="layout" value="4"
                                id="layout4"@if ($menu->layout == 4) checked="checked" @endif>
                        </div>
                        <div class="form-group">
                            <label for="layout5">
                                <img src="{{ url('public/media/images/default-images/layout5.png') }}" alt="">
                            </label>
                            <input type="radio" name="layout" value="5"
                                id="layout5"@if ($menu->layout == 5) checked="checked" @endif>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input class="form-control" type="text" name="title" id="title"
                            value="{{ $menu->title }}">
                        @error('title')
                            <small style="color:rgb(190, 50, 50)">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="content">Description</label>
                        <textarea name="desc" class="form-control" id="desc" cols="30" rows="5">{{ $menu->desc }}</textarea>
                    </div>
                    <div class="form-group">
                        <select name="parent_id" id="" class="form-control">
                            <option>Select parent menu</option>
                            @foreach ($list_menu as $item)
                                <option value="0" @if ($menu->parent_id == 0) selected="selected" @endif>Set as
                                    parent menu</option>
                                <option
                                    value="{{ $item->id }}"@if ($menu->parent_id == $item->id) selected="selected" @endif>
                                    {{ $item->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" name="btn_submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
