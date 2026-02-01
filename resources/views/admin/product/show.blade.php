@extends('layouts.adminLayout')
@section('title', 'Product')
@section('content')
    <style>
        tr th a {
            color: #000;
        }

        tr th:hover a {
            text-decoration: underline;
        }
    </style>
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">List Products<a href="{{ url('admin/product/add') }}"
                        class="btn btn-primary text-white ml-2">Add</a></h5>
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
                <form action="{{ url('admin/product/action') }}" method="GET">
                    <div class="analytic">
                        <a href="{{ url('admin/product') }}" class="text-primary">All<span
                                class="text-muted">({{ $countItem }})</span></a>
                        <a href="{{ url('admin/product?status=trash') }}" class="text-primary">
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
                                <th scope="col">Thumbnail</th>
                                <th scope="col"><a title="Click here to sort columns"
                                        href="{{ request()->fullUrlWithQuery([
                                            'sort' => 'products.name',
                                            'order' => request('sort') === 'products.name' && request('order') === 'asc' ? 'desc' : 'asc',
                                        ]) }}">
                                        Product name
                                        @if (request('sort') === 'products.name')
                                            @if (request('order') === 'asc')
                                                ▲
                                            @else
                                                ▼
                                            @endif
                                        @endif
                                    </a></th>
                                <th scope="col"><a title="Click here to sort columns"
                                        href="{{ request()->fullUrlWithQuery([
                                            'sort' => 'products.warehouse_status',
                                            'order' => request('sort') === 'products.warehouse_status' && request('order') === 'asc' ? 'desc' : 'asc',
                                        ]) }}">
                                        Warehouse status
                                        @if (request('sort') === 'products.warehouse_status')
                                            @if (request('order') === 'asc')
                                                ▲
                                            @else
                                                ▼
                                            @endif
                                        @endif
                                    </a></th>
                                <th scope="col"><a title="Click here to sort columns"
                                        href="{{ request()->fullUrlWithQuery([
                                            'sort' => 'products.privacy',
                                            'order' => request('sort') === 'products.privacy' && request('order') === 'asc' ? 'desc' : 'asc',
                                        ]) }}">
                                        Privacy
                                        @if (request('sort') === 'products.privacy')
                                            @if (request('order') === 'asc')
                                                ▲
                                            @else
                                                ▼
                                            @endif
                                        @endif
                                    </a></th>
                                <th scope="col"><a title="Click here to sort columns"
                                        href="{{ request()->fullUrlWithQuery([
                                            'sort' => 'categories.title',
                                            'order' => request('sort') === 'categories.title' && request('order') === 'asc' ? 'desc' : 'asc',
                                        ]) }}">
                                        Category
                                        @if (request('sort') === 'categories.title')
                                            @if (request('order') === 'asc')
                                                ▲
                                            @else
                                                ▼
                                            @endif
                                        @endif
                                    </a></th>
                                <th scope="col"><a title="Click here to sort columns"
                                        href="{{ request()->fullUrlWithQuery([
                                            'sort' => 'users.name',
                                            'order' => request('sort') === 'users.name' && request('order') === 'asc' ? 'desc' : 'asc',
                                        ]) }}">
                                        Created by
                                        @if (request('sort') === 'users.name')
                                            @if (request('order') === 'asc')
                                                ▲
                                            @else
                                                ▼
                                            @endif
                                        @endif
                                    </a></th>
                                <th scope="col"><a title="Click here to sort columns"
                                        href="{{ request()->fullUrlWithQuery([
                                            'sort' => 'products.created_at',
                                            'order' => request('sort') === 'products.created_at' && request('order') === 'asc' ? 'desc' : 'asc',
                                        ]) }}">
                                        Created at
                                        @if (request('sort') === 'products.created_at')
                                            @if (request('order') === 'asc')
                                                ▲
                                            @else
                                                ▼
                                            @endif
                                        @endif
                                    </a></th>
                                @if ($trash == true)
                                    <th scope="col"><a title="Click here to sort columns"
                                            href="{{ request()->fullUrlWithQuery([
                                                'sort' => 'products.deleted_at',
                                                'order' => request('sort') === 'products.deleted_at' && request('order') === 'asc' ? 'desc' : 'asc',
                                            ]) }}">
                                            Deleted at
                                            @if (request('sort') === 'products.deleted_at')
                                                @if (request('order') === 'asc')
                                                    ▲
                                                @else
                                                    ▼
                                                @endif
                                            @endif
                                        </a></th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $t = ($currentPage - 1) * 20;
                            @endphp
                            @foreach ($list_products as $product)
                                @php
                                    $t++;
                                @endphp
                                <tr>
                                    <td>
                                        <input type="checkbox" name="checkItem[]" value="{{ $product->id }}">
                                    </td>
                                    <td scope="row">{{ $t }}</td>
                                    <td style="width: 200px">
                                        <div style="display:flex;flex-direction: column; justify-content:center">
                                            <div style="display: flex; justify-content: center; width: fit-content;"><img
                                                    src="{{ $product->media ? url('public/' . $product->media->first()->file_path) : url('public/media/images/default-images/default-image.webp') }}"
                                                    alt="" style="width: 100%;max-height:80px"></div>
                                        </div>
                                    </td>


                                    <td style="display: flex; justify-content: space-between;">
                                        <span>{{ $product->name }}</span>

                                        <div class="action-wp">
                                            @if ($trash == true)
                                                <a href="{{ url('admin/product/restore/' . $product->id) }}" class="action"
                                                    title="Restore">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="20px"
                                                        viewBox="0 -960 960 960" width="20px" fill="#000">
                                                        <path
                                                            d="M479.79-408Q450-408 429-429.21t-21-51Q408-510 429.21-531t51-21Q510-552 531-530.79t21 51Q552-450 530.79-429t-51 21Zm.21 264q-140 0-238.5-98T144-480h72q2 110 78.5 187T480-216q110.31 0 187.16-76.78 76.84-76.78 76.84-187T667.16-667Q590.31-744 480-744q-59 0-111.5 25.5T277-648h107v72H144v-240h72v130q47.91-62.09 116.95-96.04Q402-816 480-816q70 0 131.13 26.6 61.14 26.6 106.4 71.87 45.27 45.26 71.87 106.4Q816-550 816-480t-26.6 131.13q-26.6 61.14-71.87 106.4-45.26 45.27-106.4 71.87Q550-144 480-144Z" />
                                                    </svg>
                                                </a>
                                            @else
                                                <a href="{{ url('category/' . $product->category->menu->slug . '/' . $product->category->slug . '/' . $product->slug) }}"
                                                    class="action" title="Restore">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20"
                                                        viewBox="0 0 640 640" fill="#000">
                                                        <path
                                                            d="M451.5 160C434.9 160 418.8 164.5 404.7 172.7C388.9 156.7 370.5 143.3 350.2 133.2C378.4 109.2 414.3 96 451.5 96C537.9 96 608 166 608 252.5C608 294 591.5 333.8 562.2 363.1L491.1 434.2C461.8 463.5 422 480 380.5 480C294.1 480 224 410 224 323.5C224 322 224 320.5 224.1 319C224.6 301.3 239.3 287.4 257 287.9C274.7 288.4 288.6 303.1 288.1 320.8C288.1 321.7 288.1 322.6 288.1 323.4C288.1 374.5 329.5 415.9 380.6 415.9C405.1 415.9 428.6 406.2 446 388.8L517.1 317.7C534.4 300.4 544.2 276.8 544.2 252.3C544.2 201.2 502.8 159.8 451.7 159.8zM307.2 237.3C305.3 236.5 303.4 235.4 301.7 234.2C289.1 227.7 274.7 224 259.6 224C235.1 224 211.6 233.7 194.2 251.1L123.1 322.2C105.8 339.5 96 363.1 96 387.6C96 438.7 137.4 480.1 188.5 480.1C205 480.1 221.1 475.7 235.2 467.5C251 483.5 269.4 496.9 289.8 507C261.6 530.9 225.8 544.2 188.5 544.2C102.1 544.2 32 474.2 32 387.7C32 346.2 48.5 306.4 77.8 277.1L148.9 206C178.2 176.7 218 160.2 259.5 160.2C346.1 160.2 416 230.8 416 317.1C416 318.4 416 319.7 416 321C415.6 338.7 400.9 352.6 383.2 352.2C365.5 351.8 351.6 337.1 352 319.4C352 318.6 352 317.9 352 317.1C352 283.4 334 253.8 307.2 237.5z" />
                                                    </svg>
                                                </a>
                                            @endif
                                            <a href="{{ url('admin/product/edit/' . $product->id) }}" class="action"
                                                title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="20px"
                                                    viewBox="0 -960 960 960" width="20px" fill="#000">
                                                    <path
                                                        d="M216-216h51l375-375-51-51-375 375v51Zm-72 72v-153l498-498q11-11 23.84-16 12.83-5 27-5 14.16 0 27.16 5t24 16l51 51q11 11 16 24t5 26.54q0 14.45-5.02 27.54T795-642L297-144H144Zm600-549-51-51 51 51Zm-127.95 76.95L591-642l51 51-25.95-25.05Z" />
                                                </svg>
                                            </a>
                                            @if ($trash == true)
                                                <a href="{{ url('admin/product/forceDelete/' . $product->id) }}"
                                                    class="action" title="Delete" style="margin-right: 20px">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="20px"
                                                        viewBox="0 -960 960 960" width="20px" fill="#000">
                                                        <path
                                                            d="M312-144q-29.7 0-50.85-21.15Q240-186.3 240-216v-480h-48v-72h192v-48h192v48h192v72h-48v479.57Q720-186 698.85-165T648-144H312Zm336-552H312v480h336v-480ZM384-288h72v-336h-72v336Zm120 0h72v-336h-72v336ZM312-696v480-480Z" />
                                                    </svg>
                                                </a>
                                            @else
                                                <a href="{{ url('admin/product/delete/' . $product->id) }}"
                                                    class="action" title="Delete" style="margin-right: 20px">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="20px"
                                                        viewBox="0 -960 960 960" width="20px" fill="#000">
                                                        <path
                                                            d="M312-144q-29.7 0-50.85-21.15Q240-186.3 240-216v-480h-48v-72h192v-48h192v48h192v72h-48v479.57Q720-186 698.85-165T648-144H312Zm336-552H312v480h336v-480ZM384-288h72v-336h-72v336Zm120 0h72v-336h-72v336ZM312-696v480-480Z" />
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $product->warehouse_status }}</td>
                                    <td>{{ $product->privacy }}</td>
                                    @if ($product->category)
                                        <td>{{ (isset($product->category->menu->title) ? $product->category->menu->title : 'None') . ' / ' . $product->category->title }}
                                        </td>
                                    @else
                                        <td>None</td>
                                    @endif
                                    <td>{{ $product->user->name }}</td>
                                    <td>{{ $product->created_at }}</td>
                                    @if ($trash == true)
                                        <td>{{ $product->deleted_at }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>

                {{ $list_products->onEachSide(2)->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}


            </div>
        </div>
    </div>
@endsection
