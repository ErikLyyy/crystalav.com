@extends('layouts.adminLayout')
@section('title', 'Edit Category')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                EDIT ABOUT
            </div>

            @if (session('success'))
                <div style="padding:10px 15px; margin-bottom:0px" class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="card-body">
                <form action="{{ url('admin/categories/update/' . $category->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input class="form-control" type="text" name="title" id="title"
                            value="{{ $category->title }}">
                        @error('title')
                            <small style="color:rgb(190, 50, 50)">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="desc">Description</label>
                        <textarea name="desc" class="form-control" id="desc" cols="30" rows="5">{{ $category->desc }}</textarea>
                        @error('desc')
                            <small style="color:rgb(190, 50, 50)">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="image">Background</label>
                        <div class="btn_upload">
                            <span>Upload image</span>
                        </div>
                        <input type="file" name="image" id="image" style="display: none">
                        <img src="{{ url('public/' . $category->image->file_path) }}" id="uploadedImage"
                            style="display: block; margin:15px 0px; height:150px;">
                        @error('image')
                            <small style="color:rgb(190, 50, 50)">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <select name="menu_id" id="" class="form-control">
                            <option>Select Menu</option>
                            @foreach ($list_menu as $menu)
                                <option
                                    value="{{ $menu->id }}"@if ($category->menu_id == $menu->id) selected="selected" @endif>
                                    {{ $menu->title }}</option>
                            @endforeach
                        </select>
                        @error('menu_id')
                            <small style="color:rgb(190, 50, 50)">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" name="btn_submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
