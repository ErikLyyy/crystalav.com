<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\DataTree;
use App\Traits\HasChild;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Menu;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    use HasChild, DataTree;
    public function show(Request $request)
    {
        $list_menu = Menu::orderBy('id', 'asc')->get();
        $list_menu = $this->data_tree($list_menu);
        $countItem = count($list_menu);
        $actions = ['delete' => 'Delete'];
        $trash = false;
        $list_trash = Menu::onlyTrashed()->get();
        $status = $request->input('status');
        if ($status) {
            if ($status == "trash") {
                $list_menu = Menu::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
                $trash = true;
                $actions = ['restore' => "Restore", 'forceDelete' => "Delete"];
            }
        }
        foreach ($list_menu as $menu) {
            if ($menu->parent_id == 0) {
                $menu->layout = "--";
            }
        }

        return view('admin.menu.show', compact('list_menu', 'list_trash', 'actions', 'countItem', 'trash'));
    }
    public function add()
    {
        $list_menu = Menu::where('parent_id', 0)->orderBy('id', 'asc')->get();
        return view('admin.menu.add', compact('list_menu'));
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                'title' => ['required', 'string', 'max:255'],
                'parent_id' => ['required', 'integer'],
                'layout' => ['required']
            ],
            [
                'integer' => 'The parent menu field is required.'
            ]
        );

        $menu = Menu::create([
            'title' => $request->input('title'),
            'desc' => $request->input('desc'),
            'layout' => $request->input('layout'),
            'parent_id' => $request->input('parent_id'),
            'slug' => Str::slug($request->input('title')),
            'user_id' => Auth::id(),
        ]);

        return redirect('admin/menu')->with('success', 'Added successfully!');
    }
    public function edit(Request $request, $id)
    {
        $list_menu = Menu::where('parent_id', 0)->where('id', 'not like', $id)->orderBy('id', 'asc')->get();
        $menu = Menu::withTrashed()->where('id', $id)->firstOrFail();
        return view('admin.menu.edit', compact('menu', 'list_menu'));
    }
    public function update(Request $request, $id)
    {
        $menu = Menu::withTrashed()->where('id', $id)->firstOrFail();
        $request->validate(
            [
                'title' => ['required', 'string', 'max:255'],
                'parent_id' => ['required', 'integer'],
                'layout' => ['required']
            ],
            [
                'integer' => 'The parent menu field is required.'
            ]
        );
        $menu->update([
            'title' => $request->input('title'),
            'desc' => $request->input('desc'),
            'layout' => $request->input('layout'),
            'parent_id' => $request->input('parent_id'),
            'slug' => Str::slug($request->input('title')),
            'user_id' => Auth::id(),
        ]);
        return redirect('admin/menu')->with('success', 'Edited successfully!');

    }
    public function delete($id)
    {
        // When delete parent menu, the children will be delete too
        Menu::where('parent_id', $id)->delete();
        $menu = Menu::find($id);
        $menu->delete();
        return redirect()->back()->with('success', 'Deleted successfully!');

    }
    public function forceDelete($id)
    {
        Menu::onlyTrashed()->where('parent_id', $id)->forceDelete();
        $menu = Menu::onlyTrashed()->where('id', $id)->firstOrFail();
        $menu->forceDelete();
        return redirect()->back()->with('success', 'Deleted successfully!');

    }
    public function restore($id)
    {
        // When restore child menu, the parent will be restore too
        $menu = Menu::onlyTrashed()->where('id', $id)->firstOrFail();
        Menu::onlyTrashed()->where('id', $menu->parent_id)->restore();
        $menu->restore();
        return redirect()->back()->with('success', 'Restored successfully!');

    }
    public function handleAction(Request $request)
    {
        if ($request->has('btn_apply')) {
            $checkItem = $request->input('checkItem');
            $action = $request->input('actions');

            if (!empty($checkItem) && in_array($action, ['delete', 'restore', 'forceDelete'])) {
                foreach ($checkItem as $item) {
                    $menu = ($action === 'delete')
                        ? Menu::find($item)
                        : Menu::onlyTrashed()->where('id', $item)->first();

                    if ($menu) {
                        if ($action === 'delete') {
                            Menu::where('parent_id', $item)->delete();
                            $menu->delete();
                        }
                        if ($action === 'restore') {
                            Menu::onlyTrashed()->where('id', $menu->parent_id)->restore();
                            $menu->restore();
                        }
                        if ($action === 'forceDelete') {
                            Menu::onlyTrashed()->where('parent_id', $item)->forceDelete();
                            $menu->forceDelete();
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
