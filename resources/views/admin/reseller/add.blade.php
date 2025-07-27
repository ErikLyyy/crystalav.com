@extends('layouts.adminLayout')
@section('title', 'Add Reseller')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                ADD NEW RESELLER
            </div>

            @if (session('success'))
                <div style="padding:10px 15px; margin-bottom:0px" class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="card-body">
                <form action="{{ url('admin/reseller/store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="url">Url</label>
                        <input class="form-control" type="text" name="url" id="url"
                            value="{{ old('title') }}">
                        @error('url')
                            <small style="color:rgb(190, 50, 50)">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="image">Background</label>
                        <div class="btn_upload">
                            <span>Upload image</span>
                        </div>
                        <input type="file" name="image" id="image" style="display: none">
                        <img src="" id="uploadedImage" style="display: none; margin:15px 0px">
                        @error('image')
                            <small style="color:rgb(190, 50, 50)">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" name="btn_submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </div>
@endsection
