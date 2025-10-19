<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;


class RequestController extends Controller
{
    function show(Request $request)
    {
        if (!Gate::allows('request.show')) {
            abort(403);
        }
        Paginator::useBootstrapFive();
        $currentPage = 1;
        if ($request->page) {
            $currentPage = $request->page;
        }
        $countItem = count(\App\Models\Request::all());
        $list_request = \App\Models\Request::orderBy('id', 'desc')
            ->paginate(20);
        $actions = ['delete' => 'Delete'];
        $trash = false;
        $list_trash = \App\Models\Request::onlyTrashed()
            ->orderBy('id', 'desc')
            ->paginate(20);
        $status = $request->input('status');
        $search = $request->input('search');
        $keywords = $search ? explode(' ', trim($search)) : [];

        if ($search) {
            $list_request = \App\Models\Request::where(function ($query) use ($keywords) {
                foreach ($keywords as $word) {
                    $query->where('email', 'LIKE', "%{$word}%")
                        ->orwhere('first_name', 'LIKE', "%{$word}%")
                        ->orwhere('last_name', 'LIKE', "%{$word}%")
                        ->orwhere('company_name', 'LIKE', "%{$word}%");
                }
            })->orderBy('id', 'desc')->paginate(20);
        }
        if ($status) {
            if ($status == "trash") {
                $list_request = $list_trash;
                $trash = true;
                $actions = ['restore' => "Restore", 'forceDelete' => "Delete"];
                if ($search) {
                    $list_request = \App\Models\Request::onlyTrashed()
                        ->where(function ($query) use ($keywords) {
                            foreach ($keywords as $word) {
                                $query->where('email', 'LIKE', "%{$word}%")
                                    ->orwhere('first_name', 'LIKE', "%{$word}%")
                                    ->orwhere('last_name', 'LIKE', "%{$word}%")
                                    ->orwhere('company_name', 'LIKE', "%{$word}%");
                            }
                        })->orderBy('id', 'desc')->paginate(20);
                }
            }
        }


        return view('admin.request.show', compact('list_request', 'list_trash', 'countItem', 'trash', 'actions', 'currentPage'));
    }
    function read($id)
    {
        if (!Gate::allows('request.read')) {
            abort(403);
        }
        $request = \App\Models\Request::withTrashed()->find($id);
        $list_cart = $request->cart;
        foreach ($list_cart as $cart) {
            if ($cart->product == null) {
                $cart->url = "";
            }
        }
        return view('admin.request.read', compact('request', 'list_cart'));
    }
    public function delete($id)
    {
        if (!Gate::allows('request.delete')) {
            abort(403);
        }
        $request = \App\Models\Request::find($id);
        $request->delete();
        return redirect()->back()->with('success', 'Deleted successfully!');

    }
    public function forceDelete($id)
    {
        if (!Gate::allows('request.delete')) {
            abort(403);
        }
        $request = \App\Models\Request::onlyTrashed()->where('id', $id)->firstOrFail();
        $request->forceDelete();
        return redirect()->back()->with('success', 'Deleted successfully!');

    }
    public function restore($id)
    {
        if (!Gate::allows('request.delete')) {
            abort(403);
        }
        $request = \App\Models\Request::onlyTrashed()->where('id', $id)->firstOrFail();
        $request->restore();
        return redirect()->back()->with('success', 'Restored successfully!');

    }
    public function handleAction(Request $request)
    {
        if (!Gate::allows('request.delete')) {
            abort(403);
        }
        if ($request->has('btn_apply')) {
            $checkItem = $request->input('checkItem');
            $action = $request->input('actions');

            if (!empty($checkItem) && in_array($action, ['delete', 'restore', 'forceDelete'])) {
                foreach ($checkItem as $item) {
                    $request = ($action === 'delete')
                        ? \App\Models\Request::find($item)
                        : \App\Models\Request::onlyTrashed()->where('id', $item)->first();

                    if ($request) {
                        if ($action === 'delete')
                            $request->delete();
                        if ($action === 'restore')
                            $request->restore();
                        if ($action === 'forceDelete')
                            $request->forceDelete();
                    }
                }

                $message = match ($action) {
                    'delete' => 'Deleted successfully!',
                    'restore' => 'Restored successfully!',
                    'forceDelete' => 'Permanently deleted successfully!',
                };
                return redirect()->back()->with('success', $message);
            }

            return redirect()->back()->with('danger', "You haven't chosen any valid action or selected items.");
        }
    }
}
