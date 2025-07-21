<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(Request $request)
    {
        return view('admin.user.show');
    }
    public function add()
    {
        return view('admin.user.add');
    }
    public function store(Request $request)
    {

    }
    public function edit(Request $request, $id)
    {
        return view('admin.user.edit');
    }
    public function update(Request $request, $id)
    {
    }
    public function handleDelete()
    {
    }
}
