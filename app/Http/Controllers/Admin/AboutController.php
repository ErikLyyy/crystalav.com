<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function show(Request $request)
    {
        return view('admin.about.show');
    }
    public function add()
    {
        return view('admin.about.add');
    }
    public function store(Request $request)
    {

    }
    public function edit(Request $request, $id)
    {
        return view('admin.about.edit');
    }
    public function update(Request $request, $id)
    {
    }
    public function handleDelete()
    {
    }
}
