@extends('layouts.adminLayout')
@section('title', 'Add Filter')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                ADD NEW FILTER
            </div>

            @if (session('success'))
                <div style="padding:10px 15px; margin-bottom:0px" class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="card-body">
                <form action="{{ url('admin/sidebar/store/filter') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input class="form-control" type="text" name="title" id="title"
                            value="{{ old('title') }}">
                        @error('title')
                            <small style="color:rgb(190, 50, 50)">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <select name="parent_id" id="" class="form-control">
                            <option>Select parent filter</option>
                            <option value="0">Set as parent filter</option>
                            @foreach ($list_filter as $item)
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
