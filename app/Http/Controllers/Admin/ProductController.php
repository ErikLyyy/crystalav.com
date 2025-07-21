<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Request $request)
    {
        return view('admin.product.show');
    }
    public function add()
    {
        return view('admin.product.add');
    }
    public function store(Request $request)
    {

    }
    public function edit(Request $request, $id)
    {
        return view('admin.product.edit');
    }
    public function update(Request $request, $id)
    {
    }
    public function handleDelete()
    {
    }
}
