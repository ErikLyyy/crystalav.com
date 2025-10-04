@extends('layouts.adminLayout')
@section('title', 'Edit Product')
@section('content')
    <style>

    </style>
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Edit Product
            </div>
            @if (session('success'))
                <div style="padding:10px 15px; margin-bottom:0px" class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="card-body">
                <form action="{{ url('admin/product/update/' . $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Product Name</label>
                        <input class="form-control" type="text" name="name" id="name"
                            value="{{ $product->name }}">
                        @error('name')
                            <small style="color:rgb(190, 50, 50)">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="keysearch">Key Search</label>
                        <input class="form-control" type="text" name="keysearch" id="keysearch"
                            value="{{ $keysearches }}">
                        <small><i>Ex: Iphone, IOS, Phone device</i></small>
                    </div>
                    <div class="form-group">
                        <label for="intro">Short Description</label>
                        <textarea name="desc" class="form-control ckeditor" id="intro" cols="30" rows="5">{{ $product->short_desc }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="intro">Product Detail</label>
                        <textarea name="detail" class="form-control ckeditor" id="intro" cols="30" rows="5">{{ $product->detail }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="image">Thumbnail</label>
                        <div class="btn_upload">
                            <span>Upload thumbnail</span>
                        </div>
                        <input type="file" name="image" id="image" style="display: none">
                        <img src="{{ url('public/' . $thumbnail->file_path) }}" id="uploadedImage"
                            style="display: block; margin:15px 0px; height:150px">
                        @error('image')
                            <small style="color:rgb(190, 50, 50)">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="media">Media</label>
                        <input type="hidden" name="uploaded_media" id="uploaded_media"
                            value='@json($media)'>
                        <div class="btn_upload">
                            <span>Upload media</span>
                        </div>

                        <input type="file" name="media[]" id="media" style="display: none" multiple>
                        <div class="product_media">
                            <div class="media" style="display: flex;flex-wrap:wrap"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">Warehouse status</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="warehouse_status" id="in-stock"
                                value="In Stock" {{ $product->warehouse_status == 'In Stock' ? 'checked' : '' }}>
                            <label class="form-check-label" for="in-stock">
                                In Stock
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="warehouse_status" id="out-of-stock"
                                value="Out of Stock" {{ $product->warehouse_status == 'Out of Stock' ? 'checked' : '' }}>
                            <label class="form-check-label" for="out-of-stock">
                                Out of Stock
                            </label>
                        </div>
                        @error('warehouse_status')
                            <small style="color:rgb(190, 50, 50)">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Privacy</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="privacy" id="private" value="Private"
                                {{ $product->privacy == 'Private' ? 'checked' : '' }}>
                            <label class="form-check-label" for="private">
                                Private
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="privacy" id="public" value="Public"
                                {{ $product->privacy == 'Public' ? 'checked' : '' }}>
                            <label class="form-check-label" for="public">
                                Public
                            </label>
                        </div>
                        @error('privacy')
                            <small style="color:rgb(190, 50, 50)">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Category</label>
                        <select class="form-control" id="add_product_category" name="category">
                            <option value="">Select Category</option>
                            @foreach ($list_categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->menu->title . ' / ' . $category->title }}
                                </option>
                            @endforeach

                        </select>
                        @error('category')
                            <small style="color:rgb(190, 50, 50)">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="" class="subcategory_title">Sidebar Subcategory</label>
                        <div class="list_subcategories" style="display: flex; flex-wrap:wrap;margin-bottom:15px;">
                        </div>
                    </div>
                    @if (count($list_filter) > 0)
                        <div class="form-group">

                            <label for="" class="filter_title">Sidebar Filter</label>
                            <div class="list_filter" style="display: flex; flex-wrap:wrap;margin-bottom:15px;">
                                @foreach ($list_filter as $filter)
                                    @if ($filter->parent_id == 0)
                                        <div class="form-checkbox cat-parent" data-id="{{ $filter->id }}"
                                            style="display: flex; width: 100%; margin-bottom: 10px;">

                                            <label for="sidebar-{{ $filter->id }}"
                                                style="padding-bottom: 0px; padding-left: 5px; margin-right: 30px; margin-bottom: 0px; text-transform: uppercase; font-weight: 500;">
                                                {{ $filter->title }}
                                            </label>
                                        </div>
                                    @else
                                        <div class="form-checkbox cat-child" data-id="{{ $filter->id }}"
                                            style="display: flex; margin-bottom: 10px;">
                                            <input type="checkbox" name="sidebar[]" value="{{ $filter->id }}"
                                                id="sidebar-{{ $filter->id }}"
                                                {{ is_array(value: $sidebars) && in_array($filter->id, $sidebars) ? 'checked' : '' }}>
                                            <label for="sidebar-{{ $filter->id }}"
                                                style="padding-bottom: 0px; padding-left: 5px; margin-right: 30px; margin-bottom: 0px;">
                                                {{ $filter->title }}
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <button type="submit" name="btn_submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        let oldCategory = "{{ $product->category_id }}";
        let oldSidebar = @json($sidebars);
        // Cast all elements of oldSidebar to string for standard comparison
        oldSidebar = oldSidebar.map(String);
        let ajaxUrl = "./../ajax"
        let uploadMedia = "../uploadMedia"
        let deleteMedia = "../deleteMedia";
    </script>
@endsection
