<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Product;
use Illuminate\Pagination\Paginator;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $totalProduct = count(Product::withTrashed()->get());
        $totalRequest = count(\App\Models\Request::withTrashed()->get());
        $totalContact = count(Contact::withTrashed()->get());
        Paginator::useBootstrapFive();
        $currentPage = 1;
        if ($request->page) {
            $currentPage = $request->page;
        }
        $list_request = \App\Models\Request::orderBy('id', 'desc')->paginate(6);

        return view('admin.index', compact('totalProduct', 'totalRequest', 'totalContact', 'list_request', 'currentPage'));
    }
}
