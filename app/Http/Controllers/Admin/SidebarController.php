<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SidebarController extends Controller
{
    public function show(Request $request)
    {
        return view('admin.sidebar.show');
    }
    public function add()
    {
        return view('admin.sidebar.add');
    }
    public function store(Request $request)
    {

    }
    public function edit(Request $request, $id)
    {
        return view('admin.sidebar.edit');
    }
    public function update(Request $request, $id)
    {
    }
    public function handleDelete()
    {
    }
}
