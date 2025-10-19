@extends('layouts.adminLayout')
@section('title', 'Sidebar')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">List Subcategory <a href="{{ url('admin/sidebar/add/subcategory') }}"
                        class="btn btn-primary text-white ml-2">Add</a>
                </h5>
                <div class="form-search form-inline">
                    <form method="GET">
                        @if ($trash == true)
                            <input type="hidden" name="status" value="trash" />
                        @endif
                        <input type="search" name="search" class="form-control form-search" placeholder="Search">
                        <input type="submit" name="btn_search" value="Search" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div style="padding:10px 15px; margin-bottom:0px" class="alert alert-success">{{ session('success') }}
                    </div>
                @elseif(session('danger'))
                    <div style="padding:10px 15px; margin-bottom:0px" class="alert alert-danger">{{ session('danger') }}
                    </div>
                @endif
                <form action="{{ url('admin/sidebar/action') }}" method="GET">
                    <div class="analytic">
                        <a href="{{ url('admin/sidebar/show/subcategory') }}" class="text-primary">All<span
                                class="text-muted">({{ $countItem }})</span></a>
                        <a href="{{ url('admin/sidebar/show/subcategory?status=trash') }}" class="text-primary">
                            Trash<span class="text-muted">({{ count($list_trash) }})</span>
                        </a>
                    </div>
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" id="" name="actions">
                            <option>Actions</option>
                            @foreach ($actions as $k => $action)
                                <option value={{ $k }}>{{ $action }}</option>
                            @endforeach
                        </select>
                        <input type="submit" name="btn_apply" value="Apply" class="btn btn-primary">
                    </div>
                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <input name="checkall" type="checkbox">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Title</th>
                                <th scope="col">Category</th>
                                <th scope="col">Created by</th>
                                <th scope="col">Created at</th>
                                @if ($trash == true)
                                    <th scope="col">Deleted at</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $t = 0;
                            @endphp
                            @foreach ($list_sidebar as $sidebar)
                                @php
                                    $t++;
                                @endphp
                                <tr>
                                    <td>
                                        <input type="checkbox" name="checkItem[]" value="{{ $sidebar->id }}">
                                    </td>
                                    <td scope="row">{{ $t }}</td>

                                    <td style="display: flex; justify-content: space-between;">
                                        @if ($sidebar->parent_id == 0)
                                            <span>{{ $sidebar->title }}</span>
                                        @else
                                            <span>---{{ $sidebar->title }}</span>
                                        @endif
                                        <div class="action-wp">
                                            @if ($trash == true)
                                                <a href="{{ url('admin/sidebar/restore/' . $sidebar->id) }}" class="action"
                                                    title="Restore">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="20px"
                                                        viewBox="0 -960 960 960" width="20px" fill="#000">
                                                        <path
                                                            d="M479.79-408Q450-408 429-429.21t-21-51Q408-510 429.21-531t51-21Q510-552 531-530.79t21 51Q552-450 530.79-429t-51 21Zm.21 264q-140 0-238.5-98T144-480h72q2 110 78.5 187T480-216q110.31 0 187.16-76.78 76.84-76.78 76.84-187T667.16-667Q590.31-744 480-744q-59 0-111.5 25.5T277-648h107v72H144v-240h72v130q47.91-62.09 116.95-96.04Q402-816 480-816q70 0 131.13 26.6 61.14 26.6 106.4 71.87 45.27 45.26 71.87 106.4Q816-550 816-480t-26.6 131.13q-26.6 61.14-71.87 106.4-45.26 45.27-106.4 71.87Q550-144 480-144Z" />
                                                    </svg>
                                                </a>
                                            @endif
                                            <a href="{{ url('admin/sidebar/edit/' . $sidebar->id) }}" class="action"
                                                title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="20px"
                                                    viewBox="0 -960 960 960" width="20px" fill="#000">
                                                    <path
                                                        d="M216-216h51l375-375-51-51-375 375v51Zm-72 72v-153l498-498q11-11 23.84-16 12.83-5 27-5 14.16 0 27.16 5t24 16l51 51q11 11 16 24t5 26.54q0 14.45-5.02 27.54T795-642L297-144H144Zm600-549-51-51 51 51Zm-127.95 76.95L591-642l51 51-25.95-25.05Z" />
                                                </svg>
                                            </a>
                                            @if ($trash == true)
                                                <a href="{{ url('admin/sidebar/forceDelete/' . $sidebar->id) }}"
                                                    class="action" title="Delete" style="margin-right: 20px">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="20px"
                                                        viewBox="0 -960 960 960" width="20px" fill="#000">
                                                        <path
                                                            d="M312-144q-29.7 0-50.85-21.15Q240-186.3 240-216v-480h-48v-72h192v-48h192v48h192v72h-48v479.57Q720-186 698.85-165T648-144H312Zm336-552H312v480h336v-480ZM384-288h72v-336h-72v336Zm120 0h72v-336h-72v336ZM312-696v480-480Z" />
                                                    </svg>
                                                </a>
                                            @else
                                                <a href="{{ url('admin/sidebar/delete/' . $sidebar->id) }}" class="action"
                                                    title="Delete" style="margin-right: 20px">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="20px"
                                                        viewBox="0 -960 960 960" width="20px" fill="#000">
                                                        <path
                                                            d="M312-144q-29.7 0-50.85-21.15Q240-186.3 240-216v-480h-48v-72h192v-48h192v48h192v72h-48v479.57Q720-186 698.85-165T648-144H312Zm336-552H312v480h336v-480ZM384-288h72v-336h-72v336Zm120 0h72v-336h-72v336ZM312-696v480-480Z" />
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    @if ($sidebar->category)
                                        <td>{{ (isset($sidebar->category->menu->title) ? $sidebar->category->menu->title : 'None') . ' / ' . $sidebar->category->title }}
                                        </td>
                                    @else
                                        <td>None</td>
                                    @endif
                                    <td>{{ $sidebar->user->name }}</td>
                                    <td>{{ $sidebar->created_at }}</td>
                                    @if ($trash == true)
                                        <td>{{ $sidebar->deleted_at }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
@endsection
