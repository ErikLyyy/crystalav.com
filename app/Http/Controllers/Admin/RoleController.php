<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function show(Request $request)
    {
        return view('admin.role.show');
    }
    public function add()
    {
        return view('admin.role.add');
    }
    public function store(Request $request)
    {

    }
    public function edit(Request $request, $id)
    {
        return view('admin.role.edit');
    }
    public function update(Request $request, $id)
    {
    }
    public function handleDelete()
    {
    }
}
