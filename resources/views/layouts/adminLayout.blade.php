<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/solid.min.css">
    <link rel="stylesheet" href="{{ asset('public/admin/css/style.css') }}">
    <link rel="icon" href="{{ asset('public/media/images/default-images/logo-1.jpg') }}" />
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <title>@yield('title')</title>
</head>

<body>
    <div id="warpper" class="nav-fixed">
        <nav class="topnav shadow navbar-light bg-white d-flex">
            <div class="navbar-brand"><a href="{{ url('admin') }}">ADMIN</a></div>
            <div class="nav-right ">
                <div class="btn-group mr-auto">
                    {{-- <button type="button" class="btn dropdown" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="plus-icon fas fa-plus-circle"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{url('admin/product/add')}}">Add Product</a>
                        <a class="dropdown-item" href="{{url('admin/request/add')}}">Add Request</a>
                    </div> --}}
                </div>
                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        {{ Auth::user()->name }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="{{ url('admin/user/profile') }}">Profile</a>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                                                             document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </nav>
        <!-- end nav  -->
        <div id="page-body" class="d-flex">
            <div id="sidebar" class="bg-white">
                <ul id="sidebar-menu">
                    <li class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}">
                        <a href="{{ url('admin/dashboard') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Dashboard
                        </a>
                    </li>
                    @can('contact.show')
                        <li class="nav-link {{ request()->is('admin/contact*') ? 'active' : '' }}">
                            <a href="{{ url('admin/contact') }}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                                Contact
                            </a>
                        </li>
                    @endcan
                    @can('request.show')
                        <li class="nav-link {{ request()->is('admin/request*') ? 'active' : '' }}">
                            <a href="{{ url('admin/request') }}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                                Request
                            </a>
                        </li>
                    @endcan
                    @can('about-us.show')
                        <li class="nav-link {{ request()->is('admin/about*') ? 'active' : '' }}">
                            <a href="{{ url('admin/about') }}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                                About Us
                            </a>
                            <i class="arrow fas fa-angle-right"></i>

                            <ul class="sub-menu">
                                <li class="{{ request()->is('admin/about') ? 'active' : '' }}"><a
                                        href="{{ url('admin/about') }}">List About</a></li>
                                @can('about-us.add')
                                    <li class="{{ request()->is('admin/about/add*') ? 'active' : '' }}"><a
                                            href="{{ url('admin/about/add') }}">Add</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endcan
                    @can('reseller.show')

                        <li class="nav-link {{ request()->is('admin/reseller*') ? 'active' : '' }}">
                            <a href="{{ url('admin/reseller') }}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                                Reseller
                            </a>
                            <i class="arrow fas fa-angle-right"></i>
                            <ul class="sub-menu">
                                <li class="{{ request()->is('admin/reseller') ? 'active' : '' }}"><a
                                        href="{{ url('admin/reseller') }}">List Resellers</a></li>
                                @can('reseller.add')
                                    <li class="{{ request()->is('admin/reseller/add') ? 'active' : '' }}"><a
                                            href="{{ url('admin/reseller/add') }}">Add</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endcan
                    @can('menu.show')

                        <li class="nav-link {{ request()->is('admin/menu*') ? 'active' : '' }}">
                            <a href="{{ url('admin/menu') }}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                                Menu
                            </a>
                            <i class="arrow fas fa-angle-right"></i>
                            <ul class="sub-menu">
                                <li class="{{ request()->is('admin/menu') ? 'active' : '' }}"><a
                                        href="{{ url('admin/menu') }}">List Menu</a></li>
                                @can('menu.add')
                                    <li class="{{ request()->is('admin/menu/add') ? 'active' : '' }}"><a
                                            href="{{ url('admin/menu/add') }}">Add</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endcan
                    @can('category.show')

                        <li class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}">
                            <a href="{{ url('admin/categories') }}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                                Category
                            </a>
                            <i class="arrow fas fa-angle-right"></i>
                            <ul class="sub-menu">
                                <li class="{{ request()->is('admin/categories') ? 'active' : '' }}"><a
                                        href="{{ url('admin/categories') }}">List Categories</a></li>
                                @can('category.add')
                                    <li class="{{ request()->is('admin/categories/add') ? 'active' : '' }}"><a
                                            href="{{ url('admin/categories/add') }}">Add</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endcan
                    @can('sidebar.show')

                        <li class="nav-link {{ request()->is('admin/sidebar*') ? 'active' : '' }}">
                            <a href="{{ url('admin/sidebar/show/subcategory') }}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                                Sidebar
                            </a>
                            <i class="arrow fas fa-angle-right"></i>
                            <ul class="sub-menu">
                                <li class="{{ request()->is('admin/sidebar/show/subcategory') ? 'active' : '' }}"><a
                                        href="{{ url('admin/sidebar/show/subcategory') }}">List Subcategory</a></li>
                                @can('sidebar.add')
                                    <li class="{{ request()->is('admin/sidebar/add/subcategory') ? 'active' : '' }}"><a
                                            href="{{ url('admin/sidebar/add/subcategory') }}">Add Subcategory</a></li>
                                @endcan
                                <li class="{{ request()->is('admin/sidebar/show/filter') ? 'active' : '' }}"><a
                                        href="{{ url('admin/sidebar/show/filter') }}">List Filter</a></li>
                                @can('sidebar.add')
                                    <li class="{{ request()->is('admin/sidebar/add/filter') ? 'active' : '' }}"><a
                                            href="{{ url('admin/sidebar/add/filter') }}">Add Filter</a></li>
                                @endcan

                            </ul>
                        </li>
                    @endcan
                    @can('product.show')
                        <li class="nav-link {{ request()->is('admin/product*') ? 'active' : '' }}">
                            <a href="{{ url('admin/product') }}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                                Product
                            </a>
                            <i class="arrow fas fa-angle-right"></i>
                            <ul class="sub-menu">
                                <li class="{{ request()->is('admin/product') ? 'active' : '' }}"><a
                                        href="{{ url('admin/product') }}">List Products</a></li>
                                @can('product.add')
                                    <li class="{{ request()->is('admin/product/add') ? 'active' : '' }}"><a
                                            href="{{ url('admin/product/add') }}">Add</a></li>
                                @endcan
                                @can('keysearch.show')
                                    <li class="{{ request()->is('admin/product/keysearch') ? 'active' : '' }}"><a
                                            href="{{ url('admin/product/keysearch') }}">Keysearches</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endcan
                    @can('user.show')

                        <li class="nav-link {{ request()->is('admin/user*') ? 'active' : '' }}">
                            <a href="{{ url('admin/user') }}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                                Users
                            </a>
                            <i class="arrow fas fa-angle-right"></i>

                            <ul class="sub-menu">
                                <li class="{{ request()->is('admin/user') ? 'active' : '' }}"><a
                                        href="{{ url('admin/user') }}">List Users</a></li>
                                @can('user.add')
                                    <li class="{{ request()->is('admin/user/add') ? 'active' : '' }}"><a
                                            href="{{ url('admin/user/add') }}">Add</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endcan
                    @can('role.show')

                        <li class="nav-link {{ request()->is('admin/role*') ? 'active' : '' }}">
                            <a href="{{ url('admin/role') }}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                                Decentralization
                            </a>
                            <i class="arrow fas fa-angle-right"></i>
                            <ul class="sub-menu">
                                <li class="{{ request()->is('admin/role') ? 'active' : '' }}"><a
                                        href="{{ url('admin/role') }}">List Roles</a></li>
                                @can('role.add')
                                    <li class="{{ request()->is('admin/role/add') ? 'active' : '' }}"><a
                                            href="{{ url('admin/role/add') }}">Add</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endcan
                </ul>
            </div>
            <div id="wp-content">
                @section('content')

                @show
            </div>
        </div>


    </div>

    <script src="{{ asset('public/admin/js/app.js') }}"></script>

    <script src="{{ asset('public/admin/js/plugins/ckeditor/ckeditor.js') }}" type="text/javascript"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
</body>

</html>
