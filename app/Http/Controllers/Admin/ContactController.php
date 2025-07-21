<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function list_contacts(Request $request)
    {
        return view('admin.contact.list_contacts');
    }
    public function show(Request $request)
    {
        return view('admin.contact.show');
    }
    public function handleDelete()
    {
    }
}
