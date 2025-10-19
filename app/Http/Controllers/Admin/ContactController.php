<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;

class ContactController extends Controller
{
    function show(Request $request)
    {
        if (!Gate::allows('contact.show')) {
            abort(403);
        }
        Paginator::useBootstrapFive();
        $currentPage = 1;
        if ($request->page) {
            $currentPage = $request->page;
        }
        $countItem = count(Contact::all());
        $list_contact = Contact::orderBy('id', 'desc')
            ->paginate(20);
        $actions = ['delete' => 'Delete'];
        $trash = false;
        $list_trash = Contact::onlyTrashed()
            ->orderBy('id', 'desc')
            ->paginate(20);
        $status = $request->input('status');
        $search = $request->input('search');
        $keywords = $search ? explode(' ', trim($search)) : [];

        if ($search) {
            $list_contact = Contact::where(function ($query) use ($keywords) {
                foreach ($keywords as $word) {
                    $query->where('email', 'LIKE', "%{$word}%")
                        ->orwhere('name', 'LIKE', "%{$word}%");
                }
            })->orderBy('id', 'desc')->paginate(20);
        }
        if ($status) {
            if ($status == "trash") {
                $list_contact = $list_trash;
                $trash = true;
                $actions = ['restore' => "Restore", 'forceDelete' => "Delete"];
                if ($search) {
                    $list_contact = Contact::onlyTrashed()
                        ->where(function ($query) use ($keywords) {
                            foreach ($keywords as $word) {
                                $query->where('email', 'LIKE', "%{$word}%")
                                    ->orwhere('name', 'LIKE', "%{$word}%");
                            }
                        })->orderBy('id', 'desc')->paginate(20);
                }
            }
        }


        return view('admin.contact.show', compact('list_contact', 'list_trash', 'countItem', 'trash', 'actions', 'currentPage'));
    }
    function read($id)
    {
        if (!Gate::allows('contact.read')) {
            abort(403);
        }
        $contact = Contact::withTrashed()->find($id);
        return view('admin.contact.read', compact('contact'));
    }
    public function delete($id)
    {
        if (!Gate::allows('contact.delete')) {
            abort(403);
        }
        $contact = Contact::find($id);
        $contact->delete();
        return redirect()->back()->with('success', 'Deleted successfully!');

    }
    public function forceDelete($id)
    {
        if (!Gate::allows('contact.delete')) {
            abort(403);
        }
        $contact = Contact::onlyTrashed()->where('id', $id)->firstOrFail();
        $contact->forceDelete();
        return redirect()->back()->with('success', 'Deleted successfully!');

    }
    public function restore($id)
    {
        if (!Gate::allows('contact.delete')) {
            abort(403);
        }
        $contact = Contact::onlyTrashed()->where('id', $id)->firstOrFail();
        $contact->restore();
        return redirect()->back()->with('success', 'Restored successfully!');

    }
    public function handleAction(Request $request)
    {
        if (!Gate::allows('contact.delete')) {
            abort(403);
        }
        if ($request->has('btn_apply')) {
            $checkItem = $request->input('checkItem');
            $action = $request->input('actions');

            if (!empty($checkItem) && in_array($action, ['delete', 'restore', 'forceDelete'])) {
                foreach ($checkItem as $item) {
                    $contact = ($action === 'delete')
                        ? Contact::find($item)
                        : Contact::onlyTrashed()->where('id', $item)->first();

                    if ($contact) {
                        if ($action === 'delete')
                            $contact->delete();
                        if ($action === 'restore')
                            $contact->restore();
                        if ($action === 'forceDelete')
                            $contact->forceDelete();
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
