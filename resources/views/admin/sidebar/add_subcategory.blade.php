@extends('layouts.adminLayout')
@section('title', 'Add Subcategory')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                ADD NEW SUBCATEGORY
            </div>

            @if (session('success'))
                <div style="padding:10px 15px; margin-bottom:0px" class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="card-body">
                <form action="{{ url('admin/sidebar/store/subcategory') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="edit_value" value="0">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input class="form-control" type="text" name="title" id="title"
                            value="{{ old('title') }}">
                        @error('title')
                            <small style="color:rgb(190, 50, 50)">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <select name="category_id" id="category" class="form-control">
                            <option value="">Select main category</option>
                            @foreach ($list_categories as $category)
                                <option
                                    value="{{ $category->id }}"@if (old('category_id') == $category->id) selected="selected" @endif>
                                    {{ $category->title }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <small style="color:rgb(190, 50, 50)">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <select name="parent_id" id="subcategory" class="form-control">
                            <option value="">Select
                                parent subcategory</option>
                            <option value="0" @if (old('parent_id', $parent_id ?? '') === '0') selected @endif>Set as
                                parent subcategory</option>
                            @if (session('list_subcategories'))
                                @php
                                    $list_subcategories = session('list_subcategories');
                                @endphp
                            @endif
                            @foreach ($list_subcategories as $item)
                                <option
                                    value="{{ $item->id }}"@if (old('parent_id') == $item->id) selected="selected" @endif>
                                    {{ $item->title }}</option>
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
