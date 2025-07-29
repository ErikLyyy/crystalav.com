<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\DataTree;
use App\Traits\HasChild;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Sidebar;
use Illuminate\Support\Str;

class SidebarController extends Controller
{
    use HasChild, DataTree;
    public function show(Request $request)
    {
        $list_sidebar = Sidebar::orderBy('id', 'asc')->get();
        $list_sidebar = $this->data_tree($list_sidebar);
        $countItem = count($list_sidebar);
        $actions = ['delete' => 'Delete'];
        $trash = false;
        $list_trash = Sidebar::onlyTrashed()->get();
        $status = $request->input('status');
        $list_subcategory = Sidebar::where('type', 'subcategory')->orderBy('id', 'asc')->get();
        $list_filter = Sidebar::where('type', 'filter')->orderBy('id', 'asc')->get();
        if ($status) {
            if ($status == "trash") {
                $list_sidebar = Sidebar::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
                $trash = true;
                $actions = ['restore' => "Restore", 'forceDelete' => "Delete"];
            } elseif ($status == "subcategory") {
                $list_sidebar = $this->data_tree($list_subcategory);
            } elseif ($status == "filter") {
                $list_sidebar = $this->data_tree($list_filter);
            }
        }
        foreach ($list_sidebar as $sidebar) {
            if ($sidebar->parent_id == 0) {
                $sidebar->layout = "--";
            }
        }

        return view('admin.sidebar.show', compact('list_sidebar', 'list_trash', 'list_subcategory', 'list_filter', 'actions', 'countItem', 'trash'));
    }
    public function add($type)
    {
        $list_subcategories = Sidebar::where('parent_id', 0)->where('type', 'subcategory')->orderBy('id', 'asc')->get();
        $list_filter = Sidebar::where('parent_id', 0)->where('type', 'filter')->orderBy('id', 'asc')->get();
        return view('admin.sidebar.add_' . $type, compact('list_subcategories', 'list_filter'));
    }
    public function store(Request $request, $type)
    {
        $request->validate(
            [
                'title' => ['required', 'string', 'max:255'],
                'parent_id' => ['required', 'integer'],
            ],
            [
                'integer' => 'The parent field is required.'
            ]
        );

        $sidebar = Sidebar::create([
            'title' => $request->input('title'),
            'type' => $type,
            'parent_id' => $request->input('parent_id'),
            'slug' => Str::slug($request->input('title')),
            'user_id' => Auth::id(),
        ]);

        return redirect('admin/sidebar')->with('success', 'Added successfully!');
    }
    public function edit(Request $request, $id)
    {
        $list_subcategories = Sidebar::where('parent_id', 0)->where('type', 'subcategory')->where('id', 'not like', $id)->orderBy('id', 'asc')->get();
        $list_filter = Sidebar::where('parent_id', 0)->where('type', 'filter')->where('id', 'not like', $id)->orderBy('id', 'asc')->get();
        $sidebar = Sidebar::withTrashed()->where('id', $id)->firstOrFail();
        return view('admin.sidebar.edit_' . $sidebar->type, compact('sidebar', 'list_subcategories', 'list_filter'));
    }
    public function update(Request $request, $id)
    {
        $sidebar = Sidebar::withTrashed()->where('id', $id)->firstOrFail();
        $request->validate(
            [
                'title' => ['required', 'string', 'max:255'],
                'parent_id' => ['required', 'integer'],
            ],
            [
                'integer' => 'The parent field is required.'
            ]
        );
        $sidebar->update([
            'title' => $request->input('title'),
            'parent_id' => $request->input('parent_id'),
            'slug' => Str::slug($request->input('title')),
            'user_id' => Auth::id(),
        ]);
        return redirect('admin/sidebar')->with('success', 'Edited successfully!');

    }
    public function delete($id)
    {
        // When delete parent sidebar, the children will be delete too
        Sidebar::where('parent_id', $id)->delete();
        $sidebar = Sidebar::find($id);
        $sidebar->delete();
        return redirect()->back()->with('success', 'Deleted successfully!');

    }
    public function forceDelete($id)
    {
        Sidebar::onlyTrashed()->where('parent_id', $id)->forceDelete();
        $sidebar = Sidebar::onlyTrashed()->where('id', $id)->firstOrFail();
        $sidebar->forceDelete();
        return redirect()->back()->with('success', 'Deleted successfully!');

    }
    public function restore($id)
    {
        // When restore child sidebar, the parent will be restore too
        $sidebar = Sidebar::onlyTrashed()->where('id', $id)->firstOrFail();
        Sidebar::onlyTrashed()->where('id', $sidebar->parent_id)->restore();
        $sidebar->restore();
        return redirect()->back()->with('success', 'Restored successfully!');

    }
    public function handleAction(Request $request)
    {
        if ($request->has('btn_apply')) {
            $checkItem = $request->input('checkItem');
            $action = $request->input('actions');

            if (!empty($checkItem) && in_array($action, ['delete', 'restore', 'forceDelete'])) {
                foreach ($checkItem as $item) {
                    $sidebar = ($action === 'delete')
                        ? Sidebar::find($item)
                        : Sidebar::onlyTrashed()->where('id', $item)->first();

                    if ($sidebar) {
                        if ($action === 'delete') {
                            Sidebar::where('parent_id', $item)->delete();
                            $sidebar->delete();
                        }
                        if ($action === 'restore') {
                            Sidebar::onlyTrashed()->where('id', $sidebar->parent_id)->restore();
                            $sidebar->restore();
                        }
                        if ($action === 'forceDelete') {
                            Sidebar::onlyTrashed()->where('parent_id', $item)->forceDelete();
                            $sidebar->forceDelete();
                        }
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
