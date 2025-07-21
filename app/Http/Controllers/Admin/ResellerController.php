<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResellerController extends Controller
{
    public function show(Request $request)
    {
        return view('admin.reseller.show');
    }
    public function add()
    {
        return view('admin.reseller.add');
    }
    public function store(Request $request)
    {

    }
    public function edit(Request $request, $id)
    {
        return view('admin.reseller.edit');
    }
    public function update(Request $request, $id)
    {
    }
    public function handleDelete()
    {
    }
}
