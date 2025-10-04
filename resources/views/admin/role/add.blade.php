@extends('layouts.adminLayout')
@section('title', 'Add Role')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Add new role</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ url('admin/role/store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="text-strong" for="name">Role name</label>
                        <input class="form-control" type="text" name="name" id="name"
                            value="{{ old('name') }}">
                        @error('name')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="text-strong" for="description">Description</label>
                        <textarea class="form-control" type="text" name="description" id="description">{{ old('description') }}</textarea>
                    </div>
                    <strong>What permissions does this role have?</strong>
                    <small class="form-text text-muted pb-2">Check the module or actions below to select the
                        permission.</small>
                    <!-- List Permission  -->
                    @foreach ($permissions as $moduleName => $modulePermissions)
                        <div class="card my-4 border">
                            <div class="card-header">
                                <input type="checkbox" class="check-all" name="" id="{{ $moduleName }}-head">
                                <label for="{{ $moduleName }}-head" class="m-0">Module
                                    {{ ucfirst($moduleName) }}</label>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($modulePermissions as $permission)
                                        <div class="col-md-3">
                                            <input type="checkbox" class="permission" value="{{ $permission->id }}"
                                                name="permission_id[]" id="{{ $permission->slug }}">
                                            <label for="{{ $permission->slug }}">{{ $permission->name }}</label>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    @endforeach

                    <input type="submit" name="btn-add" class="btn btn-primary" value="Add">
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('.check-all').click(function() {
            $(this).closest('.card').find('.permission').prop('checked', this.checked)
        })
    </script>
@endsection
