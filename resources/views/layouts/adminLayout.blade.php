<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/solid.min.css">
    <link rel="stylesheet" href="{{ asset('public/admin/css/style.css') }}">
    <link rel="icon" href="{{ asset('public/media/images/default-images/logo-1.jpg') }}" />

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
                        Admin
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#">Profile</a>
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
                    <li class="nav-link">
                        <a href="{{ url('admin/dashboard') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="{{ url('admin/contact') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Contact
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="{{ url('admin/request') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Request
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/request') }}">List Requests</a></li>
                            <li><a href="{{ url('admin/request/add') }}">Add</a></li>
                        </ul>
                    </li>
                    <li class="nav-link">
                        <a href="{{ url('admin/about') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Abou Us
                        </a>
                        <i class="arrow fas fa-angle-right"></i>

                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/about') }}">List About</a></li>
                            <li><a href="{{ url('admin/about/add') }}">Add</a></li>
                        </ul>
                    </li>
                    <li class="nav-link">
                        <a href="{{ url('admin/reseller') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Reseller
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/reseller') }}">List Resellers</a></li>
                            <li><a href="{{ url('admin/reseller/add') }}">Add</a></li>
                        </ul>
                    </li>
                    <li class="nav-link">
                        <a href="{{ url('admin/menu') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Menu
                        </a>
                        <i class="arrow fas fa-angle-down"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/menu') }}">List Menu</a></li>
                            <li><a href="{{ url('admin/menu/add') }}">Add</a></li>
                        </ul>
                    </li>
                    <li class="nav-link">
                        <a href="{{ url('admin/categories') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Category
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/categories') }}">List Categories</a></li>
                            <li><a href="{{ url('admin/categories/add') }}">Add</a></li>
                        </ul>
                    </li>
                    <li class="nav-link">
                        <a href="{{ url('admin/sidebar') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Sidebar
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/sidebar') }}">List Sidebars</a></li>
                            <li><a href="{{ url('admin/sidebar/add') }}">Add</a></li>
                        </ul>
                    </li>
                    <li class="nav-link">
                        <a href="{{ url('admin/product') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Product
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/product') }}">List Products</a></li>
                            <li><a href="{{ url('admin/product/add') }}">Add</a></li>
                        </ul>
                    </li>
                    <li class="nav-link">
                        <a href="{{ url('admin/user') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Users
                        </a>
                        <i class="arrow fas fa-angle-right"></i>

                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/user') }}">List Users</a></li>
                            <li><a href="{{ url('admin/user/add') }}">Add</a></li>
                        </ul>
                    </li>
                    <li class="nav-link active">
                        <a href="{{ url('admin/role') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Decentralization
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/role') }}">List Roles</a></li>
                            <li><a href="{{ url('admin/role/add') }}">Add Role</a></li>
                            <li><a href="{{ url('admin/permission') }}">List Permissions</a></li>
                        </ul>
                    </li>

                </ul>
            </div>
            <div id="wp-content">
                @section('content')

                @show
            </div>
        </div>


    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
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
