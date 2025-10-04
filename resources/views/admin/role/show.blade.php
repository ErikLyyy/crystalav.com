@extends('layouts.adminLayout')
@section('title', 'Role')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">List Role<a href="{{ url('admin/role/add') }}"
                        class="btn btn-primary text-white ml-2">Add</a></h5>
            </div>
            <div class="card-body">

                @if (session('success'))
                    <div style="padding:10px 15px; margin-bottom:0px" class="alert alert-success">{{ session('success') }}
                    </div>
                @elseif(session('danger'))
                    <div style="padding:10px 15px; margin-bottom:0px" class="alert alert-danger">{{ session('danger') }}
                    </div>
                @endif
                <form action="{{ url('admin/role/action') }}" method="GET">
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" id="" name="actions">
                            <option>Actions</option>
                            <option value="delete">Delete</option>
                        </select>
                        <input type="submit" name="sm_action" value="Apply" class="btn btn-primary">
                    </div>
                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <input name="checkall" type="checkbox">
                                </th>
                                <th scope="col"><span class="thead-text">#</span></th>
                                <th scope="col" style="min-width: 120px"><span class="thead-text">Role</span></th>
                                <th scope="col"><span class="thead-text">Description</span></th>
                                <th scope="col"><span class="thead-text">Created at</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $t = 0;
                            @endphp
                            @foreach ($roles as $role)
                                @php
                                    $t++;
                                @endphp
                                <tr>
                                    <td><input type="checkbox" name="checkItem[]" class="checkItem"
                                            value="{{ $role->id }}">
                                    </td>
                                    <td><span class="tbody-text">{{ $t }}</span></td>
                                    <td style="display: flex; justify-content: space-between;">
                                        <span>{{ $role->name }}</span>

                                        <div class="action-wp">

                                            <a href="{{ url('admin/role/edit/' . $role->id) }}" class="action"
                                                title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="20px"
                                                    viewBox="0 -960 960 960" width="20px" fill="#000">
                                                    <path
                                                        d="M216-216h51l375-375-51-51-375 375v51Zm-72 72v-153l498-498q11-11 23.84-16 12.83-5 27-5 14.16 0 27.16 5t24 16l51 51q11 11 16 24t5 26.54q0 14.45-5.02 27.54T795-642L297-144H144Zm600-549-51-51 51 51Zm-127.95 76.95L591-642l51 51-25.95-25.05Z" />
                                                </svg>
                                            </a>
                                            <a href="{{ url('admin/role/delete/' . $role->id) }}" class="action"
                                                title="Delete" style="margin-right: 20px">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="20px"
                                                    viewBox="0 -960 960 960" width="20px" fill="#000">
                                                    <path
                                                        d="M312-144q-29.7 0-50.85-21.15Q240-186.3 240-216v-480h-48v-72h192v-48h192v48h192v72h-48v479.57Q720-186 698.85-165T648-144H312Zm336-552H312v480h336v-480ZM384-288h72v-336h-72v336Zm120 0h72v-336h-72v336ZM312-696v480-480Z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                    <td><span class="tbody-text">{{ $role->description }}</span></td>
                                    <td><span class="tbody-text">{{ $role->created_at }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                        </tbody>
                    </table>
                </form>

            </div>
        </div>
    </div>
@endsection
